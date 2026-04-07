<?php
require_once 'config/database.php';
session_start();

// Cek Login
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data gambar untuk dihapus
    $query = mysqli_prepare($conn, "SELECT gambar FROM wisata WHERE id = ?");
    mysqli_stmt_bind_param($query, "i", $id);
    mysqli_stmt_execute($query);
    $result = mysqli_stmt_get_result($query);
    $data = mysqli_fetch_assoc($result);

    if ($data) {
        $gambar = $data['gambar'];
        
        // Hapus file gambar jika ada
        if (file_exists('public/uploads/' . $gambar) && $gambar != '') {
            unlink('public/uploads/' . $gambar);
        }

        // Hapus data dari database
        $delete = mysqli_prepare($conn, "DELETE FROM wisata WHERE id = ?");
        mysqli_stmt_bind_param($delete, "i", $id);
        
        if (mysqli_stmt_execute($delete)) {
            echo "<script>alert('Data berhasil dihapus!'); window.location='index.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus data!'); window.location='index.php';</script>";
        }
    } else {
        echo "<script>alert('Data tidak ditemukan!'); window.location='index.php';</script>";
    }
} else {
    header("Location: index.php");
}
?>
