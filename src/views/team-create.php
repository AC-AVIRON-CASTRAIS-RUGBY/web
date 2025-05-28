<div class="main-content">
    <div class="header">
        <div class="search-bar">
            <a href="index.php?route=teams&tournament_id=<?= $_GET['tournament_id'] ?>" class="btn-back">
                <i class="fas fa-arrow-left"></i> Retour aux équipes
            </a>
        </div>
        <div class="profile">
            <img src="img/logo.png" alt="Profil">
        </div>
    </div>

    <div class="welcome">
        <i class="fas fa-plus-circle"></i> Créer une nouvelle équipe
    </div>

    <div class="create-team-container">
        <div class="form-header">
            <h2><i class="fas fa-users"></i> Nouvelle équipe</h2>
            <p>Ajoutez une nouvelle équipe au tournoi</p>
            
            <!-- Switch pour changer de mode -->
            <div class="mode-switch">
                <div class="switch-container">
                    <label class="switch-label">
                        <input type="checkbox" id="bulkModeSwitch" class="switch-input">
                        <span class="switch-slider"></span>
                    </label>
                    <div class="switch-text">
                        <span class="mode-text active" data-mode="single">
                            <i class="fas fa-user-plus"></i> Ajout simple
                        </span>
                        <span class="mode-text" data-mode="bulk">
                            <i class="fas fa-users-cog"></i> Ajout en lot
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Mode ajout simple -->
        <div id="singleMode" class="creation-mode active">
            <form class="create-team-form" action="index.php?route=team-create&tournament_id=<?= $_GET['tournament_id'] ?>" method="post" enctype="multipart/form-data" id="createTeamForm">
                <div class="form-sections">
                    <!-- Section informations de base -->
                    <div class="form-section">
                        <h3><i class="fas fa-info-circle"></i> Informations générales</h3>
                        
                        <div class="form-group">
                            <label for="team_name">
                                <i class="fas fa-users"></i> Nom de l'équipe *
                            </label>
                            <input type="text" id="team_name" name="team_name" required placeholder="Entrez le nom de l'équipe" maxlength="100">
                            <small class="form-hint">Le nom de l'équipe doit être unique dans le tournoi</small>
                        </div>

                        <div class="form-group">
                            <label for="age_category">
                                <i class="fas fa-child"></i> Catégorie d'âge
                            </label>
                            <select id="age_category" name="age_category">
                                <option value="">Sélectionner une catégorie</option>
                                <option value="u6">U6 - Moins de 6 ans</option>
                                <option value="u8">U8 - Moins de 8 ans</option>
                                <option value="u10">U10 - Moins de 10 ans</option>
                                <option value="u12">U12 - Moins de 12 ans</option>
                                <option value="u14">U14 - Moins de 14 ans</option>
                            </select>
                            <small class="form-hint">La catégorie d'âge aidera à organiser les matchs</small>
                        </div>
                    </div>

                    <!-- Section logo de l'équipe -->
                    <div class="form-section">
                        <h3><i class="fas fa-image"></i> Logo de l'équipe</h3>
                        
                        <div class="form-group">
                            <label for="team_logo">
                                <i class="fas fa-upload"></i> Logo de l'équipe (optionnel)
                            </label>
                            <div class="file-upload-container">
                                <input type="file" id="team_logo" name="team_logo" accept="image/*" class="file-input">
                                <div class="file-upload-area" id="uploadArea">
                                    <div class="upload-icon">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <div class="upload-text">
                                        <p>Cliquez pour sélectionner une image</p>
                                        <small>ou glissez-déposez un fichier ici</small>
                                    </div>
                                    <div class="upload-hint">
                                        Formats acceptés: JPG, PNG, GIF (max 5MB)
                                    </div>
                                </div>
                                
                                <div class="file-preview" id="filePreview" style="display: none;">
                                    <div class="preview-image-container">
                                        <img id="previewImage" alt="Aperçu" class="preview-image">
                                        <button type="button" class="remove-image-btn" onclick="removeImage()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="file-info">
                                        <span class="file-name" id="fileName"></span>
                                        <span class="file-size" id="fileSize"></span>
                                    </div>
                                </div>
                            </div>
                            <small class="form-hint">Le logo sera affiché dans les classements et calendriers</small>
                        </div>
                    </div>
                </div>

                <!-- Actions du formulaire -->
                <div class="form-actions">
                    <a href="index.php?route=teams&tournament_id=<?= $_GET['tournament_id'] ?>" class="btn-cancel">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="submit" class="btn-submit" id="submitBtn">
                        <i class="fas fa-save"></i> Créer l'équipe
                    </button>
                </div>
            </form>
        </div>

        <!-- Mode ajout en lot -->
        <div id="bulkMode" class="creation-mode">
            <div class="bulk-creation-container">
                <div class="bulk-header">
                    <h3><i class="fas fa-table"></i> Ajout en lot d'équipes</h3>
                    <p>Ajoutez plusieurs équipes rapidement dans un tableau</p>
                </div>

                <div class="bulk-controls">
                    <div class="bulk-actions">
                        <button type="button" class="btn-add-row" onclick="addTeamRow()">
                            <i class="fas fa-plus"></i> Ajouter une ligne
                        </button>
                        <button type="button" class="btn-remove-row" onclick="removeLastRow()">
                            <i class="fas fa-minus"></i> Supprimer la dernière ligne
                        </button>
                        <button type="button" class="btn-clear-all" onclick="clearAllRows()">
                            <i class="fas fa-trash"></i> Vider le tableau
                        </button>
                    </div>
                    
                    <div class="bulk-info">
                        <span class="teams-count">0 équipe(s) à créer</span>
                    </div>
                </div>

                <div class="bulk-table-container">
                    <table class="bulk-teams-table" id="bulkTeamsTable">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="40%">
                                    <i class="fas fa-users"></i> Nom de l'équipe *
                                </th>
                                <th width="25%">
                                    <i class="fas fa-child"></i> Catégorie d'âge
                                </th>
                                <th width="25%">
                                    <i class="fas fa-image"></i> Logo
                                </th>
                                <th width="5%">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="bulkTableBody">
                            <!-- Les lignes seront ajoutées dynamiquement -->
                        </tbody>
                    </table>
                </div>

                <div class="bulk-actions-footer">
                    <a href="index.php?route=teams&tournament_id=<?= $_GET['tournament_id'] ?>" class="btn-cancel">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="button" class="btn-submit" id="bulkSubmitBtn" onclick="submitBulkTeams()">
                        <i class="fas fa-save"></i> Créer toutes les équipes
                    </button>
                </div>

                <!-- Zone de résultats -->
                <div class="bulk-results" id="bulkResults" style="display: none;">
                    <h4><i class="fas fa-check-circle"></i> Résultats de la création</h4>
                    <div class="results-list" id="resultsList">
                        <!-- Les résultats seront affichés ici -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation -->
