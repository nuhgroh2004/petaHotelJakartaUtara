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
