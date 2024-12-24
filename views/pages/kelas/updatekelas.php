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

<div id="updateKelasModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h1>Edit Kelas</h1>
        <form id="updateKelasForm" action="?action=update" method="post" enctype="multipart/form-data" class="form-container">
            <input type="hidden" name="id" id="updateKelasId">
            
            <div class="form-group">
                <label for="updateName">Nama Kelas:</label>
                <input type="text" name="name" id="updateName" required>
            </div>

            <div class="form-group">
                <label for="updateDescription">Deskripsi Kelas:</label>
                <textarea name="description" id="updateDescription" rows="4" required></textarea>
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

            <div class="form-group">
                <label for="updateQuota">Kuota:</label>
                <input type="number" name="quota" id="updateQuota" required>
            </div>

            <div class="form-group">
                <label for="updateQuotaLeft">Kuota Tersisa:</label>
                <input type="number" name="quota_left" id="updateQuotaLeft" required>
            </div>

            <div class="form-group">
                <label for="updateSchedule">Tanggal Mulai:</label>
                <input type="date" name="schedule" id="updateSchedule" required>
            </div>

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
                <legend>Apa yang Akan Dipelajari:</legend>
                <input type="text" name="what_will_learn_1" id="updateWhatWillLearn1" placeholder="Topik 1" required>
                <input type="text" name="what_will_learn_2" id="updateWhatWillLearn2" placeholder="Topik 2" required>
                <input type="text" name="what_will_learn_3" id="updateWhatWillLearn3" placeholder="Topik 3" required>
            </fieldset>

            <div class="form-group">
                <label for="updateImage">Gambar Kelas (Optional):</label>
                <input type="file" name="image" id="updateImage">
            </div>

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

// Function to open edit modal and populate data
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
    document.getElementById('updateSchedule').value = formatDate(dealData.schedule);
    document.getElementById('updateEndDate').value = formatDate(dealData.end_date);
    document.getElementById('updateLinkWa').value = dealData.link_wa;
    document.getElementById('updateStatus').value = dealData.status;
    
    // Update session fields
    document.getElementById('updateSesion1').value = dealData.sesion_1;
    document.getElementById('updateSesion2').value = dealData.sesion_2;
    document.getElementById('updateSesion3').value = dealData.sesion_3;
    
    // Update what will learn fields
    document.getElementById('updateWhatWillLearn1').value = dealData.what_will_learn_1;
    document.getElementById('updateWhatWillLearn2').value = dealData.what_will_learn_2;
    document.getElementById('updateWhatWillLearn3').value = dealData.what_will_learn_3;

    // Handle book selections
    const bookSelect = document.getElementById('updateBookIds');
    if (dealData.book_ids && Array.isArray(dealData.book_ids)) {
        Array.from(bookSelect.options).forEach(option => {
            option.selected = dealData.book_ids.includes(parseInt(option.value));
        });
    }
}

// Function to close edit modal
function closeEditModal() {
    const updateModal = document.getElementById('updateKelasModal');
    updateModal.style.display = 'none';
}

// Update mentor name when mentor is selected
document.getElementById('updateMentorId').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    document.getElementById('updateNameMentor').value = selectedOption.text;
});

// Close modal when clicking outside
window.onclick = function(event) {
    const updateModal = document.getElementById('updateKelasModal');
    if (event.target == updateModal) {
        updateModal.style.display = 'none';
    }
}
</script>