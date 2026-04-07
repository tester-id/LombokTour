<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_path = $_SERVER['PHP_SELF'];
$path_parts = explode('/', trim($current_path, '/'));
$project_index = array_search('lomboktour', $path_parts);
$depth = count($path_parts) - $project_index - 2;
$base = "";
for($i = 0; $i < $depth; $i++) { $base .= "../"; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LombokTour - Manajemen Wisata</title>
    <link rel="shortcut icon" href="https://images.icon-icons.com/644/PNG/512/yellow_beach-chair-and-umbrella_icon-icons.com_59553.png" type="image/x-icon">
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        'brand-teal': '#0d9488', // teal-600
                        'brand-dark': '#111827', // gray-900
                        'brand-light': '#f0fdfa', // teal-50
                    }
                }
            }
        }
    </script>
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo $base; ?>public/css/style.css">
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen font-sans">
