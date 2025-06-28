<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit();
}

include 'config/koneksi.php';

$message = '';
$message_type = '';

// Handle form submissions
if(isset($_POST['action'])) {
    
    if($_POST['action'] == 'add_kecamatan') {
        $kecamatan = mysqli_real_escape_string($conn, $_POST['kecamatan']);
        $jml = intval($_POST['jml']);
        
        // Check if kecamatan already exists
        $check_query = "SELECT * FROM hotel_jakarta_utara WHERE kecamatan = '$kecamatan'";
        $check_result = mysqli_query($conn, $check_query);
        
        if(mysqli_num_rows($check_result) > 0) {
            $message = "Kecamatan '$kecamatan' sudah ada dalam database!";
            $message_type = 'warning';
        } else {
            $query = "INSERT INTO hotel_jakarta_utara (kecamatan, jml) VALUES ('$kecamatan', $jml)";
            
            if(mysqli_query($conn, $query)) {
                $message = "Data kecamatan '$kecamatan' berhasil ditambahkan!";
                $message_type = 'success';
            } else {
                $message = "Gagal menambahkan data kecamatan!";
                $message_type = 'danger';
            }
        }
    }
    
    if($_POST['action'] == 'edit_kecamatan') {
        $kecamatan = mysqli_real_escape_string($conn, $_POST['kecamatan']);
        $jml = intval($_POST['jml']);
        
        // Check if kecamatan exists first
        $check_query = "SELECT * FROM hotel_jakarta_utara WHERE kecamatan = '$kecamatan'";
        $check_result = mysqli_query($conn, $check_query);
        
        if(mysqli_num_rows($check_result) > 0) {
            $query = "UPDATE hotel_jakarta_utara SET jml = $jml WHERE kecamatan = '$kecamatan'";
            
            if(mysqli_query($conn, $query)) {
                $message = "Data kecamatan '$kecamatan' berhasil diperbarui! Jumlah hotel: $jml";
                $message_type = 'success';
            } else {
                $message = "Gagal memperbarui data kecamatan: " . mysqli_error($conn);
                $message_type = 'danger';
            }
        } else {
            $message = "Data kecamatan tidak ditemukan!";
            $message_type = 'warning';
        }
    }
    
    if($_POST['action'] == 'delete_kecamatan') {
        $kecamatan = mysqli_real_escape_string($conn, $_POST['kecamatan']);
        
        // Check if kecamatan exists first
        $check_query = "SELECT * FROM hotel_jakarta_utara WHERE kecamatan = '$kecamatan'";
        $check_result = mysqli_query($conn, $check_query);
        
        if(mysqli_num_rows($check_result) > 0) {
            $query = "DELETE FROM hotel_jakarta_utara WHERE kecamatan = '$kecamatan'";
            
            if(mysqli_query($conn, $query)) {
                $message = "Data kecamatan '$kecamatan' berhasil dihapus!";
                $message_type = 'success';
            } else {
                $message = "Gagal menghapus data kecamatan: " . mysqli_error($conn);
                $message_type = 'danger';
            }
        } else {
            $message = "Data kecamatan tidak ditemukan!";
            $message_type = 'warning';
        }
    }
    
    if($_POST['action'] == 'update_counts') {
        // Update all counts based on actual hotel data
        $kecamatan_list = ['Cilincing', 'Kelapa Gading', 'Koja', 'Pademangan', 'Penjaringan', 'Tanjung Priok'];
        $updated_count = 0;
        
        foreach($kecamatan_list as $kec) {
            $count_query = "SELECT COUNT(*) as total FROM hotel WHERE kecamatan = '$kec'";
            $count_result = mysqli_query($conn, $count_query);
            
            if($count_result) {
                $count_data = mysqli_fetch_assoc($count_result);
                $total = $count_data['total'];
                
                // Check if record exists
                $check_query = "SELECT kecamatan FROM hotel_jakarta_utara WHERE kecamatan = '$kec'";
                $check_result = mysqli_query($conn, $check_query);
                
                if(mysqli_num_rows($check_result) > 0) {
                    // Update existing record
                    $update_query = "UPDATE hotel_jakarta_utara SET jml = $total WHERE kecamatan = '$kec'";
                    if(mysqli_query($conn, $update_query)) {
                        $updated_count++;
                    }
                } else {
                    // Insert new record
                    $insert_query = "INSERT INTO hotel_jakarta_utara (kecamatan, jml) VALUES ('$kec', $total)";
                    if(mysqli_query($conn, $insert_query)) {
                        $updated_count++;
                    }
                }
            }
        }
        
        if($updated_count > 0) {
            $message = "Berhasil memperbarui $updated_count data statistik kecamatan!";
            $message_type = 'success';
        } else {
            $message = "Tidak ada data yang diperbarui!";
            $message_type = 'warning';
        }
    }
}

