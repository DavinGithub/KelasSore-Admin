<?php
require_once dirname(__FILE__) . '/../../../controllers/BookController.php';

$bookController = new BookController();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    // Create Book
    if ($action === 'create') {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $ebookFile = $_FILES['ebook_file'];
        $image = $_FILES['image'];

        if ($bookController->createBook($title, $description, $ebookFile, $image)) {
            header("Location: buku.php");
        }
    }

    // Update Book
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
    <script>
        function showAddForm() {
            document.getElementById('addBookForm').style.display = 'block';
            document.getElementById('updateBookForm').style.display = 'none';
        }

        function showUpdateForm(bookId, title, description, ebookFile, image) {
            document.getElementById('addBookForm').style.display = 'none';
            document.getElementById('updateBookForm').style.display = 'block';
            document.getElementById('update_book_id').value = bookId;
            document.getElementById('update_title').value = title;
            document.getElementById('update_description').value = description;
            document.getElementById('current_ebook_file').innerHTML = ebookFile ? ebookFile : 'No File';
            document.getElementById('current_image').innerHTML = image ? image : 'No Image';
        }
    </script>
</head>
<body>
    <?php include '../../../views/layout/sidebar.php'; ?>
<div class="main-content">
    <div class="container">
        <div class="top-bar">
            <h1>Buku</h1>
            <button class="add-mentor-btn" onclick="showAddForm()">
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

        <!-- Add Book Form -->
        <div id="addBookForm" style="display:none;">
            <h2>Add New Book</h2>
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="action" value="create">
                <label>Title</label>
                <input type="text" name="title" required>
                <label>Description</label>
                <textarea name="description" required></textarea>
                <label>Ebook File</label>
                <input type="file" name="ebook_file" required>
                <label>Image</label>
                <input type="file" name="image" required>
                <button type="submit">Add Book</button>
                <button type="button" onclick="document.getElementById('addBookForm').style.display='none'">Cancel</button>
            </form>
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
</body>
</html>
