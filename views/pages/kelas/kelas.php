<?php
require_once dirname(__FILE__) . '/../../../controllers/KelasController.php';
require_once dirname(__FILE__) . '/../../../controllers/BookController.php';

// Inisialisasi controller
$kelasController = new KelasController();
$bukuController = new BookController();
$action = isset($_GET['action']) ? $_GET['action'] : 'list';

// Ambil semua buku
$allBooks = $bukuController->getAllBooks();

// Proses form submission untuk create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'create') {
    // Ambil data dari form
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

    // Ambil file gambar dari form
    $imageFile = $_FILES['image'];

    $result = $kelasController->createKelasWithBooks($data, $imageFile, $bookIds);

    if ($result === true) {
        header('Location: kelas.php');
        exit();
    } else {
        $errorMessage = "Error: " . $result;
    }
}

// Proses form submission untuk update
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

// Proses delete
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

// Ambil daftar kelas
$deals = $kelasController->getAllKelas();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6. 0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/mentor/mentor.css">
    <style>
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            max-height: 80%;
            overflow-y: auto;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover {
            color: black;
        }
    </style>
</head>
<body>
    <?php include '../../../views/layout/sidebar.php'; ?>
    <div class="main-content">
        <div class="container">
            <!-- Error Message Display -->
            <?php if(isset($errorMessage)): ?>
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
                    <h2>Deals Details</h2>
                    <select>
                        <option>October</option>
                        <option>November</option>
                        <option>December</option>
                    </select>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Nama Kelas</th>
                            <th>Nama Mentor</th>
                            <th>Tanggal Dimulai</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody> 
                        <?php foreach($deals as $deal): ?>
                        <tr>
                            <td>
                                <div class="product-info">
                                    <div class="product-image"></div>
                                    <span><?php echo htmlspecialchars($deal['name']); ?></span>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($deal['name_mentor']); ?></td>
                            <td><?php echo htmlspecialchars($deal['start_date']); ?></td>
                            <td><?php echo htmlspecialchars($deal['category']); ?></td>
                            <td>Rp.<?php echo number_format($deal['price'], 2); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $deal['status']; ?>">
                                    <?php echo ucfirst($deal['status']); ?>
                                </span>
                            </td>
                            <td>
                                <button onclick="openEditModal(<?php echo $deal['id']; ?>)">Update</button>
                                <a href="?action=delete&id=<?php echo $deal['id']; ?>" onclick="return confirm('Are you sure you want to delete this class?');">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Modal for Adding Kelas -->
            <div id="addKelasModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h1>Tambah Kelas</h1>
                    <form action="?action=create" method="post" enctype="multipart/form-data">
                        <!-- Form fields from kelaslama.php -->
                        <label for="name">Nama Kelas:</label>
                        <input type="text" name="name" required>

                        <label for="description">Deskripsi Kelas:</label>
                        <textarea name="description" rows="4" required></textarea>

                        <label for="mentor_id">ID Mentor:</label>
                        <input type="text" name="mentor_id">

                        <label for="name_mentor">Nama Mentor:</label>
                        <input type="text" name="name_mentor">

                        <label for="category">Kategori:</label>
                        <select name="category" required>
                            <option value="Private">Private </option>
                            <option value="Reguler">Reguler</option>
                        </select>

                        <label for="kurikulum">Kurikulum:</label>
                        <input type="text" name="kurikulum" required>

                        <label for="price">Harga:</label>
                        <input type="number" name="price" required>

                        <label for="quota">Kuota:</label>
                        <input type="number" name="quota" required>

                        <label for="quota_left">Kuota Tersisa:</label>
                        <input type="number" name="quota_left" required>

                        <label for="start_date">Tanggal Mulai:</label>
                        <input type="date" name="start_date" required>

                        <label for="end_date">Tanggal Selesai:</label>
                        <input type="date" name="end_date" required>

                        <label for="link_wa">Link WhatsApp:</label>
                        <input type="url" name="link_wa">

                        <label for="status">Status:</label>
                        <select name="status" required>
                            <option value="buka">Buka</option>
                            <option value="tutup">Tutup</option>
                        </select>

                        <label for="what_will_learn_1">Apa yang akan dipelajari 1:</label>
                        <input type="text" name="what_will_learn_1" required>

                        <label for="what_will_learn_2">Apa yang akan dipelajari 2:</label>
                        <input type="text" name="what_will_learn_2" required>

                        <label for="what_will_learn_3">Apa yang akan dipelajari 3:</label>
                        <input type="text" name="what_will_learn_3" required>

                        <label for="image">Gambar Kelas (Optional):</label>
                        <input type="file" name="image">

                        <label for="book_ids[]">Buku yang Dibaca:</label>
                        <select name="book_ids[]" multiple>
                            <?php foreach ($allBooks as $book): ?>
                                <option value="<?= $book['id']; ?>"><?= $book['title']; ?></option>
                            <?php endforeach; ?>
                        </select>

                        <button type="submit">Tambah Kelas</button>
                    </form>
                </div>
            </div>

            <!-- Modal for Editing Kelas -->
            <div id="updateKelasModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeEditModal()">&times;</span>
                    <h1>Edit Kelas</h1>
                    <form id="updateKelasForm" action="?action=update" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="updateKelasId">
                        <label for="updateName">Nama Kelas:</label>
                        <input type="text" name="name" id="updateName" required>

                        <label for="updateDescription">Deskripsi Kelas:</label>
                        <textarea name="description" id="updateDescription" rows="4" required></textarea>

                        <label for="updateMentorId">ID Mentor:</label>
                        <input type="text" name="mentor_id" id="updateMentorId">

                        <label for="updateNameMentor">Nama Mentor:</label>
                        <input type="text" name="name_mentor" id="updateNameMentor">

                        <label for="updateCategory">Kategori:</label>
                        <select name="category" id="updateCategory" required>
                            <option value="Private">Private</option>
                            <option value="Reguler">Reguler</option>
                        </select>

                        <label for="updateKurikulum">Kurikulum:</label>
                        <input type="text" name="kurikulum" id="updateKurikulum" required>

                        <label for="updatePrice">Harga:</label>
                        <input type="number" name="price" id="updatePrice" required>

                        <label for="updateQuota">Kuota:</label>
                        <input type="number" name="quota" id="updateQuota" required>

                        <label for="updateQuotaLeft">Kuota Tersisa:</label>
                        <input type="number" name="quota_left" id="updateQuotaLeft" required>

                        <label for="updateStartDate">Tanggal Mulai:</label>
                        <input type="date" name="start_date" id="updateStartDate" required>

                        <label for="updateEndDate">Tanggal Selesai:</label>
                        <input type ```php
="date" name="end_date" id="updateEndDate" required>

                        <label for="updateLinkWa">Link WhatsApp:</label>
                        <input type="url" name="link_wa" id="updateLinkWa">

                        <label for="updateStatus">Status:</label>
                        <select name="status" id="updateStatus" required>
                            <option value="buka">Buka</option>
                            <option value="tutup">Tutup</option>
                        </select>

                        <label for="updateWhatWillLearn1">Apa yang akan dipelajari 1:</label>
                        <input type="text" name="what_will_learn_1" id="updateWhatWillLearn1" required>

                        <label for="updateWhatWillLearn2">Apa yang akan dipelajari 2:</label>
                        <input type="text" name="what_will_learn_2" id="updateWhatWillLearn2" required>

                        <label for="updateWhatWillLearn3">Apa yang akan dipelajari 3:</label>
                        <input type="text" name="what_will_learn_3" id="updateWhatWillLearn3" required>

                        <label for="updateImage">Gambar Kelas (Optional):</label>
                        <input type="file" name="image">

                        <label for="updateBookIds[]">Buku yang Dibaca:</label>
                        <select name="book_ids[]" id="updateBookIds" multiple>
                            <?php foreach ($allBooks as $book): ?>
                                <option value="<?= $book['id']; ?>"><?= $book['title']; ?></option>
                            <?php endforeach; ?>
                        </select>

                        <button type="submit">Update Kelas</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Modal functionality
        const modal = document.getElementById('addKelasModal');
        const updateModal = document.getElementById('updateKelasModal');
        const openModalBtn = document.getElementById('openModalBtn');
        const closeBtn = document.getElementsByClassName('close')[0];

        openModalBtn.onclick = function() {
            modal.style.display = 'block';
        }

        closeBtn.onclick = function() {
            modal.style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
            if (event.target == updateModal) {
                updateModal.style.display = 'none';
            }
        }

        function openEditModal(id) {
            // Fetch the class data using AJAX or pre-fill the form with existing data
            // For demonstration, let's assume we have a function getClassData that returns the class data
            const classData = getClassData(id); // This function should be implemented to fetch data

            document.getElementById('updateKelasId').value = classData.id;
            document.getElementById('updateName').value = classData.name;
            document.getElementById('updateDescription').value = classData.description;
            document.getElementById('updateMentorId').value = classData.mentor_id;
            document.getElementById('updateNameMentor').value = classData.name_mentor;
            document.getElementById('updateCategory').value = classData.category;
            document.getElementById('updateKurikulum').value = classData.kurikulum;
            document.getElementById('updatePrice').value = classData.price;
            document.getElementById('updateQuota').value = classData.quota;
            document.getElementById('updateQuotaLeft').value = classData.quota_left;
            document.getElementById('updateStartDate').value = classData.start_date;
            document.getElementById('updateEndDate').value = classData.end_date;
            document.getElementById('updateLinkWa').value = classData.link_wa;
            document.getElementById('updateStatus').value = classData.status;
            document.getElementById('updateWhatWillLearn1').value = classData.what_will_learn_1;
            document.getElementById('updateWhatWillLearn2').value = classData.what_will_learn_2;
            document.getElementById('updateWhatWillLearn3').value = classData.what_will_learn_3;

            updateModal.style.display = 'block';
        }

        function closeEditModal() {
            updateModal.style.display = 'none';
        }
    </script>
</body>
</html>
