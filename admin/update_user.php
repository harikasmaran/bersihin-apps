<?php
include "../config/koneksi.php";

if(isset($_POST['id'])){

    $id             = mysqli_real_escape_string($koneksi,$_POST['id']);
    $nama_lengkap   = mysqli_real_escape_string($koneksi,$_POST['nama_lengkap']);
    $username       = mysqli_real_escape_string($koneksi,$_POST['username']);
    $password       = mysqli_real_escape_string($koneksi,$_POST['password']);
    $point          = mysqli_real_escape_string($koneksi,$_POST['point']);
    $role           = mysqli_real_escape_string($koneksi,$_POST['role']);

    // Jika password dikosongkan
    if($password==""){

        $sql = "UPDATE pengguna SET
                nama_lengkap='$nama_lengkap',
                username='$username',
                point='$point',
                role='$role'
                WHERE id='$id'";

    }else{

        $sql = "UPDATE pengguna SET
                nama_lengkap='$nama_lengkap',
                username='$username',
                password='$password',
                point='$point',
                role='$role'
                WHERE id='$id'";

    }

    if(mysqli_query($koneksi,$sql)){

        echo "<script>
                alert('Data user berhasil diperbarui');
                window.location='user.php';
              </script>";

    }else{

        echo "<script>
                alert('Gagal memperbarui data');
                window.location='edit_user.php?id=$id';
              </script>";

    }

}else{

    header("Location: user.php");

}
?>