<div class="modal-overlay" id="confirmModal">
    <div class="modal-content">
        <div class="modal-header success">
            <h3><i class="fas fa-check-circle"></i> Équipe créée avec succès</h3>
        </div>
        <div class="modal-body">
            <p>L'équipe a été créée et ajoutée au tournoi.</p>
            <p>Vous pouvez maintenant l'assigner à une poule et planifier ses matchs.</p>
        </div>
        <div class="modal-footer">
            <a href="index.php?route=teams&tournament_id=<?= $_GET['tournament_id'] ?>" class="btn-success">
                <i class="fas fa-arrow-left"></i> Retour aux équipes
            </a>
        </div>
    </div>
</div>

<style>
.btn-back {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 15px;
    background-color: #f8f9fa;
    color: #333;
    text-decoration: none;
    border-radius: 5px;
    font-size: 14px;
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.btn-back:hover {
    background-color: #232c5a;
    color: white;
}

.create-team-container {
    max-width: 800px;
    margin: 0 auto;
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.form-header {
    text-align: center;
    margin-bottom: 30px;
    border-bottom: 3px solid #232c5a;
    padding-bottom: 20px;
}

.form-header h2 {
    color: #232c5a;
    font-size: 28px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.form-header p {
    color: #666;
    font-size: 16px;
}

.alert {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
}

.alert-error {
    background-color: #fed7d7;
    color: #c53030;
    border: 1px solid #feb2b2;
}

.form-sections {
    display: flex;
    flex-direction: column;
    gap: 30px;
    margin-bottom: 30px;
}

.form-section {
    background: #f8f9ff;
    border: 2px solid #e8ecff;
    border-radius: 12px;
    padding: 25px;
}

.form-section h3 {
    color: #232c5a;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 18px;
    border-bottom: 1px solid #e8ecff;
    padding-bottom: 10px;
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

.form-group input[type="text"],
.form-group input[type="tel"],
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e1e5e9;
    border-radius: 8px;
    font-size: 16px;
    transition: border-color 0.3s ease;
    background-color: white;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #232c5a;
    box-shadow: 0 0 0 3px rgba(35, 44, 90, 0.1);
}

.form-group textarea {
    resize: vertical;
    min-height: 80px;
}

.form-hint {
    display: block;
    color: #666;
    font-size: 12px;
    margin-top: 5px;
    font-style: italic;
}

/* Upload d'image */
.file-upload-container {
    position: relative;
}

.file-input {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
    z-index: 2;
}

.file-upload-area {
    border: 2px dashed #e1e5e9;
    border-radius: 12px;
    padding: 30px;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    background-color: #fafbff;
}

.file-upload-area:hover {
    border-color: #232c5a;
    background-color: #f0f4ff;
}

.file-upload-area.dragover {
    border-color: #232c5a;
    background-color: #e8f4ff;
    transform: scale(1.02);
}

.upload-icon {
    font-size: 48px;
    color: #232c5a;
    margin-bottom: 15px;
}

.upload-text p {
    font-size: 16px;
    color: #333;
    margin-bottom: 5px;
    font-weight: 500;
}

.upload-text small {
    color: #666;
    font-size: 14px;
}

.upload-hint {
    margin-top: 10px;
    color: #666;
    font-size: 12px;
}

.file-preview {
    background: #f0f8ff;
    border: 2px solid #d0e8ff;
    border-radius: 12px;
    padding: 20px;
    margin-top: 15px;
}

.preview-image-container {
    position: relative;
    display: inline-block;
    margin-bottom: 15px;
}

.preview-image {
    max-width: 200px;
    max-height: 200px;
    border-radius: 8px;
    border: 2px solid #e0e0e0;
    object-fit: cover;
}

.remove-image-btn {
    position: absolute;
    top: -8px;
    right: -8px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background-color: #e53e3e;
    color: white;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    transition: all 0.3s ease;
}

.remove-image-btn:hover {
    background-color: #c53030;
    transform: scale(1.1);
}

.file-info {
    display: flex;
    gap: 15px;
    color: #333;
    font-size: 14px;
}

.file-name {
    font-weight: 600;
}

.file-size {
    color: #666;
}

/* Actions du formulaire */
.form-actions {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    padding-top: 20px;
    border-top: 2px solid #f0f0f0;
}

.btn-cancel {
    padding: 12px 24px;
    background-color: #6c757d;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-cancel:hover {
    background-color: #5a6268;
    transform: translateY(-2px);
}

.btn-submit {
    padding: 12px 24px;
    background-color: #232c5a;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-submit:hover {
    background-color: #1a2147;
    transform: translateY(-2px);
}

.btn-submit:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

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
    max-width: 500px;
    width: 90%;
    transform: scale(0.7);
    transition: transform 0.3s ease;
}

.modal-overlay.show .modal-content {
    transform: scale(1);
}

.modal-header {
    padding: 20px 25px;
    border-bottom: 1px solid #e0e0e0;
    border-radius: 15px 15px 0 0;
}

.modal-header.success {
    background: linear-gradient(135deg, #38a169, #2f855a);
    color: white;
}

.modal-header h3 {
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 18px;
}

.modal-body {
    padding: 25px;
}

.modal-body p {
    margin-bottom: 15px;
    color: #333;
    line-height: 1.5;
}

.modal-footer {
    padding: 20px 25px;
    border-top: 1px solid #e0e0e0;
    background-color: #f8f9fa;
    border-radius: 0 0 15px 15px;
    text-align: center;
}

.btn-success {
    padding: 10px 20px;
    background-color: #38a169;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-success:hover {
    background-color: #2f855a;
    transform: translateY(-1px);
}

/* === MODE SWITCH === */
.mode-switch {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 2px solid #e8ecff;
}

.switch-container {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
}

.switch-label {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 30px;
}

.switch-input {
    opacity: 0;
    width: 0;
    height: 0;
}

.switch-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #232c5a;
    transition: .4s;
    border-radius: 30px;
}

.switch-slider:before {
    position: absolute;
    content: "";
    height: 22px;
    width: 22px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

.switch-input:checked + .switch-slider {
    background-color: #2e7d32;
}

.switch-input:checked + .switch-slider:before {
    transform: translateX(30px);
}

.switch-text {
    display: flex;
    gap: 30px;
    align-items: center;
}

.mode-text {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 15px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
    border: 2px solid transparent;
}

.mode-text.active {
    background-color: #232c5a;
    color: white;
    border-color: #232c5a;
}

.mode-text:not(.active) {
    background-color: #f8f9ff;
    color: #666;
    border-color: #e8ecff;
}

.mode-text:hover:not(.active) {
    background-color: #e8ecff;
    color: #232c5a;
}

/* === CREATION MODES === */
.creation-mode {
    display: none;
    animation: fadeIn 0.3s ease-out;
}

.creation-mode.active {
    display: block;
}

/* === BULK MODE STYLES === */
.bulk-creation-container {
    background: #f8f9ff;
    border: 2px solid #e8ecff;
    border-radius: 12px;
    padding: 25px;
}

.bulk-header {
    text-align: center;
    margin-bottom: 25px;
}

.bulk-header h3 {
    color: #232c5a;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    font-size: 20px;
}

.bulk-header p {
    color: #666;
    font-size: 14px;
}

.bulk-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding: 15px;
    background: white;
    border-radius: 8px;
    border: 1px solid #e8ecff;
}

.bulk-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.btn-add-row, .btn-remove-row, .btn-clear-all {
    padding: 8px 12px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.3s ease;
}

.btn-add-row {
    background-color: #2e7d32;
    color: white;
}

.btn-add-row:hover {
    background-color: #1b5e20;
    transform: translateY(-1px);
}

.btn-remove-row {
    background-color: #f57c00;
    color: white;
}

.btn-remove-row:hover {
    background-color: #ef6c00;
    transform: translateY(-1px);
}

.btn-clear-all {
    background-color: #e53e3e;
    color: white;
}

.btn-clear-all:hover {
    background-color: #c53030;
    transform: translateY(-1px);
}

.bulk-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.teams-count {
    font-weight: 600;
    color: #232c5a;
    background: #e8ecff;
    padding: 8px 12px;
    border-radius: 15px;
    font-size: 14px;
}

.bulk-table-container {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #e8ecff;
    margin-bottom: 25px;
    max-height: 500px;
    overflow-y: auto;
}

.bulk-teams-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.bulk-teams-table th {
    background: linear-gradient(135deg, #232c5a, #1a2147);
    color: white;
    padding: 15px 10px;
    text-align: left;
    font-weight: 600;
    position: sticky;
    top: 0;
    z-index: 10;
}

.bulk-teams-table th i {
    margin-right: 5px;
}

.bulk-teams-table td {
    padding: 12px 10px;
    border-bottom: 1px solid #f0f0f0;
    vertical-align: middle;
}

.bulk-teams-table tr:hover {
    background-color: #f8f9ff;
}

.bulk-teams-table tr:last-child td {
    border-bottom: none;
}

/* Inputs dans le tableau */
.bulk-table-input {
    width: 100%;
    padding: 8px 10px;
    border: 1px solid #e1e5e9;
    border-radius: 4px;
    font-size: 14px;
    transition: border-color 0.3s ease;
}

.bulk-table-input:focus {
    outline: none;
    border-color: #232c5a;
    box-shadow: 0 0 0 2px rgba(35, 44, 90, 0.1);
}

.bulk-table-input.required {
    border-color: #e53e3e;
}

.bulk-table-select {
    width: 100%;
    padding: 8px 10px;
    border: 1px solid #e1e5e9;
    border-radius: 4px;
    font-size: 14px;
    background-color: white;
}

.bulk-file-input {
    width: 100%;
    padding: 6px;
    border: 1px solid #e1e5e9;
    border-radius: 4px;
    font-size: 12px;
    background-color: white;
}

.row-number {
    font-weight: 600;
    color: #232c5a;
    text-align: center;
}

.btn-remove-team {
    width: 28px;
    height: 28px;
    border: none;
    border-radius: 50%;
    background-color: #e53e3e;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    transition: all 0.3s ease;
}

.btn-remove-team:hover {
    background-color: #c53030;
    transform: scale(1.1);
}

.bulk-actions-footer {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    padding-top: 20px;
    border-top: 2px solid #e8ecff;
}

/* === BULK RESULTS === */
.bulk-results {
    margin-top: 25px;
    padding: 20px;
    background: white;
    border-radius: 8px;
    border: 1px solid #e8ecff;
}

.bulk-results h4 {
    color: #2e7d32;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.results-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.result-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 15px;
    border-radius: 6px;
    font-size: 14px;
}

.result-item.success {
    background-color: #e8f5e8;
    color: #2e7d32;
    border: 1px solid #c8e6c9;
}

.result-item.error {
    background-color: #ffebee;
    color: #c62828;
    border: 1px solid #ffcdd2;
}

.result-status {
    display: flex;
    align-items: center;
    gap: 5px;
    font-weight: 600;
}

/* === RESPONSIVE === */
@media (max-width: 1024px) {
    .bulk-table-container {
        overflow-x: auto;
    }
    
    .bulk-teams-table {
        min-width: 800px;
    }
}

@media (max-width: 768px) {
    .switch-text {
        flex-direction: column;
        gap: 10px;
    }
    
    .bulk-controls {
        flex-direction: column;
        gap: 15px;
    }
    
    .bulk-actions {
        justify-content: center;
    }
    
    .bulk-actions-footer {
        flex-direction: column;
    }
    
    .btn-cancel,
    .btn-submit {
        width: 100%;
        justify-content: center;
    }
    
    .bulk-teams-table th,
    .bulk-teams-table td {
        padding: 8px 6px;
        font-size: 12px;
    }
}

@media (max-width: 480px) {
    .mode-text {
        padding: 8px 12px;
        font-size: 14px;
    }
    
    .bulk-creation-container {
        padding: 15px;
    }
    
    .bulk-teams-table {
        min-width: 600px;
    }
    
    .bulk-teams-table th,
    .bulk-teams-table td {
        padding: 6px 4px;
        font-size: 11px;
    }
    
    .bulk-table-input,
    .bulk-table-select {
        padding: 6px 8px;
        font-size: 12px;
    }
}

/* === ANIMATIONS === */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.bulk-teams-table tr {
    animation: slideIn 0.3s ease-out;
}

.result-item {
    animation: slideIn 0.3s ease-out;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('team_logo');
    const uploadArea = document.getElementById('uploadArea');
    const filePreview = document.getElementById('filePreview');
    const previewImage = document.getElementById('previewImage');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('createTeamForm');

    let selectedFile = null;

    // Gestion du drag & drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        uploadArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        uploadArea.classList.add('dragover');
    }

    function unhighlight(e) {
        uploadArea.classList.remove('dragover');
    }

    uploadArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            handleFileSelection(files[0]);
        }
    }

    // Gestion de la sélection de fichier
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            handleFileSelection(file);
        }
    });

    function handleFileSelection(file) {
        // Vérifier le type de fichier
        if (!file.type.startsWith('image/')) {
            alert('Veuillez sélectionner un fichier image');
            return;
        }

        // Vérifier la taille (5MB max)
        if (file.size > 5 * 1024 * 1024) {
            alert('Le fichier est trop volumineux (max 5MB)');
            return;
        }

        selectedFile = file;
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        
        // Afficher la prévisualisation
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            uploadArea.style.display = 'none';
            filePreview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }

    // Fonction pour formater la taille du fichier
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Gestion de la soumission du formulaire
    form.addEventListener('submit', function(e) {
        const teamName = document.getElementById('team_name').value.trim();
        
        if (!teamName) {
            e.preventDefault();
            alert('Veuillez saisir un nom d\'équipe');
            return;
        }

        // Désactiver le bouton pendant la soumission
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Création en cours...';
    });

    // Validation en temps réel
    document.getElementById('team_name').addEventListener('input', function() {
        const value = this.value.trim();
        const group = this.parentElement;
        
        if (value.length === 0) {
            group.classList.remove('valid', 'invalid');
        } else if (value.length >= 2) {
            group.classList.remove('invalid');
            group.classList.add('valid');
        } else {
            group.classList.remove('valid');
            group.classList.add('invalid');
        }
    });

    // Gestion du switch de mode
    const bulkModeSwitch = document.getElementById('bulkModeSwitch');
    const singleMode = document.getElementById('singleMode');
    const bulkMode = document.getElementById('bulkMode');
    const modeTexts = document.querySelectorAll('.mode-text');

    bulkModeSwitch.addEventListener('change', function() {
        toggleMode(this.checked);
    });

    // Permettre le clic sur les textes pour changer de mode
    modeTexts.forEach(text => {
        text.addEventListener('click', function() {
            const mode = this.dataset.mode;
            const isBulk = mode === 'bulk';
            bulkModeSwitch.checked = isBulk;
            toggleMode(isBulk);
        });
    });

    function toggleMode(isBulkMode) {
        if (isBulkMode) {
            singleMode.classList.remove('active');
            bulkMode.classList.add('active');
            modeTexts[0].classList.remove('active');
            modeTexts[1].classList.add('active');
            initializeBulkTable();
        } else {
            bulkMode.classList.remove('active');
            singleMode.classList.add('active');
            modeTexts[1].classList.remove('active');
            modeTexts[0].classList.add('active');
        }
    }

    // Initialiser le tableau en mode bulk
    function initializeBulkTable() {
        const tbody = document.getElementById('bulkTableBody');
        if (tbody.children.length === 0) {
            // Ajouter 3 lignes par défaut
            for (let i = 0; i < 3; i++) {
                addTeamRow();
            }
        }
    }
});

