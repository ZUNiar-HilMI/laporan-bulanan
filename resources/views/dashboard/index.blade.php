@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card" style="border-left: 4px solid #7c3aed;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-1">Total Kegiatan Bulan Ini</h6>
                        <h2 class="mb-0">{{ $totalKegiatan }}</h2>
                        <small class="text-muted">Kegiatan yang sudah diverifikasi</small>
                    </div>

                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card" style="border-left: 4px solid #10b981;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-1">Total Anggaran Bulan Ini</h6>
                        <h2 class="mb-0">Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</h2>
                        <small class="text-muted">Total dari kegiatan terverifikasi</small>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@if(Auth::user()->isAnggota())
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card" style="border-left: 4px solid #3b82f6;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-1">Kegiatan Saya</h6>
                        <h2 class="mb-0">{{ $myKegiatan ?? 0 }}</h2>
                        <small class="text-muted">Total kegiatan yang dilaporkan</small>
                    </div>

                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card" style="border-left: 4px solid #f59e0b;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-1">Menunggu Verifikasi</h6>
                        <h2 class="mb-0">{{ $myPending ?? 0 }}</h2>
                        <small class="text-muted">Kegiatan dalam antrian</small>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card" style="border-left: 4px solid #ef4444; {{ ($myRevision ?? 0) > 0 ? 'animation: pulse 2s infinite;' : '' }}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-1">Perlu Revisi</h6>
                        <h2 class="mb-0" style="{{ ($myRevision ?? 0) > 0 ? 'color: #ef4444;' : '' }}">{{ $myRevision ?? 0 }}</h2>
                        <small class="text-muted">Perlu diperbaiki</small>
                    </div>
                    @if(($myRevision ?? 0) > 0)
                    <button class="btn btn-sm btn-outline-danger" onclick="showRevisionPopup()">Lihat Detail</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($revisionKegiatan) && $revisionKegiatan->count() > 0)
<!-- Revision Alert Banner -->
<div class="alert alert-warning mb-4" style="border-left: 4px solid #f59e0b; border-radius: 8px;">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <strong>⚠️ Perhatian!</strong> Anda memiliki {{ $revisionKegiatan->count() }} kegiatan yang perlu direvisi.
        </div>
        <button class="btn btn-warning btn-sm" onclick="showRevisionPopup()">Lihat Revisi</button>
    </div>
</div>
@endif
@endif

