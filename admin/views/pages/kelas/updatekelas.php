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
    grid-template-columns: repeat(2, 1fr);  /* Two columns */
    gap: 24px;  /* Increased gap between columns */
    padding: 16px;  /* Increased padding */
    max-width: 80%; /* Slightly wider form */
    margin: 0 auto;
    box-sizing: border-box;
    max-height: 85vh; /* Increased max-height for vertical scrolling */
    overflow-y: auto; /* Enable vertical scrolling only on the form */
}

.form-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 16px; /* Increased vertical spacing between form groups */
}

.form-group label {
    font-weight: bold;
    font-size: 15px;  /* Larger font size */
}

.form-group input, .form-group select, .form-group textarea {
    padding: 10px;  /* Increased padding */
    font-size: 15px;  /* Larger font size */
    border: 1px solid #ddd;
    border-radius: 4px;
    height: 45px;  /* Increased height of input fields */
}

.form-group textarea {
    height: 100px;  /* Increased height for textarea */
}

.form-actions {
    grid-column: span 2;  /* Button spans across all 2 columns */
    display: flex;
    justify-content: flex-end;
    gap: 16px;  /* Increased gap between buttons */
}

.btn-primary, .btn-secondary {
    padding: 10px 20px;  /* Increased padding for buttons */
    font-size: 16px;  /* Larger font size for buttons */
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
    grid-column: span 2;
    padding: 16px;  /* Increased padding */
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-top: 16px; /* Increased top margin */
}

legend {
    font-weight: bold;
    font-size: 16px;  /* Larger font size */
}

.form-group input[type="file"] {
    padding: 8px;  /* Increased padding */
    font-size: 15px;  /* Larger font size */
}

.modal-content {
    position: fixed;
    top: 0;
    left: 50%;
    transform: translateX(-50%) translateY(-30px);
    max-width: 900px;
    padding: 24px;  /* Increased padding */
    box-sizing: border-box;
    z-index: 9999;
    max-height: 90vh;  /* Allow modal content to grow */
    overflow: hidden;  /* Disable scrolling for the modal */
}

.modal-content .close {
    display: none; /* Hide the close button */
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
                <label for="updateKurikulum">Deskripsi 2:</label>
                <textarea name="kurikulum" rows="2" id="updateKurikulum" required></textarea>     
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

            <fieldset class="form-group">
                        <legend>Kurikulum</legend>
                        <textarea name="what_will_learn_1" id="updateWhatWillLearn1" placeholder="Kurikulum 1" required style="margin-bottom: 10px;"></textarea>
                        <textarea name="what_will_learn_2" id="updateWhatWillLearn2" placeholder="Kurikulum 2" required style="margin-bottom: 10px;"></textarea>
                        <textarea name="what_will_learn_3" id="updateWhatWillLearn3" placeholder="Kurikulum 3" required style="margin-bottom: 10px;"></textarea>
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
