<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-fit, initial-scale=1.0" />
  <title>@yield('title', 'Admin Console — Grand Lumina Hotel')</title>
  
  {{-- Fonts --}}
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

  <style>
    :root {
      --admin-bg: #F8F6F0;
      --sidebar-bg: #0F172A;
      --sidebar-hover: #1E293B;
      --gold: #C5A059;
      --gold-light: #D4AF37;
      --text-main: #1C1917;
      --text-muted: #64748b;
      --card-bg: #FFFFFF;
      --border: #EBE5DB;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Poppins', sans-serif; background: var(--admin-bg); color: var(--text-main); display: flex; min-height: 100vh; }

    /* SIDEBAR */
    .admin-sidebar {
      width: 270px;
      background: var(--sidebar-bg);
      color: #FFFFFF;
      display: flex;
      flex-direction: column;
      position: fixed;
      top: 0;
      bottom: 0;
      left: 0;
      z-index: 1000;
      box-shadow: 4px 0 25px rgba(0,0,0,0.15);
      border-right: 1px solid rgba(197, 160, 89, 0.2);
    }

    .sidebar-header {
      padding: 28px 24px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      text-align: center;
    }

    .sidebar-header h2 {
      font-family: 'Playfair Display', serif;
      color: var(--gold-light);
      font-size: 1.5rem;
      letter-spacing: 1px;
      margin-bottom: 4px;
    }

    .sidebar-header span {
      font-size: 0.75rem;
      color: #94a3b8;
      text-transform: uppercase;
      letter-spacing: 2px;
    }

    .sidebar-nav {
      padding: 24px 14px;
      flex: 1;
      overflow-y: auto;
    }

    .nav-label {
      font-size: 0.72rem;
      color: #64748b;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      margin: 16px 12px 8px;
      font-weight: 700;
    }

    .nav-link {
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 13px 18px;
      color: #cbd5e1;
      text-decoration: none;
      border-radius: 12px;
      font-size: 0.94rem;
      font-weight: 500;
      transition: all 0.25s ease;
      margin-bottom: 6px;
    }

    .nav-link:hover, .nav-link.active {
      background: var(--sidebar-hover);
      color: var(--gold-light);
      transform: translateX(4px);
    }

    .nav-link i { font-size: 1.2rem; color: var(--gold); }

    .sidebar-footer {
      padding: 20px;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .btn-back-web, .btn-logout {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      width: 100%;
      padding: 12px;
      background: rgba(197, 160, 89, 0.15);
      color: var(--gold-light);
      border: 1px solid var(--gold);
      border-radius: 12px;
      text-decoration: none;
      font-weight: 600;
      font-size: 0.88rem;
      transition: all 0.3s ease;
    }

    .btn-back-web:hover {
      background: var(--gold);
      color: #0F172A;
    }

    .btn-logout {
      background: rgba(239, 68, 68, 0.15);
      color: #fca5a5;
      border-color: #ef4444;
    }
    .btn-logout:hover {
      background: #ef4444;
      color: #ffffff;
    }

    /* MAIN CONTENT */
    .admin-main {
      margin-left: 270px;
      flex: 1;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    .admin-topbar {
      background: #FFFFFF;
      height: 75px;
      padding: 0 40px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 1px solid var(--border);
      position: sticky;
      top: 0;
      z-index: 900;
      box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }

    .topbar-title h1 {
      font-size: 1.35rem;
      color: #0F172A;
      font-weight: 700;
    }

    .admin-badge {
      display: flex;
      align-items: center;
      gap: 12px;
      background: #F8F6F0;
      padding: 8px 16px;
      border-radius: 30px;
      border: 1px solid var(--border);
    }

    .admin-badge i { font-size: 1.3rem; color: var(--gold); }
    .admin-badge span { font-weight: 600; font-size: 0.9rem; color: #0F172A; }

    .admin-content {
      padding: 36px 40px;
      flex: 1;
    }

    /* ALERTS */
    .alert {
      padding: 16px 20px;
      border-radius: 14px;
      margin-bottom: 24px;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }
    .alert-success { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }
    .alert-danger { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }

    /* CARDS */
    .card {
      background: #FFFFFF;
      border-radius: 20px;
      border: 1px solid var(--border);
      padding: 28px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.03);
      margin-bottom: 28px;
    }

    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      padding-bottom: 16px;
      border-bottom: 1px solid var(--border);
    }

    .card-header h3 { font-family: 'Playfair Display', serif; font-size: 1.4rem; color: #0F172A; }

    /* TABLES */
    .table-responsive { overflow-x: auto; }
    .table { width: 100%; border-collapse: collapse; text-align: left; }
    .table th { padding: 14px 16px; background: #F8F6F0; color: #64748b; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 1px; border-bottom: 2px solid var(--border); }
    .table td { padding: 16px; border-bottom: 1px solid #f1f5f9; font-size: 0.94rem; color: #1C1917; vertical-align: middle; }
    .table tr:hover { background: #FCFBF9; }

    /* BUTTONS */
    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 10px; font-weight: 600; font-size: 0.88rem; text-decoration: none; border: none; cursor: pointer; transition: all 0.25s ease; }
    .btn-gold { background: linear-gradient(135deg, #C5A059 0%, #9E7D3B 100%); color: #FFF; box-shadow: 0 4px 12px rgba(197, 160, 89, 0.25); }
    .btn-gold:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(197, 160, 89, 0.4); }
    .btn-navy { background: #0F172A; color: #FFF; }
    .btn-navy:hover { background: #1E293B; }
    .btn-danger { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }
    .btn-danger:hover { background: #fca5a5; color: #7f1d1d; }

    /* FORMS */
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-weight: 600; font-size: 0.92rem; color: #0F172A; margin-bottom: 8px; }
    .form-control { width: 100%; padding: 12px 16px; border-radius: 12px; border: 1.5px solid #D6D2C4; background: #FCFBF9; font-size: 0.95rem; font-family: inherit; }
    .form-control:focus { outline: none; border-color: var(--gold); background: #FFF; }

    @media (max-width: 900px) {
      .admin-sidebar { width: 70px; }
      .sidebar-header h2, .sidebar-header span, .nav-link span, .nav-label, .btn-back-web span { display: none; }
      .admin-main { margin-left: 70px; }
      .admin-content { padding: 20px; }
    }
  </style>
  @stack('styles')
</head>
<body>

  {{-- SIDEBAR --}}
  <aside class="admin-sidebar">
    <div class="sidebar-header">
      <h2>Grand Lumina</h2>
      <span>Management Console</span>
    </div>
    
    <nav class="sidebar-nav">
      <div class="nav-label">Menu Utama</div>
      <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard Overview</span>
      </a>
      <a href="{{ route('admin.rooms.index') }}" class="nav-link {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}">
        <i class="bi bi-door-closed-fill"></i>
        <span>Manajemen Kamar</span>
      </a>
      <a href="{{ route('admin.facilities.index') }}" class="nav-link {{ request()->routeIs('admin.facilities.*') ? 'active' : '' }}">
        <i class="bi bi-stars"></i>
        <span>Fasilitas Hotel</span>
      </a>
      <a href="{{ route('admin.bookings.index') }}" class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
        <i class="bi bi-calendar-check-fill"></i>
        <span>Daftar Reservasi</span>
      </a>
      <a href="{{ route('admin.inventory.index') }}" class="nav-link {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
        <i class="bi bi-building-check"></i>
        <span>Stok & Ketersediaan</span>
      </a>
    </nav>

    <div class="sidebar-footer">
      <form action="{{ route('admin.logout') }}" method="POST" style="margin: 0;">
        @csrf
        <button type="submit" class="btn-logout" style="width: 100%; cursor: pointer;">
          <i class="bi bi-box-arrow-right"></i>
          <span>Keluar (Logout)</span>
        </button>
      </form>
    </div>
  </aside>

  {{-- MAIN --}}
  <main class="admin-main">
    <header class="admin-topbar">
      <div class="topbar-title">
        <h1>@yield('page-title', 'Dashboard')</h1>
      </div>
      <div class="admin-badge">
        <i class="bi bi-person-badge-fill"></i>
        <span>{{ session('admin_username', 'Administrator Lumina') }}</span>
      </div>
    </header>

    <div class="admin-content">
      @if(session('success'))
        <div class="alert alert-success">
          <i class="bi bi-check-circle-fill" style="font-size:1.3rem;"></i>
          <span>{{ session('success') }}</span>
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger">
          <i class="bi bi-exclamation-triangle-fill" style="font-size:1.3rem;"></i>
          <span>{{ session('error') }}</span>
        </div>
      @endif

      @yield('content')
    </div>
  </main>

  @stack('scripts')
</body>
</html>
