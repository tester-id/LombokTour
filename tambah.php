<?php
require_once 'config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
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

    if (empty($nama_wisata) || empty($lokasi) || empty($harga_tiket)) {
        $error = "Kolom wajib tidak boleh kosong!";
    } elseif (!isset($_FILES['gambar']) || $_FILES['gambar']['error'] === 4) {
        $error = "Pilih gambar terlebih dahulu!";
    } else {
        $gambar = $_FILES['gambar'];
        $gambar_nama = time() . '_' . $gambar['name'];
        $gambar_tmp = $gambar['tmp_name'];
        $gambar_size = $gambar['size'];
        $gambar_error = $gambar['error'];
        $gambar_ext_allow = ['jpg', 'jpeg', 'png'];
        $gambar_ext = strtolower(pathinfo($gambar_nama, PATHINFO_EXTENSION));

        if (!in_array($gambar_ext, $gambar_ext_allow)) {
            $error = "Ekstensi gambar harus JPG, JPEG, atau PNG!";
        } elseif ($gambar_size > 10000000) {
            $error = "Ukuran gambar terlalu besar (Max 10MB)!";
        } else {
        move_uploaded_file($gambar_tmp, 'public/uploads/' . $gambar_nama);
        $stmt = mysqli_prepare($conn, "INSERT INTO wisata (kategori_id, nama_wisata, lokasi, total_pengunjung, deskripsi, harga_tiket, gambar) VALUES (?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "issisds", $kategori_id, $nama_wisata, $lokasi, $total_pengunjung, $deskripsi, $harga_tiket, $gambar_nama);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Data berhasil ditambahkan!'); window.location='index.php';</script>";
            exit;
        } else {
            $error = "Gagal menambah data: " . mysqli_error($conn);
        }
        }
    }
}

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="flex-grow flex items-center justify-center py-10 px-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl overflow-hidden">
        <div class="bg-brand-teal px-8 py-6 text-white flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold">Tambah Data Wisata</h2>
                <p class="text-teal-100 text-sm">Masukkan informasi lengkap objek wisata baru.</p>
            </div>
            <div class="hidden sm:block">
                <i class="fa-solid fa-cloud-upload-alt text-4xl text-teal-200/50"></i>
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Nama Wisata <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-umbrella-beach absolute left-3 top-3.5 text-gray-400"></i>
                            <input type="text" name="nama_wisata" class="pl-10 w-full border border-gray-300 rounded-lg py-3 px-4 focus:outline-none focus:ring-2 focus:ring-brand-teal focus:border-transparent transition" placeholder="Contoh: Pantai Kuta" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Kategori <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-list absolute left-3 top-3.5 text-gray-400"></i>
                            <select name="kategori_id" class="pl-10 w-full border border-gray-300 rounded-lg py-3 px-4 focus:outline-none focus:ring-2 focus:ring-brand-teal focus:border-transparent transition appearance-none bg-white" required>
                                <option value="">Pilih Kategori</option>
                                <?php while($kat = mysqli_fetch_assoc($kategori_result)): ?>
                                    <option value="<?php echo $kat['id']; ?>"><?php echo $kat['nama_kategori']; ?></option>
                                <?php endwhile; ?>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3 top-3.5 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Lokasi <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <i class="fa-solid fa-map-marker-alt absolute left-3 top-3.5 text-gray-400"></i>
                        <input type="text" name="lokasi" class="pl-10 w-full border border-gray-300 rounded-lg py-3 px-4 focus:outline-none focus:ring-2 focus:ring-brand-teal focus:border-transparent transition" placeholder="Alamat lengkap lokasi" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Harga Tiket (Rp) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-3.5 text-gray-500 font-bold">Rp</span>
                            <input type="number" name="harga_tiket" class="pl-10 w-full border border-gray-300 rounded-lg py-3 px-4 focus:outline-none focus:ring-2 focus:ring-brand-teal focus:border-transparent transition" placeholder="0" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">Total Pengunjung</label>
                        <div class="relative">
                            <i class="fa-solid fa-users absolute left-3 top-3.5 text-gray-400"></i>
                            <input type="number" name="total_pengunjung" value="0" class="pl-10 w-full border border-gray-300 rounded-lg py-3 px-4 focus:outline-none focus:ring-2 focus:ring-brand-teal focus:border-transparent transition">
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" class="w-full border border-gray-300 rounded-lg py-3 px-4 focus:outline-none focus:ring-2 focus:ring-brand-teal focus:border-transparent transition" placeholder="Deskripsi singkat objek wisata..."></textarea>
                </div>

                <div class="mb-8">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Upload Gambar <span class="text-red-500">*</span></label>
                    <div class="flex items-center justify-center w-full">
                        <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition relative overflow-hidden group">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i class="fa-solid fa-cloud-upload-alt text-2xl text-gray-400 mb-2 group-hover:text-brand-teal transition"></i>
                                <p class="text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span></p>
                                <p class="text-xs text-gray-500 text-center mt-1">JPG, JPEG, PNG (Max 10MB)</p>
                            </div>
                            <input id="dropzone-file" type="file" name="gambar" accept=".jpg,.jpeg,.png" class="hidden" onchange="previewImage(this)" />
                            <img id="img-preview" class="absolute inset-0 w-full h-full object-cover hidden" />
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 border-t pt-6">
                    <a href="index.php" class="text-gray-500 hover:text-gray-700 font-medium px-4 py-2 transition">Batal</a>
                    <button type="submit" class="bg-brand-teal hover:bg-teal-700 text-white font-bold py-3 px-8 rounded-lg shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-teal transition transform hover:-translate-y-0.5">
                        <i class="fa-solid fa-save mr-2"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
