<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f9fafb; /* lebih ringan */
            color: #334155;
        }
        
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 260px; /* lebih ramping */
            background: #1f2937; /* warna solid, lebih sederhana */
            box-shadow: 2px 0 12px rgba(0, 0, 0, 0.08); /* bayangan lebih halus */
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            z-index: 1000;
            overflow-y: auto;
        }
        
        .sidebar-header {
            padding: 24px 20px; /* sedikit lebih ringkas */
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }
        
        .sidebar-title {
            color: #fff;
            font-size: 20px; /* lebih kecil, modern */
            font-weight: 700;
            margin-bottom: 2px;
        }
        
        .sidebar-subtitle {
            color: #94a3b8;
            font-size: 13px;
        }
        
        .sidebar-nav {
            padding: 12px 8px; /* ruang lebih hemat */
        }
        
        .nav-item {
            display: flex;
            align-items: center;
            padding: 10px 14px; /* kompak */
            color: #cbd5e1;
            text-decoration: none;
            transition: background-color 0.2s ease, color 0.2s ease;
            margin: 4px 12px;
            border-radius: 10px; /* pill */
            border-left: none; /* hapus aksen kiri */
        }
        
        .nav-item:hover {
            background: rgba(255, 255, 255, 0.08); /* hover halus */
            color: #fff;
            transform: none; /* tanpa geser */
        }
        
        .nav-item.active {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.25), rgba(56, 189, 248, 0.25)); /* aksen ringan */
            color: #fff;
        }
        
        .nav-icon {
            width: 18px;
            margin-right: 10px;
            text-align: center;
            font-size: 15px;
        }
        
        .nav-text {
            font-weight: 600;
            font-size: 14px; /* konsisten */
        }
        
        .main-content {
            flex: 1;
            margin-left: 260px; /* selaras dengan lebar sidebar baru */
            background: #f9fafb;
            min-height: 100vh;
        }
        
        .content-header {
            background: #fff;
            padding: 25px 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid #e2e8f0;
        }
        
        .content-title {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 5px;
        }
        
        .content-subtitle {
            color: #64748b;
            font-size: 14px;
        }
        
        .content-body {
            padding: 24px; /* sedikit lebih ramping */
        }
        
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            color: #64748b;
        }
        
        .breadcrumb-item {
            color: #64748b;
            text-decoration: none;
        }
        
        .breadcrumb-item:hover {
            color: #667eea;
        }
        
        .breadcrumb-separator {
            color: #94a3b8;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 260px;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .content-body {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2 class="sidebar-title">Admin Panel</h2>
                <p class="sidebar-subtitle">Kelola konten website</p>
            </div>
            <nav class="sidebar-nav">
                <a href="{{ route('categories.index') }}" class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <i class="fas fa-tags nav-icon"></i>
                    <span class="nav-text">Kategori</span>
                </a>
                <a href="{{ route('articles.index') }}" class="nav-item {{ request()->routeIs('articles.*') ? 'active' : '' }}">
                    <i class="fas fa-newspaper nav-icon"></i>
                    <span class="nav-text">Artikel</span>
                </a>
                <a href="{{ route('analytics') }}" class="nav-item {{ request()->routeIs('analytics') ? 'active' : '' }}">
                    <i class="fas fa-chart-line nav-icon"></i>
                    <span class="nav-text">Analitik</span>
                </a>
            </nav>
        </aside>
        
        <main class="main-content">
            <div class="content-body">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>