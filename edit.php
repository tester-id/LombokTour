<?php
require_once 'config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

$query = mysqli_prepare($conn, "SELECT * FROM wisata WHERE id = ?");
mysqli_stmt_bind_param($query, "i", $id);
mysqli_stmt_execute($query);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($query));

if (!$data) {
    echo "Data tidak ditemukan!";
    exit;
}

$kategori_result = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_wisata = trim($_POST['nama_wisata']);
    $kategori_id = $_POST['kategori_id'];
    $lokasi = trim($_POST['lokasi']);
    $total_pengunjung = $_POST['total_pengunjung'];
    $harga_tiket = $_POST['harga_tiket'];
    $deskripsi = trim($_POST['deskripsi']);
    $gambar_lama = $_POST['gambar_lama'];

    if ($_FILES['gambar']['error'] === 4) {
        $gambar_nama = $gambar_lama;
    } else {
        $gambar = $_FILES['gambar'];
        $gambar_nama = time() . '_' . $gambar['name'];
        $gambar_tmp = $gambar['tmp_name'];
        $gambar_ext_allow = ['jpg', 'jpeg', 'png'];
        $gambar_ext = strtolower(pathinfo($gambar_nama, PATHINFO_EXTENSION));

        if (!in_array($gambar_ext, $gambar_ext_allow)) {
            $error = "Ekstensi gambar harus JPG, JPEG, atau PNG!";
        } elseif ($gambar['size'] > 10000000) {
            $error = "Ukuran gambar terlalu besar (Max 10MB)!";
        } else {
            if (file_exists('public/uploads/' . $gambar_lama) && $gambar_lama != '') {
                unlink('public/uploads/' . $gambar_lama);
            }
            move_uploaded_file($gambar_tmp, 'public/uploads/' . $gambar_nama);
        }
    }

    if (empty($error)) {
        $stmt = mysqli_prepare($conn, "UPDATE wisata SET kategori_id=?, nama_wisata=?, lokasi=?, total_pengunjung=?, deskripsi=?, harga_tiket=?, gambar=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "issisdsi", $kategori_id, $nama_wisata, $lokasi, $total_pengunjung, $deskripsi, $harga_tiket, $gambar_nama, $id);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Data berhasil diperbarui!'); window.location='index.php';</script>";
            exit;
        } else {
            $error = "Gagal update data: " . mysqli_error($conn);
        }
    }
}

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="flex-grow flex items-center justify-center py-10 px-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl overflow-hidden">
        <div class="bg-amber-500 px-8 py-6 text-white flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold">Edit Data Wisata</h2>
                <p class="text-amber-100 text-sm">Perbarui informasi objek wisata.</p>
            </div>
            <div class="hidden sm:block">
                <i class="fa-solid fa-edit text-4xl text-amber-200/50"></i>
            </div>
        </div>
        
        <div class="p-8">
            <?php if($error): ?>
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r" role="alert">
                    <p class="font-bold">Error</p>
                    <p><?php echo $error; ?></p>
                </div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="gambar_lama" value="<?php echo $data['gambar']; ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Nama Wisata</label>
                        <div class="relative">
                            <i class="fa-solid fa-umbrella-beach absolute left-3 top-3.5 text-gray-400"></i>
                            <input type="text" name="nama_wisata" value="<?php echo htmlspecialchars($data['nama_wisata']); ?>" class="pl-10 w-full border border-gray-300 rounded-lg py-3 px-4 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Kategori</label>
                        <div class="relative">
                            <i class="fa-solid fa-list absolute left-3 top-3.5 text-gray-400"></i>
                            <select name="kategori_id" class="pl-10 w-full border border-gray-300 rounded-lg py-3 px-4 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition appearance-none bg-white" required>
                                <option value="">Pilih Kategori</option>
                                <?php while($kat = mysqli_fetch_assoc($kategori_result)): ?>
                                    <option value="<?php echo $kat['id']; ?>" <?php echo ($kat['id'] == $data['kategori_id']) ? 'selected' : ''; ?>>
                                        <?php echo $kat['nama_kategori']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3 top-3.5 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Lokasi</label>
                    <div class="relative">
                        <i class="fa-solid fa-map-marker-alt absolute left-3 top-3.5 text-gray-400"></i>
                        <input type="text" name="lokasi" value="<?php echo htmlspecialchars($data['lokasi']); ?>" class="pl-10 w-full border border-gray-300 rounded-lg py-3 px-4 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Harga Tiket (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-3.5 text-gray-500 font-bold">Rp</span>
                            <input type="number" name="harga_tiket" value="<?php echo $data['harga_tiket']; ?>" class="pl-10 w-full border border-gray-300 rounded-lg py-3 px-4 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Total Pengunjung</label>
                        <div class="relative">
                            <i class="fa-solid fa-users absolute left-3 top-3.5 text-gray-400"></i>
                            <input type="number" name="total_pengunjung" value="<?php echo $data['total_pengunjung']; ?>" class="pl-10 w-full border border-gray-300 rounded-lg py-3 px-4 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition">
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" class="w-full border border-gray-300 rounded-lg py-3 px-4 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition"><?php echo htmlspecialchars($data['deskripsi']); ?></textarea>
                </div>

                <div class="mb-8">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Gambar</label>
                    <div class="flex flex-col sm:flex-row gap-4 items-center">
                        <div class="w-32 flex-shrink-0">
                            <?php if($data['gambar']): ?>
                                <img src="public/uploads/<?php echo htmlspecialchars($data['gambar']); ?>" class="w-32 h-24 object-cover rounded-lg shadow-sm border">
                                <p class="text-xs text-center text-gray-500 mt-1">Saat Ini</p>
                            <?php endif; ?>
                        </div>
                        <div class="w-full">
                            <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-24 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition relative overflow-hidden group">
                                <div class="flex flex-col items-center justify-center pt-2 pb-3">
                                    <i class="fa-solid fa-cloud-upload-alt text-xl text-gray-400 mb-1 group-hover:text-amber-500 transition"></i>
                                    <p class="text-sm text-gray-500">Klik untuk ganti gambar</p>
                                </div>
                                <input id="dropzone-file" type="file" name="gambar" accept=".jpg,.jpeg,.png" class="hidden" onchange="previewImage(this)" />
                                <img id="img-preview" class="absolute inset-0 w-full h-full object-cover hidden" />
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 border-t pt-6">
                    <a href="index.php" class="text-gray-500 hover:text-gray-700 font-medium px-4 py-2 transition">Batal</a>
                    <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-8 rounded-lg shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition transform hover:-translate-y-0.5">
                        <i class="fa-solid fa-save mr-2"></i> Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
