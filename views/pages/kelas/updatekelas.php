<!-- Modal for Editing Kelas -->
<div id="updateKelasModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h1>Edit Kelas</h1>
        <form id="updateKelasForm" action="?action=update" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" id="updateKelasId">
            
            <label for="updateName">Nama Kelas:</label>
            <input type="text" name="name" id="updateName" required>