<?php
require_once 'config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek Login
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

// Stats Query
$count_wisata = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM wisata"))['total'];
$sum_pengunjung = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_pengunjung) as total FROM wisata"))['total'];

// Data Query
$query = "SELECT w.*, k.nama_kategori 
          FROM wisata w 
          JOIN kategori k ON w.kategori_id = k.id 
          ORDER BY w.created_at DESC";
$result = mysqli_query($conn, $query);

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="flex-grow bg-gray-50 pb-10">
    <!-- Hero Section -->
    <div class="bg-brand-teal text-white py-12 mb-8 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <h1 class="text-3xl md:text-4xl font-bold mb-2">Dashboard Wisata</h1>
            <p class="text-teal-100 mb-6 max-w-2xl">Kelola data pariwisata Lombok dengan mudah. Pantau statistik dan perbarui informasi secara real-time.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-3xl">
                <div class="bg-white/10 backdrop-blur-sm p-4 rounded-lg flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center text-xl">
                        <i class="fa-solid fa-map-location-dot"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold"><?php echo $count_wisata; ?></div>
                        <div class="text-sm text-teal-100">Objek Wisata</div>
                    </div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm p-4 rounded-lg flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center text-xl">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold"><?php echo number_format($sum_pengunjung ?? 0, 0, ',', '.'); ?></div>
                        <div class="text-sm text-teal-100">Total Pengunjung</div>
                    </div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm p-4 rounded-lg flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center text-xl">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <div>
                        <div class="text-lg font-bold"><?php echo date('d M Y'); ?></div>
                        <div class="text-sm text-teal-100">Hari Ini</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4">
        <!-- Action Bar -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <h2 class="text-xl font-bold text-gray-700 border-l-4 border-brand-teal pl-3">Daftar Objek Wisata</h2>
            <div class="flex flex-wrap gap-2">
                <a href="tambah.php" class="bg-brand-teal hover:bg-teal-700 text-white px-5 py-2.5 rounded-lg shadow-md hover:shadow-lg transition flex items-center gap-2 text-sm font-medium">
                    <i class="fa-solid fa-plus"></i> Tambah Data
                </a>
                <a href="export/word.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg shadow hover:shadow-lg transition flex items-center gap-2 text-sm font-medium">
                    <i class="fa-solid fa-file-word"></i> Word
                </a>
                <a href="export/pdf.php" target="_blank" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2.5 rounded-lg shadow hover:shadow-lg transition flex items-center gap-2 text-sm font-medium">
                    <i class="fa-solid fa-file-pdf"></i> PDF
                </a>
            </div>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-5 py-4 w-10">No</th>
                        <th class="px-5 py-4 w-24">Gambar</th>
                        <th class="px-5 py-4">Nama Wisata</th>
                        <th class="px-5 py-4">Kategori</th>
                        <th class="px-5 py-4">Lokasi</th>
                        <th class="px-5 py-4">Harga</th>
                        <th class="px-5 py-4 text-center">Pengunjung</th>
                        <th class="px-5 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr class="hover:bg-teal-50/30 transition duration-150">
                                <td class="px-5 py-4 text-sm text-gray-600"><?php echo $no++; ?></td>
                                <td class="px-5 py-4">
                                    <div class="w-16 h-12 rounded overflow-hidden shadow-sm">
                                        <?php if ($row['gambar']): ?>
                                            <img src="public/uploads/<?php echo htmlspecialchars($row['gambar']); ?>" class="w-full h-full object-cover transform hover:scale-110 transition duration-300">
                                        <?php else: ?>
                                            <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400 text-xs">No img</div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-sm font-medium text-gray-800">
                                    <?php echo htmlspecialchars($row['nama_wisata']); ?>
                                </td>
                                <td class="px-5 py-4 text-sm">
                                    <span class="px-3 py-1 text-xs font-semibold text-teal-800 bg-teal-100 rounded-full">
                                        <?php echo htmlspecialchars($row['nama_kategori']); ?>
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-sm text-gray-600">
                                    <i class="fa-solid fa-map-marker-alt text-gray-400 mr-1"></i>
                                    <?php echo htmlspecialchars($row['lokasi']); ?>
                                </td>
                                <td class="px-5 py-4 text-sm text-gray-600 font-mono">
                                    Rp<?php echo number_format($row['harga_tiket'], 0, ',', '.'); ?>
                                </td>
                                <td class="px-5 py-4 text-sm text-gray-600 text-center">
                                    <?php echo number_format($row['total_pengunjung'], 0, ',', '.'); ?>
                                </td>
                                <td class="px-5 py-4 text-sm text-center">
                                    <div class="flex justify-center gap-3">
                                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="p-2 text-amber-500 hover:bg-amber-50 rounded-full transition" title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a href="hapus.php?id=<?php echo $row['id']; ?>" class="p-2 text-red-500 hover:bg-red-50 rounded-full transition" onclick="return confirmDelete(<?php echo $row['id']; ?>)" title="Hapus">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="px-5 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fa-regular fa-folder-open text-4xl mb-3 text-gray-300"></i>
                                    <p>Belum ada data wisata.</p>
                                    <a href="tambah.php" class="text-brand-teal hover:underline mt-2 text-sm">Tambah Data Sekarang</a>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="grid grid-cols-1 gap-4 md:hidden">
            <?php 
            mysqli_data_seek($result, 0); // Reset pointer
            if (mysqli_num_rows($result) > 0):
                while ($row = mysqli_fetch_assoc($result)): 
            ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="relative h-48">
                        <?php if ($row['gambar']): ?>
                            <img src="public/uploads/<?php echo htmlspecialchars($row['gambar']); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">No Image</div>
                        <?php endif; ?>
                        <div class="absolute top-2 right-2 flex gap-1">
                            <span class="px-2 py-1 bg-white/90 backdrop-blur text-xs font-bold rounded shadow text-gray-700">
                                Note: <?php echo htmlspecialchars($row['nama_kategori']); ?>
                            </span>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-bold text-gray-800"><?php echo htmlspecialchars($row['nama_wisata']); ?></h3>
                            <div class="flex gap-2">
                                <a href="edit.php?id=<?php echo $row['id']; ?>" class="text-amber-500 bg-amber-50 p-2 rounded-full text-sm">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <a href="hapus.php?id=<?php echo $row['id']; ?>" class="text-red-500 bg-red-50 p-2 rounded-full text-sm" onclick="return confirmDelete(<?php echo $row['id']; ?>)">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="flex items-center text-gray-500 text-sm mb-3">
                            <i class="fa-solid fa-map-pin mr-2 w-4"></i>
                            <?php echo htmlspecialchars($row['lokasi']); ?>
                        </div>

                        <div class="grid grid-cols-2 gap-4 border-t pt-3 mt-2">
                            <div>
                                <div class="text-xs text-gray-400 uppercase">Harga Tiket</div>
                                <div class="font-bold text-brand-teal">Rp<?php echo number_format($row['harga_tiket'], 0, ',', '.'); ?></div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-400 uppercase">Pengunjung</div>
                                <div class="font-bold text-gray-700"><?php echo number_format($row['total_pengunjung'], 0, ',', '.'); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
            <?php else: ?>
                <div class="bg-white p-8 rounded-xl shadow-sm text-center">
                    <p class="text-gray-500">Belum ada data tersedia.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