// Get all kecamatan data
$kecamatan_query = "SELECT * FROM hotel_jakarta_utara ORDER BY kecamatan ASC";
$kecamatan_result = mysqli_query($conn, $kecamatan_query);

// Get total statistics
$total_query = "SELECT SUM(jml) as total_hotel FROM hotel_jakarta_utara";
$total_result = mysqli_query($conn, $total_query);
$total_data = mysqli_fetch_assoc($total_result);
$total_hotels = $total_data['total_hotel'] ? $total_data['total_hotel'] : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Data Kecamatan - WebGIS Hotel Jakarta Utara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
        
        .dropdown-item.active {
            background-color: #667eea;
            color: white;
            font-weight: 600;
        }
        
        .progress {
            height: 8px;
            border-radius: 4px;
        }
        
        .delete-form {
            display: inline;
        }
        
        .confirm-delete {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }
        
        .confirm-delete:hover {
            background-color: #c82333;
            color: white;
        }
        
        .btn-group-sm .btn {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        
        .increase-hotel:hover, .decrease-hotel:hover {
            transform: scale(1.1);
        }
        
        .badge {
            display: block;
            margin-bottom: 0.25rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-map-marked-alt me-2"></i>
                WebGIS Hotel Jakarta Utara
            </a>
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i>
                        <?php echo $_SESSION['username']; ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="dashboard.php">
                            <i class="fas fa-dashboard me-2"></i>Dashboard
                        </a></li>
                        <li><a class="dropdown-item active" href="angka.php">
                            <i class="fas fa-chart-bar me-2"></i>Data Kecamatan
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="auth/logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <!-- Alert Messages -->
        <?php if($message): ?>
        <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
            <i class="fas fa-<?php echo $message_type == 'success' ? 'check-circle' : ($message_type == 'warning' ? 'exclamation-triangle' : 'exclamation-circle'); ?> me-2"></i>
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Manajemen Data Kecamatan</h1>
                <p class="mb-0 text-muted">Kelola data statistik hotel per kecamatan Jakarta Utara</p>
            </div>
            <div>
                <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#addKecamatanModal">
                    <i class="fas fa-plus me-2"></i>Tambah Kecamatan
                </button>
                <form method="POST" class="d-inline">
                    <input type="hidden" name="action" value="update_counts">
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-sync-alt me-2"></i>Sinkronisasi Data
                    </button>
                </form>
            </div>
        </div>

        <div class="row">
            <!-- Statistics Overview -->
            <div class="col-md-4 mb-4">
                <div class="card dashboard-card bg-primary text-white shadow position-relative">
                    <div class="card-body">
                        <h6 class="card-title">Total Hotel Jakarta Utara</h6>
                        <h2 class="mb-0"><?php echo number_format($total_hotels); ?></h2>
                        <i class="fas fa-hotel stat-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card dashboard-card bg-success text-white shadow position-relative">
                    <div class="card-body">
                        <h6 class="card-title">Total Kecamatan</h6>
                        <h2 class="mb-0"><?php echo mysqli_num_rows($kecamatan_result); ?></h2>
                        <i class="fas fa-map-marked-alt stat-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card dashboard-card bg-info text-white shadow position-relative">
                    <div class="card-body">
                        <h6 class="card-title">Rata-rata Hotel/Kecamatan</h6>
                        <h2 class="mb-0"><?php echo mysqli_num_rows($kecamatan_result) > 0 ? number_format($total_hotels / mysqli_num_rows($kecamatan_result), 1) : 0; ?></h2>
                        <i class="fas fa-chart-bar stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card dashboard-card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-table me-2"></i>Data Kecamatan Jakarta Utara</h5>
                <small class="text-light">Total: <?php echo mysqli_num_rows($kecamatan_result); ?> Kecamatan</small>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="8%">No</th>
                                <th width="25%">Nama Kecamatan</th>
                                <th width="20%">Jumlah Hotel</th>
                                <th width="22%">Persentase</th>
                                <th width="25%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            mysqli_data_seek($kecamatan_result, 0); // Reset pointer
                            while($kecamatan = mysqli_fetch_assoc($kecamatan_result)): 
                                $percentage = $total_hotels > 0 ? ($kecamatan['jml'] / $total_hotels) * 100 : 0;
                                $progress_color = $percentage >= 20 ? 'success' : ($percentage >= 10 ? 'warning' : 'danger');
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td>
                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                    <strong><?php echo htmlspecialchars($kecamatan['kecamatan']); ?></strong>
                                </td>
                                <td>
                                    <span class="badge bg-primary fs-6"><?php echo number_format($kecamatan['jml']); ?></span>
                                    <div class="btn-group btn-group-sm mt-1" role="group">
                                        <button type="button" class="btn btn-outline-danger btn-sm decrease-hotel" 
                                                data-kecamatan="<?php echo htmlspecialchars($kecamatan['kecamatan']); ?>"
                                                data-jml="<?php echo $kecamatan['jml']; ?>"
                                                title="Kurangi 1 hotel"
                                                <?php echo $kecamatan['jml'] <= 0 ? 'disabled' : ''; ?>>
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-success btn-sm increase-hotel" 
                                                data-kecamatan="<?php echo htmlspecialchars($kecamatan['kecamatan']); ?>"
                                                data-jml="<?php echo $kecamatan['jml']; ?>"
                                                title="Tambah 1 hotel">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 20px;">
                                            <div class="progress-bar bg-<?php echo $progress_color; ?>" 
                                                 style="width: <?php echo $percentage; ?>%">
                                                <?php echo number_format($percentage, 1); ?>%
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-primary btn-action edit-btn me-1" 
                                            data-kecamatan="<?php echo htmlspecialchars($kecamatan['kecamatan']); ?>"
                                            data-jml="<?php echo $kecamatan['jml']; ?>"
                                            data-bs-toggle="modal" data-bs-target="#editKecamatanModal"
                                            title="Edit data kecamatan">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-action delete-btn" 
                                            data-kecamatan="<?php echo htmlspecialchars($kecamatan['kecamatan']); ?>"
                                            title="Hapus data kecamatan">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            
                            <?php if(mysqli_num_rows($kecamatan_result) == 0): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Belum ada data kecamatan. Silakan tambah data baru.</p>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Kecamatan Modal -->
    <div class="modal fade" id="addKecamatanModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i>Tambah Data Kecamatan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add_kecamatan">
                        
                        <div class="mb-3">
                            <label class="form-label">Nama Kecamatan <span class="text-danger">*</span></label>
                            <select class="form-select" name="kecamatan" required>
                                <option value="">Pilih Kecamatan</option>
                                <option value="Cilincing">Cilincing</option>
                                <option value="Kelapa Gading">Kelapa Gading</option>
                                <option value="Koja">Koja</option>
                                <option value="Pademangan">Pademangan</option>
                                <option value="Penjaringan">Penjaringan</option>
                                <option value="Tanjung Priok">Tanjung Priok</option>
                            </select>
                            <small class="text-muted">Pilih kecamatan yang akan ditambahkan</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Jumlah Hotel <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="jml" value="0" min="0" required>
                            <small class="text-muted">Masukkan jumlah hotel di kecamatan ini</small>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Tips:</strong> Anda bisa menggunakan tombol "Sinkronisasi Data" untuk mengupdate jumlah hotel berdasarkan data aktual di database.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Simpan Kecamatan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Kecamatan Modal -->
    <div class="modal fade" id="editKecamatanModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>Edit Data Kecamatan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="edit_kecamatan">
                        <input type="hidden" name="kecamatan" id="editKecamatanName">
                        
                        <div class="mb-3">
                            <label class="form-label">Nama Kecamatan</label>
                            <input type="text" class="form-control" id="editKecamatanDisplay" readonly>
                            <small class="text-muted">Nama kecamatan tidak dapat diubah</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Jumlah Hotel <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <button type="button" class="btn btn-outline-danger" id="decreaseBtn">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" class="form-control text-center" name="jml" id="editJumlahHotel" min="0" required>
                                <button type="button" class="btn btn-outline-success" id="increaseBtn">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <small class="text-muted">Gunakan tombol +/- atau ketik langsung jumlah hotel</small>
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Perhatian:</strong> Perubahan ini akan mengubah data statistik kecamatan. Pastikan data yang dimasukkan sudah benar.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Hidden Delete Form -->
    <form id="deleteForm" method="POST" style="display: none;">
        <input type="hidden" name="action" value="delete_kecamatan">
        <input type="hidden" name="kecamatan" id="deleteKecamatan">
    </form>

    <!-- Hidden Increment/Decrement Form -->
    <form id="updateJumlahForm" method="POST" style="display: none;">
        <input type="hidden" name="action" value="edit_kecamatan">
        <input type="hidden" name="kecamatan" id="updateKecamatan">
        <input type="hidden" name="jml" id="updateJumlah">
    </form>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Handle delete button clicks
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const kecamatan = this.getAttribute('data-kecamatan');
                    confirmDelete(kecamatan);
                });
            });
            
            // Handle edit button clicks
            const editButtons = document.querySelectorAll('.edit-btn');
            editButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const kecamatan = this.getAttribute('data-kecamatan');
                    const jml = this.getAttribute('data-jml');
                    populateEditModal(kecamatan, jml);
                });
            });
            
            // Handle increase hotel buttons
            const increaseButtons = document.querySelectorAll('.increase-hotel');
            increaseButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const kecamatan = this.getAttribute('data-kecamatan');
                    const currentJml = parseInt(this.getAttribute('data-jml'));
                    updateJumlahHotel(kecamatan, currentJml + 1);
                });
            });
            
            // Handle decrease hotel buttons
            const decreaseButtons = document.querySelectorAll('.decrease-hotel');
            decreaseButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const kecamatan = this.getAttribute('data-kecamatan');
                    const currentJml = parseInt(this.getAttribute('data-jml'));
                    if(currentJml > 0) {
                        updateJumlahHotel(kecamatan, currentJml - 1);
                    }
                });
            });
        });

        function updateJumlahHotel(kecamatan, newJml) {
            // Add loading effect
            const increaseBtn = document.querySelector(`[data-kecamatan="${kecamatan}"].increase-hotel`);
            const decreaseBtn = document.querySelector(`[data-kecamatan="${kecamatan}"].decrease-hotel`);
            
            if(increaseBtn) increaseBtn.disabled = true;
            if(decreaseBtn) decreaseBtn.disabled = true;
            
            document.getElementById('updateKecamatan').value = kecamatan;
            document.getElementById('updateJumlah').value = newJml;
            document.getElementById('updateJumlahForm').submit();
        }

        function populateEditModal(kecamatan, jml) {
            document.getElementById('editKecamatanName').value = kecamatan;
            document.getElementById('editKecamatanDisplay').value = kecamatan;
            document.getElementById('editJumlahHotel').value = jml;
        }
        
        // Handle increase/decrease buttons
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('increaseBtn').addEventListener('click', function() {
                const input = document.getElementById('editJumlahHotel');
                const currentValue = parseInt(input.value) || 0;
                input.value = currentValue + 1;
            });
            
            document.getElementById('decreaseBtn').addEventListener('click', function() {
                const input = document.getElementById('editJumlahHotel');
                const currentValue = parseInt(input.value) || 0;
                if(currentValue > 0) {
                    input.value = currentValue - 1;
                }
            });
        });

        function confirmDelete(kecamatan) {
            if(confirm('Apakah Anda yakin ingin menghapus data kecamatan "' + kecamatan + '"?\n\nData yang dihapus tidak dapat dikembalikan!')) {
                document.getElementById('deleteKecamatan').value = kecamatan;
                document.getElementById('deleteForm').submit();
            }
        }
        
        // Auto dismiss alerts after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                if(alert.classList.contains('show')) {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            });
        }, 5000);
        
        // Add animation to progress bars
        document.addEventListener('DOMContentLoaded', function() {
            var progressBars = document.querySelectorAll('.progress-bar');
            progressBars.forEach(function(bar) {
                var width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(function() {
                    bar.style.width = width;
                }, 500);
            });
        });
    </script>
</body>
</html>
