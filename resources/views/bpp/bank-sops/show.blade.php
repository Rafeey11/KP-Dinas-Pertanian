@extends('layouts.app')

@section('title', $bankSop->title)
@section('header_title', 'SOP: ' . $bankSop->title)

@section('content')
<div class="mb-4">
    <a href="{{ route('bpp.bank-sops.index') }}" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
</div>

@if(session('success'))
<div class="alert alert-success mb-4">{{ session('success') }}</div>
@endif
@if($errors->any())
<div class="alert alert-danger mb-4">
    <ul class="mb-0">
        @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="row">
    <div class="col-md-5">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title m-0">Informasi Bank SOP</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <th width="100">Kategori</th>
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
                        <th>Deadline</th>
                        <td>: 
                            @if($bankSop->deadline)
                                @if(now()->greaterThan($bankSop->deadline))
                                    <span class="text-danger fw-bold">{{ $bankSop->deadline->format('d M Y H:i') }} (Terlewat)</span>
                                @else
                                    <span class="text-warning fw-bold">{{ $bankSop->deadline->format('d M Y H:i') }}</span>
                                @endif
                            @else
                                Tidak Ada
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>: {{ $bankSop->description ?: '-' }}</td>
                    </tr>
                </table>

                <div class="mt-4 border-top pt-3 text-center">
                    @if(in_array($bankSop->type, ['document', 'template']))
                        <a href="{{ route('bpp.bank-sops.download', $bankSop->id) }}" class="btn btn-primary btn-lg w-100">
                            <i class="fa-solid fa-download"></i> Download File
                        </a>
                    @elseif($bankSop->type === 'link')
                        <a href="{{ $bankSop->url }}" target="_blank" class="btn btn-primary btn-lg w-100">
                            <i class="fa-solid fa-external-link-alt"></i> Buka Link
                        </a>
                    @elseif($bankSop->type === 'internal_form')
                        <button class="btn btn-primary btn-lg w-100 disabled">
                            <i class="fa-solid fa-arrow-right"></i> Silakan Isi Form di Samping
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($bankSop->type === 'internal_form')
    <div class="col-md-7">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title m-0">Formulir Isian</h5>
            </div>
            <div class="card-body">
                @if($bankSop->deadline && now()->greaterThan($bankSop->deadline))
                    <div class="alert alert-danger">
                        <i class="fa-solid fa-clock"></i> Batas waktu pengisian form sudah terlewat. Anda tidak dapat mensubmit form ini lagi.
                    </div>
                @else
                    <form action="{{ route('bpp.bank-sops.submit', $bankSop->id) }}" method="POST">
                        @csrf
                        @forelse($bankSop->fields as $field)
                        <div class="mb-3">
                            <label class="form-label">{{ $field->label }} @if($field->is_required) <span class="text-danger">*</span> @endif</label>
                            
                            @if($field->type === 'textarea')
                                <textarea name="field_{{ $field->id }}" class="form-control" rows="3" {{ $field->is_required ? 'required' : '' }}></textarea>
                            @elseif($field->type === 'select')
                                <select name="field_{{ $field->id }}" class="form-select" {{ $field->is_required ? 'required' : '' }}>
                                    <option value="">-- Pilih --</option>
                                    @if($field->options)
                                        @foreach($field->options as $opt)
                                            <option value="{{ $opt }}">{{ $opt }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            @elseif($field->type === 'radio')
                                <div>
                                    @if($field->options)
                                        @foreach($field->options as $idx => $opt)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="field_{{ $field->id }}" id="field_{{ $field->id }}_{{ $idx }}" value="{{ $opt }}" {{ $field->is_required ? 'required' : '' }}>
                                                <label class="form-check-label" for="field_{{ $field->id }}_{{ $idx }}">{{ $opt }}</label>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            @else
                                <input type="{{ $field->type }}" name="field_{{ $field->id }}" class="form-control" {{ $field->is_required ? 'required' : '' }}>
                            @endif
                        </div>
                        @empty
                        <p class="text-muted">Formulir belum memiliki field. Silakan hubungi admin.</p>
                        @endforelse

                        @if($bankSop->fields->count() > 0)
                            <div class="text-end">
                                <button type="submit" class="btn btn-success">
                                    <i class="fa-solid fa-paper-plane"></i> Submit Form
                                </button>
                            </div>
                        @endif
                    </form>
                @endif
            </div>
        </div>

        @if($submissions->count() > 0)
        <div class="card">
            <div class="card-header">
                <h5 class="card-title m-0">Riwayat Pengisian BPP Anda</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal Submit</th>
                                <th>Disubmit Oleh</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($submissions as $sub)
                            <tr>
                                <td>{{ $sub->created_at->format('d M Y H:i') }}</td>
                                <td>{{ $sub->submitter->name ?? '-' }}</td>
                                <td><span class="badge bg-success">Terkirim</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif
</div>
@endsection
