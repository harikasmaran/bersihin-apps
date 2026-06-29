<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../config/koneksi.php';

// Proteksi halaman user wajib login
if (!isset($_SESSION['login_user']) || $_SESSION['login_user'] !== true) {
    header("Location: ../login.php");
    exit;
}

// DEFINISIKAN USERNAME DARI SESSION (PENTING AGAR TIDAK ERROR/KOSONG DI SQL)
$nama_user_login = $_SESSION['user_name'];

if (isset($_POST['kirim_request'])) {
    $alamat       = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $jenis_sampah = $_POST['jenis_sampah'];
    $berat        = floatval($_POST['berat_kg']);
    
    // Aturan bisnis: 1 Kg = 10 Poin Reward
    $poin_reward  = $berat * 10;
    $status       = "Pending";

    // Eksekusi insert data ke tabel penjemputan
    $query = "INSERT INTO penjemputan (nama_pengguna, alamat, jenis_sampah, berat_kg, poin_reward, status) 
              VALUES ('$nama_user_login', '$alamat', '$jenis_sampah', '$berat', '$poin_reward', '$status')";
    
    if (mysqli_query($koneksi, $query)) {
        // Jika sukses, alihkan kembali ke halaman form dengan parameter sukses
        header("Location: index.php?pesan=sukses");
        exit;
    } else {
        // Jika gagal database, alihkan dengan parameter gagal
        header("Location: request.php?pesan=gagal");
        exit;
    }
} else {
    // Jika mencoba akses file simpan_request.php langsung tanpa isi form
    header("Location: request.php");
    exit;
}
?>