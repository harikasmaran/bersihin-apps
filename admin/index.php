<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../config/koneksi.php';

// PROTEKSI AMAN: Pastikan yang mengakses halaman ini benar-benar Admin yang sudah login
if (!isset($_SESSION['login_admin']) || $_SESSION['login_admin'] !== true) {
    header("Location: ../login_admin.php");
    exit;
}

$notif = "";

// ========================================================
// LOGIKA UTAMA: PROSES UPDATE STATUS & DISTRIBUSI POIN DB
// ========================================================
if (isset($_POST['update_status'])) {
    $id_penjemputan = intval($_POST['id_penjemputan']);
    $status_baru    = mysqli_real_escape_string($koneksi, $_POST['status_baru']);

    // 1. Ambil data penjemputan untuk validasi username dan jumlah poin
    $query_cari = mysqli_query($koneksi, "SELECT nama_pengguna, poin_reward, status FROM penjemputan WHERE id = $id_penjemputan");
    
    if ($query_cari && mysqli_num_rows($query_cari) > 0) {
        $data_jemput   = mysqli_fetch_assoc($query_cari);
        $username_user = mysqli_real_escape_string($koneksi, $data_jemput['nama_pengguna']);
        $poin_didapat  = intval($data_jemput['poin_reward']);
        $status_lama   = $data_jemput['status'];

        // Poin hanya ditambahkan jika status berubah ke 'Selesai' dan sebelumnya bukan 'Selesai'
        if ($status_baru == 'Selesai' && $status_lama != 'Selesai') {
            
            // Mulai Transaksi Database Aman
            mysqli_begin_transaction($koneksi);

            try {
                // A. Update status transaksi di tabel penjemputan
                mysqli_query($koneksi, "UPDATE penjemputan SET status = 'Selesai' WHERE id = $id_penjemputan");

                // B. FIX BUG: Tambah poin ke kolom akun berdasarkan nama_lengkap (karena input form request memakai nama lengkap)
                mysqli_query($koneksi, "UPDATE pengguna SET point = point + $poin_didapat WHERE nama_lengkap = '$username_user'");

                mysqli_commit($koneksi);
                $notif = "<div class='alert alert-success alert-dismissible fade show shadow-sm' role='alert'>
                            <i class='bi bi-check-circle-fill me-2'></i> <strong>Sukses Konfirmasi!</strong> Status penjemputan selesai dan <strong>$poin_didapat Poin Reward</strong> resmi ditambahkan ke akun $username_user.
                            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                          </div>";
            } catch (Exception $e) {
                mysqli_rollback($koneksi);
                $notif = "<div class='alert alert-danger alert-dismissible fade show shadow-sm' role='alert'>
                            <i class='bi bi-exclamation-octagon-fill me-2'></i> Gagal memproses transaksi poin pengguna. Silakan coba lagi.
                            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                          </div>";
            }

        } else {
            // Jika admin mengubah status selain ke opsi 'Selesai'
            mysqli_query($koneksi, "UPDATE penjemputan SET status = '$status_baru' WHERE id = $id_penjemputan");
            $notif = "<div class='alert alert-info alert-dismissible fade show shadow-sm' role='alert'>
                        <i class='bi bi-info-circle-fill me-2'></i> Status penjemputan berhasil diperbarui menjadi <strong>$status_baru</strong>.
                        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                      </div>";
        }
    }
}

