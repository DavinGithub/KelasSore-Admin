<?php


if (!isset($_SESSION['admin_id'])) {
    $page = 'login';
} else {
    $page = $_GET['page'] ?? 'dashboard';
}

$allowedPages = [
    'login' => 'views/pages/login/login.php',
    'dashboard' => 'views/pages/dashboard/dashboard.php',
    'buku' => 'views/pages/buku/buku.php',
    'mentor' => 'views/pages/mentor/mentor.php',
    'kelas' => 'views/pages/kelas/kelas.php',
];


if (array_key_exists($page, $allowedPages)) {
    include $allowedPages[$page];
} else {
    echo "404 - Page not found!";
}
?>
