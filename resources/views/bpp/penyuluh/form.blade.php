@extends('layouts.app')

@section('title', isset($penyuluh) ? 'Edit Penyuluh' : 'Tambah Penyuluh')
@section('header_title', 'Manajemen Personil Penyuluh')

@section('content')
<div style="background: #fff; border-radius: var(--rounded-lg); border: 1px solid var(--color-border); box-shadow: var(--shadow-sm); overflow: hidden; max-width: 800px;">
    <div class="card-header">
        <h4 class="card-header-title">{{ isset($penyuluh) ? 'Edit Data Penyuluh' : 'Tambah Data Penyuluh Baru' }}</h4>
        <a href="{{ route('bpp.penyuluh.index') }}" class="btn btn-sm btn-outline">Kembali</a>
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

        <form action="{{ isset($penyuluh) ? route('bpp.penyuluh.update', $penyuluh->id) : route('bpp.penyuluh.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($penyuluh))
                @method('PUT')
            @endif

            <div class="grid-2" style="margin-bottom: var(--sp-4);">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">NIP / No Identitas (Opsional)</label>
                    <input type="text" name="nip" value="{{ old('nip', $penyuluh->nip ?? '') }}" style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Nama Lengkap *</label>
                    <input type="text" name="name" value="{{ old('name', $penyuluh->name ?? '') }}" required style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;">
                </div>
            </div>

            <div class="grid-2" style="margin-bottom: var(--sp-4);">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Jabatan *</label>
                    <input type="text" name="position" value="{{ old('position', $penyuluh->position ?? 'Penyuluh Pertanian Lapangan') }}" required style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Status Kepegawaian *</label>
                    <select name="employment_status" required style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;">
                        <option value="PNS" {{ old('employment_status', $penyuluh->employment_status ?? '') === 'PNS' ? 'selected' : '' }}>PNS</option>
                        <option value="PPPK" {{ old('employment_status', $penyuluh->employment_status ?? '') === 'PPPK' ? 'selected' : '' }}>PPPK</option>
                        <option value="THL-TBPP" {{ old('employment_status', $penyuluh->employment_status ?? '') === 'THL-TBPP' ? 'selected' : '' }}>THL-TBPP</option>
                        <option value="Lainnya" {{ old('employment_status', $penyuluh->employment_status ?? '') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
            </div>

            <div class="grid-2" style="margin-bottom: var(--sp-4);">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Nomor Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $penyuluh->phone ?? '') }}" style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Email</label>
                    <input type="email" name="email" value="{{ old('email', $penyuluh->email ?? '') }}" style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;">
                </div>
            </div>

            <div style="margin-bottom: var(--sp-4);">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Alamat Domisili</label>
                <textarea name="address" rows="2" style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit; resize: vertical;">{{ old('address', $penyuluh->address ?? '') }}</textarea>
            </div>

            <div class="grid-2" style="margin-bottom: var(--sp-4);">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Wilayah Binaan (Desa)</label>
                    <input type="text" name="village_area" value="{{ old('village_area', $penyuluh->village_area ?? '') }}" style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Deskripsi Wilayah</label>
                    <input type="text" name="working_area" value="{{ old('working_area', $penyuluh->working_area ?? '') }}" style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;">
                </div>
            </div>

            <div style="margin-bottom: var(--sp-4);">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Kelompok Tani Binaan (Pisahkan dengan koma)</label>
                <textarea name="farmer_group" rows="2" style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit; resize: vertical;">{{ old('farmer_group', $penyuluh->farmer_group ?? '') }}</textarea>
            </div>

            <div class="grid-2" style="margin-bottom: var(--sp-4);">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Tampilkan di Website Publik? *</label>
                    <select name="is_public" required style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;">
                        <option value="1" {{ old('is_public', isset($penyuluh) ? $penyuluh->is_public : 1) == 1 ? 'selected' : '' }}>Ya, Tampilkan Profilnya</option>
                        <option value="0" {{ old('is_public', isset($penyuluh) ? $penyuluh->is_public : 1) == 0 ? 'selected' : '' }}>Sembunyikan</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Status Keaktifan *</label>
                    <select name="status" required style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;">
                        <option value="active" {{ old('status', $penyuluh->status ?? '') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status', $penyuluh->status ?? '') === 'inactive' ? 'selected' : '' }}>Nonaktif (Mutasi/Pensiun)</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: var(--sp-6);">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Foto Profil Penyuluh (Opsional)</label>
                @if(isset($penyuluh) && $penyuluh->photo)
                    <div style="margin-bottom: 8px;">
                        <img src="{{ asset('storage/' . $penyuluh->photo) }}" alt="Foto Penyuluh" style="width: 100px; height: 100px; object-fit: cover; border-radius: var(--rounded); border: 1px solid var(--color-border);">
                    </div>
                @endif
                <input type="file" name="photo" accept="image/*" style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;">
                <div style="font-size: 0.75rem; color: var(--color-text-muted); margin-top: 4px;">Maksimal 1MB. Format gambar (JPG/PNG).</div>
            </div>

            <div style="display: flex; gap: var(--sp-3); justify-content: flex-end; padding-top: var(--sp-4); border-top: 1px solid var(--color-border-light);">
                <a href="{{ route('bpp.penyuluh.index') }}" class="btn btn-outline" style="padding: 8px 16px;">Batal</a>
                <button type="submit" class="btn btn-primary" style="padding: 8px 24px;">Simpan Data</button>
            </div>
        </form>
    </div>
</div>
@endsection