// Fonction globale pour supprimer l'image
function removeImage() {
    document.getElementById('team_logo').value = '';
    document.getElementById('filePreview').style.display = 'none';
    document.getElementById('uploadArea').style.display = 'block';
    selectedFile = null;
}

// Fermer le modal en cliquant à l'extérieur
document.addEventListener('click', function(e) {
    const modal = document.getElementById('confirmModal');
    if (e.target === modal) {
        modal.classList.remove('show');
    }
});

// Fonctions pour le mode bulk
let teamRowCounter = 0;

function addTeamRow() {
    teamRowCounter++;
    const tbody = document.getElementById('bulkTableBody');
    
    const row = document.createElement('tr');
    row.innerHTML = `
        <td class="row-number">${teamRowCounter}</td>
        <td>
            <input type="text" class="bulk-table-input team-name-input" 
                   placeholder="Nom de l'équipe" maxlength="100" required>
        </td>
        <td>
            <select class="bulk-table-select age-category-select">
                <option value="">Sélectionner</option>
                <option value="u6">U6</option>
                <option value="u8">U8</option>
                <option value="u10">U10</option>
                <option value="u12">U12</option>
                <option value="u14">U14</option>
            </select>
        </td>
        <td>
            <input type="file" class="bulk-file-input team-logo-input" 
                   accept="image/*">
        </td>
        <td>
            <button type="button" class="btn-remove-team" onclick="removeTeamRow(this)">
                <i class="fas fa-times"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
    updateTeamsCount();
    
    // Ajouter la validation en temps réel
    const nameInput = row.querySelector('.team-name-input');
    nameInput.addEventListener('input', function() {
        validateTeamName(this);
        updateTeamsCount();
    });
}

function removeTeamRow(button) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette ligne ?')) {
        const row = button.closest('tr');
        row.remove();
        updateRowNumbers();
        updateTeamsCount();
    }
}

function removeLastRow() {
    const tbody = document.getElementById('bulkTableBody');
    if (tbody.children.length > 0) {
        tbody.removeChild(tbody.lastElementChild);
        updateRowNumbers();
        updateTeamsCount();
    }
}

function clearAllRows() {
    if (confirm('Êtes-vous sûr de vouloir vider tout le tableau ?')) {
        const tbody = document.getElementById('bulkTableBody');
        tbody.innerHTML = '';
        teamRowCounter = 0;
        updateTeamsCount();
    }
}

function updateRowNumbers() {
    const rows = document.querySelectorAll('#bulkTableBody tr');
    rows.forEach((row, index) => {
        const numberCell = row.querySelector('.row-number');
        numberCell.textContent = index + 1;
    });
    teamRowCounter = rows.length;
}

function updateTeamsCount() {
    const validTeams = getValidTeams();
    const countElement = document.querySelector('.teams-count');
    countElement.textContent = `${validTeams.length} équipe(s) à créer`;
    
    const submitBtn = document.getElementById('bulkSubmitBtn');
    submitBtn.disabled = validTeams.length === 0;
}

function getValidTeams() {
    const rows = document.querySelectorAll('#bulkTableBody tr');
    const validTeams = [];
    
    rows.forEach(row => {
        const nameInput = row.querySelector('.team-name-input');
        const categorySelect = row.querySelector('.age-category-select');
        const logoInput = row.querySelector('.team-logo-input');
        
        if (nameInput.value.trim()) {
            validTeams.push({
                name: nameInput.value.trim(),
                age_category: categorySelect.value,
                logo: logoInput.files[0] || null
            });
        }
    });
    
    return validTeams;
}

function validateTeamName(input) {
    const value = input.value.trim();
    if (value.length === 0) {
        input.classList.remove('required');
        return true;
    } else if (value.length >= 2) {
        input.classList.remove('required');
        return true;
    } else {
        input.classList.add('required');
        return false;
    }
}

async function submitBulkTeams() {
    const validTeams = getValidTeams();
    
    if (validTeams.length === 0) {
        alert('Veuillez ajouter au moins une équipe avec un nom valide.');
        return;
    }
    
    // Vérifier les doublons
    const names = validTeams.map(team => team.name.toLowerCase());
    const duplicates = names.filter((name, index) => names.indexOf(name) !== index);
    
    if (duplicates.length > 0) {
        alert('Attention : Il y a des noms d\'équipe en doublon dans votre liste.');
        return;
    }
    
    const submitBtn = document.getElementById('bulkSubmitBtn');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Création en cours...';
    
    const results = [];
    const tournamentId = '<?= $_GET['tournament_id'] ?>';
    
    try {
        for (let i = 0; i < validTeams.length; i++) {
            const team = validTeams[i];
            
            try {
                // Préparer les données du formulaire
                const formData = new FormData();
                formData.append('team_name', team.name);
                formData.append('age_category', team.age_category);
                
                if (team.logo) {
                    formData.append('team_logo', team.logo);
                }
                
                // Envoyer la requête
                const response = await fetch(`index.php?route=team-create&tournament_id=${tournamentId}`, {
                    method: 'POST',
                    body: formData
                });
                
                if (response.ok) {
                    results.push({
                        team: team.name,
                        success: true,
                        message: 'Équipe créée avec succès'
                    });
                } else {
                    results.push({
                        team: team.name,
                        success: false,
                        message: 'Erreur lors de la création'
                    });
                }
            } catch (error) {
                results.push({
                    team: team.name,
                    success: false,
                    message: 'Erreur de connexion'
                });
            }
            
            // Petite pause entre les requêtes
            await new Promise(resolve => setTimeout(resolve, 200));
        }
        
        // Afficher les résultats
        displayBulkResults(results);
        
        // Si tout s'est bien passé, proposer de rediriger
        const successCount = results.filter(r => r.success).length;
        if (successCount === validTeams.length) {
            setTimeout(() => {
                if (confirm(`Toutes les équipes ont été créées avec succès ! Voulez-vous retourner à la liste des équipes ?`)) {
                    window.location.href = `index.php?route=teams&tournament_id=${tournamentId}`;
                }
            }, 2000);
        }
        
    } catch (error) {
        console.error('Erreur lors de la création en lot:', error);
        alert('Erreur lors de la création des équipes. Veuillez réessayer.');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
}

function displayBulkResults(results) {
    const resultsContainer = document.getElementById('bulkResults');
    const resultsList = document.getElementById('resultsList');
    
    resultsList.innerHTML = '';
    
    results.forEach(result => {
        const item = document.createElement('div');
        item.className = `result-item ${result.success ? 'success' : 'error'}`;
        
        item.innerHTML = `
            <span class="team-name">${result.team}</span>
            <span class="result-status">
                <i class="fas fa-${result.success ? 'check-circle' : 'exclamation-triangle'}"></i>
                ${result.message}
            </span>
        `;
        
        resultsList.appendChild(item);
    });
    
    resultsContainer.style.display = 'block';
    resultsContainer.scrollIntoView({ behavior: 'smooth' });
}
</script>
