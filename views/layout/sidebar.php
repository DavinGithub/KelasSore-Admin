<?php
function isActive($menuItem) {
    $currentPage = basename($_SERVER['PHP_SELF']);
    $menuMap = [
        'index.php' => 'Dashboard',
        'mentor.php' => 'Mentor',
        'kelas.php' => 'Kelas',
        'buku.php' => 'Buku',
        'gaji.php' => 'Gaji',
        'pembayaran.php' => 'Pembayaran'
    ];
    
    return ($menuMap[$currentPage] === $menuItem) ? 'active' : '';
}

$navItems = [
    ['icon' => 'fa-th-large', 'text' => 'Dashboard'],
    ['icon' => 'fa-chalkboard-teacher', 'text' => 'Mentor'],
    ['icon' => 'fa-graduation-cap', 'text' => 'Kelas'],
    ['icon' => 'fa-book', 'text' => 'Buku'],
    ['icon' => 'fa-money-bill-wave', 'text' => 'Gaji'],
    ['icon' => 'fa-credit-card', 'text' => 'Pembayaran']
];
?>

<div class="sidebar">
    <div class="logo">DashStack</div>
    <nav>
        <?php foreach($navItems as $item): ?>
            <div class="nav-item <?php echo isActive($item['text']); ?>">
                <i class="fas <?php echo $item['icon']; ?>"></i>
                <?php echo $item['text']; ?>
            </div>
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
}

.nav-item.active {
    background-color: #4A6CF7;
    color: white;
}

.nav-item i {
    width: 20px;
}
</style>