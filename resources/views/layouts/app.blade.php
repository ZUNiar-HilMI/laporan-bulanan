<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Bulanan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

    <!-- Geocoder CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css">

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        /* Theme Variables */
        :root {
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --bg-card: #ffffff;
            --text-primary: #1f2937;
            --text-secondary: #374151;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
            --topbar-bg: #ffffff;
            --toggle-bg: #f3f4f6;
            --toggle-hover: #e5e7eb;
            --dropdown-bg: #ffffff;
            --dropdown-hover: #f3f4f6;
            --table-stripe: #f9fafb;
        }

        [data-theme="dark"] {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-card: #1e293b;
            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;
            --border-color: #334155;
            --topbar-bg: #1e293b;
            --toggle-bg: #334155;
            --toggle-hover: #475569;
            --dropdown-bg: #1e293b;
            --dropdown-hover: #334155;
            --table-stripe: #263548;
        }

        /* Dark mode - Text and Content */
        [data-theme="dark"] h1,
        [data-theme="dark"] h2,
        [data-theme="dark"] h3,
        [data-theme="dark"] h4,
        [data-theme="dark"] h5,
        [data-theme="dark"] h6,
        [data-theme="dark"] p,
        [data-theme="dark"] span,
        [data-theme="dark"] label,
        [data-theme="dark"] strong,
        [data-theme="dark"] td,
        [data-theme="dark"] th {
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .text-muted,
        [data-theme="dark"] small {
            color: var(--text-muted) !important;
        }

        /* Dark mode - Form elements */
        [data-theme="dark"] input,
        [data-theme="dark"] select,
        [data-theme="dark"] textarea,
        [data-theme="dark"] .form-control,
        [data-theme="dark"] .form-select {
            background-color: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
        }

        [data-theme="dark"] input::placeholder,
        [data-theme="dark"] textarea::placeholder {
            color: var(--text-muted) !important;
        }

        [data-theme="dark"] input:focus,
        [data-theme="dark"] select:focus,
        [data-theme="dark"] textarea:focus,
        [data-theme="dark"] .form-control:focus,
        [data-theme="dark"] .form-select:focus {
            background-color: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
            border-color: #7c3aed !important;
            box-shadow: 0 0 0 0.25rem rgba(124, 58, 237, 0.25) !important;
        }

        /* Dark mode - Buttons */
        [data-theme="dark"] .btn-outline-primary {
            color: #a78bfa !important;
            border-color: #a78bfa !important;
        }

        [data-theme="dark"] .btn-outline-primary:hover {
            background-color: #7c3aed !important;
            color: white !important;
        }

        /* Dark mode - Alerts */
        [data-theme="dark"] .alert-success {
            background-color: #065f46 !important;
            color: #a7f3d0 !important;
            border-color: #059669 !important;
        }

        [data-theme="dark"] .alert-danger {
            background-color: #7f1d1d !important;
            color: #fecaca !important;
            border-color: #dc2626 !important;
        }

        [data-theme="dark"] .alert-warning {
            background-color: #78350f !important;
            color: #fde68a !important;
            border-color: #d97706 !important;
        }

        /* Dark mode - Links */
        [data-theme="dark"] a:not(.btn):not(.nav-link) {
            color: #a78bfa;
        }

        [data-theme="dark"] a:not(.btn):not(.nav-link):hover {
            color: #c4b5fd;
        }

        /* Dark mode - Logout button fix */
        [data-theme="dark"] .logout-btn:hover {
            background-color: #7f1d1d !important;
        }

        body { 
            background-color: var(--bg-primary);
            color: var(--text-primary);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            background: linear-gradient(180deg, #1e1b4b 0%, #312e81 100%);
            color: #fff;
            padding: 24px 16px;
            transition: transform 0.3s ease;
            z-index: 1040;
            display: flex;
            flex-direction: column;
        }

        .sidebar.hide { transform: translateX(-100%); }

        .sidebar-brand {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 8px;
            background: linear-gradient(135deg, #a855f7, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .sidebar-subtitle {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 32px;
        }

        .sidebar-menu {
            flex: 1;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255, 255, 255, 0.7);
            padding: 12px 16px;
            border-radius: 10px;
            text-decoration: none;
            margin-bottom: 4px;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .sidebar a .icon {
            font-size: 1.1rem;
        }

        .sidebar-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 16px;
            margin-top: 16px;
        }

        .content {
            margin-left: 260px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .content.full { margin-left: 0; }

        /* Top Bar */
        .topbar {
            background: var(--topbar-bg);
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 100;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .toggle-btn {
            border: none;
            background: var(--toggle-bg);
            color: var(--text-secondary);
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .toggle-btn:hover {
            background: var(--toggle-hover);
        }

        .page-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        /* Profile Dropdown */
        .profile-section {
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
        }

        .profile-info {
            text-align: right;
        }

        .profile-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-primary);
        }

        .profile-role {
            font-size: 0.75rem;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 500;
        }

        .profile-role.admin {
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            color: white;
        }

        .profile-role.anggota {
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
        }

        .profile-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            overflow: hidden;
            border: 2px solid var(--border-color);
            transition: all 0.2s ease;
        }

        .profile-avatar:hover {
            transform: scale(1.05);
            border-color: #a855f7;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 8px;
            background: var(--dropdown-bg);
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.25);
            min-width: 200px;
            padding: 8px;
            display: none;
            z-index: 1000;
            border: 1px solid var(--border-color);
        }

        .profile-dropdown.show {
            display: block;
        }

        .profile-dropdown a,
        .profile-dropdown button {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.875rem;
            width: 100%;
            border: none;
            background: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .profile-dropdown a:hover,
        .profile-dropdown button:hover {
            background: var(--dropdown-hover);
        }

        .profile-dropdown .logout-btn {
            color: #dc2626;
        }

        .profile-dropdown .logout-btn:hover {
            background: #fef2f2;
        }

        /* Main Content */
        .main-content {
            padding: 24px;
        }

        /* Cards */
        .card {
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            background: var(--bg-card);
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .card-body {
            padding: 20px;
        }

        /* Tables */
        .table {
            color: var(--text-primary);
        }

        .table-striped > tbody > tr:nth-of-type(odd) > * {
            background-color: var(--table-stripe);
            --bs-table-bg-type: var(--table-stripe);
        }

        .table > :not(caption) > * > * {
            background-color: var(--bg-card);
            border-bottom-color: var(--border-color);
        }

        /* Theme Toggle Button */
        .theme-toggle {
            border: none;
            background: var(--toggle-bg);
            color: var(--text-secondary);
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-left: 8px;
        }

        .theme-toggle:hover {
            background: var(--toggle-hover);
        }

        /* Status badges */
        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-approved {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Dark mode - Status badges */
        [data-theme="dark"] .badge-pending {
            background: #78350f;
            color: #fde68a;
        }

        [data-theme="dark"] .badge-approved {
            background: #065f46;
            color: #a7f3d0;
        }

        [data-theme="dark"] .badge-rejected {
            background: #7f1d1d;
            color: #fecaca;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .content {
                margin-left: 0;
            }

            .profile-info {
                display: none;
            }
        }
    </style>
</head>
<body>

<div id="sidebar" class="sidebar">
    <div class="sidebar-brand">Laporan Bulanan</div>
    <div class="sidebar-subtitle">Sistem Pelaporan Kegiatan</div>
    
    <div class="sidebar-menu">
        <a href="/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}">
            Dashboard
        </a>
        
        @if(Auth::user()->isAnggota())
        <a href="/kegiatan/create" class="{{ request()->is('kegiatan/create') ? 'active' : '' }}">
            Lapor Kegiatan
        </a>
        <a href="/kegiatan/saya" class="{{ request()->is('kegiatan/saya') ? 'active' : '' }}">
            Kegiatan Saya
        </a>
        @endif

        @if(Auth::user()->isAdmin())
        <a href="/admin/verifikasi" class="{{ request()->is('admin/verifikasi') ? 'active' : '' }}">
            Verifikasi
        </a>
        <a href="/admin/users" class="{{ request()->is('admin/users') ? 'active' : '' }}">
            Kelola User
        </a>
        @endif

        <a href="/kegiatan" class="{{ request()->is('kegiatan') ? 'active' : '' }}">
            Hasil Laporan
        </a>
    </div>

    <div class="sidebar-footer">
        <a href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
            Keluar
        </a>
        <form id="logout-form-sidebar" action="/logout" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</div>

<div id="content" class="content">
    <div class="topbar">
        <div class="topbar-left">
            <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
            <h1 class="page-title">@yield('title', 'Dashboard')</h1>
        </div>

        <div class="profile-section">
            <button class="theme-toggle" onclick="toggleTheme()" title="Toggle Theme">
                <span id="themeIcon">◐</span>
            </button>
            <div class="profile-info">
                <div class="profile-name">{{ Auth::user()->name }}</div>
                <span class="profile-role {{ Auth::user()->role }}">{{ ucfirst(Auth::user()->role) }}</span>
            </div>
            <div class="profile-avatar" onclick="toggleProfileDropdown()">
                @if(Auth::user()->avatar)
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar">
                @else
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                @endif
            </div>

            <div id="profileDropdown" class="profile-dropdown">
                <a href="/profile">
                    <span>👤</span> Profil Saya
                </a>
                <form action="/logout" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <span>🚪</span> Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="main-content">
        @yield('content')
    </div>
</div>

<!-- JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

<script>
// Theme Toggle
function toggleTheme() {
    const html = document.documentElement;
    const currentTheme = html.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    html.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    updateThemeIcon(newTheme);
}

function updateThemeIcon(theme) {
    const icon = document.getElementById('themeIcon');
    if (icon) {
        icon.textContent = theme === 'dark' ? '○' : '◐';
    }
}

// Load saved theme on page load
(function() {
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
    updateThemeIcon(savedTheme);
})();

function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('hide');
    document.getElementById('content').classList.toggle('full');
}

function toggleProfileDropdown() {
    document.getElementById('profileDropdown').classList.toggle('show');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('profileDropdown');
    const avatar = document.querySelector('.profile-avatar');
    if (!avatar.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.remove('show');
    }
});
</script>

</body>
</html>
