<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'config/koneksi.php';

// Jika user sudah login sebelumnya, langsung dialihkan ke dashboard user
if (isset($_SESSION['login_user']) && $_SESSION['login_user'] === true) {
    header("Location: user/index.php");
    exit;
}

$notif = "";

// LOGIKA PROSES VALIDASI LOGIN USER
if (isset($_POST['login_user_btn'])) {
    $username = mysqli_real_escape_string($koneksi, trim($_POST['username']));
    $password = mysqli_real_escape_string($koneksi, trim($_POST['password']));

    // Query pencarian akun
    $query = "SELECT * FROM pengguna WHERE username = '$username' AND password = '$password'";
    $eksekusi = mysqli_query($koneksi, $query);

    if ($eksekusi && mysqli_num_rows($eksekusi) > 0) {
        $data_user = mysqli_fetch_assoc($eksekusi);

        // Validasi Role: Pastikan kolom role bernilai 'User'
        if (isset($data_user['role']) && $data_user['role'] === 'User') {
            
            // Set Session User
            $_SESSION['login_user'] = true;
            $_SESSION['user_id']    = $data_user['id'];
            $_SESSION['user_name']  = $data_user['nama_lengkap'];
            $_SESSION['user_uname'] = $data_user['username'];
            
            // Alihkan ke dashboard user
            header("Location: user/index.php");
            exit;
        } else {
            // Jika yang login ternyata admin, arahkan ke login_admin.php
            $notif = "<div class='alert alert-warning alert-dismissible fade show small shadow-sm' role='alert'>
                        <i class='bi bi-shield-exclamation me-2'></i> Akun Anda terdeteksi sebagai Admin. Silakan login melalui halaman khusus admin.
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
    <title>BersihIn - Masuk Akun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: #f4f7f6; height: 100vh; }
        .login-container { max-width: 420px; width: 100%; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center p-3">

    <div class="login-container">
        
        <?= $notif; ?>

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-header bg-success text-white text-center py-4 border-0">
                <div class="fs-1 text-white mb-1"><i class="bi bi-recycle"></i></div>
                <h5 class="fw-bold mb-0">Selamat Datang di BersihIn</h5>
                <small class="text-white-50">Tukarkan sampahmu menjadi poin reward menarik</small>
            </div>
            
            <div class="card-body p-4 bg-white">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted small">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                            <input type="text" name="username" class="form-control bg-light border-start-0" placeholder="Masukkan username" required autocomplete="off">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted small">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" class="form-control bg-light border-start-0" placeholder="••••••••" required>
                        </div>
                    </div>
                    
                    <button type="submit" name="login_user_btn" class="btn btn-success w-100 py-2.5 fw-bold shadow-sm rounded-3">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Aplikasi
                    </button>
                </form>
            </div>
            
            <div class="card-footer bg-light border-0 py-3 text-center d-flex justify-content-between px-4">
                <a href="register.php" class="text-decoration-none text-muted small">Belum punya akun? <strong class="text-success">Daftar</strong></a>
                <a href="login_admin.php" class="text-decoration-none text-warning small fw-bold"><i class="bi bi-shield-lock"></i> Portal Admin</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>