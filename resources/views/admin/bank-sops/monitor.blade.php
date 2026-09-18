@extends('layouts.app')

@section('title', 'Monitoring SOP: ' . $bankSop->title)
@section('header_title', 'Monitoring SOP')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <a href="{{ route('admin.bank-sops.show', $bankSop->id) }}" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
    <a href="{{ route('admin.bank-sops.export', $bankSop->id) }}" class="btn btn-success">
        <i class="fa-solid fa-file-excel"></i> Export CSV
    </a>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title m-0">{{ $bankSop->title }}</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Tanggal Submit</th>
                        <th>BPP</th>
                        <th>Disubmit Oleh</th>
                        @foreach($bankSop->fields as $field)
                            <th>{{ $field->label }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($submissions as $submission)
                    <tr>
                        <td>{{ $submission->created_at->format('d M Y H:i') }}</td>
                        <td>{{ $submission->bpp->name ?? '-' }}</td>
                        <td>{{ $submission->submitter->name ?? '-' }}</td>
                        @foreach($bankSop->fields as $field)
                            @php
                                $val = $submission->values->where('field_id', $field->id)->first();
                            @endphp
                            <td>{{ $val ? $val->value : '-' }}</td>
                        @endforeach
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ 3 + $bankSop->fields->count() }}" class="text-center text-muted py-4">
                            Belum ada data yang disubmit.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($submissions->hasPages())
    <div class="card-footer bg-white border-top">
        {{ $submissions->links() }}
    </div>
    @endif
</div>
@endsection
