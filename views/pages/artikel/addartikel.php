<?php
include dirname(__FILE__) . '/../../../controllers/ArtikelController.php';

$artikelController = new ArtikelController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mengambil data dari form
    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];
    $content = $_POST['content'];
    $image = $_FILES['image'];

    // Panggil metode createArtikel untuk menyimpan data
    $result = $artikelController->createArtikel($title, $subtitle, $content, $image);

    // Mengirimkan response JSON
    if ($result === true) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $result]);
    }
}
?>

<?php
// pastikan untuk menyertakan file layout atau sidebar sesuai kebutuhan
include '../../../views/layout/sidebar.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/artikel/artikel.css">
    <style>
        /* Gaya untuk modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .modal h3 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 1.5rem;
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-control-file {
            font-size: 1rem;
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
        }

        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
        }

        .btn-success {
            background-color: #28a745;
            color: #fff;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: #fff;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .close-btn {
            font-size: 1.5rem;
            cursor: pointer;
            float: right;
        }

        /* Nonaktifkan scroll saat modal tampil */
        body.modal-open {
            overflow: hidden;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="container">
            <!-- Modal -->
            <div id="addArtikelModal" class="modal">
                <div class="modal-content">
                    <span class="close-btn" id="closeModal">&times;</span>
                    <h3>Tambah Artikel</h3>
                    <form id="addArtikelForm" method="POST" action="artikel.php" enctype="multipart/form-data">

                        <div class="form-group">
                            <label for="title">Judul Artikel</label>
                            <input type="text" id="title" name="title" class="form-control" placeholder="Masukkan judul artikel" required>
                        </div>
                        <div class="form-group">
                            <label for="subtitle">Subjudul</label>
                            <input type="text" id="subtitle" name="subtitle" class="form-control" placeholder="Masukkan subjudul artikel" required>
                        </div>
                        <div class="form-group">
                            <label for="content">Konten</label>
                            <textarea id="content" name="content" class="form-control" rows="5" placeholder="Tulis konten artikel" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="image">Gambar</label>
                            <input type="file" id="image" name="image" class="form-control-file" accept="image/*" required>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success">Simpan</button>
                            <button type="button" id="cancelBtn" class="btn btn-secondary">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('addArtikelModal');
        const closeModal = document.getElementById('closeModal');
        const cancelBtn = document.getElementById('cancelBtn');
        const body = document.body;

        // Fungsi untuk membuka modal
        function openModal() {
            modal.style.display = 'flex';
            body.classList.add('modal-open'); // Nonaktifkan scroll
        }

        // Menutup modal
        closeModal.onclick = () => {
            modal.style.display = 'none';
            body.classList.remove('modal-open'); // Aktifkan scroll kembali
        };

        cancelBtn.onclick = () => {
            modal.style.display = 'none';
            body.classList.remove('modal-open'); // Aktifkan scroll kembali
        };

        window.onclick = (event) => {
            if (event.target === modal) {
                modal.style.display = 'none';
                body.classList.remove('modal-open'); // Aktifkan scroll kembali
            }
        };

        // Menambahkan event listener untuk menutup modal setelah berhasil menambah data
        const addArtikelForm = document.getElementById('addArtikelForm');

        addArtikelForm.onsubmit = function(event) {
            event.preventDefault(); // Mencegah form submit standar

            const formData = new FormData(addArtikelForm);

            // Lakukan request AJAX menggunakan Fetch API
            fetch('artikel.php', { // Pastikan endpoint ini mengarah ke artikel.php
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Jika sukses, tutup modal dan refresh halaman
                    modal.style.display = 'none';
                    body.classList.remove('modal-open'); // Aktifkan scroll kembali
                    alert('Artikel berhasil ditambahkan!');
                    window.location.reload(); // Refresh halaman setelah berhasil menambah data
                } else {
                    alert('Terjadi kesalahan: ' + data.error);
                }
            })
            .catch(error => {
                alert('Error: ' + error);
            });
        };
    </script>
</body>
</html>
