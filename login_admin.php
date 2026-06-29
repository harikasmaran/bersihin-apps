<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'config/koneksi.php';

// Jika admin sudah login sebelumnya, langsung lempar ke dashboard admin
if (isset($_SESSION['login_admin']) && $_SESSION['login_admin'] === true) {
    header("Location: admin/index.php");
    exit;
}

$notif = "";

// =========================================================================
// LOGIKA PROSES VALIDASI LOGIN ADMIN
// =========================================================================
if (isset($_POST['login_admin_btn'])) {
    $username = mysqli_real_escape_string($koneksi, trim($_POST['username']));
    $password = mysqli_real_escape_string($koneksi, trim($_POST['password']));

    // Query cek username dan password (sesuaikan jika password pakai md5 atau password_verify)
    $query = "SELECT * FROM pengguna WHERE username = '$username' AND password = '$password'";
    $eksekusi = mysqli_query($koneksi, $query);

    if ($eksekusi && mysqli_num_rows($eksekusi) > 0) {
        $data_admin = mysqli_fetch_assoc($eksekusi);

        // Validasi Role: Pastikan kolom 'role' atau 'level' bernilai Admin
        if (
            (isset($data_admin['role']) && $data_admin['role'] === 'Admin') || 
            (isset($data_admin['level']) && $data_admin['level'] === 'Admin')
        ) {
            // Set Session Admin
            $_SESSION['login_admin'] = true;
            $_SESSION['admin_id']    = $data_admin['id'];
            $_SESSION['admin_name']  = $data_admin['nama_lengkap'];
            $_SESSION['admin_user']  = $data_admin['username'];

            // Alihkan ke dashboard admin
            header("Location: admin/index.php");
            exit;
        } else {
            $notif = "<div class='alert alert-warning alert-dismissible fade show small shadow-sm' role='alert'>
                        <i class='bi bi-shield-exclamation me-2'></i> Akun Anda tidak memiliki hak akses Administrator.
                        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                      </div>";
        }
    } else {
        $notif = "<div class='alert alert-danger alert-dismissible fade show small shadow-sm' role='alert'>
                    <i class='bi bi-exclamation-triangle-fill me-2'></i> Username atau Password salah!
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
    <title>BersihIn - Login Admin</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: #f8f9fa;
            height: 100vh;
        }
        .login-container {
            max-width: 420px;
            width: 100%;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center p-3">

    <div class="login-container">
        
        <!-- Wadah Notifikasi Hasil Proses SQL -->
        <?= $notif; ?>

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <!-- Header Form -->
            <div class="card-header bg-dark text-white text-center py-4 border-0">
                <div class="fs-1 text-warning mb-1"><i class="bi bi-shield-lock-fill"></i></div>
                <h5 class="fw-bold mb-0">BersihIn Administrator</h5>
                <small class="text-muted">Silakan masuk untuk mengelola sistem</small>
            </div>
            
            <!-- Isi Form (Action kosong "" artinya memproses ke file ini sendiri) -->
            <div class="card-body p-4 bg-white">
                <form action="" method="POST">
                    
                    <!-- Input Username -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted small">Username Admin</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person-fill"></i></span>
                            <input type="text" name="username" class="form-control bg-light border-start-0" placeholder="Masukkan username admin" required autocomplete="off">
                        </div>
                    </div>
                    
                    <!-- Input Password -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted small">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-key-fill"></i></span>
                            <input type="password" name="password" class="form-control bg-light border-start-0" placeholder="••••••••" required>
                        </div>
                    </div>
                    
                    <!-- Tombol Submit Login -->
                    <button type="submit" name="login_admin_btn" class="btn btn-dark w-100 py-2.5 fw-bold shadow-sm rounded-3">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Sistem
                    </button>

                </form>
            </div>
            
            <!-- Footer Card Kembali -->
            <div class="card-footer bg-light border-0 py-3 text-center">
                <a href="login.php" class="text-decoration-none text-success small fw-semibold">
                    <i class="bi bi-arrow-left"></i> Kembali ke Login Pengguna
                </a>
            </div>
        </div>
        
        <p class="text-center text-muted small mt-3">&copy; 2026 BersihIn Team. All rights reserved.</p>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>