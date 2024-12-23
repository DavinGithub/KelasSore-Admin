<?php
require_once dirname(__FILE__) . '/../../../controllers/KelasController.php';
require_once dirname(__FILE__) . '/../../../controllers/BookController.php';

// Initialize controllers
$kelasController = new KelasController();
$bukuController = new BookController();
$action = isset($_GET['action']) ? $_GET['action'] : 'list';

// Get all books
$allBooks = $bukuController->getAllBooks();

// Handle form submission for create
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
        'start_date' => $_POST['start_date'],
        'end_date' => $_POST['end_date'],
        'link_wa' => $_POST['link_wa'],
        'status' => $_POST['status'],
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
        'start_date' => $_POST['start_date'],
        'end_date' => $_POST['end_date'],
        'link_wa' => $_POST['link_wa'],
        'status' => $_POST['status'],
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

// Handle delete action
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

// Get all classes
$deals = $kelasController->getAllKelas();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kelas</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/mentor/mentor.css">
    <style>
        .delete-btn {
            background-color: #dc3545;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            border: none;
            cursor: pointer;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 14px;
            display: inline-block;
        }

        .status-active {
            background-color: #28a745;
            color: white;
        }

        .status-inactive {
            background-color: #dc3545;
            color: white;
        }

        .status-pending {
            background-color: #ffc107;
            color: black;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
        }
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
                                <td><?php echo htmlspecialchars($deal['start_date']); ?></td>
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

        function openEditModal(dealData) {
            const updateModal = document.getElementById('updateKelasModal');
            updateModal.style.display = 'block';

            // Populate form fields
            document.getElementById('updateKelasId').value = dealData.id;
            document.getElementById('updateName').value = dealData.name;
            document.getElementById('updateDescription').value = dealData.description;
            document.getElementById('updateMentorId').value = dealData.mentor_id;
            document.getElementById('updateNameMentor').value = dealData.name_mentor;
            document.getElementById('updateCategory').value = dealData.category;
            document.getElementById('updateKurikulum').value = dealData.kurikulum;
            document.getElementById('updatePrice').value = dealData.price;
            document.getElementById('updateQuota').value = dealData.quota;
            document.getElementById('updateQuotaLeft').value = dealData.quota_left;
            document.getElementById('updateStartDate').value = dealData.start_date;
            document.getElementById('updateEndDate').value = dealData.end_date;
            document.getElementById('updateLinkWa').value = dealData.link_wa;
            document.getElementById('updateStatus').value = dealData.status;
            document.getElementById('updateWhatWillLearn1').value = dealData.what_will_learn_1;
            document.getElementById('updateWhatWillLearn2').value = dealData.what_will_learn_2;
            document.getElementById('updateWhatWillLearn3').value = dealData.what_will_learn_3;

            // Handle book selections
            if (dealData.book_ids) {
                const bookSelect = document.getElementById('updateBookIds');
                Array.from(bookSelect.options).forEach(option => {
                    option.selected = dealData.book_ids.includes(parseInt(option.value));
                });
            }
        }

        function closeEditModal() {
            updateModal.style.display = 'none';
        }
    </script>
</body>

</html>