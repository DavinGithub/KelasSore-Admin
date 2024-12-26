<?php
include dirname(__FILE__) . '/../../../controllers/ArtikelController.php';

$artikelController = new ArtikelController();
$artikels = $artikelController->getAllArtikel();

// Urutkan data berdasarkan waktu terbaru
if (!empty($artikels)) {
    usort($artikels, function ($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];
    $content = $_POST['content'];
    $image = $_FILES['image'];

    $result = $artikelController->createArtikel($title, $subtitle, $content, $image);

    if ($result === true) {
        // Get the latest data after insertion
        $newArtikels = $artikelController->getAllArtikel();
        usort($newArtikels, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        // Return the new data along with success message
        echo json_encode([
            'success' => true,
            'data' => $newArtikels
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => $result]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikel Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/artikel/artikel.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <style>
        .deals-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-add {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn-add:hover {
            background-color: #45a049;
        }

        .text-ellipsis {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px;
        }
    </style>
</head>
<body>
    <?php include '../../../views/layout/sidebar.php'; ?>

    <div class="main-content">
        <div class="container">
            <div class="top-bar">
                <h1>Artikel</h1>
            </div>
            
            <div class="deals-table">
                <div class="deals-header">
                    <h2>Artikel Management</h2>
                    <button class="btn-add" onclick="openAddArtikelModal()">+ Add Artikel</button>
                </div>

                <div id="tableContainer">
                    <?php if (empty($artikels)): ?>
                        <div class="empty-state">
                            <i class="fas fa-newspaper"></i>
                            <p>Tidak ada artikel yang tersedia saat ini.</p>
                        </div>
                    <?php else: ?>
                        <table id="artikelTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Title</th>
                                    <th>Subtitle</th>
                                    <th>Image</th>
                                    <th>Content</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($artikels as $artikel): ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td class="text-ellipsis"><?php echo htmlspecialchars($artikel['title']); ?></td>
                                        <td class="text-ellipsis"><?php echo htmlspecialchars($artikel['subtitle']); ?></td>
                                        <td>
                                            <img src="../../../assets/images/artikels/<?php echo htmlspecialchars($artikel['image']); ?>" 
                                                 alt="<?php echo htmlspecialchars($artikel['title']); ?>"
                                                 class="artikel-image">
                                        </td>
                                        <td>
                                            <div class="content-preview">
                                                <?php echo htmlspecialchars($artikel['content']); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="artikel-detail.php?id=<?php echo $artikel['id']; ?>" class="btn btn-info">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                                <a href="artikel-edit.php?id=<?php echo $artikel['id']; ?>" class="btn-edit">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script>
        let dataTable;

        $(document).ready(function() {
            initializeDataTable();
        });

        function initializeDataTable() {
            if ($.fn.DataTable.isDataTable('#artikelTable')) {
                $('#artikelTable').DataTable().destroy();
            }
            dataTable = $('#artikelTable').DataTable();
        }

        function updateTable(data) {
            // Destroy existing DataTable
            if ($.fn.DataTable.isDataTable('#artikelTable')) {
                $('#artikelTable').DataTable().destroy();
            }

            let tableHtml = `
                <table id="artikelTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Title</th>
                            <th>Subtitle</th>
                            <th>Image</th>
                            <th>Content</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>`;

            data.forEach((artikel, index) => {
                tableHtml += `
                    <tr>
                        <td>${index + 1}</td>
                        <td class="text-ellipsis">${artikel.title}</td>
                        <td class="text-ellipsis">${artikel.subtitle}</td>
                        <td>
                            <img src="../../../assets/images/artikels/${artikel.image}" 
                                 alt="${artikel.title}"
                                 class="artikel-image">
                        </td>
                        <td>
                            <div class="content-preview">
                                ${artikel.content}
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="artikel-detail.php?id=${artikel.id}" class="btn btn-info">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                <a href="artikel-edit.php?id=${artikel.id}" class="btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </div>
                        </td>
                    </tr>`;
            });

            tableHtml += `
                    </tbody>
                </table>`;

            document.getElementById('tableContainer').innerHTML = tableHtml;
            initializeDataTable();
        }

        function openAddArtikelModal() {
            fetch('addartikel.php')
                .then(response => response.text())
                .then(data => {
                    const modalContainer = document.createElement('div');
                    modalContainer.innerHTML = data;
                    document.body.appendChild(modalContainer);

                    const modal = document.getElementById('addArtikelModal');
                    modal.style.display = 'flex';

                    const closeModal = document.getElementById('closeModal');
                    const cancelBtn = document.getElementById('cancelBtn');
                    const addArtikelForm = document.getElementById('addArtikelForm');

                    closeModal.onclick = () => {
                        modal.style.display = 'none';
                        modalContainer.remove();
                    };

                    cancelBtn.onclick = () => {
                        modal.style.display = 'none';
                        modalContainer.remove();
                    };

                    window.onclick = (event) => {
                        if (event.target === modal) {
                            modal.style.display = 'none';
                            modalContainer.remove();
                        }
                    };

                    addArtikelForm.onsubmit = function(event) {
                        event.preventDefault();
                        const formData = new FormData(addArtikelForm);

                        fetch('artikel.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(result => {
                            if (result.success) {
                                alert('Artikel berhasil ditambahkan!');
                                updateTable(result.data); // Update table with new data
                                modal.style.display = 'none';
                                modalContainer.remove();
                            } else {
                                alert('Terjadi kesalahan: ' + result.error);
                            }
                        })
                        .catch(error => {
                            alert('Error: ' + error);
                        });
                    };
                })
                .catch(error => console.error('Error loading modal:', error));
        }
    </script>
</body>
</html>