<?php
require_once '../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = trim($_POST['nama_lengkap']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($nama) || empty($username) || empty($password)) {
        $error = "Semua kolom wajib diisi!";
    } else {
        $check = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Username sudah digunakan, silakan pilih yang lain.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = mysqli_prepare($conn, "INSERT INTO users (nama_lengkap, username, password) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sss", $nama, $username, $hashed_password);
            
            if (mysqli_stmt_execute($stmt)) {
                $success = "Registrasi berhasil!";
            } else {
                $error = "Terjadi kesalahan: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        }
    }
}

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="flex-grow flex items-center justify-center bg-teal-50/50 py-10 px-4">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden w-full max-w-4xl flex flex-col md:flex-row h-full">
        
        <!-- Left Side: Image -->
        <div class="hidden md:block w-1/2 bg-cover bg-center relative" style="background-image: url('../public/assets/rinjani.webp?q=100&w=1000&auto=format&fit=crop');">
            <div class="absolute inset-0 bg-teal-900/40 backdrop-blur-[1px] flex flex-col justify-end p-8 text-white">
                <h3 class="text-3xl font-bold mb-2">Bergabung Bersama Kami</h3>
                <p class="text-teal-100">Daftarkan akun admin baru untuk mulai mengelola data pariwisata Lombok.</p>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
            <div class="text-center md:text-left mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Buat Akun</h2>
                <p class="text-gray-500">Isi data diri Anda di bawah ini.</p>
            </div>

            <?php if($error): ?>
                <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-4 border border-red-100 flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if($success): ?>
                <div class="bg-green-50 text-green-700 p-4 rounded-lg text-center mb-6 border border-green-200">
                    <div class="text-4xl mb-2"><i class="fa-solid fa-check-circle"></i></div>
                    <p class="font-bold text-lg mb-1">Registrasi Berhasil!</p>
                    <p class="text-sm mb-4">Akun Anda telah berhasil dibuat.</p>
                    <a href="login.php" class="inline-block bg-green-600 text-white font-bold py-2 px-6 rounded-full hover:bg-green-700 transition shadow-lg shadow-green-500/30">
                        Login Sekarang
                    </a>
                </div>
            <?php else: ?>

            <form action="" method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Nama Lengkap</label>
                    <div class="relative">
                        <i class="fa-solid fa-id-card absolute left-3 top-3.5 text-gray-400"></i>
                        <input class="pl-10 w-full border border-gray-300 rounded-lg py-3 px-4 focus:outline-none focus:ring-2 focus:ring-brand-teal focus:border-transparent transition" name="nama_lengkap" type="text" placeholder="Nama Lengkap" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Username</label>
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-3 top-3.5 text-gray-400"></i>
                        <input class="pl-10 w-full border border-gray-300 rounded-lg py-3 px-4 focus:outline-none focus:ring-2 focus:ring-brand-teal focus:border-transparent transition" name="username" type="text" placeholder="Buat Username" required>
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-3 top-3.5 text-gray-400"></i>
                        <input class="pl-10 w-full border border-gray-300 rounded-lg py-3 px-4 focus:outline-none focus:ring-2 focus:ring-brand-teal focus:border-transparent transition" name="password" type="password" placeholder="Buat Password" required>
                    </div>
                    <!-- <p class="text-xs text-gray-400 mt-1">Minimal 8 karakter.</p> -->
                </div>
                
                <button class="w-full bg-brand-teal hover:bg-teal-700 text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300 shadow-lg shadow-teal-500/30 transform hover:-translate-y-0.5" type="submit">
                    Daftar Akun
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-gray-500">
                Sudah punya akun? 
                <a href="login.php" class="text-brand-teal font-bold hover:underline">Login disini</a>
            </div>
            
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
