<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuditLogService;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = \App\Models\User::with('bpp')->orderBy('role')->orderBy('name')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $bpps = \App\Models\Bpp::orderBy('name')->get();
        return view('admin.users.form', compact('bpps'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username|max:255',
            'email' => 'required|string|email|unique:users,email|max:255',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,bpp,admin_tim_kerja',
            'bpp_id' => 'nullable|exists:bpps,id|required_if:role,bpp',
            'bpp_binaans' => 'nullable|array',
            'bpp_binaans.*' => 'exists:bpps,id'
        ]);

        $bppBinaans = $request->input('bpp_binaans', []);
        unset($validated['bpp_binaans']);

        $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        if (in_array($validated['role'], ['admin', 'admin_tim_kerja'])) $validated['bpp_id'] = null;

        $user = \App\Models\User::create($validated);

        if ($validated['role'] === 'admin_tim_kerja') {
            $user->bpp_binaans()->sync($bppBinaans);
        }

        AuditLogService::log(
            'created', 
            'User', 
            $user, 
            "Pembuatan akun {$user->role} bernama {$user->name}.", 
            null, 
            $user->toArray()
        );

        return redirect()->route('admin.users.index')->with('success', 'Akun pengguna berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $bpps = \App\Models\Bpp::orderBy('name')->get();
        return view('admin.users.form', compact('user', 'bpps'));
    }

    public function update(Request $request, string $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $oldData = $user->toArray();
        $oldBinaans = $user->bpp_binaans()->pluck('bpps.id')->toArray();

        $rules = [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,'.$id,
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'role' => 'required|in:admin,bpp,admin_tim_kerja',
            'bpp_id' => 'nullable|exists:bpps,id|required_if:role,bpp',
            'bpp_binaans' => 'nullable|array',
            'bpp_binaans.*' => 'exists:bpps,id'
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:6|confirmed';
        }

        $validated = $request->validate($rules);

        if ($request->filled('password')) {
            $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
            $oldData['password'] = '***';
        }
        if (in_array($validated['role'], ['admin', 'admin_tim_kerja'])) $validated['bpp_id'] = null;

        $bppBinaans = $request->input('bpp_binaans', []);
        unset($validated['bpp_binaans']);

        $user->update($validated);

        if ($validated['role'] === 'admin_tim_kerja') {
            $user->bpp_binaans()->sync($bppBinaans);
            if ($oldBinaans !== $bppBinaans) {
                AuditLogService::log(
                    'updated', 
                    'Tim Kerja', 
                    $user, 
                    "Update wilayah binaan Tim Kerja {$user->name}.", 
                    ['binaans' => $oldBinaans], 
                    ['binaans' => $bppBinaans]
                );
            }
        } else {
            $user->bpp_binaans()->sync([]);
        }

        AuditLogService::log(
            'updated', 
            'User', 
            $user, 
            "Perubahan data akun {$user->name}.", 
            $oldData, 
            $user->fresh()->toArray()
        );

        return redirect()->route('admin.users.index')->with('success', 'Akun pengguna berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $user = \App\Models\User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->withErrors(['error' => 'Anda tidak bisa menonaktifkan akun Anda sendiri.']);
        }
        
        $oldData = $user->toArray();
        $user->update(['status' => 'inactive']);

        AuditLogService::log(
            'deactivated', 
            'User', 
            $user, 
            "Penonaktifan akun {$user->name}.", 
            $oldData, 
            $user->fresh()->toArray()
        );

        return redirect()->route('admin.users.index')->with('success', 'Akun pengguna berhasil dinonaktifkan.');
    }
}

