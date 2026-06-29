<?php
include 'config/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BersihIn - Pengelolaan Sampah Rumah Tangga Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #198754 0%, #146c43 100%);
            padding: 70px 0;
        }
    </style>
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg navbar-dark bg-success sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="index.php">
                <i class="bi bi-trash3-fill me-2"></i>BersihIn
            </a>
            <div class="ms-auto">
                <a href="login.php" class="btn btn-outline-light btn-sm px-4 rounded-pill fw-semibold">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Aplikasi
                </a>
            </div>
        </div>
    </nav>

    <header class="hero-section text-white text-center text-md-start">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-md-6">
                    <span class="badge bg-light text-success px-3 py-2 rounded-pill fw-bold mb-3 shadow-sm">⚡ StartUp Hijau Digital</span>
                    <h1 class="display-4 fw-bold mb-3">Ubah Sampah Rumah Tangga Menjadi Berkah</h1>
                    <p class="lead text-white-50 mb-4">
                        BersihIn membantu Anda memilah sampah organik dan anorganik secara praktis, menjadwalkan penjemputan langsung ke rumah, dan mengumpulkan poin loyalitas untuk ditukarkan dengan hadiah menarik.
                    </p>
                    <a href="login.php" class="btn btn-light text-success btn-md fw-bold px-4 py-2.5 rounded-3 shadow">
                        Mulai Request Penjemputan <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="col-md-6 text-center">
                    <img src="https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?auto=format&fit=crop&w=600&q=80" alt="Daur Ulang" class="img-fluid rounded-4 shadow-lg border border-4 border-white border-opacity-25" style="max-height: 350px; object-fit: cover; width: 100%;">
                </div>
            </div>
        </div>
    </header>

    <section class="container my-5 py-4">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-success">Bagaimana BersihIn Bekerja?</h2>
            <p class="text-muted">3 langkah mudah berkontribusi untuk bumi yang lebih hijau dan bersih</p>
        </div>
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-3 p-4 h-100 bg-white">
                    <div class="p-3 bg-success bg-opacity-10 text-success rounded-circle mx-auto mb-3" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;"><i class="bi bi-flower1 fs-2"></i></div>
                    <h5 class="fw-bold">1. Pilah Dari Rumah</h5>
                    <p class="text-muted small mb-0">Pisahkan sampah organik (sisa makanan) dan sampah anorganik (botol plastik, kertas, kardus).</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-3 p-4 h-100 bg-white">
                    <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-3" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;"><i class="bi bi-truck fs-2"></i></div>
                    <h5 class="fw-bold">2. Request Penjemputan</h5>
                    <p class="text-muted small mb-0">Petugas kebersihan armada BersihIn akan datang mengambil sampah langsung ke lokasi rumah Anda setelah melakukan login.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-3 p-4 h-100 bg-white">
                    <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-circle mx-auto mb-3" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;"><i class="bi bi-star-fill fs-2"></i></div>
                    <h5 class="fw-bold">3. Klaim Reward Poin</h5>
                    <p class="text-muted small mb-0">Setiap kilogram sampah yang disetor akan otomatis dikonversikan menjadi poin reward digital di akun Anda.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white-50 text-center py-3 mt-auto">
        <p class="mb-0 small">&copy; 2026 BersihIn - Mahasiswa Teknik Informatika USTI.</p>
    </footer>
</body>
</html>