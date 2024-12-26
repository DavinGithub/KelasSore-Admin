<?php
require_once dirname(__FILE__) . '/../../../controllers/KelasController.php';
require_once dirname(__FILE__) . '/../../../controllers/MentorController.php';
require_once dirname(__FILE__) . '/../../../controllers/BookController.php';

// Initialize controllers
$kelasController = new KelasController();
$mentorController = new MentorController();
$bookController = new BookController();

// Get all mentors and books for dropdowns
$allMentors = $mentorController->getAllMentors();
$allBooks = $bookController->getAllBooks();
?>

<style>
.form-container {
    display: grid;
    grid-template-columns: repeat(3, 1fr);  /* Three columns */
    gap: 12px;  /* Slightly reduced gap between columns */
    padding: 8px;  /* Reduced padding */
    max-width: 90%; /* Slightly reduced max-width of the form */
    margin: 0 auto;
    box-sizing: border-box;
}

.form-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 6px; /* Reduced vertical spacing between form groups */
}

.form-group label {
    font-weight: bold;
    font-size: 11px;  /* Slightly reduced font size */
}

.form-group input, .form-group select, .form-group textarea {
    padding: 5px;  /* Reduced padding */
    font-size: 11px;  /* Slightly reduced font size */
    border: 1px solid #ddd;
    border-radius: 4px;
    height: 28px;  /* Reduced height of input fields */
}

.form-group textarea {
    height: 55px;  /* Adjusted height for textarea */
}

.form-actions {
    grid-column: span 3;  /* Button spans across all 3 columns */
    display: flex;
    justify-content: flex-end;
    gap: 8px;  /* Slightly reduced gap between buttons */
}

