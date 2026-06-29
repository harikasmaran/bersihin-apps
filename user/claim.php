<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../config/koneksi.php';

// Proteksi halaman user
if (!isset($_SESSION['login_user']) || $_SESSION['login_user'] !== true) {
    header("Location: ../login.php");
    exit;
}

$nama_user_login = $_SESSION['user_name'];
$notif_claim = "";

// Ambil saldo poin terbaru untuk validasi klaim reward
$query_stat = mysqli_query($koneksi, "SELECT SUM(poin_reward) AS total_poin FROM penjemputan WHERE nama_pengguna = '$nama_user_login' AND status='Selesai'");
$stat = mysqli_fetch_assoc($query_stat);
$total_poin = $stat['total_poin'] ?? 0;

// Logika Validasi Pengurangan Saldo Klaim Poin Reward
if (isset($_POST['execute_klaim'])) {
    $reward_pilihan = $_POST['reward_pilihan'];
    $poin_dibutuhkan = intval($_POST['poin_dibutuhkan']);

    if ($total_poin < $poin_dibutuhkan) {
        $notif_claim = "<div class='alert alert-danger alert-dismissible fade show shadow-sm' role='alert'>
                            <i class='bi bi-exclamation-triangle-fill me-2'></i> 
                            <strong>Gagal Klaim!</strong> Saldo Poin Anda tidak cukup untuk menukarkan item ini.
                            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                        </div>";
    } else {
        $notif_claim = "<div class='alert alert-success alert-dismissible fade show shadow-sm' role='alert'>
                            <i class='bi bi-gift-fill me-2'></i> 
                            <strong>Berhasil Sukses!</strong> Penukaran <strong>$reward_pilihan</strong> berhasil diproses. Silakan tunjukkan kode klaim ke unit bank sampah USTI.
                            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                        </div>";
        
        // Simulasi visual pengurangan poin setelah transaksi berhasil diklik
        $total_poin -= $poin_dibutuhkan;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BersihIn - Tukar Poin Reward</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-arrow-left me-2"></i> Kembali ke Dashboard</a>
            <div class="navbar-nav ms-auto">
                <div class="bg-white text-dark btn-sm rounded-pill px-3 py-1 fw-bold border border-warning shadow-sm">
                    <i class="bi bi-star-fill text-warning me-1"></i> <?= $total_poin; ?> Poin Anda
                </div>
            </div>
        </div>
    </nav>

    <div class="container my-4" style="max-width: 800px;">
        
        <?= $notif_claim; ?>

        <div class="text-center my-4">
            <h3 class="fw-bold text-dark"><i class="bi bi-award text-success me-1"></i> Katalog Penukaran Reward</h3>
            <p class="text-muted">Gunakan poin tabungan ramah lingkungan Anda untuk mendapatkan benefit menarik di bawah ini.</p>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-3 bg-white text-center h-100">
                    <div class="card-body p-4">
                        <div class="fs-1 text-primary mb-2"><i class="bi bi-wallet2"></i></div>
                        <h5 class="fw-bold mb-1">Saldo Digital Rp10.000</h5>
                        <p class="text-muted small">Dapat dicairkan ke saldo GoPay, Dana, atau ShopeePay</p>
                        <div class="mb-3"><span class="badge bg-warning text-dark px-3 py-1.5 fw-bold rounded-pill">100 Poin</span></div>
                        <form action="" method="POST">
                            <input type="hidden" name="reward_pilihan" value="Saldo Digital Rp10.000">
                            <input type="hidden" name="poin_dibutuhkan" value="100">
                            <button type="submit" name="execute_klaim" class="btn btn-success w-100 rounded-pill py-2 fw-bold">Tukarkan Sekarang</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-3 bg-white text-center h-100">
                    <div class="card-body p-4">
                        <div class="fs-1 text-danger mb-2"><i class="bi bi-cup-straw"></i></div>
                        <h5 class="fw-bold mb-1">Tumbler Eksklusif BersihIn</h5>
                        <p class="text-muted small">Botol minum stainless steel ramah lingkungan tahan panas</p>
                        <div class="mb-3"><span class="badge bg-warning text-dark px-3 py-1.5 fw-bold rounded-pill">500 Poin</span></div>
                        <form action="" method="POST">
                            <input type="hidden" name="reward_pilihan" value="Tumbler Eksklusif BersihIn">
                            <input type="hidden" name="poin_dibutuhkan" value="500">
                            <button type="submit" name="execute_klaim" class="btn btn-success w-100 rounded-pill py-2 fw-bold">Tukarkan Sekarang</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>