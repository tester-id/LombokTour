// Custom scripts for LombokTour

document.addEventListener('DOMContentLoaded', function () {
    // Mobile Menu Toggle
    const btn = document.getElementById('mobile-menu-btn');
    const menu = document.getElementById('mobile-menu');

    if (btn && menu) {
        btn.addEventListener('click', () => {
            const isOpen = btn.classList.contains('active');

            if (!isOpen) {
                // Opening
                btn.classList.add('active');
                menu.classList.remove('hidden');
                setTimeout(() => {
                    menu.classList.add('active');
                }, 10);
            } else {
                // Closing
                btn.classList.remove('active');
                menu.classList.remove('active');
                // Wait for animation to finish before hiding
                setTimeout(() => {
                    menu.classList.add('hidden');
                }, 300); // matches CSS duration
            }
        });
    }

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('[role="alert"]');
    if (alerts.length > 0) {
        setTimeout(() => {
            alerts.forEach(el => {
                el.style.transition = "opacity 0.5s ease";
                el.style.opacity = "0";
                setTimeout(() => el.remove(), 500);
            });
        }, 5000);
    }
});

function confirmDelete(id) {
    if (confirm("Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.")) {
        window.location.href = 'hapus.php?id=' + id;
    }
}

function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            const preview = document.getElementById('img-preview');
            if (preview) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}