.btn-primary, .btn-secondary {
    padding: 6px 12px;  /* Padding for buttons remains the same */
    font-size: 12px;  /* Font size for buttons remains the same */
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn-primary {
    background-color: #4CAF50;
    color: white;
}

.btn-secondary {
    background-color: #f44336;
    color: white;
}

fieldset {
    grid-column: span 3;
    padding: 10px;  /* Keeping the padding here as is */
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-top: 8px;
}

legend {
    font-weight: bold;
    font-size: 12px;  /* Slightly reduced font size */
}

.form-group input[type="file"] {
    padding: 3px;  /* Reduced padding */
    font-size: 11px;  /* Slightly reduced font size */
}

.modal-content {
    position: fixed; /* Fix the modal's position relative to the viewport */
    top: 0; /* Set the top position to keep it fixed near the top of the screen */
    left: 50%; /* Center the modal horizontally */
    transform: translateX(-50%) translateY(-30px); /* Adjusted to make modal slightly higher */
    max-width: 900px; /* Slightly reduced max-width */
    padding: 18px;  /* Slightly reduced padding */
    overflow: auto;
    box-sizing: border-box;
    z-index: 9999; /* Ensure it appears above other content */
}

.close {
    font-size: 22px; /* Slightly reduced font size */
    position: absolute;
    top: 10px;
    right: 15px;
    cursor: pointer;
}
</style>



<div id="updateKelasModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h1>Edit Kelas</h1>
        <form id="updateKelasForm" action="?action=update" method="post" enctype="multipart/form-data" class="form-container">
            <input type="hidden" name="id" id="updateKelasId">

            <!-- First Row -->
            <div class="form-group">
                <label for="updateName">Nama Kelas:</label>
                <input type="text" name="name" id="updateName" required>
            </div>

            <div class="form-group">
                <label for="updateDescription">Deskripsi Kelas:</label>
                <input name="description" id="updateDescription" required></input>
            </div>
            <div class="form-group">
                <label for="updateMentorId">Pilih Mentor:</label>
                <select name="mentor_id" id="updateMentorId" required>
                    <option value="">Pilih Mentor</option>
                    <?php foreach ($allMentors as $mentor): ?>
                        <option value="<?= htmlspecialchars($mentor['id']); ?>">
                            <?= htmlspecialchars($mentor['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="updateNameMentor">Nama Mentor:</label>
                <input type="text" name="name_mentor" id="updateNameMentor" readonly>
            </div>

            <!-- Second Row -->
            <div class="form-group">
                <label for="updateCategory">Kategori:</label>
                <select name="category" id="updateCategory" required>
                    <option value="Private">Private</option>
                    <option value="Reguler">Reguler</option>
                </select>
            </div>
            <div class="form-group">
                <label for="updateKurikulum">Kurikulum:</label>
                <input type="text" name="kurikulum" id="updateKurikulum" required>
            </div>
            <div class="form-group">
                <label for="updatePrice">Harga:</label>
                <input type="number" name="price" id="updatePrice" required>
            </div>

            <!-- Third Row -->
            <div class="form-group">
                <label for="updateQuota">Kuota:</label>
                <input type="number" name="quota" id="updateQuota" required>
            </div>
            <div class="form-group">
                <label for="updateQuotaLeft">Kuota Tersisa:</label>
                <input type="number" name="quota_left" id="updateQuotaLeft" required>
            </div>
            <div class="form-group">
                <label for="updateSchedule">Schedule:</label>
                <input type="text" name="schedule" id="updateSchedule" required>
            </div>

            <!-- Fourth Row -->
            <div class="form-group">
                <label for="updateEndDate">Tanggal Selesai:</label>
                <input type="date" name="end_date" id="updateEndDate" required>
            </div>
            <div class="form-group">
                <label for="updateSesion1">Sesion 1:</label>
                <input type="url" name="sesion_1" id="updateSesion1" required>
            </div>
            <div class="form-group">
                <label for="updateSesion2">Sesion 2:</label>
                <input type="url" name="sesion_2" id="updateSesion2" required>
            </div>

            <!-- Fifth Row -->
            <div class="form-group">
                <label for="updateSesion3">Sesion 3:</label>
                <input type="url" name="sesion_3" id="updateSesion3" required>
            </div>
            <div class="form-group">
                <label for="updateLinkWa">Link WhatsApp:</label>
                <input type="url" name="link_wa" id="updateLinkWa" required>
            </div>
            <div class="form-group">
                <label for="updateStatus">Status:</label>
                <select name="status" id="updateStatus" required>
                    <option value="buka">Buka</option>
                    <option value="tutup">Tutup</option>
                </select>
            </div>

            <!-- Sixth Row -->
            <fieldset class="form-group">
                <legend>Apa yang Akan Dipelajari:</legend>
                <input type="text" name="what_will_learn_1" id="updateWhatWillLearn1" placeholder="Topik 1" required>
                <input type="text" name="what_will_learn_2" id="updateWhatWillLearn2" placeholder="Topik 2" required>
                <input type="text" name="what_will_learn_3" id="updateWhatWillLearn3" placeholder="Topik 3" required>
            </fieldset>

            <!-- Seventh Row -->
            <div class="form-group">
                <label for="updateImage">Gambar Kelas (Optional):</label>
                <input type="file" name="image" id="updateImage">
            </div>

            <!-- Eighth Row -->
            <div class="form-group">
                <label for="updateBookIds">Buku yang Dibaca:</label>
                <select name="book_ids[]" id="updateBookIds" multiple>
                    <?php foreach ($allBooks as $book): ?>
                        <option value="<?= htmlspecialchars($book['id']); ?>">
                            <?= htmlspecialchars($book['title']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Update Kelas</button>
                <button type="button" class="btn-secondary" onclick="closeEditModal()">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
// Function to format date string
function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return '';
    return date.toISOString().split('T')[0];
}

function updateMentorName() {
    const mentorId = document.getElementById('updateMentorId').value;
    
    // Cari mentor yang dipilih berdasarkan ID
    const selectedMentor = <?= json_encode($allMentors); ?>.find(mentor => mentor.id == mentorId);
    
    // Jika ditemukan, update field name_mentor
    if (selectedMentor) {
        document.getElementById('updateNameMentor').value = selectedMentor.name;
    } else {
        document.getElementById('updateNameMentor').value = '';
    }
}

document.getElementById('updateMentorId').addEventListener('change', updateMentorName);

function openEditModal(dealData) {
    const updateModal = document.getElementById('updateKelasModal');
    updateModal.style.display = 'block';

    // Populate form fields with data from dealData
    document.getElementById('updateKelasId').value = dealData.id;
    document.getElementById('updateName').value = dealData.name;
    document.getElementById('updateDescription').value = dealData.description;
    document.getElementById('updateMentorId').value = dealData.mentor_id;
    document.getElementById('updateCategory').value = dealData.category;
    document.getElementById('updateKurikulum').value = dealData.kurikulum;
    document.getElementById('updatePrice').value = dealData.price;
    document.getElementById('updateQuota').value = dealData.quota;
    document.getElementById('updateQuotaLeft').value = dealData.quota_left;
    document.getElementById('updateSchedule').value = dealData.schedule;
    document.getElementById('updateEndDate').value = formatDate(dealData.end_date);
    document.getElementById('updateLinkWa').value = dealData.link_wa;
    document.getElementById('updateStatus').value = dealData.status;

    // Populate "what will learn" fields
    document.getElementById('updateWhatWillLearn1').value = dealData.what_will_learn_1;
    document.getElementById('updateWhatWillLearn2').value = dealData.what_will_learn_2;
    document.getElementById('updateWhatWillLearn3').value = dealData.what_will_learn_3;

    // Populate session fields
    document.getElementById('updateSesion1').value = dealData.sesion_1;
    document.getElementById('updateSesion2').value = dealData.sesion_2;
    document.getElementById('updateSesion3').value = dealData.sesion_3;

    // Update mentor name field based on selected mentor
    updateMentorName();

    // Show image if available
    const imageContainer = document.getElementById('imageContainer');
    if (dealData.image) {
        // Create an image element
        const imageElement = document.createElement('img');
        imageElement.src = dealData.image;  // Assuming 'dealData.image' contains the image URL
        imageElement.alt = 'Kelas Image';
        imageElement.style.maxWidth = '100%';  // You can adjust the size as needed
        imageElement.style.marginTop = '10px';
        
        // Add image to the container
        imageContainer.innerHTML = '';  // Clear existing image if any
        imageContainer.appendChild(imageElement);
    } else {
        // If no image, clear the container
        imageContainer.innerHTML = 'No image available';
    }
}

function formatDate(dateString) {
    // Assuming dateString is in YYYY-MM-DD format
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-based
    const year = date.getFullYear();

    return `${year}-${month}-${day}`;
}

// Function to close modal
function closeEditModal() {
    const updateModal = document.getElementById('updateKelasModal');
    updateModal.style.display = 'none';
}
</script>
