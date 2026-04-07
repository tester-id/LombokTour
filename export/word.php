<?php
// 1. Load Konfigurasi & Cek Session
require_once '../config/database.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$username = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');

// 2. Setting Timezone (PENTING: Agar waktu cetak sesuai lokasi Lombok)
date_default_timezone_set('Asia/Makassar');

// 3. Buat Nama File Dinamis (Agar tidak menimpa file lama)
$filename = "Laporan_Wisata_LombokTour_" . date('Ymd_His') . ".doc";

// 4. Header untuk memforce download sebagai Word
header("Content-Type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");

// 5. Query Data (Sama seperti sebelumnya)
$query = "SELECT w.*, k.nama_kategori 
          FROM wisata w 
          JOIN kategori k ON w.kategori_id = k.id 
          ORDER BY w.nama_wisata ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Data Wisata</title>
    <style>
        /* CSS Inline untuk mempercantik tampilan di Word */
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #0f766e; /* Teal-700 similar */ }
        .header p { margin: 5px 0; font-size: 12px; color: #555; }
        
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #14b8a6; /* Teal-500 */ color: white; padding: 10px; }
        td { padding: 8px; vertical-align: top; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .footer { font-size: 10px; color: #888; margin-top: 20px; text-align: right; }
    </style>
</head>
<body>

    <div class="header">
        <h2>LOMBOK TOUR - DATA PARIWISATA</h2>
        <p>Laporan Data Objek Wisata Provinsi Nusa Tenggara Barat</p>
        <hr>
    </div>

    <table border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Nama Wisata</th>
                <th width="15%">Kategori</th>
                <th width="30%">Lokasi</th>
                <th width="15%">Harga Tiket</th>
                <th width="15%">Pengunjung</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            if (mysqli_num_rows($result) > 0):
                while ($row = mysqli_fetch_assoc($result)): 
            ?>
            <tr>
                <td class="text-center"><?php echo $no++; ?></td>
                <td>
                    <strong><?php echo htmlspecialchars($row['nama_wisata']); ?></strong>
                </td>
                <td><?php echo htmlspecialchars($row['nama_kategori']); ?></td>
                <td><?php echo htmlspecialchars($row['lokasi']); ?></td>
                <td class="text-right">Rp <?php echo number_format($row['harga_tiket'], 0, ',', '.'); ?></td>
                <td class="text-right"><?php echo number_format($row['total_pengunjung'], 0, ',', '.'); ?> Org</td>
            </tr>
            <?php 
                endwhile;
            else:
            ?>
            <tr>
                <td colspan="6" class="text-center">Belum ada data wisata yang diinput.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak oleh <?php echo $username; ?> pada: <?php echo date('d F Y, H:i'); ?> WITA</p>
    </div>

</body>
</html>