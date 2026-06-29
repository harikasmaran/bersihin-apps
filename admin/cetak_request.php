<?php
session_start();
include "../config/koneksi.php";

$id = $_GET['id'];

$query = mysqli_query($koneksi,"
SELECT *
FROM penjemputan
WHERE id='$id'
");

$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<title>Formulir Request Penjemputan</title>

<style>

body{

    font-family:Arial;
    margin:40px;

}

table{

    width:100%;
    border-collapse:collapse;

}

td{

    padding:8px;

}

h2{

    text-align:center;

}

hr{

    margin-bottom:25px;

}

@media print{

button{

display:none;

}

}

</style>

</head>

<body>

<button onclick="window.print()">

🖨 Cetak

</button>

<h2>FORMULIR REQUEST PENJEMPUTAN SAMPAH</h2>

<hr>

<table border="1">

<tr>

<td width="35%"><b>Nama Pengguna</b></td>

<td><?= $data['nama_pengguna']; ?></td>

</tr>

<tr>

<td><b>Alamat</b></td>

<td><?= $data['alamat']; ?></td>

</tr>

<tr>

<td><b>Jenis Sampah</b></td>

<td><?= $data['jenis_sampah']; ?></td>

</tr>

<tr>

<td><b>Berat Sampah</b></td>

<td><?= $data['berat_kg']; ?> Kg</td>

</tr>

<tr>

<td><b>Poin Reward</b></td>

<td><?= $data['poin_reward']; ?></td>

</tr>

<tr>

<td><b>Status</b></td>

<td><?= $data['status']; ?></td>

</tr>

</table>

<br><br>

<table width="100%" border="0">

<tr>

<td width="60%"></td>

<td align="center">

.........................,<br><br><br><br>

_____________________<br>

Petugas BersihIn

</td>

</tr>

</table>

<script>

window.print();

</script>

</body>

</html>