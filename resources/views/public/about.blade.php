@extends('layouts.public')

@section('title', 'Tentang Kami')

@section('content')

<div class="page-header">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Beranda</a>
            <span class="breadcrumb-sep">›</span>
            <span>Tentang Kami</span>
        </div>
        <h1>Tentang CWS GARUT</h1>
        <p class="subtitle">Mengenal lebih dekat Co Working Space Kantor Penyuluh Kementerian Pertanian, Badan Penyuluhan dan Pengembangan Sumber Daya Manusia Pertanian (BPPSDMP) wilayah Garut</p>
    </div>
</div>

<div class="container" style="padding-top: var(--sp-8); padding-bottom: var(--sp-12);">
    <div class="grid-sidebar">
        {{-- LEFT --}}
        <div>
            <div class="card mb-6">
                <div class="card-body" style="padding: var(--sp-8);">
                    <h3 class="section-title mb-4">Profil CWS GARUT</h3>
                    <p style="margin-bottom: var(--sp-4); line-height: 1.8;">
                        CWS GARUT adalah sistem kerja berbasis wilayah komunitas yang dikembangkan sebagai pusat informasi terpadu yang menghubungkan CWS Pusat, seluruh BPP (Balai Penyuluhan Pertanian) di Kabupaten Garut, penyuluh pertanian, dan masyarakat luas di bawah naungan Kementerian Pertanian, BPPSDMP.
                    </p>
                    <p style="margin-bottom: var(--sp-4); line-height: 1.8;">
                        Sistem ini membantu proses perencanaan, pelaksanaan, pelaporan, monitoring, dan evaluasi kegiatan penyuluhan pertanian di wilayah Garut dengan memanfaatkan teknologi informasi untuk meningkatkan efisiensi dan transparansi kinerja penyuluh.
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-body" style="padding: var(--sp-8);">
                    <h3 class="section-title mb-4">Tujuan</h3>
                    <ul style="list-style-type: disc; padding-left: 1.5rem; line-height: 2;">
                        <li>Mempermudah BPP dalam mengirim dan mengelola laporan penyuluh secara digital.</li>
                        <li>Mempermudah Admin CWS dalam memantau pengumpulan laporan seluruh BPP.</li>
                        <li>Menyediakan media publikasi informasi dan berita kegiatan penyuluhan.</li>
                        <li>Menampilkan data keragaan dan profil seluruh BPP di Kabupaten Garut.</li>
                        <li>Menyimpan arsip laporan secara terstruktur dan mencegah kehilangan data.</li>
                        <li>Menyediakan informasi CWS GARUT yang dapat diakses masyarakat.</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- RIGHT --}}
        <div>
            <div class="card mb-6">
                <div class="card-body" style="padding: var(--sp-6);">
                    <h4 class="font-bold mb-4" style="padding-bottom: var(--sp-3); border-bottom: 1px solid var(--color-border);">Informasi Kontak</h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-icon"><i class="fa-solid fa-location-dot"></i></div>
                            <div>
                                <div class="info-label">Alamat</div>
                                <div class="info-value">Kementerian Pertanian | BPPSDMP<br>Jl. Pembangunan No. 181, Tarogong Kidul, Garut 44151</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon"><i class="fa-solid fa-phone"></i></div>
                            <div>
                                <div class="info-label">Telepon</div>
                                <div class="info-value">(0262) 233155</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon"><i class="fa-solid fa-envelope"></i></div>
                            <div>
                                <div class="info-label">Email</div>
                                <div class="info-value">info@cws-garut.go.id</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body" style="padding: var(--sp-6);">
                    <h4 class="font-bold mb-4" style="padding-bottom: var(--sp-3); border-bottom: 1px solid var(--color-border);">Quick Links</h4>
                    <div style="display: flex; flex-direction: column; gap: var(--sp-2);">
                        <a href="{{ route('bpp.index') }}" class="btn btn-outline btn-block btn-sm"><i class="fa-solid fa-building-columns"></i> Lihat Keragaan BPP</a>
                        <a href="{{ route('news.index') }}" class="btn btn-outline btn-block btn-sm"><i class="fa-solid fa-newspaper"></i> Lihat Berita</a>
                        <a href="{{ route('video.index') }}" class="btn btn-outline btn-block btn-sm"><i class="fa-solid fa-video"></i> Dashboard Video</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
