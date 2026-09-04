@extends('layouts.app')

@section('title', 'Profil BPP')
@section('header_title', 'Profil Balai Penyuluhan Pertanian')

@section('content')
<div style="background: #fff; border-radius: var(--rounded-lg); border: 1px solid var(--color-border); box-shadow: var(--shadow-sm); overflow: hidden; max-width: 800px;">
    <div class="card-header">
        <h4 class="card-header-title">Informasi {{ $bpp->name }}</h4>
    </div>

    <div style="padding: var(--sp-6);">
        @if(session('success'))
            <div style="background: #DCFCE7; color: #166534; padding: var(--sp-3) var(--sp-4); border-radius: var(--rounded); margin-bottom: var(--sp-6); font-size: 0.85rem;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background: #FEE2E2; color: #991B1B; padding: var(--sp-3) var(--sp-4); border-radius: var(--rounded); margin-bottom: var(--sp-6);">
                <ul style="margin: 0; padding-left: 1.5rem; font-size: 0.85rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div style="display: flex; gap: var(--sp-6); align-items: center; margin-bottom: var(--sp-6); padding-bottom: var(--sp-4); border-bottom: 1px solid var(--color-border-light);">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: var(--color-bg); display: flex; align-items: center; justify-content: center; font-size: 2rem; color: var(--color-primary);">
                <i class="fa-solid fa-building-user"></i>
            </div>
            <div>
                <h3 style="margin: 0 0 var(--sp-2) 0;">{{ $bpp->name }}</h3>
                <p style="margin: 0; color: var(--color-text-muted); font-size: 0.9rem;">
                    Kode: <span style="font-family: monospace;">{{ $bpp->code }}</span> &nbsp;|&nbsp;
                    Kecamatan: <strong>{{ $bpp->district }}</strong>
                </p>
                <p style="margin: var(--sp-1) 0 0 0; color: var(--color-text-muted); font-size: 0.8rem;">
                    *Beberapa data utama (Kode, Nama, Kecamatan) hanya dapat diubah oleh Administrator tingkat Kabupaten.
                </p>
            </div>
        </div>

        <form action="{{ route('bpp.profil.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid-2" style="margin-bottom: var(--sp-4);">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Nomor Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $bpp->phone) }}" style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Email BPP</label>
                    <input type="email" name="email" value="{{ old('email', $bpp->email) }}" style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;">
                </div>
            </div>

            <div style="margin-bottom: var(--sp-4);">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Alamat Lengkap Kantor *</label>
                <textarea name="address" required rows="3" style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit; resize: vertical;">{{ old('address', $bpp->address) }}</textarea>
            </div>

            <div style="margin-bottom: var(--sp-4);">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Koordinator (Contact Person)</label>
                <input type="text" name="contact_person" value="{{ old('contact_person', $bpp->contact_person) }}" style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;">
            </div>

            <div style="margin-bottom: var(--sp-6);">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Wilayah Kerja (Desa Binaan)</label>
                <textarea name="working_area" rows="3" style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit; resize: vertical;">{{ old('working_area', $bpp->working_area) }}</textarea>
                <small style="color: var(--color-text-muted); font-size: 0.75rem; display: block; margin-top: 4px;">Tuliskan daftar desa atau wilayah yang menjadi area binaan BPP ini.</small>
            </div>

            <div style="display: flex; gap: var(--sp-3); justify-content: flex-end; padding-top: var(--sp-4); border-top: 1px solid var(--color-border-light);">
                <button type="submit" class="btn btn-primary" style="padding: 8px 24px;">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
