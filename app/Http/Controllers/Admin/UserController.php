<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
            'role' => 'required|in:admin,bpp',
            'bpp_id' => 'nullable|exists:bpps,id|required_if:role,bpp',
        ]);

        $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        if ($validated['role'] === 'admin') $validated['bpp_id'] = null;

        \App\Models\User::create($validated);

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

        $rules = [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,'.$id,
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'role' => 'required|in:admin,bpp',
            'bpp_id' => 'nullable|exists:bpps,id|required_if:role,bpp',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:6|confirmed';
        }

        $validated = $request->validate($rules);

        if ($request->filled('password')) {
            $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }
        if ($validated['role'] === 'admin') $validated['bpp_id'] = null;

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'Akun pengguna berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $user = \App\Models\User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->withErrors(['error' => 'Anda tidak bisa menghapus akun Anda sendiri.']);
        }
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Akun pengguna berhasil dihapus.');
    }
}
