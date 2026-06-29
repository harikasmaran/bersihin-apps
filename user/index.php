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
$notif = "";

// Tangkap status pesan dari file hapus.php atau simpan_edit.php
if (isset($_GET['pesan'])) {
    if ($_GET['pesan'] == 'batal_sukses') {
        $notif = "<div class='alert alert-warning alert-dismissible fade show shadow-sm' role='alert'>
                    <i class='bi bi-trash-fill me-2'></i> Request penjemputan berhasil dibatalkan!
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                  </div>";
    } elseif ($_GET['pesan'] == 'batal_gagal' || $_GET['pesan'] == 'edit_gagal_status') {
        $notif = "<div class='alert alert-danger alert-dismissible fade show shadow-sm' role='alert'>
                    <i class='bi bi-exclamation-triangle-fill me-2'></i> Akses ditolak! Data sudah disetujui admin atau tidak ditemukan.
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                  </div>";
    } elseif ($_GET['pesan'] == 'edit_sukses') {
        $notif = "<div class='alert alert-success alert-dismissible fade show shadow-sm' role='alert'>
                    <i class='bi bi-check-circle-fill me-2'></i> Perubahan data request penjemputan berhasil disimpan!
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                  </div>";
    } elseif ($_GET['pesan'] == 'gagal_sistem') {
        $notif = "<div class='alert alert-danger alert-dismissible fade show shadow-sm' role='alert'>
                    <i class='bi bi-exclamation-triangle-fill me-2'></i> Terjadi kesalahan internal pada sistem database.
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                  </div>";
    }
}

// 1. Ambil saldo poin terbaru langsung dari database tabel pengguna
$query_user = mysqli_query($koneksi, "SELECT point FROM pengguna WHERE nama_lengkap = '$nama_user_login' OR username = '$nama_user_login'");
$data_user  = mysqli_fetch_assoc($query_user);
$total_poin = $data_user['point'] ?? 0;

