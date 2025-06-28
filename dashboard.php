<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit();
}

include 'config/koneksi.php';

// Handle AJAX requests
if(isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    if($_POST['action'] == 'add_hotel') {
        $nama = mysqli_real_escape_string($conn, $_POST['nama']);
        $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
        $kecamatan = mysqli_real_escape_string($conn, $_POST['kecamatan']);
        $latitude = floatval($_POST['latitude']);
        $longitude = floatval($_POST['longitude']);
        $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
        
        $query = "INSERT INTO hotel (nama, alamat, kecamatan, latitude, longitude, kategori) VALUES ('$nama', '$alamat', '$kecamatan', $latitude, $longitude, '$kategori')";
        
        if(mysqli_query($conn, $query)) {
            // Update hotel_jakarta_utara count
            $update_count = "UPDATE hotel_jakarta_utara SET jml = (SELECT COUNT(*) FROM hotel WHERE kecamatan = '$kecamatan') WHERE kecamatan = '$kecamatan'";
            mysqli_query($conn, $update_count);
            
            echo json_encode(['success' => true, 'message' => 'Hotel berhasil ditambahkan!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menambahkan hotel!']);
        }
        exit();
    }
    
    if($_POST['action'] == 'update_hotel') {
        $id = intval($_POST['id']);
        $nama = mysqli_real_escape_string($conn, $_POST['nama']);
        $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
        $kecamatan = mysqli_real_escape_string($conn, $_POST['kecamatan']);
        $latitude = floatval($_POST['latitude']);
        $longitude = floatval($_POST['longitude']);
        $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
        
        // Get old kecamatan for count update
        $old_kecamatan_query = "SELECT kecamatan FROM hotel WHERE id = $id";
        $old_result = mysqli_query($conn, $old_kecamatan_query);
        $old_kecamatan = mysqli_fetch_assoc($old_result)['kecamatan'];
        
        $query = "UPDATE hotel SET nama = '$nama', alamat = '$alamat', kecamatan = '$kecamatan', latitude = $latitude, longitude = $longitude, kategori = '$kategori' WHERE id = $id";
        
        if(mysqli_query($conn, $query)) {
            // Update counts for both old and new kecamatan
            $update_old = "UPDATE hotel_jakarta_utara SET jml = (SELECT COUNT(*) FROM hotel WHERE kecamatan = '$old_kecamatan') WHERE kecamatan = '$old_kecamatan'";
            $update_new = "UPDATE hotel_jakarta_utara SET jml = (SELECT COUNT(*) FROM hotel WHERE kecamatan = '$kecamatan') WHERE kecamatan = '$kecamatan'";
            mysqli_query($conn, $update_old);
            mysqli_query($conn, $update_new);
            
            echo json_encode(['success' => true, 'message' => 'Hotel berhasil diperbarui!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal memperbarui hotel!']);
        }
        exit();
    }
    
    if($_POST['action'] == 'delete_hotel') {
        $id = intval($_POST['id']);
        
        // Get kecamatan for count update
        $kecamatan_query = "SELECT kecamatan FROM hotel WHERE id = $id";
        $kecamatan_result = mysqli_query($conn, $kecamatan_query);
        $kecamatan = mysqli_fetch_assoc($kecamatan_result)['kecamatan'];
        
        $query = "DELETE FROM hotel WHERE id = $id";
        
        if(mysqli_query($conn, $query)) {
            // Update count
            $update_count = "UPDATE hotel_jakarta_utara SET jml = (SELECT COUNT(*) FROM hotel WHERE kecamatan = '$kecamatan') WHERE kecamatan = '$kecamatan'";
            mysqli_query($conn, $update_count);
            
            echo json_encode(['success' => true, 'message' => 'Hotel berhasil dihapus!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus hotel!']);
        }
        exit();
    }
}

// Get statistics
$stats_query = "SELECT 
    (SELECT COUNT(*) FROM hotel) as total_hotel,
    (SELECT COUNT(*) FROM hotel_jakarta_utara) as total_kecamatan,
    (SELECT SUM(jml) FROM hotel_jakarta_utara) as total_persebaran,
    (SELECT COUNT(*) FROM users) as total_users";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);

