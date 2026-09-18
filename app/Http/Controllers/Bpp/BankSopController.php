<?php

namespace App\Http\Controllers\Bpp;

use App\Http\Controllers\Controller;
use App\Models\BankSop;
use App\Models\BankSopSubmission;
use App\Models\BankSopSubmissionValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BankSopController extends Controller
{
    public function index()
    {
        $bppId = auth()->user()->bpp_id;
        
        $bankSops = BankSop::where('status', 'published')
            ->where(function($q) use ($bppId) {
                $q->where('target_type', 'all_bpp')
                  ->orWhereHas('targets', function($q2) use ($bppId) {
                      $q2->where('bpp_id', $bppId);
                  });
            })
            ->latest()
            ->paginate(20);

        return view('bpp.bank-sops.index', compact('bankSops'));
    }

    public function show(BankSop $bankSop)
    {
        if ($bankSop->status !== 'published') {
            abort(404);
        }

        $bppId = auth()->user()->bpp_id;
        if ($bankSop->target_type === 'selected_bpp') {
            $hasAccess = $bankSop->targets()->where('bpp_id', $bppId)->exists();
            if (!$hasAccess) abort(403, 'Unauthorized Access.');
        }

        $bankSop->load('fields');
        
        // Cek submission history
        $submissions = $bankSop->submissions()
            ->with(['submitter', 'values.field'])
            ->where('bpp_id', $bppId)
            ->latest()
            ->get();

        return view('bpp.bank-sops.show', compact('bankSop', 'submissions'));
    }

    public function download(BankSop $bankSop)
    {
        if ($bankSop->status !== 'published') abort(404);

        $bppId = auth()->user()->bpp_id;
        if ($bankSop->target_type === 'selected_bpp') {
            $hasAccess = $bankSop->targets()->where('bpp_id', $bppId)->exists();
            if (!$hasAccess) abort(403, 'Unauthorized Access.');
        }

        if (!$bankSop->file_path || !Storage::disk('local')->exists($bankSop->file_path)) {
            return back()->withErrors(['error' => 'File tidak ditemukan.']);
        }

        return Storage::disk('local')->download($bankSop->file_path, $bankSop->file_name);
    }

    public function submit(Request $request, BankSop $bankSop)
    {
        if ($bankSop->status !== 'published') abort(404);
        if ($bankSop->type !== 'internal_form') abort(400, 'Tipe SOP bukan formulir internal.');

        $bppId = auth()->user()->bpp_id;
        if ($bankSop->target_type === 'selected_bpp') {
            $hasAccess = $bankSop->targets()->where('bpp_id', $bppId)->exists();
            if (!$hasAccess) abort(403, 'Unauthorized Access.');
        }

        if ($bankSop->deadline && now()->greaterThan($bankSop->deadline)) {
            return back()->withErrors(['error' => 'Batas waktu pengisian form sudah lewat.']);
        }

        // Validasi form dinamis
        $rules = [];
        $fields = $bankSop->fields;
        foreach ($fields as $field) {
            $rule = [];
            if ($field->is_required) {
                $rule[] = 'required';
            } else {
                $rule[] = 'nullable';
            }

            if ($field->type === 'number') {
                $rule[] = 'numeric';
            } elseif ($field->type === 'date') {
                $rule[] = 'date';
            } else {
                $rule[] = 'string';
            }

            $rules['field_' . $field->id] = implode('|', $rule);
        }

        $validated = $request->validate($rules);

        $submission = BankSopSubmission::create([
            'bank_sop_id' => $bankSop->id,
            'bpp_id' => $bppId,
            'submitter_id' => auth()->id(),
            'status' => 'submitted',
        ]);

        foreach ($fields as $field) {
            if (isset($validated['field_' . $field->id])) {
                BankSopSubmissionValue::create([
                    'submission_id' => $submission->id,
                    'field_id' => $field->id,
                    'value' => $validated['field_' . $field->id],
                ]);
            }
        }

        return back()->with('success', 'Form berhasil disubmit.');
    }
}
