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
                        <option value="<?= $mentor['id']; ?>"><?= $mentor['name']; ?></option>
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
                <label for="updateStartDate">Tanggal Mulai:</label>
                <input type="date" name="start_date" id="updateStartDate" required>
            </div>

            <div class="form-group">
                <label for="updateEndDate">Tanggal Selesai:</label>
                <input type="date" name="end_date" id="updateEndDate" required>
            </div>

            <div class="form-group">
                <label for="updateLinkWa">Link WhatsApp:</label>
                <input type="url" name="link_wa" id="updateLinkWa">
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
                        <option value="<?= $book['id']; ?>"><?= $book['title']; ?></option>
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
    // Update the mentor name when selecting a mentor in the update form
    document.querySelector('#updateMentorId').addEventListener('change', function() {
        const selectedMentorId = this.value;
        const mentorNameField = document.getElementById('updateNameMentor');
        
        const selectedMentor = mentors.find(mentor => mentor.id == selectedMentorId);
        mentorNameField.value = selectedMentor ? selectedMentor.name : '';
    });

    // Function to get class data - this should be implemented to fetch the actual data
    function getClassData(id) {
        // Make an AJAX request to get the class data
        // This is a placeholder - you'll need to implement the actual data fetching
        fetch(`get_class_data.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                // Populate the form fields with the retrieved data
                document.getElementById('updateKelasId').value = data.id;
                document.getElementById('updateName').value = data.name;
                document.getElementById('updateDescription').value = data.description;
                document.getElementById('updateMentorId').value = data.mentor_id;
                document.getElementById('updateNameMentor').value = data.name_mentor;
                document.getElementById('updateCategory').value = data.category;
                document.getElementById('updateKurikulum').value = data.kurikulum;
                document.getElementById('updatePrice').value = data.price;
                document.getElementById('updateQuota').value = data.quota;
                document.getElementById('updateQuotaLeft').value = data.quota_left;
                document.getElementById('updateStartDate').value = data.start_date;
                document.getElementById('updateEndDate').value = data.end_date;
                document.getElementById('updateLinkWa').value = data.link_wa;
                document.getElementById('updateStatus').value = data.status;
                document.getElementById('updateWhatWillLearn1').value = data.what_will_learn_1;
                document.getElementById('updateWhatWillLearn2').value = data.what_will_learn_2;
                document.getElementById('updateWhatWillLearn3').value = data.what_will_learn_3;

                // Set the selected books
                const bookSelect = document.getElementById('updateBookIds');
                if (data.book_ids) {
                    data.book_ids.forEach(bookId => {
                        for (let option of bookSelect.options) {
                            if (option.value == bookId) {
                                option.selected = true;
                            }
                        }
                    });
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function closeEditModal() {
        document.getElementById('updateKelasModal').style.display = 'none';
    }
</script>