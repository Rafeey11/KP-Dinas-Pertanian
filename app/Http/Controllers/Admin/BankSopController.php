<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankSop;
use App\Models\BankSopTarget;
use App\Models\BankSopField;
use App\Models\BankSopSubmission;
use App\Models\Bpp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class BankSopController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = BankSop::query();

        if ($user->role === 'admin_tim_kerja') {
            $binaans = $user->bpp_binaans->pluck('id')->toArray();
            $query->where('status', 'published')
                  ->where(function ($q) use ($binaans) {
                      $q->where('target_type', 'all_bpp')
                        ->orWhereHas('targets', function ($q2) use ($binaans) {
                            $q2->whereIn('bpp_id', $binaans);
                        });
                  });
        }

        $bankSops = $query->latest()->paginate(20);
        return view('admin.bank-sops.index', compact('bankSops'));
    }

    public function create()
    {
        $bpps = Bpp::orderBy('name')->get();
        return view('admin.bank-sops.form', compact('bpps'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'type' => 'required|in:document,link,template,internal_form',
            'link_type' => 'nullable|string|in:google_form,google_spreadsheet,youtube,google_drive,website,other',
            'target_type' => 'required|in:all_bpp,selected_bpp',
            'deadline' => 'nullable|date',
            'track_read' => 'nullable|boolean',
            'status' => 'required|in:draft,published,inactive,archived',
            'url' => 'nullable|url|required_if:type,link',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,csv|max:10240|required_if:type,document,template',
            'bpp_ids' => 'nullable|array|required_if:target_type,selected_bpp',
            'bpp_ids.*' => 'exists:bpps,id'
        ]);

        $validated['track_read'] = $request->has('track_read');

        $bankSop = new BankSop($validated);
        $bankSop->creator_id = auth()->id();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('bank_sops', 'local');
            $bankSop->file_path = $path;
            $bankSop->file_name = $file->getClientOriginalName();
        }

        $bankSop->save();

        if ($validated['target_type'] === 'selected_bpp' && isset($validated['bpp_ids'])) {
            $targets = array_map(function ($id) {
                return ['bpp_id' => $id];
            }, $validated['bpp_ids']);
            $bankSop->targets()->createMany($targets);
        }

        \App\Services\AuditLogService::log(
            'created',
            'Bank SOP',
            $bankSop,
            "Bank SOP '{$bankSop->title}' dibuat."
        );

        if ($bankSop->status === 'published') {
            $this->notifyTargets($bankSop, 'new_sop');
        }

        return redirect()->route('admin.bank-sops.show', $bankSop->id)->with('success', 'Bank SOP berhasil dibuat.');
    }

    public function show(BankSop $bankSop)
    {
        $user = auth()->user();
        if ($user->role === 'admin_tim_kerja') {
            $binaans = $user->bpp_binaans->pluck('id')->toArray();
            if ($bankSop->target_type === 'selected_bpp') {
                $hasAccess = $bankSop->targets()->whereIn('bpp_id', $binaans)->exists();
                if (!$hasAccess) abort(403, 'Unauthorized Access.');
            }
        }

        $bankSop->load(['targets.bpp', 'fields']);
        return view('admin.bank-sops.show', compact('bankSop'));
    }

    public function edit(BankSop $bankSop)
    {
        $bpps = Bpp::orderBy('name')->get();
        $bankSop->load('targets');
        return view('admin.bank-sops.form', compact('bankSop', 'bpps'));
    }

    public function update(Request $request, BankSop $bankSop)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'type' => 'required|in:document,link,template,internal_form',
            'link_type' => 'nullable|string|in:google_form,google_spreadsheet,youtube,google_drive,website,other',
            'target_type' => 'required|in:all_bpp,selected_bpp',
            'deadline' => 'nullable|date',
            'track_read' => 'nullable|boolean',
            'status' => 'required|in:draft,published,inactive,archived',
            'url' => 'nullable|url',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,csv|max:10240',
            'bpp_ids' => 'nullable|array|required_if:target_type,selected_bpp',
            'bpp_ids.*' => 'exists:bpps,id'
        ]);

        $validated['track_read'] = $request->has('track_read');

        $oldStatus = $bankSop->status;
        $documentChanged = false;

        if ($request->hasFile('file')) {
            if ($bankSop->file_path) Storage::disk('local')->delete($bankSop->file_path);
            $file = $request->file('file');
            $path = $file->store('bank_sops', 'local');
            $bankSop->file_path = $path;
            $bankSop->file_name = $file->getClientOriginalName();
            $documentChanged = true;
        }

        $bankSop->fill($validated)->save();

        $bankSop->targets()->delete();
        if ($validated['target_type'] === 'selected_bpp' && isset($validated['bpp_ids'])) {
            $targets = array_map(function ($id) {
                return ['bpp_id' => $id];
            }, $validated['bpp_ids']);
            $bankSop->targets()->createMany($targets);
        }

        \App\Services\AuditLogService::log(
            'updated',
            'Bank SOP',
            $bankSop,
            "Bank SOP '{$bankSop->title}' diperbarui."
        );

        if ($validated['status'] === 'published') {
            if ($oldStatus !== 'published') {
                $this->notifyTargets($bankSop, 'new_sop');
            } elseif ($documentChanged && in_array($bankSop->type, ['document', 'template'])) {
                $this->notifyTargets($bankSop, 'new_document');
            }
        }

        return redirect()->route('admin.bank-sops.show', $bankSop->id)->with('success', 'Bank SOP berhasil diperbarui.');
    }

    private function notifyTargets(BankSop $bankSop, $type)
    {
        $bppQuery = Bpp::where('status', 'active');
        if ($bankSop->target_type === 'selected_bpp') {
            $bppQuery->whereIn('id', $bankSop->targets()->pluck('bpp_id'));
        }
        
        $bpps = $bppQuery->with('users')->get();
        foreach ($bpps as $bpp) {
            foreach ($bpp->users->where('role', 'bpp')->where('status', 'active') as $user) {
                $user->notify(new \App\Notifications\BankSopPublishedNotification($bankSop, $type));
            }
        }
    }

    public function destroy(BankSop $bankSop)
    {
        if ($bankSop->file_path) {
            Storage::disk('local')->delete($bankSop->file_path);
        }
        $bankSop->delete();
        return redirect()->route('admin.bank-sops.index')->with('success', 'Bank SOP berhasil dihapus.');
    }

    public function download(BankSop $bankSop)
    {
        $user = auth()->user();
        if ($user->role === 'admin_tim_kerja') {
            $binaans = $user->bpp_binaans->pluck('id')->toArray();
            if ($bankSop->target_type === 'selected_bpp') {
                $hasAccess = $bankSop->targets()->whereIn('bpp_id', $binaans)->exists();
                if (!$hasAccess) abort(403, 'Unauthorized Access.');
            }
        }

        if (!$bankSop->file_path || !Storage::disk('local')->exists($bankSop->file_path)) {
            return back()->withErrors(['error' => 'File tidak ditemukan.']);
        }

        return Storage::disk('local')->download($bankSop->file_path, $bankSop->file_name);
    }

    public function storeField(Request $request, BankSop $bankSop)
    {
        if ($bankSop->type !== 'internal_form') abort(404);

        $validated = $request->validate([
            'field_name' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'type' => 'required|string',
            'is_required' => 'nullable|boolean',
            'options' => 'nullable|string', // comma separated for select/radio
            'order' => 'required|integer',
        ]);

        if (!empty($validated['options'])) {
            $validated['options'] = array_map('trim', explode(',', $validated['options']));
        } else {
            $validated['options'] = null;
        }
        $validated['is_required'] = $request->has('is_required');

        $bankSop->fields()->create($validated);

        \App\Services\AuditLogService::log(
            'updated',
            'Bank SOP',
            $bankSop,
            "Field baru ditambahkan pada form '{$bankSop->title}'."
        );

        if ($bankSop->status === 'published') {
            $this->notifyTargets($bankSop, 'new_format');
        }

        return back()->with('success', 'Field berhasil ditambahkan.');
    }

    public function destroyField(BankSopField $field)
    {
        $bankSop = $field->bankSop;
        $field->delete();

        \App\Services\AuditLogService::log(
            'updated',
            'Bank SOP',
            $bankSop,
            "Field dihapus dari form '{$bankSop->title}'."
        );

        return back()->with('success', 'Field berhasil dihapus.');
    }

    public function monitor(BankSop $bankSop)
    {
        $user = auth()->user();
        $binaans = $user->role === 'admin_tim_kerja' ? $user->bpp_binaans->pluck('id')->toArray() : [];
        if ($user->role === 'admin_tim_kerja') {
            if ($bankSop->target_type === 'selected_bpp') {
                $hasAccess = $bankSop->targets()->whereIn('bpp_id', $binaans)->exists();
                if (!$hasAccess) abort(403, 'Unauthorized Access.');
            }
        }

        // Get target BPPs
        $targetBppsQuery = Bpp::where('status', 'active');
        if ($user->role === 'admin_tim_kerja') {
            $targetBppsQuery->whereIn('id', $binaans);
        }
        if ($bankSop->target_type === 'selected_bpp') {
            $targetBppsQuery->whereIn('id', $bankSop->targets()->pluck('bpp_id'));
        }
        $targetBpps = $targetBppsQuery->get();
        $targetBppIds = $targetBpps->pluck('id')->toArray();

        // Get submissions for target BPPs
        $submissionsQuery = $bankSop->submissions()->with(['bpp', 'submitter', 'values.field'])
            ->whereIn('bpp_id', $targetBppIds);
            
        $submissions = $submissionsQuery->latest()->get();
        $submittedBppIds = $submissions->pluck('bpp_id')->unique()->toArray();
        
        $totalTarget = count($targetBppIds);
        $totalSubmitted = count($submittedBppIds);
        $totalNotSubmitted = $totalTarget - $totalSubmitted;
        
        $totalLate = 0;
        if ($bankSop->deadline) {
            $deadline = \Carbon\Carbon::parse($bankSop->deadline)->endOfDay();
            foreach ($submissions as $sub) {
                if ($sub->created_at->gt($deadline)) {
                    $totalLate++;
                }
            }
            if (now()->gt($deadline)) {
                $totalLate += $totalNotSubmitted;
            }
        }

        $submissions = $submissionsQuery->paginate(20);
        return view('admin.bank-sops.monitor', compact('bankSop', 'submissions', 'totalTarget', 'totalSubmitted', 'totalNotSubmitted', 'totalLate'));
    }

    public function export(Request $request, BankSop $bankSop)
    {
        $user = auth()->user();
        $binaans = $user->role === 'admin_tim_kerja' ? $user->bpp_binaans->pluck('id')->toArray() : [];
        if ($user->role === 'admin_tim_kerja') {
            if ($bankSop->target_type === 'selected_bpp') {
                $hasAccess = $bankSop->targets()->whereIn('bpp_id', $binaans)->exists();
                if (!$hasAccess) abort(403, 'Unauthorized Access.');
            }
        }

        $submissions = $bankSop->submissions()->with(['bpp', 'submitter', 'values.field']);
        if ($user->role === 'admin_tim_kerja') {
            $submissions->whereIn('bpp_id', $binaans);
        }
        $submissions = $submissions->get();
        $fields = $bankSop->fields;

        $exportData = [];
        foreach ($submissions as $submission) {
            $row = [
                'Tanggal Submit' => $submission->created_at->format('Y-m-d H:i:s'),
                'BPP' => $submission->bpp->name ?? '-',
                'Disubmit Oleh' => $submission->submitter->name ?? '-'
            ];
            
            foreach($fields as $field) {
                $val = $submission->values->where('field_id', $field->id)->first();
                $row[$field->label] = $val ? $val->value : '';
            }
            $exportData[] = $row;
        }

        $format = $request->query('format', 'csv');
        $extension = $format === 'xlsx' ? 'xlsx' : 'csv';
        $filename = "export_sop_{$bankSop->id}_" . date('Ymd_His') . "." . $extension;

        return (new \Rap2hpoutre\FastExcel\FastExcel(collect($exportData)))->download($filename);
    }
}
