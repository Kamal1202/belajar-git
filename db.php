<?php
// Konfigurasi koneksi database
$host = "localhost"; // Nama host database
$username = "root"; // Username MySQL
$password = ""; // Password MySQL (kosong secara default di XAMPP)
$dbname = "gudang"; // Nama database yang digunakan

// Membuat koneksi ke database
$conn = new mysqli(hostname: $host, username: $username, password: $password, database: $dbname);

// Mengecek apakah koneksi berhasil
if($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Menampilkan pesan jika koneksi gagal
}
?>