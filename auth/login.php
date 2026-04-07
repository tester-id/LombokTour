<?php
require_once '../config/database.php';

// Start session at the very beginning to be safe
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Username dan Password wajib diisi!";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id, nama_lengkap, password FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['nama_lengkap'] = $row['nama_lengkap'];
                $_SESSION['username'] = $username;
                header("Location: ../index.php");
                exit;
            } else {
                $error = "Password salah!";
            }
        } else {
            $error = "Username tidak ditemukan!";
        }
    }
}

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="flex-grow flex items-center justify-center bg-teal-50/50 py- px-4">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden w-full max-w-4xl flex flex-col md:flex-row h-full md:h-[500px]">
        
        <!-- Left Side: Image -->
        <div class="hidden md:block w-1/2 bg-cover bg-center relative" style="background-image: url('../public/assets/beach.webp');">
            <div class="absolute inset-0 bg-teal-900/40 backdrop-blur-[1px] flex flex-col justify-end p-8 text-white">
                <h3 class="text-3xl font-bold mb-2">Jelajahi Lombok</h3>
                <p class="text-teal-100">Kelola informasi pariwisata dengan mudah dan cepat melalui dashboard admin kami.</p>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
            <div class="text-center md:text-left mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Selamat Datang</h2>
                <p class="text-gray-500">Silakan login untuk masuk ke dashboard.</p>
            </div>

            <?php if($error): ?>
                <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-4 border border-red-100 flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Username</label>
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-3 top-3.5 text-gray-400"></i>
                        <input class="pl-10 w-full border border-gray-300 rounded-lg py-3 px-4 focus:outline-none focus:ring-2 focus:ring-brand-teal focus:border-transparent transition" name="username" type="text" placeholder="Masukkan username" required>
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-3 top-3.5 text-gray-400"></i>
                        <input class="pl-10 w-full border border-gray-300 rounded-lg py-3 px-4 focus:outline-none focus:ring-2 focus:ring-brand-teal focus:border-transparent transition" name="password" type="password" placeholder="••••••••" required>
                    </div>
                    <div class="text-right mt-1">
                        <!-- <a href="#" class="text-xs text-brand-teal hover:underline">Lupa password?</a> -->
                    </div>
                </div>
                
                <button class="w-full bg-brand-teal hover:bg-teal-700 text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300 shadow-lg shadow-teal-500/30 transform hover:-translate-y-0.5">
                    Masuk Sekarang
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-gray-500">
                Belum punya akun? 
                <a href="register.php" class="text-brand-teal font-bold hover:underline">Daftar disini</a>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
