@extends('layouts.app')

@section('title', 'Detail Bank SOP')
@section('header_title', 'Detail Bank SOP')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.bank-sops.index') }}" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
</div>

@if(session('success'))
<div class="alert alert-success mb-4">{{ session('success') }}</div>
@endif

<div class="row">
    <div class="col-md-7">
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="card-title m-0">Informasi Bank SOP</h4>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Judul</th>
                        <td>: {{ $bankSop->title }}</td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>: {{ $bankSop->category }}</td>
                    </tr>
                    <tr>
                        <th>Tipe</th>
                        <td>: 
                            @if($bankSop->type === 'document') <span class="badge bg-secondary">Dokumen</span>
                            @elseif($bankSop->type === 'link') <span class="badge bg-info">Link</span>
                            @elseif($bankSop->type === 'template') <span class="badge bg-warning">Template</span>
                            @elseif($bankSop->type === 'internal_form') <span class="badge bg-success">Form Internal</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>: 
                            @if($bankSop->status === 'published') <span class="badge bg-success">Aktif</span>
                            @else <span class="badge bg-secondary">{{ ucfirst($bankSop->status) }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Batas Waktu</th>
                        <td>: {{ $bankSop->deadline ? $bankSop->deadline->format('d M Y H:i') : 'Tidak Ada' }}</td>
                    </tr>
                    <tr>
                        <th>Target BPP</th>
                        <td>: 
                            @if($bankSop->target_type === 'all_bpp')
                                Seluruh BPP
                            @else
                                BPP Tertentu ({{ $bankSop->targets->count() }} BPP)
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>: {{ $bankSop->description ?: '-' }}</td>
                    </tr>
                </table>

                <div class="mt-4 border-top pt-3">
                    @if(in_array($bankSop->type, ['document', 'template']))
                        <a href="{{ route('admin.bank-sops.download', $bankSop->id) }}" class="btn btn-primary">
                            <i class="fa-solid fa-download"></i> Download File ({{ $bankSop->file_name }})
                        </a>
                    @elseif($bankSop->type === 'link')
                        <a href="{{ $bankSop->url }}" target="_blank" class="btn btn-primary">
                            <i class="fa-solid fa-external-link-alt"></i> Buka Link
                        </a>
                    @elseif($bankSop->type === 'internal_form')
                        <a href="{{ route('admin.bank-sops.monitor', $bankSop->id) }}" class="btn btn-success">
                            <i class="fa-solid fa-chart-line"></i> Monitoring Pengisian
                        </a>
                    @endif
                    
                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.bank-sops.edit', $bankSop->id) }}" class="btn btn-warning">
                        <i class="fa-solid fa-pen"></i> Edit
                    </a>
                    @endif
                </div>
            </div>
        </div>

        @if($bankSop->target_type === 'selected_bpp')
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title m-0">Daftar BPP Tertarget</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
                    @foreach($bankSop->targets as $target)
                    <li class="list-group-item">{{ $target->bpp->name }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
    </div>

    @if($bankSop->type === 'internal_form')
    <div class="col-md-5">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0">Form Builder</h5>
                @if(auth()->user()->role === 'admin')
                <button type="button" class="btn btn-sm btn-primary" onclick="showAddFieldForm()">
                    <i class="fa-solid fa-plus"></i> Tambah Field
                </button>
                @endif
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($bankSop->fields as $field)
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div>
                            <strong>{{ $field->label }}</strong> 
                            @if($field->is_required) <span class="text-danger">*</span> @endif
                            <br>
                            <small class="text-muted">Type: {{ $field->type }} | Name: {{ $field->field_name }}</small>
                            @if($field->options)
                                <br><small class="text-muted">Opsi: {{ implode(', ', $field->options) }}</small>
                            @endif
                        </div>
                        @if(auth()->user()->role === 'admin')
                        <form action="{{ route('admin.bank-sops.fields.destroy', $field->id) }}" method="POST" class="form-delete">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                        </form>
                        @endif
                    </li>
                    @empty
                    <li class="list-group-item text-center text-muted">Belum ada field form.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    @endif
</div>

@if(auth()->user()->role === 'admin' && $bankSop->type === 'internal_form')
<div class="card mt-2 mb-5" id="addFieldFormSection" style="display: none; border-top: 4px solid var(--color-primary);">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title m-0"><i class="fa-solid fa-puzzle-piece"></i> Tambah Field Form</h5>
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="document.getElementById('addFieldFormSection').style.display='none'">
            <i class="fa-solid fa-times"></i> Tutup
        </button>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.bank-sops.fields.store', $bankSop->id) }}" method="POST">
            @csrf
            <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                <div>
                    <label class="form-label" style="font-weight: 600;">Label <span class="text-danger">*</span></label>
                    <input type="text" name="label" class="form-control" placeholder="Contoh: Nama Kegiatan" required>
                    <small style="color: var(--color-text-muted);">Teks yang akan dibaca oleh BPP.</small>
                </div>
                <div>
                    <label class="form-label" style="font-weight: 600;">Name (Penamaan Kolom) <span class="text-danger">*</span></label>
                    <input type="text" name="field_name" class="form-control" placeholder="Contoh: nama_kegiatan" required>
                    <small style="color: var(--color-text-muted);">Gunakan huruf kecil & underscore (_).</small>
                </div>
                <div>
                    <label class="form-label" style="font-weight: 600;">Tipe Input <span class="text-danger">*</span></label>
                    <select name="type" class="form-control" required style="padding: 0.5rem;">
                        <option value="text">Teks Pendek</option>
                        <option value="textarea">Teks Panjang</option>
                        <option value="number">Angka</option>
                        <option value="date">Tanggal</option>
                        <option value="time">Waktu</option>
                        <option value="email">Email</option>
                        <option value="select">Dropdown Select</option>
                        <option value="radio">Radio Button</option>
                    </select>
                </div>
                <div>
                    <label class="form-label" style="font-weight: 600;">Opsi Pilihan <small class="fw-normal text-muted">(Khusus Select/Radio)</small></label>
                    <input type="text" name="options" class="form-control" placeholder="pisahkan dengan koma (A, B, C)">
                </div>
                <div>
                    <label class="form-label" style="font-weight: 600;">Urutan Tampil <span class="text-danger">*</span></label>
                    <input type="number" name="order" class="form-control" value="0" required>
                </div>
                <div style="display: flex; align-items: flex-end;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.5rem; background: var(--color-bg); border-radius: var(--rounded-md); border: 1px solid var(--color-border); width: 100%;">
                        <input type="checkbox" name="is_required" value="1" checked style="width: 18px; height: 18px; accent-color: var(--color-primary);">
                        <span style="font-weight: 600;">Wajib Diisi (Required)</span>
                    </label>
                </div>
            </div>
            <div class="mt-4 text-end" style="border-top: 1px solid var(--color-border-light); padding-top: 1rem;">
                <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.5rem;">
                    <i class="fa-solid fa-save"></i> Simpan Field
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function showAddFieldForm() {
        document.getElementById('addFieldFormSection').style.display = 'block';
        document.getElementById('addFieldFormSection').scrollIntoView({ behavior: 'smooth' });
    }
</script>
@endif

@endsection
