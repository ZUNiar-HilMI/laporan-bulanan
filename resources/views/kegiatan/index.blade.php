@extends('layouts.app')

@section('title', 'Hasil Laporan Kegiatan')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Hasil Laporan Kegiatan</h2>
    @if(auth()->user()->isAdmin())
    <div class="d-flex gap-2">
        <a href="/admin/export-excel?bulan={{ $bulan }}&tahun={{ $tahun }}" class="btn btn-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-spreadsheet me-1" viewBox="0 0 16 16">
                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V9H3V2a1 1 0 0 1 1-1h5.5v2zM3 12v-2h2v2H3zm0 1h2v2H4a1 1 0 0 1-1-1v-1zm3 2v-2h3v2H6zm4 0v-2h3v1a1 1 0 0 1-1 1h-2zm3-3h-3v-2h3v2zm0-3H3v-2h10v2z"/>
            </svg>
            Export Excel
        </a>
        <a href="/admin/export-word?bulan={{ $bulan }}&tahun={{ $tahun }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-word me-1" viewBox="0 0 16 16">
                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                <path d="M4.5 12.5A.5.5 0 0 1 5 12h3a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zm0-2A.5.5 0 0 1 5 10h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zm1.639-3.708l1.33 4.63a.5.5 0 0 0 .962 0l1.33-4.63a.5.5 0 1 0-.962-.278L8 9.317l-.799-2.803a.5.5 0 0 0-.962.278z"/>
            </svg>
            Export Word
        </a>
    </div>
    @endif
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Total Kegiatan</h6>
                <h3>{{ $kegiatan->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Total Anggaran</h6>
                <h3>Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kegiatan</th>
                        <th>Deskripsi</th>
                        <th>Anggaran</th>
                        <th>Lokasi</th>
                        <th>Pelapor</th>
                        <th>Tanggal</th>
                        <th>Foto Sebelum</th>
                        <th>Foto Sesudah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kegiatan as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $item->nama_kegiatan }}</strong></td>
                        <td>{{ Str::limit($item->deskripsi, 50) }}</td>
                        <td>Rp {{ number_format($item->anggaran, 0, ',', '.') }}</td>
                        <td>
                            <a href="https://maps.google.com/?q={{ $item->latitude }},{{ $item->longitude }}" target="_blank" class="text-decoration-none">
                                📍 Lihat Peta
                            </a>
                        </td>
                        <td>{{ $item->user->name ?? '-' }}</td>
                        <td>{{ $item->created_at->format('d M Y') }}</td>
                        <td>
                            @if($item->foto_sebelum)
                            <img src="{{ asset('storage/' . $item->foto_sebelum) }}" 
                                 alt="Foto Sebelum" 
                                 class="img-thumbnail foto-preview" 
                                 style="width: 80px; height: 60px; object-fit: cover; cursor: pointer;"
                                 onclick="openImageModal('{{ asset('storage/' . $item->foto_sebelum) }}', 'Foto Sebelum - {{ $item->nama_kegiatan }}')">
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($item->foto_sesudah)
                            <img src="{{ asset('storage/' . $item->foto_sesudah) }}" 
                                 alt="Foto Sesudah" 
                                 class="img-thumbnail foto-preview" 
                                 style="width: 80px; height: 60px; object-fit: cover; cursor: pointer;"
                                 onclick="openImageModal('{{ asset('storage/' . $item->foto_sesudah) }}', 'Foto Sesudah - {{ $item->nama_kegiatan }}')">
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">Belum ada kegiatan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Preview Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img id="modalImage" src="" alt="Preview" style="max-width: 100%; max-height: 70vh; object-fit: contain;">
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function openImageModal(imageSrc, title) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModalLabel').textContent = title;
    var modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
}
</script>

<style>
.foto-preview {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.foto-preview:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
[data-theme="dark"] .modal-content {
    background-color: var(--bg-card);
    border-color: var(--border-color);
}
[data-theme="dark"] .modal-header {
    border-bottom-color: var(--border-color);
}
[data-theme="dark"] .btn-close {
    filter: invert(1);
}
</style>

@endsection
