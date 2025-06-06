<div class="main-content">
    <div class="header">
        <div class="search-bar">
            <input type="text" placeholder="Rechercher un tournoi" id="searchTournament">
            <button type="submit" id="searchButton"><i class="fas fa-search"></i></button>
        </div>
        <div class="profile">
            <img src="img/logo.png" alt="Profil">
        </div>
    </div>

    <div class="welcome">
        Bonjour, <?= htmlspecialchars($_SESSION['username']) ?>
    </div>

    <!-- Section des tournois -->
    <div class="tournaments-container">
        <div class="tournaments-header">
            <h2><i class="fas fa-trophy"></i> Mes tournois</h2>
            <button class="btn-add" id="createTournamentBtn">
                Créer un tournoi <i class="fas fa-plus"></i>
            </button>
        </div>

        <?php if (!empty($tournaments)): ?>
            <div class="tournaments-grid">
                <?php foreach ($tournaments as $tournament): ?>
                    <div class="tournament-card">
                        <div class="tournament-header">
                            <h3><?= htmlspecialchars($tournament['name']) ?></h3>
                            <span class="tournament-date"><?= date('d/m/Y', strtotime($tournament['start_date'])) ?></span>
                        </div>

                        <div class="tournament-info">
                            <div class="info-item">
                                <i class="fas fa-users"></i>
                                <span><?= $tournament['teams_count'] ?? 0 ?> équipes</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-layer-group"></i>
                                <span><?= $tournament['pools_count'] ?? 0 ?> poules</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-gamepad"></i>
                                <span><?= $tournament['games_count'] ?? 0 ?> matchs</span>
                            </div>
                        </div>

                        <div class="tournament-actions">
                            <a href="index.php?route=bigeye&tournament_id=<?= $tournament['Tournament_Id'] ?>" class="btn-view">
                                <i class="fas fa-eye"></i> Consulter
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal de création de tournoi -->
<div class="modal-overlay" id="createTournamentModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-trophy"></i> Créer un nouveau tournoi</h3>
            <button class="btn-close" onclick="closeCreateTournamentModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="createTournamentForm" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="form-group">
                    <label for="tournamentName">
                        <i class="fas fa-trophy"></i> Nom du tournoi *
                    </label>
                    <input type="text" id="tournamentName" name="name" required 
                           placeholder="Ex: Tournoi de Printemps 2024">
                </div>

                <div class="form-group">
                    <label for="tournamentDescription">
                        <i class="fas fa-align-left"></i> Description
                    </label>
                    <textarea id="tournamentDescription" name="description" 
                              placeholder="Description du tournoi (optionnel)" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label for="tournamentLocation">
                        <i class="fas fa-map-marker-alt"></i> Lieu *
                    </label>
                    <input type="text" id="tournamentLocation" name="location" required 
                           placeholder="Ex: Stade Municipal de Castres">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="tournamentDate">
                            <i class="fas fa-calendar"></i> Date de début *
                        </label>
                        <input type="date" id="tournamentDate" name="start_date" required>
                    </div>

                    <div class="form-group">
                        <label for="breakTime">
                            <i class="fas fa-clock"></i> Pause entre matchs (min)
                        </label>
                        <input type="number" id="breakTime" name="break_time" 
                               value="5" min="0" max="60">
                    </div>
                </div>

                <div class="form-section">
                    <h4><i class="fas fa-award"></i> Système de points</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="pointsWin">
                                <i class="fas fa-trophy"></i> Victoire
                            </label>
                            <input type="number" id="pointsWin" name="points_win" 
                                   value="3" min="0" max="10">
                        </div>
                        <div class="form-group">
                            <label for="pointsDraw">
                                <i class="fas fa-handshake"></i> Match nul
                            </label>
                            <input type="number" id="pointsDraw" name="points_draw" 
                                   value="1" min="0" max="10">
                        </div>
                        <div class="form-group">
                            <label for="pointsLoss">
                                <i class="fas fa-times"></i> Défaite
                            </label>
                            <input type="number" id="pointsLoss" name="points_loss" 
                                   value="0" min="0" max="10">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="tournamentImage">
                        <i class="fas fa-image"></i> Image du tournoi (optionnel)
                    </label>
                    <input type="file" id="tournamentImage" name="image" 
                           accept="image/*" class="file-input">
                    
                    <!-- Champ caché pour stocker l'URL de l'image -->
                    <input type="hidden" id="imageUrl" name="image_url" value="">
                    
                    <div class="file-preview" id="imagePreview" style="display: none;">
                        <img id="previewImg" src="" alt="Aperçu">
                        <button type="button" onclick="removeImage()" class="remove-image">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <!-- Indicateur d'upload -->
                    <div class="upload-status" id="uploadStatus" style="display: none;">
                        <div class="upload-progress">
                            <i class="fas fa-spinner fa-spin"></i>
                            <span id="uploadText">Upload en cours...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeCreateTournamentModal()">
                    <i class="fas fa-times"></i> Annuler
                </button>
                <button type="submit" class="btn-save">
                    <i class="fas fa-plus"></i> Créer le tournoi
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Modal styles */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 10000;
    justify-content: center;
    align-items: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.modal-overlay.show {
    display: flex;
    opacity: 1;
}

