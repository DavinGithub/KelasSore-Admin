<?php


if (!isset($_SESSION['admin_id'])) {
    $page = 'login';
} else {
    $page = $_GET['page'] ?? 'dashboard';
}

$allowedPages = [
    'login' => 'admin/views/pages/login/login.php',
    'dashboard' => 'admin/views/pages/dashboard/dashboard.php',
    'buku' => 'admin/views/pages/buku/buku.php',
    'mentor' => 'admin/views/pages/mentor/mentor.php',
    'kelas' => 'admin/views/pages/kelas/kelas.php',
];


if (array_key_exists($page, $allowedPages)) {
    include $allowedPages[$page];
} else {
    echo "404 - Page not found!";
}
?>
