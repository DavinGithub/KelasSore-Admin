<?php
require_once dirname(__FILE__) . '/../../../controllers/KelasController.php';
require_once dirname(__FILE__) . '/../../../controllers/BookController.php';

// Initialize controllers
$kelasController = new KelasController();
$bukuController = new BookController();

// Get all books
$allBooks = $bukuController->getAllBooks();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        // Optionally log the error for debugging purposes (no output to the user)
        // error_log("Error: " . $result);
        // Redirect to kelas.php if there's an error
        header('Location: kelas.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kelas</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/kelas/tambahkelas.css">
    <style>
        .form-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-container h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            background-color: #fff;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #007BFF;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        fieldset {
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 15px;
        }

        fieldset legend {
            font-size: 1.1rem;
            font-weight: bold;
            color: #555;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end; /* Change from center to flex-end */
            gap: 15px;
            margin-bottom: 20px;
        }

        .btn-primary,
        .btn-secondary {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }

        .btn-primary {
            background-color: #007BFF;
            color: white;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6C757D;
            color: white;
            transition: background-color 0.3s;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .form-group input[type="file"] {
            padding: 5px;
        }

        .form-group select[multiple] {
            height: auto;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px; /* Jarak antar elemen */
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }
    </style>
</head>
<body>
    <?php include '../../../views/layout/sidebar.php'; ?>

    <div class="main-content">
        <div class="container">
            <!-- Removed error message section -->
            <div class="form-container">
                <h1>Tambah Kelas</h1>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name">Nama Kelas:</label>
                            <input type="text" name="name" required>
                        </div>

                        <div class="form-group">
                            <label for="category">Kategori:</label>
                            <select name="category" required>
                                <option value="Private">Private</option>
                                <option value="Reguler">Reguler</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="description">Deskripsi Kelas:</label>
                            <textarea name="description" rows="2" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="mentor_id">Pilih Mentor:</label>
                            <select name="mentor_id" required>
                                <option value="">Pilih Mentor</option>
                                <?php 
                                $allMentors = $kelasController->getAllMentors();
                                foreach ($allMentors as $mentor): ?>
                                    <option value="<?= $mentor['id']; ?>"><?= $mentor['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="name_mentor">Nama Mentor:</label>
                            <input type="text" name="name_mentor" id="name_mentor" readonly>
                        </div>

                        <div class="form-group">
                            <label for="kurikulum">Kurikulum:</label>
                            <input type="text" name="kurikulum" required>
                        </div>

                        <div class="form-group">
                            <label for="price">Harga:</label>
                            <input type="number" name="price" required>
                        </div>

                        <div class="form-group">
                            <label for="quota">Kuota:</label>
                            <input type="number" name="quota" required>
                        </div>

                        <div class="form-group">
                            <label for="quota_left">Kuota Tersisa:</label>
                            <input type="number" name="quota_left" required>
                        </div>

                        <div class="form-group">
                            <label for="schedule">Jadwal:</label>
                            <input type="text" name="schedule" required>
                        </div>

                        <div class="form-group">
                            <label for="end_date">Tanggal Selesai:</label>
                            <input type="date" name="end_date" required>
                        </div>

                        <div class="form-group">
                            <label for="link_wa">Link WhatsApp:</label>
                            <input type="url" name="link_wa">
                        </div>

                        <div class="form-group">
                            <label for="sesion_1">Sesion 1:</label>
                            <input type="url" name="sesion_1" required>
                        </div>

                        <div class="form-group">
                            <label for="sesion_2">Sesion 2:</label>
                            <input type="url" name="sesion_2" required>
                        </div>

                        <div class="form-group">
                            <label for="sesion_3">Sesion 3:</label>
                            <input type="url" name="sesion_3" required>
                        </div>

                        <div class="form-group">
                            <label for="status">Status:</label>
                            <select name="status" required>
                                <option value="buka">Buka</option>
                                <option value="tutup">Tutup</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="image">Gambar Kelas (Optional):</label>
                            <input type="file" name="image">
                        </div>

                        <div class="form-group">
                            <label for="book_ids[]">Buku yang Dibaca:</label>
                            <select name="book_ids[]" multiple>
                                <?php foreach ($allBooks as $book): ?>
                                    <option value="<?= $book['id']; ?>"><?= $book['title']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <fieldset class="form-group">
                            <legend>Apa yang Akan Dipelajari:</legend>
                            <input type="text" name="what_will_learn_1" placeholder="Topik 1" required>
                            <input type="text" name="what_will_learn_2" placeholder="Topik 2" required>
                            <input type="text" name="what_will_learn_3" placeholder="Topik 3" required>
                        </fieldset>

                        <div class="form-actions">
                            <button type="submit" class="btn-primary">Tambah Kelas</button>
                            <a href="kelas.php" class="btn-secondary">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const mentors = <?php echo json_encode($allMentors); ?>;
        
        document.querySelector('[name="mentor_id"]').addEventListener('change', function() {
            const selectedMentorId = this.value;
            const mentorNameField = document.getElementById('name_mentor');
            const selectedMentor = mentors.find(mentor => mentor.id == selectedMentorId);
            mentorNameField.value   = selectedMentor ? selectedMentor.name : '';
        });
    </script>
</body>
</html>
