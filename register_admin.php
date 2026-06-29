<?php

include 'config/koneksi.php';


$notif = "";

// LOGIKA PROSES PENDAFTARAN ADMINISTRATOR BARU
if (isset($_POST['register_admin_btn'])) {
    $nama_lengkap = mysqli_real_escape_string($koneksi, trim($_POST['nama_lengkap']));
    $username     = mysqli_real_escape_string($koneksi, trim($_POST['username']));
    $password     = mysqli_real_escape_string($koneksi, trim($_POST['password']));
    
    // Kunci hak akses ke level tertinggi (Admin)
    $role  = 'Admin';
    $point = 0;

    // Cek duplikasi username di database
    $cek_username = mysqli_query($koneksi, "SELECT username FROM pengguna WHERE username = '$username'");
    
    if (mysqli_num_rows($cek_username) > 0) {
        $notif = "<div class='alert alert-danger alert-dismissible fade show small shadow-sm' role='alert'>
                    <i class='bi bi-exclamation-triangle-fill me-2'></i> ID Username Admin <strong>$username</strong> sudah terpakai!
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                  </div>";
    } else {
        // Simpan data kedalam tabel pengguna dengan hak akses admin
        $query_register = "INSERT INTO pengguna (nama_lengkap, username, password, point, role) 
                           VALUES ('$nama_lengkap', '$username', '$password', $point, '$role')";
        
        if (mysqli_query($koneksi, $query_register)) {
            $notif = "<div class='alert alert-success alert-dismissible fade show small shadow-sm' role='alert'>
                        <i class='bi bi-check-circle-fill me-2'></i> <strong>Sukses!</strong> Akun Administrator baru berhasil ditambahkan ke database.
                        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                      </div>";
        } else {
            $notif = "<div class='alert alert-danger alert-dismissible fade show small shadow-sm' role='alert'>
                        <i class='bi bi-exclamation-triangle-fill me-2'></i> Gagal memproses data ke server database.
                        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                      </div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BersihIn - Registrasi Admin Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: #f8f9fa; height: 100vh; }
        .register-container { max-width: 440px; width: 100%; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center p-3">

    <div class="register-container">
        
        <?= $notif; ?>

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-header bg-dark text-white text-center py-4 border-0">
                <div class="fs-1 text-warning mb-1"><i class="bi bi-shield-plus"></i></div>
                <h5 class="fw-bold mb-0">Tambah Administrator</h5>
                <small class="text-muted">Pendaftaran akun internal petugas otoritas BersihIn</small>
            </div>
            
            <div class="card-body p-4 bg-white">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted small">Nama Lengkap Petugas</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person-vcard"></i></span>
                            <input type="text" name="nama_lengkap" class="form-control bg-light border-start-0" placeholder="Nama lengkap staf admin" required autocomplete="off">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted small">Username Admin Baru</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-shield-lock"></i></span>
                            <input type="text" name="username" class="form-control bg-light border-start-0" placeholder="Contoh: admin_riau" required autocomplete="off">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted small">Password Hak Akses</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-key-fill"></i></span>
                            <input type="password" name="password" class="form-control bg-light border-start-0" placeholder="••••••••" required>
                        </div>
                    </div>
                    
                    <button type="submit" name="register_admin_btn" class="btn btn-dark w-100 py-2.5 fw-bold shadow-sm rounded-3 text-warning">
                        <i class="bi bi-person-plus-fill me-1"></i> Daftarkan Admin Baru
                    </button>
                </form>
            </div>
            
            <div class="card-footer bg-light border-0 py-3 text-center">
                <a href="admin/index.php" class="text-decoration-none text-muted small fw-semibold">
                    <i class="bi bi-arrow-left"></i> Kembali ke Dashboard Admin
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>