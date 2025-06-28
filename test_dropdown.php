<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Dropdown Bootstrap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { padding: 50px; }
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Test Dropdown Bootstrap</h2>
        <p>Ini adalah test sederhana untuk memastikan dropdown Bootstrap berfungsi:</p>
        
        <!-- Test Dropdown -->
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-map"></i> Pilih Peta
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                <li><a class="dropdown-item" href="webgis-pesebaran-hotel/index.php" target="_blank">
                    <i class="fas fa-map-marker-alt"></i> Peta Persebaran Hotel
                </a></li>
                <li><a class="dropdown-item" href="webgis-pesebaran%20angka/index.php" target="_blank">
                    <i class="fas fa-chart-area"></i> Peta Statistik Jumlah Hotel
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="test_hotel_data.php" target="_blank">
                    <i class="fas fa-database"></i> Test Data
                </a></li>
            </ul>
        </div>
        
        <hr class="my-4">
        
        <!-- Test Database Connection -->
        <h3>Test Database Connection</h3>
        <?php
        include 'config/koneksi.php';
        
        if ($conn) {
            echo '<div class="alert alert-success"><i class="fas fa-check"></i> Database connected successfully!</div>';
            
            // Test query
            $query = "SELECT kecamatan, jml FROM hotel_jakarta_utara ORDER BY jml DESC";
            $result = mysqli_query($conn, $query);
            
            if ($result) {
                echo '<div class="alert alert-info"><i class="fas fa-info"></i> Data dari hotel_jakarta_utara:</div>';
                echo '<table class="table table-striped">';
                echo '<thead><tr><th>Kecamatan</th><th>Jumlah Hotel</th></tr></thead>';
                echo '<tbody>';
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr><td>{$row['kecamatan']}</td><td>{$row['jml']}</td></tr>";
                }
                echo '</tbody></table>';
            } else {
                echo '<div class="alert alert-danger"><i class="fas fa-exclamation"></i> Error: ' . mysqli_error($conn) . '</div>';
            }
        } else {
            echo '<div class="alert alert-danger"><i class="fas fa-times"></i> Database connection failed!</div>';
        }
        ?>
        
        <hr class="my-4">
        <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali ke Halaman Utama</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        console.log('Bootstrap JS loaded');
        
        // Test dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded');
            
            // Manual test
            const dropdownToggle = document.querySelector('.dropdown-toggle');
            if (dropdownToggle) {
                console.log('Dropdown toggle found');
                dropdownToggle.addEventListener('click', function() {
                    console.log('Dropdown clicked!');
                });
            }
        });
    </script>
</body>
</html>
