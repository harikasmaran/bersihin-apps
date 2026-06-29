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

// Tangkap kiriman form dari edit.php
if (isset($_POST['simpan_perubahan'])) {
    $id_edit      = intval($_POST['id_penjemputan']);
    $alamat       = mysqli_real_escape_string($koneksi, trim($_POST['alamat']));
    $jenis_sampah = $_POST['jenis_sampah'];
    $berat        = floatval($_POST['berat_kg']);
    
    // Hitung ulang target poin berdasarkan perubahan berat baru (1 Kg = 10 Poin)
    $poin_baru    = $berat * 10;

    // VALIDASI KEAMANAN KEDUA: Pastikan statusnya memang belum berubah dari Pending di database
    $cek_status = mysqli_query($koneksi, "SELECT status FROM penjemputan WHERE id = $id_edit AND nama_pengguna = '$nama_user_login'");
    $data_status = mysqli_fetch_assoc($cek_status);

    if ($data_status && $data_status['status'] === 'Pending') {
        // Eksekusi pembaruan data tabel penjemputan
        $query_update = "UPDATE penjemputan SET 
                            alamat = '$alamat', 
                            jenis_sampah = '$jenis_sampah', 
                            berat_kg = $berat, 
                            poin_reward = $poin_baru 
                         WHERE id = $id_edit";
        
        if (mysqli_query($koneksi, $query_update)) {
            header("Location: index.php?pesan=edit_sukses");
            exit;
        } else {
            header("Location: index.php?pesan=gagal_sistem");
            exit;
        }
    } else {
        header("Location: index.php?pesan=edit_gagal_status");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>