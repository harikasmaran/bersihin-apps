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

$nama_user_login = $_SESSION['user_name'];

// Cek apakah parameter ID ada di URL
if (isset($_GET['id'])) {
    $id_batal = intval($_GET['id']);

    // KEAMANAN: Pastikan request yang akan dihapus memang milik user yang sedang login & statusnya masih Pending
    $cek_status = mysqli_query($koneksi, "SELECT status FROM penjemputan WHERE id = $id_batal AND nama_pengguna = '$nama_user_login'");
    $data_status = mysqli_fetch_assoc($cek_status);

    if ($data_status && $data_status['status'] === 'Pending') {
        // Jalankan perintah hapus data
        $query_hapus = "DELETE FROM penjemputan WHERE id = $id_batal";
        
        if (mysqli_query($koneksi, $query_hapus)) {
            // Berhasil: Alihkan kembali ke index dengan status sukses
            header("Location: index.php?pesan=batal_sukses");
            exit;
        } else {
            header("Location: index.php?pesan=gagal_sistem");
            exit;
        }
    } else {
        // Gagal: Jika data tidak ditemukan atau statusnya sudah 'Selesai'
        header("Location: index.php?pesan=batal_gagal");
        exit;
    }
} else {
    // Jika akses file hapus.php langsung tanpa ID
    header("Location: index.php");
    exit;
}
?>