.modal-content {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    max-width: 600px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    transform: scale(0.7);
    transition: transform 0.3s ease;
}

.modal-overlay.show .modal-content {
    transform: scale(1);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px;
    border-bottom: 1px solid #e0e0e0;
    background: linear-gradient(135deg, #232c5a, #1a2147);
    color: white;
    border-radius: 15px 15px 0 0;
}

.modal-header h3 {
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 18px;
}

.btn-close {
    background: none;
    border: none;
    color: white;
    font-size: 18px;
    cursor: pointer;
    padding: 5px;
    border-radius: 50%;
    transition: background-color 0.3s ease;
}

.btn-close:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

.modal-body {
    padding: 25px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    font-size: 14px;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 12px;
    border: 2px solid #e1e5e9;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.3s ease;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #232c5a;
    box-shadow: 0 0 0 3px rgba(35, 44, 90, 0.1);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.form-section {
    background: #f8f9ff;
    padding: 20px;
    border-radius: 8px;
    margin: 20px 0;
}

.form-section h4 {
    margin: 0 0 15px 0;
    color: #232c5a;
    display: flex;
    align-items: center;
    gap: 8px;
}

.file-input {
    padding: 8px !important;
}

.file-preview {
    margin-top: 10px;
    position: relative;
    display: inline-block;
}

.file-preview img {
    max-width: 200px;
    max-height: 150px;
    border-radius: 8px;
    border: 2px solid #e0e0e0;
}

.remove-image {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 20px 25px;
    border-top: 1px solid #e0e0e0;
    background-color: #f8f9fa;
    border-radius: 0 0 15px 15px;
}

.btn-cancel, .btn-save {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-cancel {
    background-color: #6c757d;
    color: white;
}

.btn-cancel:hover {
    background-color: #5a6268;
    transform: translateY(-1px);
}

.btn-save {
    background-color: #232c5a;
    color: white;
}

.btn-save:hover {
    background-color: #1a2147;
    transform: translateY(-1px);
}

.btn-save:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* Styles pour l'indicateur d'upload */
.upload-status {
    margin-top: 10px;
    padding: 10px;
    background: #e8f4ff;
    border: 1px solid #bee5eb;
    border-radius: 5px;
    text-align: center;
}

.upload-progress {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    color: #232c5a;
    font-size: 14px;
    font-weight: 500;
}

.upload-status.success {
    background: #e8f5e8;
    border-color: #c8e6c9;
}

.upload-status.success .upload-progress {
    color: #2e7d32;
}

.upload-status.error {
    background: #fff5f5;
    border-color: #fed7d7;
}

.upload-status.error .upload-progress {
    color: #c53030;
}

@media (max-width: 768px) {
    .modal-content {
        width: 95%;
        margin: 10px;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .modal-footer {
        flex-direction: column;
    }
    
    .btn-cancel, .btn-save {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gérer les clics sur les boutons de création de tournoi
    document.getElementById('createTournamentBtn')?.addEventListener('click', openCreateTournamentModal);
    document.getElementById('createFirstTournamentBtn')?.addEventListener('click', openCreateTournamentModal);
    
    // Gérer la sélection d'image pour upload immédiat
    document.getElementById('tournamentImage')?.addEventListener('change', handleImageSelection);
    
    // Gérer la soumission du formulaire
    document.getElementById('createTournamentForm')?.addEventListener('submit', handleCreateTournament);
    
    // Définir la date par défaut à demain
    const dateInput = document.getElementById('tournamentDate');
    if (dateInput) {
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        dateInput.value = tomorrow.toISOString().split('T')[0];
    }
});

function openCreateTournamentModal() {
    document.getElementById('createTournamentModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeCreateTournamentModal() {
    document.getElementById('createTournamentModal').classList.remove('show');
    document.body.style.overflow = 'auto';
    
    // Réinitialiser le formulaire
    document.getElementById('createTournamentForm').reset();
    document.getElementById('imagePreview').style.display = 'none';
}

function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
}

function removeImage() {
    document.getElementById('tournamentImage').value = '';
    document.getElementById('imageUrl').value = '';
    document.getElementById('imagePreview').style.display = 'none';
    document.getElementById('uploadStatus').style.display = 'none';
}

async function handleImageSelection(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const uploadStatus = document.getElementById('uploadStatus');
    const uploadText = document.getElementById('uploadText');
    const imageUrlInput = document.getElementById('imageUrl');
    
    // Réinitialiser les états
    preview.style.display = 'none';
    uploadStatus.style.display = 'none';
    uploadStatus.className = 'upload-status';
    imageUrlInput.value = '';
    
    if (file) {
        // Afficher la prévisualisation immédiatement
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
        
        // Démarrer l'upload
        uploadStatus.style.display = 'block';
        uploadText.textContent = 'Upload en cours...';
        
        try {
            console.log('Début upload immédiat de l\'image:', file.name);
            const imageUrl = await uploadImageToAPI(file);
            
            // Stocker l'URL dans le champ caché
            imageUrlInput.value = imageUrl;
            
            // Afficher le succès
            uploadStatus.className = 'upload-status success';
            uploadText.innerHTML = '<i class="fas fa-check"></i> Image uploadée avec succès';
            
            console.log('Image uploadée avec succès, URL stockée:', imageUrl);
            
        } catch (error) {
            console.error('Erreur upload image:', error);
            
            // Afficher l'erreur
            uploadStatus.className = 'upload-status error';
            uploadText.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Erreur d\'upload: ' + error.message;
            
            // Masquer la prévisualisation en cas d'erreur
            preview.style.display = 'none';
        }
    }
}

async function uploadImageToAPI(imageFile) {
    const uploadFormData = new FormData();
    uploadFormData.append('image', imageFile);
    
    const response = await fetch('index.php?route=upload-image', {
        method: 'POST',
        body: uploadFormData
    });
    
    if (!response.ok) {
        const errorText = await response.text();
        let errorMessage;
        
        try {
            const errorJson = JSON.parse(errorText);
            errorMessage = errorJson.error || 'Erreur lors de l\'upload';
        } catch (e) {
            errorMessage = 'Erreur lors de l\'upload de l\'image';
        }
        
        throw new Error(errorMessage);
    }
    
    const result = await response.json();
    
    if (!result.success || !result.url) {
        throw new Error('URL de l\'image non retournée par l\'API');
    }
    
    return result.url;
}

async function handleCreateTournament(event) {
    event.preventDefault();
    
    const submitBtn = event.target.querySelector('.btn-save');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Création du tournoi...';
    
    try {
        const formData = new FormData(event.target);

        const imageUrl = formData.get('image_url');
        
        const tournamentData = {
            name: formData.get('name'),
            description: formData.get('description') || '',
            location: formData.get('location'),
            start_date: formData.get('start_date'),
            break_time: parseInt(formData.get('break_time')) || 5,
            points_win: parseInt(formData.get('points_win')) || 3,
            points_draw: parseInt(formData.get('points_draw')) || 1,
            points_loss: parseInt(formData.get('points_loss')) || 0,
            account_id: <?= $_SESSION['user_id'] ?>
        };
        if (imageUrl) {
            tournamentData.image = imageUrl;
        }
        
        console.log('Données du tournoi à envoyer:');
        console.log(tournamentData.image);
        
        const response = await fetch('index.php?route=create-tournament', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(tournamentData)
        });
        
        const result = await response.json();
        
        if (response.ok && result.success) {
            closeCreateTournamentModal();
            alert('Tournoi créé avec succès !');
            window.location.reload();
        } else {
            throw new Error(result.error || 'Erreur lors de la création du tournoi');
        }
        
    } catch (error) {
        console.error('Erreur création tournoi:', error);
        alert('Erreur lors de la création du tournoi: ' + error.message);
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
}

// Fermer le modal en cliquant à l'extérieur
document.addEventListener('click', function(e) {
    const modal = document.getElementById('createTournamentModal');
    if (e.target === modal) {
        closeCreateTournamentModal();
    }
});

// Fermer le modal avec la touche Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCreateTournamentModal();
    }
});
</script>