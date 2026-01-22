@extends('layouts.app')

@section('title', 'Kegiatan Saya')

@section('content')

<style>
    .badge-revision {
        background: #fef3c7;
        color: #92400e;
    }
    
    [data-theme="dark"] .badge-revision {
        background: #78350f;
        color: #fde68a;
    }
</style>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card" style="border-left: 4px solid #f59e0b;">
            <div class="card-body">
                <h6 class="text-muted mb-1">Pending</h6>
                <h2 class="mb-0">{{ $kegiatan->where('status', 'pending')->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card" style="border-left: 4px solid #10b981;">
            <div class="card-body">
                <h6 class="text-muted mb-1">Disetujui</h6>
                <h2 class="mb-0">{{ $kegiatan->where('status', 'approved')->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card" style="border-left: 4px solid #3b82f6;">
            <div class="card-body">
                <h6 class="text-muted mb-1">Perlu Revisi</h6>
                <h2 class="mb-0" style="{{ $kegiatan->where('status', 'revision')->count() > 0 ? 'color: #ef4444;' : '' }}">{{ $kegiatan->where('status', 'revision')->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card" style="border-left: 4px solid #ef4444;">
            <div class="card-body">
                <h6 class="text-muted mb-1">Ditolak</h6>
                <h2 class="mb-0">{{ $kegiatan->where('status', 'rejected')->count() }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">Daftar Kegiatan Saya</h5>
            <a href="/kegiatan/create" class="btn btn-primary">+ Lapor Kegiatan Baru</a>
        </div>

        @if($kegiatan->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kegiatan</th>
                        <th>Anggaran</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kegiatan as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $item->nama_kegiatan }}</strong>
                            <div class="text-muted small">{{ Str::limit($item->deskripsi, 40) }}</div>
                        </td>
                        <td>Rp {{ number_format($item->anggaran, 0, ',', '.') }}</td>
                        <td>
                            <a href="https://maps.google.com/?q={{ $item->latitude }},{{ $item->longitude }}" target="_blank" class="text-decoration-none">
                                📍 Peta
                            </a>
                        </td>
                        <td>
                            @if($item->status == 'pending')
                                <span class="badge badge-pending">Pending</span>
                            @elseif($item->status == 'approved')
                                <span class="badge badge-approved">Disetujui</span>
                            @elseif($item->status == 'revision')
                                <span class="badge badge-revision">Perlu Revisi</span>
                            @else
                                <span class="badge badge-rejected">Ditolak</span>
                            @endif
                        </td>
                        <td>{{ $item->created_at->format('d M Y') }}</td>
                        <td>
                            @if($item->status == 'revision')
                                <div class="d-flex gap-1">
                                    <button class="btn btn-sm btn-warning" onclick="showRevisionDetail('{{ addslashes($item->nama_kegiatan) }}', '{{ addslashes($item->catatan_revisi) }}')">
                                        📝 Revisi
                                    </button>
                                    <a href="/kegiatan/{{ $item->id }}/edit" class="btn btn-sm btn-primary">
                                        ✏️ Edit
                                    </a>
                                </div>
                            @elseif($item->status == 'pending')
                                <span class="text-muted small">Menunggu...</span>
                            @elseif($item->status == 'approved')
                                <span class="text-success small">✓ Selesai</span>
                            @else
                                <span class="text-danger small">✕ Ditolak</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <p>Belum ada kegiatan yang dilaporkan</p>
            <a href="/kegiatan/create" class="btn btn-primary mt-2">Lapor Kegiatan Pertama</a>
        </div>
        @endif
    </div>
</div>

<!-- Modal Detail Revisi -->
<div id="revisionDetailModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: var(--bg-card); border-radius: 16px; max-width: 500px; width: 90%; padding: 0; overflow: hidden; box-shadow: 0 25px 50px rgba(0,0,0,0.25);">
        <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); padding: 20px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h5 style="margin: 0;">📝 Catatan Revisi</h5>
                <button onclick="closeRevisionDetail()" style="background: rgba(255,255,255,0.2); border: none; color: white; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; font-size: 1.25rem;">&times;</button>
            </div>
        </div>
        <div style="padding: 24px;">
            <p class="text-muted mb-2">Kegiatan:</p>
            <h6 id="revisionDetailName" style="margin-bottom: 16px;"></h6>
            <p class="text-muted mb-2">Catatan dari Admin:</p>
            <div id="revisionDetailContent" style="background: var(--toggle-bg); padding: 16px; border-radius: 12px; border-left: 4px solid #f59e0b;"></div>
            <div class="mt-4 text-muted small">
                <em>Silakan perbaiki kegiatan sesuai catatan di atas, lalu hubungi admin untuk verifikasi ulang.</em>
            </div>
        </div>
    </div>
</div>

<script>
function showRevisionDetail(nama, catatan) {
    document.getElementById('revisionDetailName').textContent = nama;
    document.getElementById('revisionDetailContent').textContent = catatan;
    document.getElementById('revisionDetailModal').style.display = 'flex';
}

function closeRevisionDetail() {
    document.getElementById('revisionDetailModal').style.display = 'none';
}

document.getElementById('revisionDetailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRevisionDetail();
    }
});
</script>

@endsection

