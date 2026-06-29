<?php
include "../config/koneksi.php";

if (!isset($_GET['id'])) {
    header("Location: user.php");
    exit;
}

$id = $_GET['id'];

$query = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE id='$id'");

if (mysqli_num_rows($query) == 0) {
    echo "<script>
            alert('Data user tidak ditemukan');
            window.location='user.php';
          </script>";
    exit;
}

$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Data User</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f5f5f5;
}

.card{
    margin-top:40px;
    border:none;
    box-shadow:0 0 10px rgba(0,0,0,.15);
}

</style>

</head>

<body>

<div class="container">

<div class="card">

<div class="card-header bg-warning">

<h4>Edit Data User</h4>

</div>

<div class="card-body">

<form action="update_user.php" method="POST">

<input type="hidden" name="id" value="<?= $data['id']; ?>">

<div class="mb-3">

<label>Nama Lengkap</label>

<input
type="text"
name="nama_lengkap"
class="form-control"
value="<?= $data['nama_lengkap']; ?>"
required>

</div>

<div class="mb-3">

<label>Username</label>

<input
type="text"
name="username"
class="form-control"
value="<?= $data['username']; ?>"
required>

</div>

<div class="mb-3">

<label>Password Baru</label>

<input
type="text"
name="password"
class="form-control"
placeholder="Kosongkan jika tidak ingin diubah">

<small class="text-muted">
Kosongkan apabila password tidak diganti.
</small>

</div>

<div class="mb-3">

<label>Point</label>

<input
type="number"
name="point"
class="form-control"
value="<?= $data['point']; ?>"
required>

</div>

<div class="mb-3">

<label>Role</label>

<select
name="role"
class="form-control">

<option value="User"
<?= ($data['role']=="User") ? "selected" : ""; ?>>
User
</option>

<option value="Admin"
<?= ($data['role']=="Admin") ? "selected" : ""; ?>>
Admin
</option>

</select>

</div>

<button
type="submit"
class="btn btn-success">

Simpan Perubahan

</button>

<a
href="user.php"
class="btn btn-secondary">

Kembali

</a>

</form>

</div>

</div>

</div>

</body>

</html>