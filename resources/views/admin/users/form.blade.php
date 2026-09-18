@extends('layouts.app')

@section('title', isset($user) ? 'Edit Akun' : 'Tambah Akun')
@section('header_title', 'Manajemen Akun Pengguna')

@section('content')
<div style="background: #fff; border-radius: var(--rounded-lg); border: 1px solid var(--color-border); box-shadow: var(--shadow-sm); overflow: hidden; max-width: 800px;">
    <div class="card-header">
        <h4 class="card-header-title">{{ isset($user) ? 'Edit Akun Pengguna' : 'Tambah Akun Baru' }}</h4>
        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline">Kembali</a>
    </div>

    <div style="padding: var(--sp-6);">
        @if($errors->any())
            <div style="background: #FEE2E2; color: #991B1B; padding: var(--sp-3) var(--sp-4); border-radius: var(--rounded); margin-bottom: var(--sp-6);">
                <ul style="margin: 0; padding-left: 1.5rem; font-size: 0.85rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ isset($user) ? route('admin.users.update', $user->id) : route('admin.users.store') }}" method="POST">
            @csrf
            @if(isset($user))
                @method('PUT')
            @endif

            <div class="grid-2" style="margin-bottom: var(--sp-4);">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Nama Lengkap / Instansi *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Email *</label>
                    <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;">
                </div>
            </div>

            <div style="margin-bottom: var(--sp-4);">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Username *</label>
                <input type="text" name="username" value="{{ old('username', $user->username ?? '') }}" required style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;">
                <small style="color: var(--color-text-muted); font-size: 0.75rem;">Username akan digunakan untuk login.</small>
            </div>

            <div class="grid-2" style="margin-bottom: var(--sp-6);">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Password {{ isset($user) ? '(Kosongkan jika tidak diubah)' : '*' }}</label>
                    <input type="password" name="password" {{ isset($user) ? '' : 'required' }} style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" {{ isset($user) ? '' : 'required' }} style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;">
                </div>
            </div>

            <div style="padding: var(--sp-4); background: var(--color-bg); border-radius: var(--rounded-lg); margin-bottom: var(--sp-6);">
                <h5 style="margin-top: 0; margin-bottom: var(--sp-3); font-size: 0.95rem;">Pengaturan Hak Akses (Role)</h5>
                
                <div style="margin-bottom: var(--sp-3);">
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Tipe Akun *</label>
                    <select name="role" id="role-selector" required style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;">
                        <option value="">-- Pilih Tipe Akun --</option>
                        <option value="admin" {{ old('role', $user->role ?? '') === 'admin' ? 'selected' : '' }}>Administrator CWS</option>
                        <option value="admin_tim_kerja" {{ old('role', $user->role ?? '') === 'admin_tim_kerja' ? 'selected' : '' }}>Admin Tim Kerja</option>
                        <option value="bpp" {{ old('role', $user->role ?? '') === 'bpp' ? 'selected' : '' }}>Operator BPP</option>
                    </select>
                </div>

                <div id="bpp-selector-group" style="display: {{ old('role', $user->role ?? '') === 'bpp' ? 'block' : 'none' }}; margin-bottom: var(--sp-3);">
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Pilih BPP *</label>
                    <select name="bpp_id" id="bpp-id-select" style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;">
                        <option value="">-- Pilih BPP --</option>
                        @foreach($bpps as $bpp)
                            <option value="{{ $bpp->id }}" {{ old('bpp_id', $user->bpp_id ?? '') == $bpp->id ? 'selected' : '' }}>{{ $bpp->name }} ({{ $bpp->district }})</option>
                        @endforeach
                    </select>
                    <small style="color: var(--color-text-muted); font-size: 0.75rem;">Akun ini hanya dapat mengelola data BPP yang dipilih.</small>
                </div>

                <div id="bpp-binaan-group" style="display: {{ old('role', $user->role ?? '') === 'admin_tim_kerja' ? 'block' : 'none' }};">
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Pilih BPP Binaan (Bisa lebih dari satu)</label>
                    <select name="bpp_binaans[]" id="bpp-binaan-select" multiple style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit; min-height: 120px;">
                        @php
                            $selectedBinaans = isset($user) ? $user->bpp_binaans->pluck('id')->toArray() : [];
                            $oldBinaans = old('bpp_binaans', $selectedBinaans);
                        @endphp
                        @foreach($bpps as $bpp)
                            <option value="{{ $bpp->id }}" {{ in_array($bpp->id, $oldBinaans) ? 'selected' : '' }}>{{ $bpp->name }} ({{ $bpp->district }})</option>
                        @endforeach
                    </select>
                    <small style="color: var(--color-text-muted); font-size: 0.75rem;">Tahan tombol CTRL (Windows) atau CMD (Mac) untuk memilih lebih dari satu BPP.</small>
                </div>
            </div>

            <div style="display: flex; gap: var(--sp-3); justify-content: flex-end; padding-top: var(--sp-4); border-top: 1px solid var(--color-border-light);">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline" style="padding: 8px 16px;">Batal</a>
                <button type="submit" class="btn btn-primary" style="padding: 8px 24px;">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelector = document.getElementById('role-selector');
        const bppGroup = document.getElementById('bpp-selector-group');
        const bppSelect = document.getElementById('bpp-id-select');

        const bppBinaanGroup = document.getElementById('bpp-binaan-group');
        const bppBinaanSelect = document.getElementById('bpp-binaan-select');

        function toggleBppSelector() {
            if (roleSelector.value === 'bpp') {
                bppGroup.style.display = 'block';
                bppSelect.setAttribute('required', 'required');
                bppBinaanGroup.style.display = 'none';
            } else if (roleSelector.value === 'admin_tim_kerja') {
                bppGroup.style.display = 'none';
                bppSelect.removeAttribute('required');
                bppSelect.value = '';
                bppBinaanGroup.style.display = 'block';
            } else {
                bppGroup.style.display = 'none';
                bppSelect.removeAttribute('required');
                bppSelect.value = '';
                bppBinaanGroup.style.display = 'none';
                bppBinaanSelect.selectedIndex = -1;
            }
        }

        roleSelector.addEventListener('change', toggleBppSelector);
        
        // Run once on page load (for editing or old input)
        toggleBppSelector();
    });
</script>
@endsection
