<?php
require_once dirname(__FILE__) . '/../../../controllers/KelasController.php';
require_once dirname(__FILE__) . '/../../../controllers/BookController.php';

// Initialize controllers
$kelasController = new KelasController();
$bukuController = new BookController();
$action = isset($_GET['action']) ? $_GET['action'] : 'list';

// Get all books
$allBooks = $bukuController->getAllBooks();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'create') {
    $data = [
        'mentor_id' => $_POST['mentor_id'] ?? '',
        'name_mentor' => $_POST['name_mentor'] ?? '',
        'name' => $_POST['name'],
        'description' => $_POST['description'],
        'category' => $_POST['category'],
        'kurikulum' => $_POST['kurikulum'],
        'price' => $_POST['price'],
        'quota' => $_POST['quota'],
        'quota_left' => $_POST['quota_left'],
        'schedule' => $_POST['schedule'],
        'end_date' => $_POST['end_date'],
        'link_wa' => $_POST['link_wa'],
        'status' => $_POST['status'],
        'sesion_1' => $_POST['sesion_1'],
        'sesion_2' => $_POST['sesion_2'],
        'sesion_3' => $_POST['sesion_3'],
        'what_will_learn_1' => $_POST['what_will_learn_1'],
        'what_will_learn_2' => $_POST['what_will_learn_2'],
        'what_will_learn_3' => $_POST['what_will_learn_3'],
    ];

    $bookIds = isset($_POST['book_ids']) ? $_POST['book_ids'] : [];
    $imageFile = $_FILES['image'];

    $result = $kelasController->createKelasWithBooks($data, $imageFile, $bookIds);

    if ($result === true) {
        header('Location: kelas.php');
        exit();
    } else {
        $errorMessage = "Error: " . $result;
    }
}

// Handle form submission for update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update') {
    $id = $_POST['id'];
    $data = [
        'mentor_id' => $_POST['mentor_id'] ?? '',
        'name_mentor' => $_POST['name_mentor'] ?? '',
        'name' => $_POST['name'],
        'description' => $_POST['description'],
        'category' => $_POST['category'],
        'kurikulum' => $_POST['kurikulum'],
        'price' => $_POST['price'],
        'quota' => $_POST['quota'],
        'quota_left' => $_POST['quota_left'],
        'schedule' => $_POST['schedule'],
        'end_date' => $_POST['end_date'],
        'link_wa' => $_POST['link_wa'],
        'status' => $_POST['status'],
        'sesion_1' => $_POST['sesion_1'],
        'sesion_2' => $_POST['sesion_2'],
        'sesion_3' => $_POST['sesion_3'],
        'what_will_learn_1' => $_POST['what_will_learn_1'],
        'what_will_learn_2' => $_POST['what_will_learn_2'],
        'what_will_learn_3' => $_POST['what_will_learn_3'],
    ];

    $bookIds = isset($_POST['book_ids']) ? $_POST['book_ids'] : [];
    $imageFile = $_FILES['image'];

    $result = $kelasController->updateKelasWithBooks($id, $data, $imageFile, $bookIds);

    if ($result === true) {
        header('Location: kelas.php');
        exit();
    } else {
        $errorMessage = "Error: " . $result;
    }
}

if ($action === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $kelasController->deleteKelas($id);
    if ($result === true) {
        header('Location: kelas.php');
        exit();
    } else {
        $errorMessage = "Error: " . $result;
    }
}

$deals = $kelasController->getAllKelas();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kelas</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/kelas/kelas.css">
    <style>
       
    </style>
</head>

<body>
    <?php include '../../../views/layout/sidebar.php'; ?>

    <div class="main-content">
        <div class="container">
            <?php if (isset($errorMessage)): ?>
                <div class="error-message" style="color: red; margin-bottom: 15px;">
                    <?php echo $errorMessage; ?>
                </div>
            <?php endif; ?>

            <div class="top-bar">
                <h1>Kelas</h1>
                <button class="add-mentor-btn" id="openModalBtn">
                    <i class="fas fa-plus"></i> Tambah Kelas
                </button>
            </div>

            <div class="deals-table">
                <div class="deals-header">
                    <h2>Daftar Kelas</h2>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Nama Kelas</th>
                            <th>Nama Mentor</th>
                            <th>Tanggal Dimulai</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($deals as $deal):
                            // Prepare deal data for JavaScript
                            $dealData = array_merge($deal, [
                                'book_ids' => $kelasController->getAllKelas($deal['id'])
                            ]);
                        ?>
                            <tr>
                                <td>
                                    <div class="product-info">
                                        <span><?php echo htmlspecialchars($deal['name']); ?></span>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($deal['name_mentor']); ?></td>
                                <td><?php echo htmlspecialchars($deal['schedule']); ?></td>
                                <td><?php echo htmlspecialchars($deal['category']); ?></td>
                                <td>Rp.<?php echo number_format($deal['price'], 2); ?></td>
                                
                                <td>
                                    <button onclick='openEditModal(<?php echo htmlspecialchars(json_encode($dealData)); ?>)' class="btn-primary">
                                        <i class="fas fa-edit"></i>
                                        Update
                                    </button>
                                    <a href="?action=delete&id=<?php echo $deal['id']; ?>"
                                        onclick="return confirm('Are you sure you want to delete this class?');"
                                        class="delete-btn">
                                        <i class="fas fa-trash"></i>
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Include the modal components -->
            <?php include 'tambahkelas.php'; ?>
            <?php include 'updatekelas.php'; ?>
        </div>
    </div>

    <script>
        const modal = document.getElementById('addKelasModal');
        const updateModal = document.getElementById('updateKelasModal');
        const openModalBtn = document.getElementById('openModalBtn');
        const closeBtns = document.getElementsByClassName('close');

        openModalBtn.onclick = function() {
            modal.style.display = 'block';
        }

        for (let closeBtn of closeBtns) {
            closeBtn.onclick = function() {
                modal.style.display = 'none';
                updateModal.style.display = 'none';
            }
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
            if (event.target == updateModal) {
                updateModal.style.display = 'none';
            }
        }

    </script>
</body>

</html>