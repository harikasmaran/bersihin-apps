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
$notif = "";

// Tangkap status pesan kiriman dari simpan_request.php
if (isset($_GET['pesan'])) {
    if ($_GET['pesan'] == 'sukses') {
        $notif = "<div class='alert alert-success shadow-sm alert-dismissible fade show' role='alert'>
                    <i class='bi bi-check-circle-fill me-2'></i> <strong>Request Berhasil!</strong> Petugas kebersihan armada BersihIn akan segera datang menjemput ke lokasi Anda.
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                  </div>";
    } elseif ($_GET['pesan'] == 'gagal') {
        $notif = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    <i class='bi bi-exclamation-triangle-fill me-2'></i> <strong>Gagal mengajukan!</strong> Silakan periksa kembali data Anda atau coba lagi.
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                  </div>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BersihIn - Ajukan Penjemputan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-arrow-left me-2"></i> Kembali ke Dashboard</a>
        </div>
    </nav>

    <div class="container my-4" style="max-width: 600px;">
        
        <?= $notif; ?>

        <div class="card border-0 shadow rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-success text-white py-3 border-0 text-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-truck me-2"></i>Form Penjemputan Sampah</h5>
            </div>
            <div class="card-body p-4">
                <form action="simpan_request.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap Pengaju</label>
                        <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($nama_user_login); ?>" readonly disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alamat Rumah Penjemputan</label>
                        <textarea name="alamat" class="form-control" rows="3" placeholder="Sebutkan nama jalan, nomor rumah, RT/RW, dan kelurahan lengkap" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Kategori Sampah</label>
                            <select name="jenis_sampah" class="form-select" required>
                                <option value="Organik">Organik (Sisa Masakan/Daun)</option>
                                <option value="Anorganik">Anorganik (Botol/Kardus/Plastik)</option>
                                <option value="Campuran">Campuran Kedua Jenis</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Estimasi Berat Kasar (Kg)</label>
                            <input type="number" step="0.1" name="berat_kg" class="form-control" placeholder="0.0" required>
                        </div>
                    </div>
                    <div class="form-text small text-muted mb-3">
                        <i class="bi bi-info-circle me-1"></i> Setiap 1 Kg sampah yang disetujui akan menghasilkan 10 Poin Reward.
                    </div>
                    <button type="submit" name="kirim_request" class="btn btn-success w-100 py-2.5 fw-bold shadow-sm">
                        <i class="bi bi-telegram me-1"></i> Kirim Permintaan Penjemputan
                    </button>
                </form>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>