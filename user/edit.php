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

// Validasi kecocokan data parameter ID URL
if (isset($_GET['id'])) {
    $id_edit = intval($_GET['id']);
    
    // Keamanan: Data harus milik user aktif dan statusnya wajib masih Pending
    $query = mysqli_query($koneksi, "SELECT * FROM penjemputan WHERE id = $id_edit AND nama_pengguna = '$nama_user_login'");
    $data = mysqli_fetch_assoc($query);
    
    if (!$data) {
        header("Location: index.php?pesan=edit_gagal_status");
        exit;
    }
    
    if ($data['status'] !== 'Pending') {
        header("Location: index.php?pesan=edit_gagal_status");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BersihIn - Edit Data Penjemputan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-success shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-arrow-left me-2"></i> Batal & Kembali</a>
        </div>
    </nav>

    <div class="container my-4" style="max-width: 600px;">
        <div class="card border-0 shadow rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-warning text-dark py-3 border-0 text-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Form Koreksi Data Penjemputan</h5>
            </div>
            <div class="card-body p-4">
                <form action="simpan_edit.php" method="POST">
                    <input type="hidden" name="id_penjemputan" value="<?= $data['id']; ?>">

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted">Alamat Rumah Penjemputan</label>
                        <textarea name="alamat" class="form-control" rows="3" required><?= htmlspecialchars($data['alamat']); ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-muted">Kategori Sampah</label>
                            <select name="jenis_sampah" class="form-select" required>
                                <option value="Organik" <?= $data['jenis_sampah'] == 'Organik' ? 'selected' : ''; ?>>Organik (Sisa Makanan)</option>
                                <option value="Anorganik" <?= $data['jenis_sampah'] == 'Anorganik' ? 'selected' : ''; ?>>Anorganik (Plastik/Botol/Kardus)</option>
                                <option value="Campuran" <?= $data['jenis_sampah'] == 'Campuran' ? 'selected' : ''; ?>>Campuran Kedua Jenis</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-muted">Estimasi Berat Baru (Kg)</label>
                            <input type="number" step="0.1" name="berat_kg" class="form-control" value="<?= $data['berat_kg']; ?>" required>
                        </div>
                    </div>

                    <div class="form-text small text-muted mb-4">
                        <i class="bi bi-info-circle me-1"></i> Perubahan berat akan otomatis menghitung ulang kalkulasi target perolehan poin Anda.
                    </div>

                    <button type="submit" name="simpan_perubahan" class="btn btn-warning w-100 py-2.5 fw-bold shadow-sm text-dark">
                        <i class="bi bi-cloud-arrow-up-fill me-1"></i> Simpan & Perbarui Data
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>