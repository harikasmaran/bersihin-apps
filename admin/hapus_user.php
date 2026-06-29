<?php
include "../config/koneksi.php";

if (!isset($_GET['id'])) {
    header("Location: user.php");
    exit;
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);

// Ambil data user
$query = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE id='$id'");

if (mysqli_num_rows($query) == 0) {

    echo "<script>
            alert('Data user tidak ditemukan!');
            window.location='user.php';
          </script>";
    exit;
}

$data = mysqli_fetch_assoc($query);

/*
|----------------------------------------------------------
| Mencegah akun Admin dihapus
|----------------------------------------------------------
*/
if ($data['role'] == "Admin") {

    echo "<script>
            alert('Akun Admin tidak boleh dihapus!');
            window.location='user.php';
          </script>";
    exit;
}

/*
|----------------------------------------------------------
| Hapus data user
|----------------------------------------------------------
*/

$hapus = mysqli_query($koneksi, "DELETE FROM pengguna WHERE id='$id'");

if ($hapus) {

    echo "<script>
            alert('Data user berhasil dihapus');
            window.location='user.php';
          </script>";

} else {

    echo "<script>
            alert('Data user gagal dihapus');
            window.location='user.php';
          </script>";
}
?>