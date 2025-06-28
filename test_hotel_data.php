<?php
include "config/koneksi.php";

echo "<h2>Test Koneksi Database</h2>";

// Test koneksi
if ($conn) {
    echo "✓ Koneksi database berhasil<br>";
} else {
    echo "✗ Koneksi database gagal: " . mysqli_connect_error() . "<br>";
    exit;
}

// Test tabel hotel
echo "<h3>Data Hotel:</h3>";
$sql = "SELECT COUNT(*) as total FROM hotel";
$result = mysqli_query($conn, $sql);
if ($result) {
    $data = mysqli_fetch_array($result);
    echo "Total hotel: " . $data['total'] . "<br>";
} else {
    echo "Error: " . mysqli_error($conn) . "<br>";
}

// Test tabel hotel_jakarta_utara
echo "<h3>Data Hotel per Kecamatan:</h3>";
$sql = "SELECT * FROM hotel_jakarta_utara";
$result = mysqli_query($conn, $sql);
if ($result) {
    echo "<table border='1'>";
    echo "<tr><th>Kecamatan</th><th>Jumlah</th></tr>";
    while ($data = mysqli_fetch_array($result)) {
        echo "<tr><td>" . $data['kecamatan'] . "</td><td>" . $data['jml'] . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "Error: " . mysqli_error($conn) . "<br>";
}

// Test data hotel dengan koordinat
echo "<h3>Hotel dengan Koordinat:</h3>";
$sql = "SELECT id, nama, kecamatan, longitude, latitude FROM hotel WHERE longitude IS NOT NULL AND latitude IS NOT NULL LIMIT 5";
$result = mysqli_query($conn, $sql);
if ($result) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Nama</th><th>Kecamatan</th><th>Longitude</th><th>Latitude</th></tr>";
    while ($data = mysqli_fetch_array($result)) {
        echo "<tr>";
        echo "<td>" . $data['id'] . "</td>";
        echo "<td>" . $data['nama'] . "</td>";
        echo "<td>" . $data['kecamatan'] . "</td>";
        echo "<td>" . $data['longitude'] . "</td>";
        echo "<td>" . $data['latitude'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Error: " . mysqli_error($conn) . "<br>";
}
?>