<!-- Budget Chart Section -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="mb-1">Statistik Penggunaan Anggaran</h5>
                        <small class="text-muted">Data 12 bulan terakhir</small>
                    </div>
                </div>

                <!-- Budget Overview Cards -->
                @php
                    $anggaranTersedia = 1000000000; // 1 Miliar
                    $anggaranTerpakai = collect($monthlyStats)->sum('budget');
                    $anggaranSisa = $anggaranTersedia - $anggaranTerpakai;
                    $persentaseTerpakai = $anggaranTersedia > 0 ? min(($anggaranTerpakai / $anggaranTersedia) * 100, 100) : 0;
                @endphp

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="budget-card" style="background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%); border-radius: 12px; padding: 20px; color: white;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <small style="opacity: 0.8;">Anggaran Tersedia (Setahun)</small>
                                    <h3 class="mb-0 mt-1" style="font-weight: 700;">Rp {{ number_format($anggaranTersedia, 0, ',', '.') }}</h3>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="budget-card" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); border-radius: 12px; padding: 20px; color: white;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <small style="opacity: 0.8;">Anggaran Terpakai</small>
                                    <h3 class="mb-0 mt-1" style="font-weight: 700;">Rp {{ number_format($anggaranTerpakai, 0, ',', '.') }}</h3>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="budget-card" style="background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%); border-radius: 12px; padding: 20px; color: white;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <small style="opacity: 0.8;">Sisa Anggaran</small>
                                    <h3 class="mb-0 mt-1" style="font-weight: 700;">Rp {{ number_format(max($anggaranSisa, 0), 0, ',', '.') }}</h3>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-semibold">Penggunaan Anggaran Tahunan</span>
                        <span class="badge {{ $persentaseTerpakai >= 90 ? 'bg-danger' : ($persentaseTerpakai >= 70 ? 'bg-warning' : 'bg-success') }}" style="font-size: 0.9rem; padding: 6px 12px;">
                            {{ number_format($persentaseTerpakai, 1) }}% Terpakai
                        </span>
                    </div>
                    <div class="progress" style="height: 24px; border-radius: 12px; background: var(--toggle-bg);">
                        <div class="progress-bar {{ $persentaseTerpakai >= 90 ? 'bg-danger' : ($persentaseTerpakai >= 70 ? 'bg-warning' : 'bg-success') }}" 
                             role="progressbar" 
                             style="width: {{ $persentaseTerpakai }}%; border-radius: 12px; transition: width 1s ease-in-out;"
                             aria-valuenow="{{ $persentaseTerpakai }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <small class="text-muted">Rp 0</small>
                        <small class="text-muted">Rp 1.000.000.000</small>
                    </div>
                </div>

                <!-- Chart -->
                <div style="height: 350px;">
                    <canvas id="budgetChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    @if(Auth::user()->isAnggota())
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body text-center py-5">

                <h5>Lapor Kegiatan Baru</h5>
                <p class="text-muted">Laporkan kegiatan bulanan dengan lokasi GPS</p>
                <a href="/kegiatan/create" class="btn btn-primary">Mulai Lapor</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body text-center py-5">

                <h5>Lihat Kegiatan Saya</h5>
                <p class="text-muted">Pantau status kegiatan yang sudah dilaporkan</p>
                <a href="/kegiatan/saya" class="btn btn-outline-primary">Lihat Semua</a>
            </div>
        </div>
    </div>
    @endif

    @if(Auth::user()->isAdmin())
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body text-center py-5">

                <h5>Verifikasi Kegiatan</h5>
                <p class="text-muted">Setujui atau tolak kegiatan yang dilaporkan</p>
                <a href="/admin/verifikasi" class="btn btn-primary">Buka Verifikasi</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body text-center py-5">

                <h5>Hasil Laporan</h5>
                <p class="text-muted">Lihat semua kegiatan yang sudah diverifikasi</p>
                <a href="/kegiatan" class="btn btn-outline-primary">Lihat Laporan</a>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('budgetChart').getContext('2d');
    
    // Data from PHP
    const monthlyData = @json($monthlyStats);
    const labels = monthlyData.map(item => item.short_month);
    const data = monthlyData.map(item => item.budget);
    
    // Detect dark mode
    const isDarkMode = document.documentElement.getAttribute('data-theme') === 'dark';
    const textColor = isDarkMode ? '#e2e8f0' : '#374151';
    const gridColor = isDarkMode ? '#334155' : '#e5e7eb';
    
    // Create gradient
    const gradient = ctx.createLinearGradient(0, 0, 0, 350);
    gradient.addColorStop(0, 'rgba(124, 58, 237, 0.8)');
    gradient.addColorStop(1, 'rgba(124, 58, 237, 0.3)');
    
    const budgetChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Penggunaan Anggaran',
                data: data,
                backgroundColor: gradient,
                borderColor: 'rgba(124, 58, 237, 1)',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: isDarkMode ? '#1e293b' : '#ffffff',
                    titleColor: isDarkMode ? '#f1f5f9' : '#1f2937',
                    bodyColor: isDarkMode ? '#cbd5e1' : '#4b5563',
                    borderColor: isDarkMode ? '#475569' : '#e5e7eb',
                    borderWidth: 1,
                    cornerRadius: 8,
                    padding: 12,
                    displayColors: false,
                    callbacks: {
                        title: function(context) {
                            return monthlyData[context[0].dataIndex].month;
                        },
                        label: function(context) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: textColor,
                        font: {
                            weight: 500
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: gridColor,
                        drawBorder: false
                    },
                    ticks: {
                        color: textColor,
                        callback: function(value) {
                            if (value >= 1000000) {
                                return 'Rp ' + (value / 1000000).toFixed(0) + 'Jt';
                            } else if (value >= 1000) {
                                return 'Rp ' + (value / 1000).toFixed(0) + 'Rb';
                            }
                            return 'Rp ' + value;
                        }
                    }
                }
            },
            animation: {
                duration: 1000,
                easing: 'easeOutQuart'
            }
        }
    });
    
    // Update chart colors when theme changes
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === 'data-theme') {
                const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
                const newTextColor = isDark ? '#e2e8f0' : '#374151';
                const newGridColor = isDark ? '#334155' : '#e5e7eb';
                
                budgetChart.options.scales.x.ticks.color = newTextColor;
                budgetChart.options.scales.y.ticks.color = newTextColor;
                budgetChart.options.scales.y.grid.color = newGridColor;
                budgetChart.options.plugins.tooltip.backgroundColor = isDark ? '#1e293b' : '#ffffff';
                budgetChart.options.plugins.tooltip.titleColor = isDark ? '#f1f5f9' : '#1f2937';
                budgetChart.options.plugins.tooltip.bodyColor = isDark ? '#cbd5e1' : '#4b5563';
                budgetChart.options.plugins.tooltip.borderColor = isDark ? '#475569' : '#e5e7eb';
                
                budgetChart.update();
            }
        });
    });
    
    observer.observe(document.documentElement, { attributes: true });
});
</script>

