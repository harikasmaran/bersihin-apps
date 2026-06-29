<?php
include "../config/koneksi.php";

$query = mysqli_query($koneksi, "SELECT * FROM pengguna ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Data User</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body{
            background:#f5f5f5;
        }

        .card{
            margin-top:30px;
            border:none;
            box-shadow:0 0 10px rgba(0,0,0,.1);
        }

        .table th{
            background:#198754;
            color:white;
            text-align:center;
        }

        .table td{
            vertical-align:middle;
        }

        h3{
            font-weight:bold;
        }
    </style>

</head>
<body>

<div class="container">

<div class="card">

<div class="card-header bg-success text-white">

<h3>Kelola Data User</h3>
    <a href="index.php"
    class="btn btn-light rounded-circle shadow-sm"
    title="Kembali">
        <i class="bi bi-arrow-left-circle-fill fs-5"></i>
    </a>
</div>

<div class="card-body">

<table class="table table-bordered table-hover">

<thead>

<tr>

<th>No</th>
<th>Nama Lengkap</th>
<th>Username</th>
<th>Point</th>
<th>Role</th>
<th width="170">Aksi</th>

</tr>

</thead>

<tbody>

<?php

$no=1;

while($data=mysqli_fetch_assoc($query)){

?>

<tr>

<td align="center"><?= $no++; ?></td>

<td><?= $data['nama_lengkap']; ?></td>

<td><?= $data['username']; ?></td>

<td align="center">

<span class="badge bg-success">

<?= $data['point']; ?>

</span>

</td>

<td align="center">

<?php

if($data['role']=="Admin"){

?>

<span class="badge bg-danger">

Admin

</span>

<?php

}else{

?>

<span class="badge bg-primary">

User

</span>

<?php

}

?>

</td>

<td align="center">

<a href="edit_user.php?id=<?= $data['id']; ?>" class="btn btn-warning btn-sm">

Edit

</a>

<a href="hapus_user.php?id=<?= $data['id']; ?>"

onclick="return confirm('Yakin ingin menghapus user ini?')"

class="btn btn-danger btn-sm">

Hapus

</a>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

</div>

</div>

</body>
</html>