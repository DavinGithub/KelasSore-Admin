<?php
if (!function_exists('isActive')) {
    function isActive($menuItem) {
        // Ambil current page dari URL
        $currentUrl = $_SERVER['REQUEST_URI'];
        // Jika di dashboard (root path atau /index.php)
        if ($currentUrl == '/' || $currentUrl == '/index.php' || strpos($currentUrl, 'dashboard') !== false) {
            $currentPage = 'dashboard';
        } else {
            // Extract page name dari URL (mentor, kelas, dll)
            preg_match('/pages\/([^\/]+)/', $currentUrl, $matches);
            $currentPage = isset($matches[1]) ? $matches[1] : 'dashboard';
        }
        return (strtolower($menuItem) === strtolower($currentPage)) ? 'active' : '';
    }
}

$navItems = [
    ['icon' => 'fa-th-large', 'text' => 'Dashboard', 'url' => '/admin/views/pages/dashboard/dashboard.php'],
    ['icon' => 'fa-th-large', 'text' => 'Pembayaran', 'url' => '/admin/views/pages/pembayaran/pembayaran.php'],
    ['icon' => 'fa-chalkboard-teacher', 'text' => 'Mentor', 'url' => '/admin/views/pages/mentor/mentor.php'],
    ['icon' => 'fa-graduation-cap', 'text' => 'Kelas', 'url' => '/admin/views/pages/kelas/kelas.php'],
    ['icon' => 'fa-book', 'text' => 'Buku', 'url' => '/admin/views/pages/buku/buku.php'],
    ['icon' => 'fa-th-large', 'text' => 'Artikel', 'url' => '/admin/views/pages/artikel/artikel.php'],
];
?>

<div class="sidebar">
<div class="logo">
        <img src="/public/logo.svg" alt="Logo" style="max-width: 100%; height: auto;">
    </div>
    <nav>
        <?php foreach($navItems as $item): ?>
            <a href="<?php echo $item['url']; ?>" 
               class="nav-item <?php echo isActive($item['text']); ?>">
                <i class="fas <?php echo $item['icon']; ?>"></i>
                <?php echo $item['text']; ?>
            </a>
        <?php endforeach; ?>
    </nav>
</div>

<style>
.sidebar {
    width: 250px;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    background-color: #fff;
    padding: 20px;
    box-shadow: 2px 0 5px rgba(0,0,0,0.1);
    z-index: 1000;
}

.logo {
    font-size: 24px;
    font-weight: bold;
    color: #4A6CF7;
    margin-bottom: 30px;
}

.nav-item {
    padding: 12px 15px;
    margin-bottom: 5px;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
    color: #666;
    text-decoration: none;
    transition: all 0.3s ease;
}

.nav-item:hover {
    background-color: #f0f0f0;
}

.nav-item.active {
    background-color: #4A6CF7;
    color: white;
    text-decoration: none;
}

.nav-item i {
    width: 20px;
}
</style>