// 2. Ambil akumulasi total berat sampah yang berstatus Selesai
$query_sampah = mysqli_query($koneksi, "SELECT SUM(berat_kg) AS total_berat FROM penjemputan WHERE nama_pengguna = '$nama_user_login' AND status='Selesai'");
$stat_sampah  = mysqli_fetch_assoc($query_sampah);
$total_berat  = $stat_sampah['total_berat'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BersihIn - Dashboard User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .menu-card {
            transition: all 0.2s ease;
            cursor: pointer;
            border: 1px solid rgba(0,0,0,0.05);
        }
        .menu-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 .4rem .8rem rgba(0,0,0,.08) !important;
        }
        .btn-mobile {
            padding: 6px 12px;
            font-size: 0.85rem;
        }
        .table-responsive {
            -webkit-overflow-scrolling: touch;
        }
    </style>
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-success shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold fs-5" href="index.php">
                <i class="bi bi-recycle me-1"></i>BersihIn
            </a>
            <div class="ms-auto d-flex align-items-center">
                <span class="navbar-text text-white me-3 d-none d-md-inline">Halo, <strong><?= htmlspecialchars($nama_user_login); ?></strong></span>
                <a href="../logout.php" class="btn btn-sm btn-danger px-3 rounded-pill fw-bold btn-mobile">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container my-3 my-md-4" style="max-width: 750px;">
        
        <?= $notif; ?>

        <div class="row g-2 g-md-3 mb-3">
            <div class="col-6">
                <div class="card bg-white border-0 shadow-sm p-3 rounded-3 h-100">
                    <div class="d-flex align-items-center justify-content-center justify-content-sm-start">
                        <div class="p-2 bg-success bg-opacity-10 text-success rounded-3 me-2.5 d-none d-sm-block"><i class="bi bi-box-seam fs-4"></i></div>
                        <div class="text-center text-sm-start">
                            <h6 class="text-muted small mb-0" style="font-size: 0.75rem;">Total Sampah</h6>
                            <h4 class="fw-bold mb-0 text-success fs-5"><?= $total_berat; ?> <span class="fs-6 fw-normal text-muted">Kg</span></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card bg-white border-0 shadow-sm p-3 rounded-3 h-100">
                    <div class="d-flex align-items-center justify-content-center justify-content-sm-start">
                        <div class="p-2 bg-warning bg-opacity-10 text-warning rounded-3 me-2.5 d-none d-sm-block"><i class="bi bi-star-fill fs-4"></i></div>
                        <div class="text-center text-sm-start">
                            <h6 class="text-muted small mb-0" style="font-size: 0.75rem;">Tabungan Poin</h6>
                            <h4 class="fw-bold mb-0 text-warning fs-5"><?= $total_poin; ?> <span class="fs-6 fw-normal text-muted">Pts</span></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-2 g-md-3 mb-4">
            <div class="col-6">
                <a href="request.php" class="text-decoration-none text-dark">
                    <div class="card menu-card bg-white border-0 p-3 rounded-3 text-center shadow-sm h-100">
                        <div class="text-success fs-2 mb-1"><i class="bi bi-truck"></i></div>
                        <h6 class="fw-bold mb-0 small text-success" style="font-size: 0.85rem;">Request Jemput</h6>
                    </div>
                </a>
            </div>
            <div class="col-6">
                <a href="claim.php" class="text-decoration-none text-dark">
                    <div class="card menu-card bg-white border-0 p-3 rounded-3 text-center shadow-sm h-100">
                        <div class="text-warning fs-2 mb-1"><i class="bi bi-gift-fill"></i></div>
                        <h6 class="fw-bold mb-0 small text-warning" style="font-size: 0.85rem;">Tukar Reward</h6>
                    </div>
                </a>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-2 px-1">
            <h6 class="fw-bold mb-0 text-muted small"><i class="bi bi-clock-history me-1"></i>Riwayat Aktivitas</h6>
            <span class="text-muted d-inline d-sm-none" style="font-size: 0.7rem;"><i class="bi bi-arrow-left-right"></i> Geser tabel</span>
        </div>
        
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0 text-center table-hover">
                    <thead class="table-success text-muted small">
                        <tr>
                            <th class="py-2.5">No</th>
                            <th>Alamat</th>
                            <th>Kategori</th>
                            <th>Berat</th>
                            <th>Poin</th>
                            <th>Status</th>
                            <th>Aksi Kelola</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                    <?php
                    $no = 1;
                    $query = mysqli_query($koneksi, "SELECT * FROM penjemputan WHERE nama_pengguna = '$nama_user_login' ORDER BY id DESC");
                    if ($query && mysqli_num_rows($query) > 0) {
                        while ($data = mysqli_fetch_assoc($query)) {
                    ?>
                        <tr>
                            <td class="py-2 fw-bold"><?= $no++; ?></td>
                            <td><?= $data['alamat']; ?></td>
                            <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($data['jenis_sampah']); ?></span></td>
                            <td><?= $data['berat_kg']; ?> Kg</td>
                            <td class="text-warning fw-bold"><?= $data['poin_reward']; ?></td>
                            <td>
                                <span class="badge px-2 py-1 <?= $data['status'] == 'Selesai' ? 'bg-success' : 'bg-warning text-dark'; ?>">
                                    <?= $data['status']; ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($data['status'] === 'Pending') : ?>
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="edit.php?id=<?= $data['id']; ?>" class="btn btn-sm btn-warning text-dark p-1 py-0 rounded-2 shadow-sm fw-bold" style="font-size: 0.75rem;">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                        <a href="hapus.php?id=<?= $data['id']; ?>" class="btn btn-sm btn-danger p-1 py-0 rounded-2 text-white shadow-sm fw-bold" style="font-size: 0.75rem;" onclick="return confirm('Apakah Anda yakin ingin membatalkan request penjemputan ini?')">
                                            <i class="bi bi-x-circle-fill"></i> Batal
                                        </a>
                                    </div>
                                <?php else : ?>
                                    <span class="text-muted small" style="font-size: 0.75rem;"><i class="bi bi-lock-fill"></i> Terkunci</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-muted py-3 small'>Belum ada riwayat transaksi penjemputan.</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>