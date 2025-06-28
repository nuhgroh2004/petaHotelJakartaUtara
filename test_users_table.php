<?php
include 'config/koneksi.php';

echo "<h2>Test Koneksi Database dan Tabel Users</h2>";

// Test koneksi
if ($conn) {
    echo "<p style='color: green;'>✓ Koneksi database berhasil</p>";
} else {
    echo "<p style='color: red;'>✗ Koneksi database gagal</p>";
    exit;
}

// Cek apakah tabel users ada
$check_table = "SHOW TABLES LIKE 'users'";
$result = mysqli_query($conn, $check_table);

if (mysqli_num_rows($result) > 0) {
    echo "<p style='color: green;'>✓ Tabel 'users' sudah ada</p>";
    
    // Tampilkan struktur tabel
    echo "<h3>Struktur Tabel Users:</h3>";
    $desc_query = "DESCRIBE users";
    $desc_result = mysqli_query($conn, $desc_query);
    
    if ($desc_result) {
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while ($row = mysqli_fetch_assoc($desc_result)) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . $row['Default'] . "</td>";
            echo "<td>" . $row['Extra'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Tampilkan data users yang ada
        echo "<h3>Data Users yang Ada:</h3>";
        $users_query = "SELECT id, username, email, created_at FROM users";
        $users_result = mysqli_query($conn, $users_query);
        
        if (mysqli_num_rows($users_result) > 0) {
            echo "<table border='1' cellpadding='5' cellspacing='0'>";
            echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Created At</th></tr>";
            while ($user = mysqli_fetch_assoc($users_result)) {
                echo "<tr>";
                echo "<td>" . $user['id'] . "</td>";
                echo "<td>" . $user['username'] . "</td>";
                echo "<td>" . $user['email'] . "</td>";
                echo "<td>" . $user['created_at'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Belum ada data users di tabel.</p>";
        }
    }
    
} else {
    echo "<p style='color: red;'>✗ Tabel 'users' tidak ditemukan!</p>";
    echo "<p>Akan membuat tabel users...</p>";
    
    // Buat tabel users
    $create_table = "CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if (mysqli_query($conn, $create_table)) {
        echo "<p style='color: green;'>✓ Tabel 'users' berhasil dibuat!</p>";
    } else {
        echo "<p style='color: red;'>✗ Gagal membuat tabel 'users': " . mysqli_error($conn) . "</p>";
    }
}

// Test insert user (jika tidak ada data)
$test_user_query = "SELECT COUNT(*) as count FROM users";
$test_result = mysqli_query($conn, $test_user_query);
$user_count = mysqli_fetch_assoc($test_result)['count'];

if ($user_count == 0) {
    echo "<h3>Membuat User Test:</h3>";
    $test_username = 'admin';
    $test_email = 'admin@hotel.com';
    $test_password = password_hash('123456', PASSWORD_DEFAULT);
    
    $insert_test = "INSERT INTO users (username, email, password) VALUES ('$test_username', '$test_email', '$test_password')";
    
    if (mysqli_query($conn, $insert_test)) {
        echo "<p style='color: green;'>✓ User test berhasil dibuat (username: admin, password: 123456)</p>";
    } else {
        echo "<p style='color: red;'>✗ Gagal membuat user test: " . mysqli_error($conn) . "</p>";
    }
}

mysqli_close($conn);
?>
<br><br>
<a href="auth/login.php">Test Login</a> | 
<a href="auth/register.php">Test Register</a> | 
<a href="index.php">Kembali ke Home</a>
