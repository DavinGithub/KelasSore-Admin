<?php
require_once dirname(__FILE__) . '/../../../controllers/BookController.php';

$bookController = new BookController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'create') {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $ebookFile = $_FILES['ebook_file'];
        $image = $_FILES['image'];

        if ($bookController->createBook($title, $description, $ebookFile, $image)) {
            header("Location: buku.php");
        }
    }

    if ($action === 'update') {
        $bookId = $_POST['book_id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $ebookFile = $_FILES['ebook_file'];
        $image = $_FILES['image'];

        if ($bookController->updateBook($bookId, $title, $description, $ebookFile, $image)) {
            header("Location: buku.php");
        }
    }

    // Delete Book
    if (isset($_POST['book_id']) && $_POST['action'] === 'delete') {
        $bookId = $_POST['book_id'];
        if ($bookController->deleteBook($bookId)) {
            header("Location: buku.php");
        }
    }
}

// Fetch all books
$books = $bookController->getAllBooks();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/mentor/mentor.css">
    <style>
        /* Modal Styles */
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
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
            position: relative;
        }

        .close-modal {
            position: absolute;
            right: 10px;
            top: 5px;
            font-size: 24px;
            cursor: pointer;
            color: #666;
        }

        .close-modal:hover {
            color: #000;
        }

        .modal form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .modal form input,
        .modal form textarea {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .modal form button {
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .modal form button[type="submit"] {
            background-color: #4CAF50;
            color: white;
        }

        .modal form button[type="button"] {
            background-color: #f44336;
            color: white;
        }

        .button-group {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>
    <?php include '../../../views/layout/sidebar.php'; ?>
    <div class="main-content">
        <div class="container">
            <div class="top-bar">
                <h1>Buku</h1>
                <button class="add-mentor-btn" onclick="openAddModal()">
                    <i class="fas fa-plus"></i> Add Book
                </button>
            </div>

            <div class="deals-table">
                <div class="deals-header">
                    <h2>Buku Details</h2>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Buku</th>
                            <th>Rating</th>
                            <th>Deskripsi</th>
                            <th>File Ebook</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($books as $book): ?>
                        <tr>
                            <td>
                                <div class="product-info">
                                    <div class="product-image">
                                        <img src="<?php echo htmlspecialchars($book['image']); ?>" alt="Book Image" width="50">
                                    </div>
                                    <span><?php echo htmlspecialchars($book['title'] ?? 'No Title'); ?></span>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($book['rating'] ?? 0); ?></td>
                            <td><?php echo htmlspecialchars($book['description'] ?? 'No Description'); ?></td>
                            <td>
                                <a href="<?php echo htmlspecialchars($book['ebook_file']); ?>" target="_blank">Download Ebook</a>
                            </td>
                            <td>
                                <button onclick="showUpdateForm(
                                    '<?php echo $book['id']; ?>', 
                                    '<?php echo htmlspecialchars($book['title']); ?>', 
                                    '<?php echo htmlspecialchars($book['description']); ?>', 
                                    '<?php echo htmlspecialchars($book['ebook_file']); ?>', 
                                    '<?php echo htmlspecialchars($book['image']); ?>'
                                )">Update</button>
                                <form method="POST" action="" style="display:inline;">
                                    <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                                    <button type="submit" name="action" value="delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Add Book Modal -->
            <div id="addBookModal" class="modal">
                <div class="modal-content">
                    <span class="close-modal" onclick="closeAddModal()">&times;</span>
                    <h2>Add New Book</h2>
                    <form method="POST" action="" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="create">
                        
                        <div>
                            <label>Title</label>
                            <input type="text" name="title" required>
                        </div>
                        
                        <div>
                            <label>Description</label>
                            <textarea name="description" required></textarea>
                        </div>
                        
                        <div>
                            <label>Ebook File</label>
                            <input type="file" name="ebook_file" required>
                        </div>
                        
                        <div>
                            <label>Image</label>
                            <input type="file" name="image" required>
                        </div>
                        
                        <div class="button-group">
                            <button type="submit">Add Book</button>
                            <button type="button" onclick="closeAddModal()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Update Book Form -->
            <div id="updateBookForm" style="display:none;">
                <h2>Update Book</h2>
                <form method="POST" action="" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="book_id" id="update_book_id">
                    <label>Title</label>
                    <input type="text" name="title" id="update_title" required>
                    <label>Description</label>
                    <textarea name="description" id="update_description" required></textarea>
                    <label>Ebook File</label>
                    <input type="file" name="ebook_file">
                    <small>Current file: <span id="current_ebook_file">No File</span></small>
                    <label>Image</label>
                    <input type="file" name="image">
                    <small>Current image: <span id="current_image">No Image</span></small>
                    <button type="submit">Update Book</button>
                    <button type="button" onclick="document.getElementById('updateBookForm').style.display='none'">Cancel</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('addBookModal').style.display = 'block';
        }

        function closeAddModal() {
            document.getElementById('addBookModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('addBookModal');
            if (event.target == modal) {
                closeAddModal();
            }
        }

        function showUpdateForm(bookId, title, description, ebookFile, image) {
            document.getElementById('updateBookForm').style.display = 'block';
            document.getElementById('update_book_id').value = bookId;
            document.getElementById('update_title').value = title;
            document.getElementById('update_description').value = description;
            document.getElementById('current_ebook_file').innerHTML = ebookFile ? ebookFile : 'No File';
            document.getElementById('current_image').innerHTML = image ? image : 'No Image';
        }
    </script>
</body>
</html>-