<?php
// File: views/pages/mentor/mentor.php


// Include necessary files
include_once dirname(__FILE__) . '/../../../controllers/MentorController.php';
include_once dirname(__FILE__) . '/../../../models/MentorModel.php';
include_once dirname(__FILE__) . '/../../../services/database.php';


// Initialize controller
$mentorController = new MentorController();

// Handle actions
$action = isset($_GET['action']) ? $_GET['action'] : '';
$message = '';

// Create Mentor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_mentor'])) {
    $message = $mentorController->register(
        $_POST['email'], 
        $_POST['name'], 
        $_POST['password'], 
        $_POST['password_confirm'], 
        $_POST['phone_number']
    );
}

// Update Mentor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_mentor'])) {
    $message = $mentorController->update(
        $_POST['mentor_id'],
        $_POST['email'], 
        $_POST['name'], 
        $_POST['phone_number'],
        $_POST['salary_recived'], 
        $_POST['salary_remaining']
    );
}

// Fetch mentors
$mentors = $mentorController->getAllMentors();

// Fetch specific mentor for edit (if applicable)
$editMentor = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $editMentor = $mentorController->getMentorById($_GET['edit']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentor Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/mentor/mentor.css">
    <link rel="stylesheet" href="../../../assets/css/mentor/mentormodal.css">
</head>
<body>
    <?php include '../../../views/layout/sidebar.php'; ?>

    <div class="main-content">
        <div class="container">
            <div class="top-bar">
                <h1>Mentor Management</h1>
                <button class="add-mentor-btn">
                    <i class="fas fa-plus"></i> Buat Akun Mentor
                </button>
            </div>

            <?php if (!empty($message)): ?>
                <div class="alert <?php echo strpos($message, 'Error') !== false ? 'alert-danger' : 'alert-success'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <div class="mentors-table">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Mentor</th>
                            <th>Email</th>
                            <th>Nomor Handphone</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($mentors as $mentor): ?>
                        <tr>
                            <td>
                                <div class="mentor-info">
                                    <div class="mentor-image">
                                        <?php if (!empty($mentor['profile_picture'])): ?>
                                            <img src="<?php echo htmlspecialchars($mentor['profile_picture']); ?>" alt="Profile Picture">
                                        <?php else: ?>
                                            <img src="../../../assets/images/default-profile.png" alt="Default Profile">
                                        <?php endif; ?>
                                    </div>
                                    <span><?php echo htmlspecialchars($mentor['name']); ?></span>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($mentor['email']); ?></td>
                            <td><?php echo htmlspecialchars($mentor['phone_number']); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="?action=detail&id=<?php echo $mentor['id']; ?>" class="btn btn-info">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    <a href="?edit=<?php echo $mentor['id']; ?>" class="btn btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add/Edit Mentor Modal -->
    <div id="addMentorModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2><?php echo $editMentor ? 'Edit Mentor' : 'Tambah Mentor Baru'; ?></h2>
            
            <form action="" method="POST">
                <?php if ($editMentor): ?>
                    <input type="hidden" name="mentor_id" value="<?php echo $editMentor['id']; ?>">
                    <input type="hidden" name="update_mentor" value="1">
                <?php else: ?>
                    <input type="hidden" name="create_mentor" value="1">
                <?php endif; ?>

                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" 
                           value="<?php echo $editMentor ? htmlspecialchars($editMentor['name']) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" 
                           value="<?php echo $editMentor ? htmlspecialchars($editMentor['email']) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="phone_number">Nomor Handphone</label>
                    <input type="tel" id="phone_number" name="phone_number" 
                           value="<?php echo $editMentor ? htmlspecialchars($editMentor['phone_number']) : ''; ?>" required>
                </div>

                <?php if (!$editMentor): ?>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <div class="form-group">
                        <label for="password_confirm">Konfirmasi Password</label>
                        <input type="password" id="password_confirm" name="password_confirm" required>
                    </div>
                <?php endif; ?>

                <?php if ($editMentor): ?>
                    <div class="form-group">
                        <label for="salary_recived">Gaji Diterima</label>
                        <input type="number" id="salary_recived" name="salary_recived" 
                               value="<?php echo $editMentor['salary_recived'] ?? 0; ?>">
                    </div>

                    <div class="form-group">
                        <label for="salary_remaining">Gaji Tersisa</label>
                        <input type="number" id="salary_remaining" name="salary_remaining" 
                               value="<?php echo $editMentor['salary_remaining'] ?? 0; ?>">
                    </div>
                <?php endif; ?>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <?php echo $editMentor ? 'Update Mentor' : 'Tambah Mentor'; ?>
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal handling
        const modal = document.getElementById('addMentorModal');
        const addMentorButton = document.querySelector('.add-mentor-btn');

        <?php if ($editMentor): ?>
            // If in edit mode, automatically open the modal
            window.onload = function() {
                modal.style.display = 'block';
            };
        <?php endif; ?>

        // Open modal for adding mentor
        addMentorButton.addEventListener('click', function() {
            modal.style.display = 'block';
        });

        // Close modal function
        function closeModal() {
            modal.style.display = 'none';
            // Reset URL to remove edit parameter
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.pathname);
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target === modal) {
                closeModal();
            }
        };
    </script>
</body>
</html>