// 2. Ambil statistik summary untuk dashboard admin
$total_permintaan = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM penjemputan"));
$total_pending    = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM penjemputan WHERE status='Pending'"));
$total_selesai    = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM penjemputan WHERE status='Selesai'"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BersihIn - Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="bi bi-shield-lock-fill text-warning me-2"></i>BersihIn Administrator
            </a>
            <div class="navbar-nav ms-auto d-flex align-items-center gap-2">
                <span class="text-white-50 small d-none d-md-inline">
                    Halo, <?= htmlspecialchars($_SESSION['admin_name']); ?>
                </span>

                <a href="user.php" class="btn btn-sm btn-primary px-3 rounded-pill fw-bold">
                    <i class="bi bi-people-fill me-1"></i>
                    Kelola User
                </a>

                <a href="../logout.php"
                class="btn btn-sm btn-danger px-3 rounded-pill fw-bold"
                onclick="return confirm('Yakin ingin keluar sistem?')">

                    <i class="bi bi-box-arrow-right me-1"></i>
                    Keluar

                </a>

            </div>
        </div>
    </nav>

    <div class="container my-4">
        
        <?= $notif; ?>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card bg-white border-0 shadow-sm p-3 rounded-3">
                    <div class="d-flex align-items-center">
                        <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-3 me-3"><i class="bi bi-list-task fs-3"></i></div>
                        <div>
                            <h6 class="text-muted small mb-1">Total Masuk</h6>
                            <h4 class="fw-bold mb-0"><?= $total_permintaan; ?> Data</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-white border-0 shadow-sm p-3 rounded-3">
                    <div class="d-flex align-items-center">
                        <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-3 me-3"><i class="bi bi-hourglass-split fs-3"></i></div>
                        <div>
                            <h6 class="text-muted small mb-1">Status Pending</h6>
                            <h4 class="fw-bold mb-0 text-warning"><?= $total_pending; ?> Antrean</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-white border-0 shadow-sm p-3 rounded-3">
                    <div class="d-flex align-items-center">
                        <div class="p-3 bg-success bg-opacity-10 text-success rounded-3 me-3"><i class="bi bi-check2-all fs-3"></i></div>
                        <div>
                            <h6 class="text-muted small mb-1">Status Selesai</h6>
                            <h4 class="fw-bold mb-0 text-success"><?= $total_selesai; ?> Transaksi</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3 fw-bold border-bottom d-flex align-items-center justify-content-between">
                <span><i class="bi bi-table me-2 text-success"></i>Daftar Pengajuan Penjemputan Pengguna</span>
                <span class="badge bg-success bg-opacity-10 text-success px-3 py-1.5 rounded-pill small">Real-time Data</span>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0 text-center table-hover table-sm">
                    <thead class="table-dark small">
                        <tr>
                            <th class="py-2.5">No</th>
                            <th>Nama User</th>
                            <th>Alamat Lengkap</th>
                            <th>Kategori</th>
                            <th>Berat (Kg)</th>
                            <th>Poin Target</th>
                            <th>Status Kelola</th>
                            <th>Formulir</th>
                            <th>Aksi Konfirmasi</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                    <?php
                    $no = 1;
                    $query = mysqli_query($koneksi, "SELECT * FROM penjemputan ORDER BY id DESC");
                    if ($query && mysqli_num_rows($query) > 0) {
                        while ($row = mysqli_fetch_assoc($query)) {
                    ?>
                        <tr>
                            <td class="fw-bold"><?= $no++; ?></td>
                            <td class="text-start fw-semibold"><?= htmlspecialchars($row['nama_pengguna']); ?></td>
                            <td class="text-start text-muted small"><?= htmlspecialchars($row['alamat']); ?></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($row['jenis_sampah']); ?></span></td>
                            <td class="fw-bold"><?= htmlspecialchars($row['berat_kg']); ?> Kg</td>
                            <td class="text-success fw-bold"><i class="bi bi-star-fill me-1 small"></i><?= htmlspecialchars($row['poin_reward']); ?></td>
                            <td>
                                <span class="badge px-2.5 py-1 <?= $row['status'] == 'Selesai' ? 'bg-success' : 'bg-warning text-dark'; ?>">
                                    <?= htmlspecialchars($row['status']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="cetak_request.php?id=<?= $row['id']; ?>"
                                target="_blank"
                                class="btn btn-primary btn-sm rounded-pill">
                                    <i class="bi bi-printer-fill"></i>
                                </a>
                            </td>
                            <td>
                                <?php if ($row['status'] !== 'Selesai') : ?>
                                    <form action="" method="POST" class="d-inline">
                                        <input type="hidden" name="id_penjemputan" value="<?= $row['id']; ?>">
                                        <input type="hidden" name="status_baru" value="Selesai">
                                        <button type="submit" name="update_status" class="btn btn-success btn-sm fw-bold px-3 rounded-pill shadow-sm" onclick="return confirm('Selesaikan pesanan ini? Poin otomatis terkirim ke tabungan akun <?= htmlspecialchars($row['nama_pengguna']); ?>.')">
                                            <i class="bi bi-check-lg me-1"></i>Setujui & Beri Poin
                                        </button>
                                    </form>
                                <?php else : ?>
                                    <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" disabled>
                                        <i class="bi bi-lock-fill me-1"></i>Selesai Locked
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='8' class='text-muted py-4 fs-6'>Belum ada ajuan masuk dari pengguna saat ini.</td></tr>";
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