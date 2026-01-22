@extends('layouts.app')

@section('title', 'Verifikasi Kegiatan')

@section('content')

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card" style="border-left: 4px solid #f59e0b;">
            <div class="card-body">
                <h6 class="text-muted mb-1">Menunggu Verifikasi</h6>
                <h2 class="mb-0">{{ $pending->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card" style="border-left: 4px solid #10b981;">
            <div class="card-body">
                <h6 class="text-muted mb-1">Disetujui</h6>
                <h2 class="mb-0">{{ $approved }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card" style="border-left: 4px solid #ef4444;">
            <div class="card-body">
                <h6 class="text-muted mb-1">Ditolak</h6>
                <h2 class="mb-0">{{ $rejected }}</h2>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card">
    <div class="card-body">
        <h5 class="mb-4">Kegiatan Menunggu Verifikasi</h5>

        @if($pending->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Pelapor</th>
                        <th>Nama Kegiatan</th>
                        <th>Anggaran</th>
                        <th>Lokasi</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pending as $item)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #7c3aed, #a855f7); display: flex; align-items: center; justify-content: center; color: white; font-size: 0.75rem; font-weight: 600;">
                                    @if($item->user && $item->user->avatar)
                                        <img src="{{ asset('storage/' . $item->user->avatar) }}" alt="" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                    @else
                                        {{ $item->user ? strtoupper(substr($item->user->name, 0, 1)) : '?' }}
                                    @endif
                                </div>
                                <span>{{ $item->user->name ?? 'Unknown' }}</span>
                            </div>
                        </td>
                        <td>
                            <strong>{{ $item->nama_kegiatan }}</strong>
                            <div class="text-muted small">{{ Str::limit($item->deskripsi, 40) }}</div>
                        </td>
                        <td>Rp {{ number_format($item->anggaran, 0, ',', '.') }}</td>
                        <td>
                            <a href="https://maps.google.com/?q={{ $item->latitude }},{{ $item->longitude }}" target="_blank" class="text-decoration-none">
                                📍 Lihat Peta
                            </a>
                        </td>
                        <td>{{ $item->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <form action="/admin/verifikasi/{{ $item->id }}/approve" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">✓ Setuju</button>
                                </form>
                                <button type="button" class="btn btn-sm btn-warning" onclick="openRevisionModal({{ $item->id }}, '{{ addslashes($item->nama_kegiatan) }}')">
                                    ✎ Revisi
                                </button>
                                <form action="/admin/verifikasi/{{ $item->id }}/reject" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">✕ Tolak</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <div style="font-size: 3rem; margin-bottom: 16px;">✅</div>
            <p>Tidak ada kegiatan yang menunggu verifikasi</p>
        </div>
        @endif
    </div>
</div>

<!-- Modal Revisi -->
<div id="revisionModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: var(--bg-card); border-radius: 16px; max-width: 500px; width: 90%; padding: 24px; box-shadow: 0 25px 50px rgba(0,0,0,0.25);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h5 style="margin: 0;">Minta Revisi</h5>
            <button onclick="closeRevisionModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted);">&times;</button>
        </div>
        <p class="text-muted mb-3">Kegiatan: <strong id="revisionKegiatanName"></strong></p>
        <form id="revisionForm" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Catatan Revisi <span class="text-danger">*</span></label>
                <textarea name="catatan_revisi" class="form-control" rows="4" required placeholder="Jelaskan apa yang perlu diperbaiki oleh anggota..."></textarea>
            </div>
            <div class="d-flex gap-2 justify-content-end">
                <button type="button" class="btn btn-secondary" onclick="closeRevisionModal()">Batal</button>
                <button type="submit" class="btn btn-warning">Kirim Permintaan Revisi</button>
            </div>
        </form>
    </div>
</div>

<script>
function openRevisionModal(id, nama) {
    document.getElementById('revisionModal').style.display = 'flex';
    document.getElementById('revisionKegiatanName').textContent = nama;
    document.getElementById('revisionForm').action = '/admin/verifikasi/' + id + '/revision';
}

function closeRevisionModal() {
    document.getElementById('revisionModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('revisionModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRevisionModal();
    }
});
</script>

@endsection