// Get hotels data
$hotels_query = "SELECT * FROM hotel ORDER BY id DESC";
$hotels_result = mysqli_query($conn, $hotels_query);

// Get kecamatan data
$kecamatan_query = "SELECT * FROM hotel_jakarta_utara ORDER BY jml DESC";
$kecamatan_result = mysqli_query($conn, $kecamatan_query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - WebGIS Hotel Jakarta Utara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .dashboard-card {
            border: none;
            border-radius: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
        }
        
        .stat-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 2.5rem;
            opacity: 0.2;
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 15px 15px 0 0 !important;
        }
        
        .table th {
            background-color: #f8f9fa;
            border-top: none;
            font-weight: 600;
            color: #495057;
        }
        
        .btn-action {
            padding: 0.375rem 0.75rem;
            margin: 0 2px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-action:hover {
            transform: translateY(-2px);
        }
        
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-bottom: none;
        }
        
        .modal-content {
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .alert {
            border: none;
            border-radius: 10px;
        }
        
        .navbar-brand {
            font-weight: 600;
        }
        
        .dropdown-menu {
            border: none;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .dropdown-item:hover {
            background-color: #667eea;
            color: white;
        }
        
        .card-title {
            font-weight: 600;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-map-marked-alt me-2"></i> WebGIS Hotel Jakarta Utara
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="fas fa-home me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">
                            <i class="fas fa-dashboard me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="petaDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-map me-1"></i> Peta
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="webgis-pesebaran-hotel/index.php" target="_blank">
                                <i class="fas fa-map-marker-alt me-2"></i> Peta Persebaran Hotel
                            </a></li>
                            <li><a class="dropdown-item" href="webgis-pesebaran%20angka/index.php" target="_blank">
                                <i class="fas fa-chart-area me-2"></i> Peta Statistik Jumlah Hotel
                            </a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i> <?php echo $_SESSION['username']; ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="profile.php">
                                <i class="fas fa-user-cog me-2"></i> Profil
                            </a></li>
                            <li><a class="dropdown-item active" href="dashboard.php">
                                <i class="fas fa-dashboard me-2"></i> Dashboard
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="auth/logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <!-- Alert Container -->
        <div id="alertContainer" class="mb-4"></div>

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                <p class="mb-0 text-muted">Selamat datang, <?php echo $_SESSION['username']; ?>!</p>
            </div>
            <div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addHotelModal">
                    <i class="fas fa-plus me-2"></i> Tambah Hotel
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card shadow h-100 border-start border-primary border-4">
                    <div class="card-body position-relative">
                        <div class="row no-gutters align-items-center">
                            <div class="col">
                                <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Hotel</div>
                                <div class="h4 mb-0 fw-bold text-gray-800"><?php echo number_format($stats['total_hotel']); ?></div>
                            </div>
                        </div>
                        <i class="fas fa-hotel stat-icon text-primary"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card shadow h-100 border-start border-success border-4">
                    <div class="card-body position-relative">
                        <div class="row no-gutters align-items-center">
                            <div class="col">
                                <div class="text-xs fw-bold text-success text-uppercase mb-1">Total Kecamatan</div>
                                <div class="h4 mb-0 fw-bold text-gray-800"><?php echo number_format($stats['total_kecamatan']); ?></div>
                            </div>
                        </div>
                        <i class="fas fa-map-marked-alt stat-icon text-success"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card shadow h-100 border-start border-info border-4">
                    <div class="card-body position-relative">
                        <div class="row no-gutters align-items-center">
                            <div class="col">
                                <div class="text-xs fw-bold text-info text-uppercase mb-1">Total Persebaran</div>
                                <div class="h4 mb-0 fw-bold text-gray-800"><?php echo number_format($stats['total_persebaran']); ?></div>
                            </div>
                        </div>
                        <i class="fas fa-chart-bar stat-icon text-info"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card shadow h-100 border-start border-warning border-4">
                    <div class="card-body position-relative">
                        <div class="row no-gutters align-items-center">
                            <div class="col">
                                <div class="text-xs fw-bold text-warning text-uppercase mb-1">Total Users</div>
                                <div class="h4 mb-0 fw-bold text-gray-800"><?php echo number_format($stats['total_users']); ?></div>
                            </div>
                        </div>
                        <i class="fas fa-users stat-icon text-warning"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs" id="dashboardTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="hotels-tab" data-bs-toggle="tab" data-bs-target="#hotels" type="button" role="tab">
                    <i class="fas fa-hotel"></i> Manajemen Hotel
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="kecamatan-tab" data-bs-toggle="tab" data-bs-target="#kecamatan" type="button" role="tab">
                    <i class="fas fa-chart-bar"></i> Data Kecamatan
                </button>
            </li>
        </ul>

        <div class="tab-content" id="dashboardTabsContent">
            <!-- Hotels Tab -->
            <div class="tab-pane fade show active" id="hotels" role="tabpanel">
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-hotel"></i> Data Hotel</h5>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#hotelModal" onclick="openHotelModal()">
                            <i class="fas fa-plus"></i> Tambah Hotel
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="hotelsTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Hotel</th>
                                        <th>Alamat</th>
                                        <th>Kecamatan</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Kategori</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($hotel = mysqli_fetch_assoc($hotels_result)): ?>
                                    <tr>
                                        <td><?php echo $hotel['id']; ?></td>
                                        <td><?php echo $hotel['nama']; ?></td>
                                        <td><?php echo substr($hotel['alamat'], 0, 50) . '...'; ?></td>
                                        <td><?php echo $hotel['kecamatan']; ?></td>
                                        <td><?php echo $hotel['latitude']; ?></td>
                                        <td><?php echo $hotel['longitude']; ?></td>
                                        <td><span class="badge bg-primary"><?php echo $hotel['kategori']; ?></span></td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" onclick="editHotel(<?php echo htmlspecialchars(json_encode($hotel)); ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteHotel(<?php echo $hotel['id']; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kecamatan Tab -->
            <div class="tab-pane fade" id="kecamatan" role="tabpanel">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Data Persebaran Per Kecamatan</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Kecamatan</th>
                                        <th>Jumlah Hotel</th>
                                        <th>Persentase</th>
                                        <th>Progress</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($kec = mysqli_fetch_assoc($kecamatan_result)): 
                                        $percentage = ($kec['jml'] / $stats['total_persebaran']) * 100;
                                    ?>
                                    <tr>
                                        <td><?php echo $kec['kecamatan']; ?></td>
                                        <td><strong><?php echo $kec['jml']; ?></strong></td>
                                        <td><?php echo number_format($percentage, 1); ?>%</td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $percentage; ?>%" aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100">
                                                    <?php echo number_format($percentage, 1); ?>%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hotel Modal -->
    <div class="modal fade" id="hotelModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="hotelModalTitle">Tambah Hotel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="hotelForm">
                    <div class="modal-body">
                        <input type="hidden" id="hotelId" name="hotel_id">
                        
                        <div class="row">
                            <!-- Form Fields -->
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nama" class="form-label">Nama Hotel</label>
                                        <input type="text" class="form-control" id="nama" name="nama" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="kategori" class="form-label">Kategori</label>
                                        <select class="form-control" id="kategori" name="kategori" required>
                                            <option value="">Pilih Kategori</option>
                                            <option value="1★">1★</option>
                                            <option value="2★">2★</option>
                                            <option value="3★">3★</option>
                                            <option value="4★">4★</option>
                                            <option value="5★">5★</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <textarea class="form-control" id="alamat" name="alamat" rows="3" required 
                                              placeholder="Klik pada peta untuk mendapatkan alamat otomatis, atau ketik manual"></textarea>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i> Tip: Klik lokasi pada peta untuk mengisi alamat dan koordinat secara otomatis
                                    </small>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="kecamatan" class="form-label">Kecamatan</label>
                                        <select class="form-control" id="kecamatan" name="kecamatan" required>
                                            <option value="">Pilih Kecamatan</option>
                                            <option value="Cilincing">Cilincing</option>
                                            <option value="Kelapa Gading">Kelapa Gading</option>
                                            <option value="Koja">Koja</option>
                                            <option value="Pademangan">Pademangan</option>
                                            <option value="Penjaringan">Penjaringan</option>
                                            <option value="Tanjung Priok">Tanjung Priok</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="latitude" class="form-label">Latitude</label>
                                        <input type="number" step="any" class="form-control" id="latitude" name="latitude" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="longitude" class="form-label">Longitude</label>
                                        <input type="number" step="any" class="form-control" id="longitude" name="longitude" required>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Map -->
                            <div class="col-md-6">
                                <label class="form-label">Pilih Lokasi pada Peta</label>
                                <div id="map" style="height: 350px; border-radius: 10px; border: 2px solid #e9ecef;"></div>
                                <small class="text-muted mt-2 d-block">
                                    <i class="fas fa-mouse-pointer"></i> Klik pada peta untuk menentukan lokasi hotel
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary" id="saveHotelBtn">
                            <i class="fas fa-save me-2"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
                                            <option value="Pademangan">Pademangan</option>
                                            <option value="Penjaringan">Penjaringan</option>
                                            <option value="Tanjung Priok">Tanjung Priok</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="latitude" class="form-label">Latitude</label>
                                        <input type="number" step="any" class="form-control" id="latitude" name="latitude" required readonly>
                                        <small class="text-muted">Akan terisi otomatis saat klik peta</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="longitude" class="form-label">Longitude</label>
                                        <input type="number" step="any" class="form-control" id="longitude" name="longitude" required readonly>
                                        <small class="text-muted">Akan terisi otomatis saat klik peta</small>
                                    </div>
                                </div>
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <strong>Petunjuk:</strong> Klik pada lokasi di peta sebelah kanan untuk menentukan posisi hotel. 
                                    Koordinat dan alamat akan terisi secara otomatis.
                                </div>
                            </div>
                            
                            <!-- Interactive Map -->
                            <div class="col-md-6">
                                <label class="form-label">Pilih Lokasi pada Peta</label>
                                <div id="map" style="height: 400px; width: 100%; border: 2px solid #dee2e6; border-radius: 8px;"></div>
                                <small class="text-muted mt-2 d-block">
                                    <i class="fas fa-mouse-pointer"></i> Klik pada peta untuk menandai lokasi hotel
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary" id="saveHotelBtn">
                            <i class="fas fa-save me-2"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Hotel Modal (triggered from navbar) -->
    <div class="modal fade" id="addHotelModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Hotel Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Form Fields - Simplified -->
                        <div class="col-md-4">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="fas fa-edit me-2"></i>Data Hotel</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="addNama" class="form-label fw-bold">
                                            <i class="fas fa-hotel me-2 text-primary"></i>Nama Hotel
                                        </label>
                                        <input type="text" class="form-control form-control-lg" id="addNama" name="nama" required 
                                               placeholder="Masukkan nama hotel...">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="addKategori" class="form-label fw-bold">
                                            <i class="fas fa-star me-2 text-warning"></i>Kategori Hotel
                                        </label>
                                        <select class="form-select form-select-lg" id="addKategori" name="kategori" required>
                                            <option value="">Pilih Kategori Hotel</option>
                                            <option value="1★">⭐ Hotel Bintang 1</option>
                                            <option value="2★">⭐⭐ Hotel Bintang 2</option>
                                            <option value="3★">⭐⭐⭐ Hotel Bintang 3</option>
                                            <option value="4★">⭐⭐⭐⭐ Hotel Bintang 4</option>
                                            <option value="5★">⭐⭐⭐⭐⭐ Hotel Bintang 5</option>
                                        </select>
                                    </div>

                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Cara Penggunaan:</strong><br>
                                        1. Isi nama hotel dan kategori<br>
                                        2. Klik lokasi pada peta<br>
                                        3. Data alamat akan terisi otomatis
                                    </div>

                                    <!-- Auto-filled fields (read-only) -->
                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            <label class="form-label fw-bold text-muted">
                                                <i class="fas fa-map-marker-alt me-2"></i>Alamat (otomatis)
                                            </label>
                                            <textarea class="form-control" id="addAlamat" name="alamat" rows="2" readonly 
                                                      placeholder="Klik lokasi pada peta untuk mengisi alamat..."></textarea>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            <label class="form-label fw-bold text-muted">
                                                <i class="fas fa-building me-2"></i>Kecamatan (otomatis)
                                            </label>
                                            <input type="text" class="form-control" id="addKecamatan" name="kecamatan" readonly 
                                                   placeholder="Akan terisi otomatis...">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-6 mb-2">
                                            <label class="form-label fw-bold text-muted">
                                                <i class="fas fa-crosshairs me-2"></i>Latitude
                                            </label>
                                            <input type="number" step="any" class="form-control" id="addLatitude" name="latitude" readonly 
                                                   placeholder="Auto">
                                        </div>
                                        <div class="col-6 mb-2">
                                            <label class="form-label fw-bold text-muted">
                                                <i class="fas fa-crosshairs me-2"></i>Longitude
                                            </label>
                                            <input type="number" step="any" class="form-control" id="addLongitude" name="longitude" readonly 
                                                   placeholder="Auto">
                                        </div>
                                    </div>

                                    <div class="text-center mt-3">
                                        <div id="locationStatus" class="badge bg-warning">
                                            <i class="fas fa-clock me-1"></i>Menunggu titik lokasi...
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Interactive Map using WebGIS -->
                        <div class="col-md-8">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-map me-2"></i>Pilih Lokasi Hotel di Peta Jakarta Utara
                                    </h6>
                                </div>
                                <div class="card-body p-0">
                                    <iframe 
                                        id="addMapFrame" 
                                        src="webgis-pesebaran-hotel/index.php" 
                                        style="width: 100%; height: 500px; border: none; border-radius: 0 0 8px 8px;"
                                        title="Peta Jakarta Utara">
                                    </iframe>
                                </div>
                                <div class="card-footer">
                                    <small class="text-muted">
                                        <i class="fas fa-mouse-pointer me-2"></i>
                                        <strong>Petunjuk:</strong> Klik pada lokasi di peta untuk menentukan posisi hotel. 
                                        Sistem akan mengisi alamat, kecamatan, dan koordinat secara otomatis.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <button type="button" class="btn btn-success btn-lg" id="saveAddHotelBtn" disabled>
                        <i class="fas fa-save me-2"></i>Simpan Hotel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    
    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        let isEditing = false;

        function openHotelModal() {
            isEditing = false;
            document.getElementById('hotelModalTitle').textContent = 'Tambah Hotel';
            document.getElementById('hotelForm').reset();
            document.getElementById('hotelId').value = '';
            document.getElementById('saveHotelBtn').innerHTML = '<i class="fas fa-save"></i> Simpan';
        }

        function editHotel(hotel) {
            isEditing = true;
            document.getElementById('hotelModalTitle').textContent = 'Edit Hotel';
            document.getElementById('hotelId').value = hotel.id;
            document.getElementById('nama').value = hotel.nama;
            document.getElementById('alamat').value = hotel.alamat;
            document.getElementById('kecamatan').value = hotel.kecamatan;
            document.getElementById('latitude').value = hotel.latitude;
            document.getElementById('longitude').value = hotel.longitude;
            document.getElementById('kategori').value = hotel.kategori;
            document.getElementById('saveHotelBtn').innerHTML = '<i class="fas fa-edit"></i> Update';
            
            new bootstrap.Modal(document.getElementById('hotelModal')).show();
        }

        function deleteHotel(id) {
            if(confirm('Apakah Anda yakin ingin menghapus hotel ini?')) {
                const formData = new FormData();
                formData.append('action', 'delete_hotel');
                formData.append('id', id);

                fetch('dashboard.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        showAlert(data.message, 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showAlert(data.message, 'danger');
                    }
                })
                .catch(error => {
                    showAlert('Terjadi kesalahan!', 'danger');
                });
            }
        }

        // Handle form submission
        document.getElementById('hotelForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const action = isEditing ? 'update_hotel' : 'add_hotel';
            formData.append('action', action);
            
            if(isEditing) {
                formData.append('id', document.getElementById('hotelId').value);
            }

            const saveBtn = document.getElementById('saveHotelBtn');
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
            saveBtn.disabled = true;

            fetch('dashboard.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    showAlert(data.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('hotelModal')).hide();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showAlert(data.message, 'danger');
                }
            })
            .catch(error => {
                showAlert('Terjadi kesalahan!', 'danger');
            })
            .finally(() => {
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            });
    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        let isEditing = false;
        let map = null;
        let addMap = null;
        let currentMarker = null;
        let addCurrentMarker = null;

        // Jakarta Utara bounds
        const jakartaUtaraBounds = [
            [-6.058, 106.72], // Southwest corner
            [-6.1, 107.0]     // Northeast corner
        ];

        function showAlert(message, type) {
            const alertContainer = document.getElementById('alertContainer');
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show`;
            alert.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            alertContainer.appendChild(alert);
            
            // Auto dismiss after 5 seconds
            setTimeout(() => {
                if (alert && alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 5000);
        }

        function initMap(mapId, isAddModal = false) {
            const targetMap = isAddModal ? 'addMap' : 'map';
            
            if (mapId === 'map') {
                if (map) {
                    map.remove();
                }
                map = L.map(mapId).setView([-6.08, 106.85], 12);
            } else {
                if (addMap) {
                    addMap.remove();
                }
                addMap = L.map(mapId).setView([-6.08, 106.85], 12);
            }
            
            const currentMap = mapId === 'map' ? map : addMap;
            
            // Add tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(currentMap);

            // Add click event
            currentMap.on('click', function(e) {
                const { lat, lng } = e.latlng;
                
                // Remove previous marker
                if (mapId === 'map' && currentMarker) {
                    currentMap.removeLayer(currentMarker);
                }
                if (mapId === 'addMap' && addCurrentMarker) {
                    currentMap.removeLayer(addCurrentMarker);
                }
                
                // Add new marker
                const marker = L.marker([lat, lng]).addTo(currentMap);
                
                if (mapId === 'map') {
                    currentMarker = marker;
                } else {
                    addCurrentMarker = marker;
                }
                
                // Update form fields
                const prefix = mapId === 'addMap' ? 'add' : '';
                document.getElementById(prefix + 'Latitude').value = lat.toFixed(6);
                document.getElementById(prefix + 'Longitude').value = lng.toFixed(6);
                
                // Reverse geocoding untuk mendapatkan alamat
                reverseGeocode(lat, lng, mapId === 'addMap');
                
                marker.bindPopup(`Koordinat: ${lat.toFixed(6)}, ${lng.toFixed(6)}`).openPopup();
            });

            // Resize map when modal is shown
            setTimeout(() => {
                currentMap.invalidateSize();
            }, 300);
        }

        function reverseGeocode(lat, lng, isAddModal = false) {
            const prefix = isAddModal ? 'add' : '';
            
            // Simple reverse geocoding using Nominatim
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.display_name) {
                        document.getElementById(prefix + 'Alamat').value = data.display_name;
                        
                        // Try to determine kecamatan from address
                        const address = data.display_name.toLowerCase();
                        const kecamatanSelect = document.getElementById(prefix + 'Kecamatan');
                        
                        if (address.includes('cilincing')) {
                            kecamatanSelect.value = 'Cilincing';
                        } else if (address.includes('kelapa gading')) {
                            kecamatanSelect.value = 'Kelapa Gading';
                        } else if (address.includes('koja')) {
                            kecamatanSelect.value = 'Koja';
                        } else if (address.includes('pademangan')) {
                            kecamatanSelect.value = 'Pademangan';
                        } else if (address.includes('penjaringan')) {
                            kecamatanSelect.value = 'Penjaringan';
                        } else if (address.includes('tanjung priok')) {
                            kecamatanSelect.value = 'Tanjung Priok';
                        }
                    }
                })
                .catch(error => {
                    console.log('Reverse geocoding failed:', error);
                    // Fallback: set a generic address
                    document.getElementById(prefix + 'Alamat').value = `Lokasi: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                });
        }

        function openHotelModal() {
            isEditing = false;
            document.getElementById('hotelModalTitle').textContent = 'Tambah Hotel';
            document.getElementById('hotelForm').reset();
            document.getElementById('hotelId').value = '';
            document.getElementById('saveHotelBtn').innerHTML = '<i class="fas fa-save me-2"></i> Simpan';
            
            // Initialize map when modal is shown
            setTimeout(() => {
                initMap('map');
            }, 300);
        }

        function editHotel(hotel) {
            isEditing = true;
            document.getElementById('hotelModalTitle').textContent = 'Edit Hotel';
            document.getElementById('hotelId').value = hotel.id;
            document.getElementById('nama').value = hotel.nama;
            document.getElementById('alamat').value = hotel.alamat;
            document.getElementById('kecamatan').value = hotel.kecamatan;
            document.getElementById('latitude').value = hotel.latitude;
            document.getElementById('longitude').value = hotel.longitude;
            document.getElementById('kategori').value = hotel.kategori;
            document.getElementById('saveHotelBtn').innerHTML = '<i class="fas fa-edit me-2"></i> Update';
            
            new bootstrap.Modal(document.getElementById('hotelModal')).show();
            
            // Initialize map and add marker for existing hotel
            setTimeout(() => {
                initMap('map');
                if (hotel.latitude && hotel.longitude) {
                    const lat = parseFloat(hotel.latitude);
                    const lng = parseFloat(hotel.longitude);
                    
                    if (currentMarker) {
                        map.removeLayer(currentMarker);
                    }
                    
                    currentMarker = L.marker([lat, lng]).addTo(map);
                    currentMarker.bindPopup(`${hotel.nama}<br>Koordinat: ${lat}, ${lng}`).openPopup();
                    map.setView([lat, lng], 15);
                }
            }, 300);
        }

        function deleteHotel(id) {
            if(confirm('Apakah Anda yakin ingin menghapus hotel ini?')) {
                const formData = new FormData();
                formData.append('action', 'delete_hotel');
                formData.append('id', id);

                fetch('dashboard.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        showAlert(data.message, 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showAlert(data.message, 'danger');
                    }
                })
                .catch(error => {
                    showAlert('Terjadi kesalahan!', 'danger');
                });
            }
        }

        // Handle form submission
        document.getElementById('hotelForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const action = isEditing ? 'update_hotel' : 'add_hotel';
            formData.append('action', action);
            
            if(isEditing) {
                formData.append('id', document.getElementById('hotelId').value);
            }

            const saveBtn = document.getElementById('saveHotelBtn');
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
            saveBtn.disabled = true;

            fetch('dashboard.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    showAlert(data.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('hotelModal')).hide();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showAlert(data.message, 'danger');
                }
            })
            .catch(error => {
                showAlert('Terjadi kesalahan!', 'danger');
            })
            .finally(() => {
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            });
        });

        // Handle add hotel modal
        document.getElementById('addHotelModal').addEventListener('shown.bs.modal', function() {
            setTimeout(() => {
                initMap('addMap', true);
            }, 300);
        });

        // Handle save add hotel
        document.getElementById('saveAddHotelBtn').addEventListener('click', function() {
            const nama = document.getElementById('addNama').value;
            const kategori = document.getElementById('addKategori').value;
            const alamat = document.getElementById('addAlamat').value;
            const kecamatan = document.getElementById('addKecamatan').value;
            const latitude = document.getElementById('addLatitude').value;
            const longitude = document.getElementById('addLongitude').value;

            // Validation
            if (!nama || !kategori) {
                showAlert('Nama hotel dan kategori harus diisi!', 'danger');
                return;
            }

            if (!latitude || !longitude) {
                showAlert('Silakan pilih lokasi pada peta terlebih dahulu!', 'danger');
                return;
            }

            const formData = new FormData();
            formData.append('action', 'add_hotel');
            formData.append('nama', nama);
            formData.append('alamat', alamat);
            formData.append('kecamatan', kecamatan);
            formData.append('latitude', latitude);
            formData.append('longitude', longitude);
            formData.append('kategori', kategori);

            const saveBtn = this;
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
            saveBtn.disabled = true;

            fetch('dashboard.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    showAlert(data.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('addHotelModal')).hide();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showAlert(data.message, 'danger');
                }
            })
            .catch(error => {
                showAlert('Terjadi kesalahan!', 'danger');
            })
            .finally(() => {
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            });
        });

        // Handle communication with iframe map
        window.addEventListener('message', function(event) {
            // Security check - ensure message is from our domain
            if (event.origin !== window.location.origin) {
                return;
            }

            if (event.data.type === 'mapClick') {
                const { lat, lng } = event.data;
                
                // Update coordinates
                document.getElementById('addLatitude').value = lat.toFixed(6);
                document.getElementById('addLongitude').value = lng.toFixed(6);
                
                // Update status
                updateLocationStatus('success', 'Lokasi dipilih!');
                
                // Enable save button
                document.getElementById('saveAddHotelBtn').disabled = false;
                
                // Reverse geocoding to get address and kecamatan
                reverseGeocodeSimple(lat, lng);
            }
        });

        function updateLocationStatus(type, message) {
            const statusElement = document.getElementById('locationStatus');
            statusElement.className = `badge bg-${type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'danger'}`;
            statusElement.innerHTML = `<i class="fas fa-${type === 'success' ? 'check' : type === 'warning' ? 'clock' : 'times'} me-1"></i>${message}`;
        }

        function reverseGeocodeSimple(lat, lng) {
            updateLocationStatus('warning', 'Mengambil alamat...');
            
            // Use simple reverse geocoding
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.display_name) {
                        document.getElementById('addAlamat').value = data.display_name;
                        
                        // Try to determine kecamatan from address
                        const address = data.display_name.toLowerCase();
                        let kecamatan = '';
                        
                        if (address.includes('cilincing')) {
                            kecamatan = 'Cilincing';
                        } else if (address.includes('kelapa gading')) {
                            kecamatan = 'Kelapa Gading';
                        } else if (address.includes('koja')) {
                            kecamatan = 'Koja';
                        } else if (address.includes('pademangan')) {
                            kecamatan = 'Pademangan';
                        } else if (address.includes('penjaringan')) {
                            kecamatan = 'Penjaringan';
                        } else if (address.includes('tanjung priok')) {
                            kecamatan = 'Tanjung Priok';
                        } else {
                            // Fallback: determine by coordinates (rough estimation)
                            if (lat >= -6.07 && lng <= 106.88) {
                                kecamatan = 'Penjaringan';
                            } else if (lat >= -6.08 && lng >= 106.88) {
                                kecamatan = 'Kelapa Gading';
                            } else if (lat <= -6.09) {
                                kecamatan = 'Tanjung Priok';
                            } else {
                                kecamatan = 'Jakarta Utara'; // Generic fallback
                            }
                        }
                        
                        document.getElementById('addKecamatan').value = kecamatan;
                        updateLocationStatus('success', `Alamat ditemukan: ${kecamatan}`);
                    } else {
                        // Fallback address
                        document.getElementById('addAlamat').value = `Lokasi: ${lat.toFixed(6)}, ${lng.toFixed(6)}, Jakarta Utara`;
                        document.getElementById('addKecamatan').value = 'Jakarta Utara';
                        updateLocationStatus('success', 'Koordinat tersimpan');
                    }
                })
                .catch(error => {
                    console.log('Reverse geocoding failed:', error);
                    // Fallback
                    document.getElementById('addAlamat').value = `Lokasi: ${lat.toFixed(6)}, ${lng.toFixed(6)}, Jakarta Utara`;
                    document.getElementById('addKecamatan').value = 'Jakarta Utara';
                    updateLocationStatus('success', 'Koordinat tersimpan');
                });
        }

        // Clean up maps when modals are hidden
        document.getElementById('hotelModal').addEventListener('hidden.bs.modal', function() {
            if (map) {
                map.remove();
                map = null;
            }
            currentMarker = null;
        });

        // Clean up when add hotel modal is hidden
        document.getElementById('addHotelModal').addEventListener('hidden.bs.modal', function() {
            // Reset form
            document.getElementById('addNama').value = '';
            document.getElementById('addKategori').value = '';
            document.getElementById('addAlamat').value = '';
            document.getElementById('addKecamatan').value = '';
            document.getElementById('addLatitude').value = '';
            document.getElementById('addLongitude').value = '';
            
            // Reset status
            updateLocationStatus('warning', 'Menunggu titik lokasi...');
            
            // Disable save button
            document.getElementById('saveAddHotelBtn').disabled = true;
            
            // Reload iframe to reset map
            const iframe = document.getElementById('addMapFrame');
            iframe.src = iframe.src;
        });

        // Initialize when add hotel modal is shown
        document.getElementById('addHotelModal').addEventListener('shown.bs.modal', function() {
            // Focus on nama field
            document.getElementById('addNama').focus();
            
            // Reset status
            updateLocationStatus('warning', 'Menunggu titik lokasi...');
        });
    </script>
</body>
</html>
