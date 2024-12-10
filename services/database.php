<?php
// File: services/database.php
$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "database_maker";

// Membuat koneksi menggunakan mysqli (Object-Oriented)
$conn = mysqli_connect($hostname, $username, $password, $dbname);

// Cek apakah koneksi berhasil
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}