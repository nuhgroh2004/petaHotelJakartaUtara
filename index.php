<?php
session_start();
include 'config/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebGIS Hotel Jakarta Utara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin-top: -20px; /* Adjust for fixed navbar */
            display: flex;
            align-items: center;
        }
        .dropdown-menu {
            border-radius: 10px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .dropdown-item {
            border-radius: 8px;
            margin: 2px 5px;
            transition: all 0.3s ease;
        }
        .dropdown-item:hover {
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white !important;
            transform: translateX(5px);
        }
        .dropdown-item i {
            width: 20px;
            text-align: center;
            margin-right: 10px;
        }
        .hero-image {
            text-align: center;
            opacity: 0.3;
        }
        body {
            padding-top: 76px; /* Account for fixed navbar */
        }
        
        /* History Section Styles */
        .timeline-item {
            position: relative;
        }
        
        .timeline-marker {
            font-size: 0.75rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .history-content .timeline-item:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 19px;
            top: 50px;
            width: 2px;
            height: calc(100% - 10px);
            background: linear-gradient(to bottom, #dee2e6, transparent);
        }
        
        .history-image i.fa-landmark {
            color: #667eea;
            filter: drop-shadow(0 4px 8px rgba(102, 126, 234, 0.3));
        }
        
        .history-content h6 {
            color: #495057;
            font-weight: 600;
        }
        
        .history-content .lead {
            font-size: 1.1rem;
            line-height: 1.6;
        }
        
        /* Organization & Vision Mission Styles */
        .org-box {
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .org-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .card {
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
        }
        
        .card-header {
            position: relative;
            overflow: hidden;
        }
        
        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .card:hover .card-header::before {
            left: 100%;
        }
        
        .list-unstyled li {
            transition: all 0.3s ease;
            padding: 0.25rem 0;
            border-radius: 5px;
        }
        
        .list-unstyled li:hover {
            background-color: rgba(0,0,0,0.05);
            padding-left: 1rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fas fa-map-marked-alt"></i> WebGIS Hotel Jakarta Utara</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#beranda">Beranda</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="petaDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-map"></i> Peta
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="webgis-pesebaran-hotel/index.php" target="_blank">
                                <i class="fas fa-map-marker-alt"></i> Peta Persebaran Hotel
                            </a></li>
                            <li><a class="dropdown-item" href="webgis-pesebaran%20angka/index.php" target="_blank">
                                <i class="fas fa-chart-area"></i> Peta Statistik Jumlah Hotel
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#peta">
                                <i class="fas fa-eye"></i> Lihat Peta Embedded
                            </a></li>
                            <li><a class="dropdown-item" href="test_hotel_data.php" target="_blank">
                                <i class="fas fa-database"></i> Test Data
                            </a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#profil">Profil Wilayah</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">
                                <i class="fas fa-dashboard me-1"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i> <?php echo $_SESSION['username']; ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="profile.php">
                                    <i class="fas fa-user-cog me-2"></i> Profil
                                </a></li>
                                <li><a class="dropdown-item" href="dashboard.php">
                                    <i class="fas fa-dashboard me-2"></i> Dashboard
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="auth/logout.php">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="auth/login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="auth/register.php"><i class="fas fa-user-plus"></i> Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="beranda" class="hero-section">
        <div class="container">
            <div class="row align-items-center min-vh-100 ">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold text-white mb-4">WebGIS Hotel Jakarta Utara</h1>
                    <p class="lead text-white-50 mb-4">Sistem Informasi Geografis untuk pemetaan dan analisis persebaran hotel di wilayah Jakarta Utara</p>
                    <div class="d-flex gap-3">
                        <a href="#peta" class="btn btn-light btn-lg">
                            <i class="fas fa-map"></i> Lihat Peta
                        </a>
                        <?php if(!isset($_SESSION['user_id'])): ?>
                        <a href="auth/login.php" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image">
                        <i class="fas fa-map-marked-alt display-1 text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- History Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="history-image text-center mb-4 mb-lg-0">
                        <i class="fas fa-landmark fa-4x text-primary mb-3"></i>
                        <div class="row text-center mt-4">
                            <div class="col-4">
                                <i class="fas fa-ship fa-2x text-secondary"></i>
                                <p class="small mt-2">Pelabuhan</p>
                            </div>
                            <div class="col-4">
                                <i class="fas fa-industry fa-2x text-secondary"></i>
                                <p class="small mt-2">Industri</p>
                            </div>
                            <div class="col-4">
                                <i class="fas fa-building fa-2x text-secondary"></i>
                                <p class="small mt-2">Perkotaan</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="history-content">
                        <h2 class="text-primary mb-4">
                            <i class="fas fa-history me-2"></i>
                            Sejarah Jakarta Utara
                        </h2>
                        <p class="lead text-muted mb-4">
                            Jakarta Utara memiliki sejarah panjang sebagai pintu gerbang perdagangan dan maritim Indonesia.
                        </p>
                        
                        <div class="timeline-item mb-4">
                            <div class="d-flex align-items-start">
                                <div class="timeline-marker bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                    <small class="fw-bold">1619</small>
                                </div>
                                <div>
                                    <h6 class="mb-2">Era Kolonial Belanda</h6>
                                    <p class="text-muted mb-0">Wilayah ini mulai berkembang sebagai pelabuhan utama Batavia (sekarang Jakarta) di bawah pemerintahan VOC.</p>
                                </div>
                            </div>
                        </div>

                        <div class="timeline-item mb-4">
                            <div class="d-flex align-items-start">
                                <div class="timeline-marker bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                    <small class="fw-bold">1945</small>
                                </div>
                                <div>
                                    <h6 class="mb-2">Pasca Kemerdekaan</h6>
                                    <p class="text-muted mb-0">Menjadi bagian penting dari ibu kota Indonesia dengan Pelabuhan Tanjung Priok sebagai gerbang utama.</p>
                                </div>
                            </div>
                        </div>

                        <div class="timeline-item mb-4">
                            <div class="d-flex align-items-start">
                                <div class="timeline-marker bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                    <small class="fw-bold">1975</small>
                                </div>
                                <div>
                                    <h6 class="mb-2">Kota Administratif</h6>
                                    <p class="text-muted mb-0">Resmi menjadi salah satu dari lima kota administratif di DKI Jakarta dengan 6 kecamatan.</p>
                                </div>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="d-flex align-items-start">
                                <div class="timeline-marker bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                    <small class="fw-bold">2000s</small>
                                </div>
                                <div>
                                    <h6 class="mb-2">Era Modern</h6>
                                    <p class="text-muted mb-0">Berkembang menjadi pusat bisnis dan industri dengan banyak hotel, mall, dan fasilitas modern.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision Mission & Organization Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-12 text-center">
                    <h2 class="text-primary mb-4">
                        <i class="fas fa-bullseye me-2"></i>
                        Visi & Misi Jakarta Utara
                    </h2>
                    <p class="lead text-muted mb-5">Komitmen Jakarta Utara dalam membangun wilayah yang maju, berkelanjutan, dan sejahtera</p>
                </div>
            </div>

            <div class="row mb-5">
                <!-- Visi -->
                <div class="col-lg-6 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-primary text-white text-center">
                            <h4 class="mb-0">
                                <i class="fas fa-eye me-2"></i>VISI
                            </h4>
                        </div>
                        <div class="card-body d-flex align-items-center">
                            <div class="text-center w-100">
                                <i class="fas fa-star fa-3x text-warning mb-3"></i>
                                <p class="fs-5 text-dark mb-0 fw-bold">
                                    "Terwujudnya Jakarta Utara sebagai kota administrasi yang maju, sejahtera, berkeadilan, dan berkelanjutan sebagai pusat maritim dan industri nasional"
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Misi -->
                <div class="col-lg-6 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-success text-white text-center">
                            <h4 class="mb-0">
                                <i class="fas fa-tasks me-2"></i>MISI
                            </h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Meningkatkan kualitas pelayanan publik yang profesional dan transparan
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Mengembangkan sektor maritim dan industri yang berkelanjutan
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Meningkatkan infrastruktur dan fasilitas pariwisata
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Menciptakan lapangan kerja dan memberdayakan ekonomi masyarakat
                                </li>
                                <li class="mb-0">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Menjaga kelestarian lingkungan dan pembangunan berkelanjutan
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Struktur Organisasi -->
            <div class="row mb-5">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-info text-white text-center">
                            <h4 class="mb-0">
                                <i class="fas fa-sitemap me-2"></i>
                                Struktur Organisasi Jakarta Utara
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <!-- Walikota -->
                                <div class="col-12 mb-4">
                                    <div class="org-box bg-primary text-white p-3 rounded mx-auto" style="max-width: 300px;">
                                        <i class="fas fa-user-tie fa-2x mb-2"></i>
                                        <h6 class="mb-1">WALIKOTA</h6>
                                        <p class="small mb-0">Jakarta Utara</p>
                                    </div>
                                </div>
                                
                                <!-- Wakil Walikota -->
                                <div class="col-12 mb-4">
                                    <div class="org-box bg-secondary text-white p-3 rounded mx-auto" style="max-width: 280px;">
                                        <i class="fas fa-user fa-2x mb-2"></i>
                                        <h6 class="mb-1">WAKIL WALIKOTA</h6>
                                        <p class="small mb-0">Jakarta Utara</p>
                                    </div>
                                </div>

                                <!-- Sekretaris Kota -->
                                <div class="col-12 mb-4">
                                    <div class="org-box bg-success text-white p-3 rounded mx-auto" style="max-width: 260px;">
                                        <i class="fas fa-user-cog fa-2x mb-2"></i>
                                        <h6 class="mb-1">SEKRETARIS KOTA</h6>
                                        <p class="small mb-0">Jakarta Utara</p>
                                    </div>
                                </div>

                                <!-- Kecamatan -->
                                <div class="col-12">
                                    <h6 class="text-muted mb-3">KECAMATAN</h6>
                                    <div class="row">
                                        <div class="col-md-4 col-sm-6 mb-3">
                                            <div class="org-box bg-warning text-dark p-2 rounded">
                                                <i class="fas fa-map-marker-alt mb-1"></i>
                                                <p class="small mb-0 fw-bold">Cilincing</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 mb-3">
                                            <div class="org-box bg-warning text-dark p-2 rounded">
                                                <i class="fas fa-map-marker-alt mb-1"></i>
                                                <p class="small mb-0 fw-bold">Kelapa Gading</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 mb-3">
                                            <div class="org-box bg-warning text-dark p-2 rounded">
                                                <i class="fas fa-map-marker-alt mb-1"></i>
                                                <p class="small mb-0 fw-bold">Koja</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 mb-3">
                                            <div class="org-box bg-warning text-dark p-2 rounded">
                                                <i class="fas fa-map-marker-alt mb-1"></i>
                                                <p class="small mb-0 fw-bold">Pademangan</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 mb-3">
                                            <div class="org-box bg-warning text-dark p-2 rounded">
                                                <i class="fas fa-map-marker-alt mb-1"></i>
                                                <p class="small mb-0 fw-bold">Penjaringan</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 mb-3">
                                            <div class="org-box bg-warning text-dark p-2 rounded">
                                                <i class="fas fa-map-marker-alt mb-1"></i>
                                                <p class="small mb-0 fw-bold">Tanjung Priok</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik & Informasi Umum -->
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-pie me-2"></i>
                                Data Demografis
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <div class="border-end">
                                        <h4 class="text-primary mb-1">1.7 Juta</h4>
                                        <p class="small text-muted mb-0">Penduduk</p>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <h4 class="text-success mb-1">142.2 km²</h4>
                                    <p class="small text-muted mb-0">Luas Wilayah</p>
                                </div>
                                <div class="col-6">
                                    <div class="border-end">
                                        <h4 class="text-info mb-1">31</h4>
                                        <p class="small text-muted mb-0">Kelurahan</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-warning mb-1">6</h4>
                                    <p class="small text-muted mb-0">Kecamatan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-landmark me-2"></i>
                                Fasilitas Utama
                            </h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i class="fas fa-ship text-primary me-2"></i>
                                    <strong>Pelabuhan Tanjung Priok</strong> - Pelabuhan terbesar di Indonesia
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-plane text-success me-2"></i>
                                    <strong>Bandara Halim Perdanakusuma</strong> - Penerbangan domestik
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-shopping-cart text-info me-2"></i>
                                    <strong>Mall Kelapa Gading</strong> - Pusat perbelanjaan
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-hospital text-danger me-2"></i>
                                    <strong>RS Pantai Indah Kapuk</strong> - Fasilitas kesehatan
                                </li>
                                <li class="mb-0">
                                    <i class="fas fa-graduation-cap text-warning me-2"></i>
                                    <strong>Universitas Tarumanagara</strong> - Pendidikan tinggi
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Data Tabular Hotel Jakarta Utara -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-12 text-center">
                    <h2 class="text-primary mb-4">
                        <i class="fas fa-table me-2"></i>
                        Data Statistik Hotel Jakarta Utara
                    </h2>
                    <p class="lead text-muted mb-5">Data real-time jumlah hotel per kecamatan berdasarkan database terkini</p>
                </div>
            </div>

            <?php
            // Query untuk mengambil data hotel
            $query = "SELECT kecamatan, jml FROM hotel_jakarta_utara ORDER BY jml DESC";
            $result = mysqli_query($conn, $query);
            
            // Hitung total dan statistik
            $total_hotel = 0;
            $data_hotel = [];
            while($row = mysqli_fetch_assoc($result)) {
                $data_hotel[] = $row;
                $total_hotel += $row['jml'];
            }
            
            $jumlah_kecamatan = count($data_hotel);
            $rata_rata = $jumlah_kecamatan > 0 ? round($total_hotel / $jumlah_kecamatan, 1) : 0;
            $tertinggi = $jumlah_kecamatan > 0 ? $data_hotel[0] : ['kecamatan' => '-', 'jml' => 0];
            $terendah = $jumlah_kecamatan > 0 ? end($data_hotel) : ['kecamatan' => '-', 'jml' => 0];
            ?>

            <!-- Summary Cards -->
            <div class="row mb-5">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="bg-primary bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-hotel text-white fa-lg"></i>
                            </div>
                            <h3 class="text-primary mb-2"><?php echo $total_hotel; ?></h3>
                            <p class="text-muted mb-0">Total Hotel</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="bg-success bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-map-marker-alt text-white fa-lg"></i>
                            </div>
                            <h3 class="text-success mb-2"><?php echo $jumlah_kecamatan; ?></h3>
                            <p class="text-muted mb-0">Kecamatan</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="bg-info bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-chart-line text-white fa-lg"></i>
                            </div>
                            <h3 class="text-info mb-2"><?php echo $rata_rata; ?></h3>
                            <p class="text-muted mb-0">Rata-rata per Kecamatan</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="bg-warning bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-crown text-white fa-lg"></i>
                            </div>
                            <h3 class="text-warning mb-2"><?php echo $tertinggi['jml']; ?></h3>
                            <p class="text-muted mb-0">Tertinggi (<?php echo $tertinggi['kecamatan']; ?>)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card shadow border-0">
                        <div class="card-header bg-gradient bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-bar me-2"></i>
                                Ranking Hotel per Kecamatan
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center" style="width: 80px;">Rank</th>
                                            <th>Kecamatan</th>
                                            <th class="text-center" style="width: 120px;">Jumlah Hotel</th>
                                            <th style="width: 200px;">Persentase</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($data_hotel as $index => $hotel): 
                                            $persentase = $total_hotel > 0 ? round(($hotel['jml'] / $total_hotel) * 100, 1) : 0;
                                            $is_highest = ($index == 0);
                                            $is_lowest = ($index == count($data_hotel) - 1);
                                        ?>
                                        <tr class="<?php echo $is_highest ? 'table-success' : ($is_lowest ? 'table-warning' : ''); ?>">
                                            <td class="text-center">
                                                <?php if($is_highest): ?>
                                                    <i class="fas fa-trophy text-warning"></i>
                                                <?php elseif($index == 1): ?>
                                                    <i class="fas fa-medal text-secondary"></i>
                                                <?php elseif($index == 2): ?>
                                                    <i class="fas fa-award text-warning"></i>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary"><?php echo $index + 1; ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong><?php echo $hotel['kecamatan']; ?></strong>
                                                <?php if($is_highest): ?>
                                                    <span class="badge bg-success ms-2">Tertinggi</span>
                                                <?php elseif($is_lowest): ?>
                                                    <span class="badge bg-warning">Terendah</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary fs-6"><?php echo $hotel['jml']; ?></span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1 me-2" style="height: 20px;">
                                                        <div class="progress-bar <?php echo $is_highest ? 'bg-success' : ($is_lowest ? 'bg-warning' : 'bg-primary'); ?>" 
                                                             role="progressbar" 
                                                             style="width: <?php echo $persentase; ?>%"
                                                             aria-valuenow="<?php echo $persentase; ?>" 
                                                             aria-valuemin="0" 
                                                             aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <span class="text-muted small" style="min-width: 45px;"><?php echo $persentase; ?>%</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-light text-muted">
                            <div class="row">
                                <div class="col-md-6">
                                    <small><i class="fas fa-info-circle me-1"></i> Data diperbarui secara real-time dari database</small>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <small><i class="fas fa-calendar me-1"></i> Last updated: <?php echo date('d M Y H:i'); ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Stats -->
            <div class="row mt-5">
                <div class="col-lg-6 mx-auto">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-3">Distribusi Hotel</h6>
                            <div class="row">
                                <div class="col-4">
                                    <div class="border-end">
                                        <h5 class="text-success mb-1"><?php echo count(array_filter($data_hotel, function($h) use ($rata_rata) { return $h['jml'] > $rata_rata; })); ?></h5>
                                        <small class="text-muted">Di atas rata-rata</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-end">
                                        <h5 class="text-warning mb-1"><?php echo count(array_filter($data_hotel, function($h) use ($rata_rata) { return $h['jml'] == $rata_rata; })); ?></h5>
                                        <small class="text-muted">Sesuai rata-rata</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <h5 class="text-danger mb-1"><?php echo count(array_filter($data_hotel, function($h) use ($rata_rata) { return $h['jml'] < $rata_rata; })); ?></h5>
                                    <small class="text-muted">Di bawah rata-rata</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <i class="fas fa-chart-bar fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Statistik Hotel</h5>
                            <p class="card-text">Visualisasi data jumlah hotel per kecamatan di Jakarta Utara</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <i class="fas fa-map-marker-alt fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Lokasi Hotel</h5>
                            <p class="card-text">Pemetaan lokasi hotel dengan informasi detail dan kategori</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <i class="fas fa-database fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Manajemen Data</h5>
                            <p class="card-text">Kelola data hotel dengan sistem CRUD yang mudah digunakan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Maps Section -->
    <section id="peta" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Peta Persebaran Hotel</h2>
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-chart-area"></i> Peta Persebaran Angka</h5>
                        </div>
                        <div class="card-body p-0">
                            <iframe src="webgis-pesebaran%20angka/index.php" width="100%" height="400" frameborder="0"></iframe>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">Data: Jumlah hotel per kecamatan</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-map-marked"></i> Peta Persebaran Hotel</h5>
                        </div>
                        <div class="card-body p-0">
                            <iframe src="webgis-pesebaran-hotel/index.php" width="100%" height="400" frameborder="0"></iframe>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">Data: Lokasi detail hotel</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Profile Section -->
    <section id="profil" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Profil Wilayah Jakarta Utara</h2>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card shadow">
                        <div class="card-body">
                            <h4 class="text-primary mb-4">Jakarta Utara</h4>
                            <p class="lead">Jakarta Utara adalah salah satu kota administrasi di Provinsi DKI Jakarta yang memiliki peran strategis sebagai pintu gerbang utama Jakarta melalui Pelabuhan Tanjung Priok.</p>
                            
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h6><i class="fas fa-map-marked-alt text-primary"></i> Kecamatan</h6>
                                    <ul class="list-unstyled">
                                        <li>• Cilincing</li>
                                        <li>• Kelapa Gading</li>
                                        <li>• Koja</li>
                                        <li>• Pademangan</li>
                                        <li>• Penjaringan</li>
                                        <li>• Tanjung Priok</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6><i class="fas fa-hotel text-primary"></i> Statistik Hotel</h6>
                                    <?php
                                    $query = "SELECT kecamatan, jml FROM hotel_jakarta_utara ORDER BY jml DESC";
                                    $result = mysqli_query($conn, $query);
                                    $total = 0;
                                    while($row = mysqli_fetch_assoc($result)) {
                                        echo "<div class='d-flex justify-content-between'>";
                                        echo "<span>{$row['kecamatan']}:</span>";
                                        echo "<span><strong>{$row['jml']} hotel</strong></span>";
                                        echo "</div>";
                                        $total += $row['jml'];
                                    }
                                    ?>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <span><strong>Total:</strong></span>
                                        <span><strong><?php echo $total; ?> hotel</strong></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>WebGIS Hotel Jakarta Utara</h5>
                    <p class="mb-0">Sistem Informasi Geografis untuk analisis persebaran hotel</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; 2025 WebGIS Hotel Jakarta Utara. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Ensure dropdown works
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize all dropdowns
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });
        });
    </script>
</body>
</html>
