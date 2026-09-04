@extends('layouts.app')

@section('title', isset($bpp) ? 'Edit BPP' : 'Tambah BPP')
@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map { height: 400px; width: 100%; border-radius: var(--rounded); border: 1px solid var(--color-border); }
</style>
@endsection

@section('content')
<div style="background: #fff; border-radius: var(--rounded-lg); border: 1px solid var(--color-border); box-shadow: var(--shadow-sm); overflow: hidden; max-width: 800px;">
    <div class="card-header">
        <h4 class="card-header-title">{{ isset($bpp) ? 'Edit Data BPP' : 'Tambah Data BPP Baru' }}</h4>
        <a href="{{ route('admin.bpp.index') }}" class="btn btn-sm btn-outline">Kembali</a>
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

        <form action="{{ isset($bpp) ? route('admin.bpp.update', $bpp->id) : route('admin.bpp.store') }}" method="POST">
            @csrf
            @if(isset($bpp))
                @method('PUT')
            @endif

            <div class="grid-2" style="margin-bottom: var(--sp-4);">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Kode BPP *</label>
                    <input type="text" name="code" value="{{ old('code', $bpp->code ?? '') }}" required style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Kecamatan *</label>
                    <input type="text" name="district" value="{{ old('district', $bpp->district ?? '') }}" required style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;">
                </div>
            </div>

            <div style="margin-bottom: var(--sp-4);">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Nama BPP *</label>
                <input type="text" name="name" value="{{ old('name', $bpp->name ?? '') }}" required style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;">
            </div>

            <div style="margin-bottom: var(--sp-4);">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Alamat *</label>
                <textarea name="address" required rows="3" style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit; resize: vertical;">{{ old('address', $bpp->address ?? '') }}</textarea>
            </div>

            <div class="grid-2" style="margin-bottom: var(--sp-4);">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Nomor Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $bpp->phone ?? '') }}" style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Email</label>
                    <input type="email" name="email" value="{{ old('email', $bpp->email ?? '') }}" style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;">
                </div>
            </div>

            <div class="grid-2" style="margin-bottom: var(--sp-4);">
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Koordinator (Contact Person)</label>
                    <input type="text" name="contact_person" value="{{ old('contact_person', $bpp->contact_person ?? '') }}" style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Status *</label>
                    <select name="status" required style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit;">
                        <option value="active" {{ old('status', $bpp->status ?? '') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status', $bpp->status ?? '') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: var(--sp-6);">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Wilayah Kerja (Deskripsi)</label>
                <textarea name="working_area" rows="2" style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit; resize: vertical;">{{ old('working_area', $bpp->working_area ?? '') }}</textarea>
            </div>

            <div style="margin-bottom: var(--sp-4);">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: var(--sp-2);">Titik Koordinat (Pilih Lokasi di Peta)</label>
                <div id="map" style="margin-bottom: var(--sp-3);"></div>
                <div class="grid-2">
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: var(--sp-1); color: var(--color-text-muted);">Latitude</label>
                        <input type="text" name="latitude" id="latitude" value="{{ old('latitude', $bpp->latitude ?? '') }}" style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit; background: #fff;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: var(--sp-1); color: var(--color-text-muted);">Longitude</label>
                        <input type="text" name="longitude" id="longitude" value="{{ old('longitude', $bpp->longitude ?? '') }}" style="width: 100%; padding: var(--sp-2); border: 1px solid var(--color-border); border-radius: var(--rounded); font-family: inherit; background: #fff;">
                    </div>
                </div>
                <small style="color: var(--color-text-muted); font-size: 0.75rem; display: block; margin-top: 4px;">Klik pada peta untuk menentukan lokasi baru, atau geser pin merah.</small>
            </div>

            <div style="display: flex; gap: var(--sp-3); justify-content: flex-end; padding-top: var(--sp-4); border-top: 1px solid var(--color-border-light);">
                <a href="{{ route('admin.bpp.index') }}" class="btn btn-outline" style="padding: 8px 16px;">Batal</a>
                <button type="submit" class="btn btn-primary" style="padding: 8px 24px;">Simpan Data</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Default Garut Center
        let defaultLat = -7.2278;
        let defaultLng = 107.9087;

        let existingLat = document.getElementById('latitude').value;
        let existingLng = document.getElementById('longitude').value;

        if (existingLat && existingLng) {
            defaultLat = parseFloat(existingLat);
            defaultLng = parseFloat(existingLng);
        }

        let map = L.map('map').setView([defaultLat, defaultLng], 12);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        let marker = L.marker([defaultLat, defaultLng], {
            draggable: true
        }).addTo(map);

        function updateInputs(lat, lng) {
            document.getElementById('latitude').value = lat.toFixed(7);
            document.getElementById('longitude').value = lng.toFixed(7);
        }

        marker.on('dragend', function (e) {
            let position = marker.getLatLng();
            updateInputs(position.lat, position.lng);
        });

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateInputs(e.latlng.lat, e.latlng.lng);
        });

        function updateMapFromInputs() {
            let lat = parseFloat(document.getElementById('latitude').value);
            let lng = parseFloat(document.getElementById('longitude').value);
            
            if (!isNaN(lat) && !isNaN(lng)) {
                let newLatLng = new L.LatLng(lat, lng);
                marker.setLatLng(newLatLng);
                map.panTo(newLatLng);
            }
        }

        document.getElementById('latitude').addEventListener('input', updateMapFromInputs);
        document.getElementById('longitude').addEventListener('input', updateMapFromInputs);
    });
</script>
@endsection
