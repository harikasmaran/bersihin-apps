<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'config/koneksi.php';

// Jika sudah login, lempar ke dashboard masing-masing
if (isset($_SESSION['login_user']) && $_SESSION['login_user'] === true) {
    header("Location: user/index.php");
    exit;
}

$notif = "";

// LOGIKA PROSES PENDAFTARAN USER
if (isset($_POST['register_user_btn'])) {
    $nama_lengkap = mysqli_real_escape_string($koneksi, trim($_POST['nama_lengkap']));
    $username     = mysqli_real_escape_string($koneksi, trim($_POST['username']));
    $password     = mysqli_real_escape_string($koneksi, trim($_POST['password']));
    
    // Nilai default untuk pendaftaran user baru
    $role  = 'User';
    $point = 0;

    // Cek apakah username sudah pernah digunakan oleh orang lain
    $cek_username = mysqli_query($koneksi, "SELECT username FROM pengguna WHERE username = '$username'");
    
    if (mysqli_num_rows($cek_username) > 0) {
        $notif = "<div class='alert alert-danger alert-dismissible fade show small shadow-sm' role='alert'>
                    <i class='bi bi-exclamation-triangle-fill me-2'></i> Username <strong>$username</strong> sudah terdaftar! Gunakan nama lain.
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                  </div>";
    } else {
        // Daftarkan data baru ke tabel pengguna
        $query_register = "INSERT INTO pengguna (nama_lengkap, username, password, point, role) 
                           VALUES ('$nama_lengkap', '$username', '$password', $point, '$role')";
        
        if (mysqli_query($koneksi, $query_register)) {
            $notif = "<div class='alert alert-success alert-dismissible fade show small shadow-sm' role='alert'>
                        <i class='bi bi-check-circle-fill me-2'></i> Pendaftaran Berhasil! Silakan <a href='login.php' class='alert-link fw-bold'>Login di sini</a>.
                        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                      </div>";
        } else {
            $notif = "<div class='alert alert-danger alert-dismissible fade show small shadow-sm' role='alert'>
                        <i class='bi bi-exclamation-triangle-fill me-2'></i> Terjadi kesalahan sistem pendaftaran database.
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
    <title>BersihIn - Pendaftaran Akun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: #f4f7f6; height: 100vh; }
        .register-container { max-width: 440px; width: 100%; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center p-3">

    <div class="register-container my-auto">
        
        <?= $notif; ?>

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-header bg-success text-white text-center py-4 border-0">
                <div class="fs-1 text-white mb-1"><i class="bi bi-person-plus-fill"></i></div>
                <h5 class="fw-bold mb-0">Gabung BersihIn</h5>
                <small class="text-white-50">Isi data diri untuk mulai menabung poin sampah</small>
            </div>
            
            <div class="card-body p-4 bg-white">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted small">Nama Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-card-text"></i></span>
                            <input type="text" name="nama_lengkap" class="form-control bg-light border-start-0" placeholder="Nama lengkap Anda" required autocomplete="off">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted small">Username Pilihan</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                            <input type="text" name="username" class="form-control bg-light border-start-0" placeholder="Gunakan huruf/angka tanpa spasi" required autocomplete="off">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted small">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" class="form-control bg-light border-start-0" placeholder="Minimal 6 karakter rahasia" required>
                        </div>
                    </div>
                    
                    <button type="submit" name="register_user_btn" class="btn btn-success w-100 py-2.5 fw-bold shadow-sm rounded-3">
                        <i class="bi bi-clipboard-check-fill me-1"></i> Buat Akun Sekarang
                    </button>
                </form>
            </div>
            
            <div class="card-footer bg-light border-0 py-3 text-center">
                <span class="text-muted small">Sudah memiliki akun? </span>
                <a href="login.php" class="text-decoration-none text-success small fw-bold">Masuk Aplikasi</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>