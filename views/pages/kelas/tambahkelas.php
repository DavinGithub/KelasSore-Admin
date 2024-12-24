<div id="addKelasModal" class="modal">
    <div class="modal-content">
        <h1>Tambah Kelas</h1>
        <form action="?action=create" method="post" enctype="multipart/form-data" class="form-container">
            <div class="form-group">
                <label for="name">Nama Kelas:</label>
                <input type="text" name="name" required>
            </div>

            <div class="form-group">
                <label for="description">Deskripsi Kelas:</label>
                <textarea name="description" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="mentor_id">Pilih Mentor:</label>
                <select name="mentor_id" required>
                    <option value="">Pilih Mentor</option>
                    <?php 
                    // Mengambil semua mentor dari controller
                    $mentorController = new KelasController();
                    $allMentors = $mentorController->getAllMentors();
                    foreach ($allMentors as $mentor): ?>
                        <option value="<?= $mentor['id']; ?>"><?= $mentor['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Nama Mentor (auto-filled from dropdown) -->
            <div class="form-group">
                <label for="name_mentor">Nama Mentor:</label>
                <input type="text" name="name_mentor" id="name_mentor" readonly>
            </div>

            <div class="form-group">
                <label for="category">Kategori:</label>
                <select name="category" required>
                    <option value="Private">Private</option>
                    <option value="Reguler">Reguler</option>
                </select>
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
                <label for="schedule">Tanggal Mulai:</label>
                <input type="date" name="schedule" required>
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
                <label for="sesion_1">Sesion 1</label>
                <input type="url" name="sesion_1" required> 
            </div>
            <div class="form-group">
                <label for="sesion_2">Sesion 2</label>
                <input type="url" name="sesion_2" required> 
            </div>
            <div class="form-group">
                <label for="sesion_3">Sesion 3</label>
                <input type="url" name="sesion_3" required> 
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <select name="status" required>
                    <option value="buka">Buka</option>
                    <option value="tutup">Tutup</option>
                </select>
            </div>

            <fieldset class="form-group">
                <legend>Apa yang Akan Dipelajari:</legend>
                <input type="text" name="what_will_learn_1" placeholder="Topik 1" required>
                <input type="text" name="what_will_learn_2" placeholder="Topik 2" required>
                <input type="text" name="what_will_learn_3" placeholder="Topik 3" required>
            </fieldset>

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

            <div class="form-actions">
                <button type="submit" class="btn-primary">Tambah Kelas</button>
                <button type="button" class="btn-secondary" onclick="closeModal()">Batal</button>
            </div>
        </form>
    </div>
</div>
<style>
    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;  /* Keep the modal fixed in place */
        z-index: 1000;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);  /* Center the modal */
        width: 100%;
        max-width: 600px;  /* Limit the modal's width */
        height: auto;
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        width: 90%;
        max-height: 90vh; /* Limit the height of the modal */
        overflow-y: auto; /* Allow scrolling if content overflows */
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            transform: translateY(-20%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .form-container {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }

    input, textarea, select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 1rem;
    }

    button {
        padding: 10px 15px;
        font-size: 1rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .btn-primary {
        background-color: #007BFF;
        color: white;
    }

    .btn-secondary {
        background-color: #6C757D;
        color: white;
    }

    button:hover {
        opacity: 0.9;
    }
</style>

<script>
    // Array of mentors available from PHP
    const mentors = <?php echo json_encode($allMentors); ?>;

    document.querySelector('[name="mentor_id"]').addEventListener('change', function () {
        const selectedMentorId = this.value;
        const mentorNameField = document.getElementById('name_mentor');
        
        // Find the selected mentor by id and set the mentor name
        const selectedMentor = mentors.find(mentor => mentor.id == selectedMentorId);
        mentorNameField.value = selectedMentor ? selectedMentor.name : '';
    });

    function closeModal() {
        document.getElementById('addKelasModal').style.display = 'none';
    }
</script>