@if(Auth::user()->isAnggota() && isset($revisionKegiatan) && $revisionKegiatan->count() > 0)
<!-- Revision Popup Modal -->
<div id="revisionPopup" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: var(--bg-card); border-radius: 16px; max-width: 600px; width: 90%; max-height: 80vh; overflow: hidden; box-shadow: 0 25px 50px rgba(0,0,0,0.3);">
        <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); padding: 20px 24px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h5 style="margin: 0; font-weight: 700;">⚠️ Perhatian: Revisi Diperlukan</h5>
                    <small style="opacity: 0.9;">{{ $revisionKegiatan->count() }} kegiatan perlu diperbaiki</small>
                </div>
                <button onclick="closeRevisionPopup()" style="background: rgba(255,255,255,0.2); border: none; color: white; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; font-size: 1.25rem;">&times;</button>
            </div>
        </div>
        <div style="padding: 24px; overflow-y: auto; max-height: calc(80vh - 80px);">
            @foreach($revisionKegiatan as $rev)
            <div style="background: var(--toggle-bg); border-radius: 12px; padding: 16px; margin-bottom: 12px; border-left: 4px solid #ef4444;">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px;">
                    <div>
                        <strong style="font-size: 1.1rem;">{{ $rev->nama_kegiatan }}</strong>
                        <div class="text-muted" style="font-size: 0.8rem;">{{ $rev->created_at->format('d M Y') }}</div>
                    </div>
                    <span class="badge" style="background: #fef2f2; color: #dc2626;">Perlu Revisi</span>
                </div>
                <div style="background: var(--bg-secondary); border-radius: 8px; padding: 12px; margin-bottom: 12px;">
                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 4px;">📝 Catatan dari Admin:</div>
                    <div style="color: var(--text-primary);">{{ $rev->catatan_revisi }}</div>
                </div>
                <a href="/kegiatan/saya" class="btn btn-sm btn-outline-primary">Edit Kegiatan</a>
            </div>
            @endforeach
        </div>
    </div>
</div>

<style>
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}
</style>

<script>
function showRevisionPopup() {
    document.getElementById('revisionPopup').style.display = 'flex';
}

function closeRevisionPopup() {
    document.getElementById('revisionPopup').style.display = 'none';
    // Set flag to not auto-show again in this session
    sessionStorage.setItem('revisionPopupShown', 'true');
}

// Auto-show popup on page load if not shown before in this session
document.addEventListener('DOMContentLoaded', function() {
    if (!sessionStorage.getItem('revisionPopupShown')) {
        setTimeout(function() {
            showRevisionPopup();
        }, 500);
    }
});

// Close on click outside
document.getElementById('revisionPopup').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRevisionPopup();
    }
});
</script>
@endif

@endsection