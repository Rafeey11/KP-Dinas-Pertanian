@extends('layouts.app')
@section('title', isset($reportType) ? 'Edit Jenis Laporan' : 'Tambah Jenis Laporan')
@section('header_title', 'Kelola Jenis Laporan')
@section('content')
<div style="background:#fff;border-radius:var(--rounded-lg);border:1px solid var(--color-border);box-shadow:var(--shadow-sm);overflow:hidden;max-width:600px;">
    <div class="card-header"><h4 class="card-header-title">{{ isset($reportType) ? 'Edit' : 'Tambah' }} Jenis Laporan</h4><a href="{{ route('admin.report-types.index') }}" class="btn btn-sm btn-outline">Kembali</a></div>
    <div style="padding:var(--sp-6);">
        @if($errors->any())<div style="background:#FEE2E2;color:#991B1B;padding:var(--sp-3);border-radius:var(--rounded);margin-bottom:var(--sp-6);"><ul style="margin:0;padding-left:1.5rem;font-size:0.85rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
        <form action="{{ isset($reportType) ? route('admin.report-types.update', $reportType) : route('admin.report-types.store') }}" method="POST">@csrf @if(isset($reportType))@method('PUT')@endif
            <div style="margin-bottom:var(--sp-4);"><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:var(--sp-2);">Nama Jenis Laporan *</label><input type="text" name="name" value="{{ old('name', $reportType->name ?? '') }}" required style="width:100%;padding:var(--sp-2);border:1px solid var(--color-border);border-radius:var(--rounded);font-family:inherit;"></div>
            <div style="margin-bottom:var(--sp-6);"><label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:var(--sp-2);">Deskripsi</label><textarea name="description" rows="3" style="width:100%;padding:var(--sp-2);border:1px solid var(--color-border);border-radius:var(--rounded);font-family:inherit;resize:vertical;">{{ old('description', $reportType->description ?? '') }}</textarea></div>
            <div style="display:flex;gap:var(--sp-3);justify-content:flex-end;padding-top:var(--sp-4);border-top:1px solid var(--color-border-light);"><a href="{{ route('admin.report-types.index') }}" class="btn btn-outline" style="padding:8px 16px;">Batal</a><button type="submit" class="btn btn-primary" style="padding:8px 24px;">Simpan</button></div>
        </form></div></div>
@endsection
