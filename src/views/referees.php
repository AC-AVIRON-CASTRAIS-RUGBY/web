<div class="main-content">
    <div class="header">
        <div class="search-bar">
            <input type="text" placeholder="Rechercher un arbitre" id="searchInput">
            <button type="submit" id="searchButton"><i class="fas fa-search"></i></button>
        </div>
        <div class="profile">
            <img src="img/logo.png" alt="Profil">
        </div>
    </div>

    <div class="welcome">
        Gestion des arbitres
    </div>

    <div class="referees-container" data-tournament-id="<?= $_GET['tournament_id'] ?>">
        <div class="referees-header">
            <div class="referees-title">
                <i class="fas fa-user-tie"></i>
                <span>Arbitres du tournoi</span>
                <span class="referee-count">(<?= count($referees) ?> arbitre<?= count($referees) > 1 ? 's' : '' ?>)</span>
            </div>
            <button class="btn-add" onclick="openRefereeModal()">
                <i class="fas fa-plus"></i> Ajouter un arbitre
            </button>
        </div>

        <div class="referees-table-container">
            <?php if (!empty($referees)): ?>
                <table class="referees-table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Identifiant</th>
                            <th>Mot de passe</th>
                            <th>Matchs</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php

                    $uniqueReferees = [];
                    foreach ($referees as $r) {
                        $uniqueReferees[$r['Referee_Id']] = $r;
                    }
                    $referees = array_values($uniqueReferees);

                    ?>
                        <?php foreach ($referees as $referee): ?>
                            <tr data-referee-id="<?= $referee['Referee_Id'] ?>">
                                <td class="referee-name"><?= htmlspecialchars($referee['last_name']) ?></td>
                                <td class="referee-firstname"><?= htmlspecialchars($referee['first_name']) ?></td>
                                <td class="referee-login">
                                    <span class="login-code"><?= htmlspecialchars($referee['loginUUID'] ?? 'N/A') ?></span>
                                    <button class="copy-btn" onclick="copyToClipboard('<?= htmlspecialchars($referee['loginUUID'] ?? '') ?>', this)" title="Copier l'identifiant">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </td>
                                <td class="referee-password">
                                    <span class="password-code"><?= htmlspecialchars($referee['password'] ?? 'N/A') ?></span>
                                    <button class="copy-btn" onclick="copyToClipboard('<?= htmlspecialchars($referee['password'] ?? '') ?>', this)" title="Copier le mot de passe">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </td>
                                <td class="referee-matches">
                                    <?php 
                                    $gamesCount = $referee['games_count'] ?? 0;
                                    $badgeClass = '';
                                    if ($gamesCount === 0) {
                                        $badgeClass = 'no-games';
                                    } elseif ($gamesCount >= 5) {
                                        $badgeClass = 'many-games';
                                    }
                                    ?>
                                    <span class="games-count <?= $badgeClass ?>">
                                        <i class="fas fa-whistle"></i> 
                                        <span class="count"><?= $gamesCount ?></span> match<?= $gamesCount > 1 ? 's' : '' ?>
                                    </span>
                                </td>
                                <td class="referee-actions">
                                    <button class="btn-edit" onclick="editReferee(<?= $referee['Referee_Id'] ?>, <?= htmlspecialchars(json_encode($referee)) ?>)" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-delete" onclick="deleteReferee(<?= $referee['Referee_Id'] ?>, '<?= htmlspecialchars($referee['first_name'] . ' ' . $referee['last_name']) ?>')" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <p class="empty-message">Aucun arbitre assigné</p>
                    <p class="empty-description">Commencez par ajouter des arbitres pour gérer vos matchs</p>
                    <button class="btn-add" onclick="openRefereeModal()">
                        <i class="fas fa-plus"></i> Ajouter le premier arbitre
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal d'ajout/modification d'arbitre -->
<div class="modal-overlay hidden" id="refereeModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="refereeModalTitle">
                <i class="fas fa-plus"></i> Ajouter un arbitre
            </h3>
            <button class="btn-close" onclick="closeRefereeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="refereeForm" onsubmit="submitRefereeForm(event)">
                <input type="hidden" id="refereeId" name="refereeId">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="refereeFirstName">
                            <i class="fas fa-user"></i> Prénom *
                        </label>
                        <input type="text" id="refereeFirstName" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <label for="refereeLastName">
                            <i class="fas fa-user"></i> Nom *
                        </label>
                        <input type="text" id="refereeLastName" name="last_name" required>
                    </div>
                </div>

                <div class="form-section">
                    <h4><i class="fas fa-key"></i> Identifiants de connexion</h4>
                    <p class="form-description">Ces identifiants permettront à l'arbitre de se connecter sur l'application mobile pour saisir les résultats.</p>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="refereeLoginUUID">
                                <i class="fas fa-id-card"></i> Identifiant *
                            </label>
                            <div class="input-with-button">
                                <input type="text" id="refereeLoginUUID" name="loginUUID" required>
                                <button type="button" class="btn-generate" onclick="generateLoginUUID()">
                                    <i class="fas fa-random"></i> Générer
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="refereePassword">
                                <i class="fas fa-lock"></i> Mot de passe *
                            </label>
                            <div class="input-with-button">
                                <input type="text" id="refereePassword" name="password" required>
                                <button type="button" class="btn-generate" onclick="generatePassword()">
                                    <i class="fas fa-random"></i> Générer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeRefereeModal()">
                <i class="fas fa-times"></i> Annuler
            </button>
            <button type="submit" form="refereeForm" class="btn-submit">
                <i class="fas fa-save"></i> Ajouter
            </button>
        </div>
    </div>
</div>

<!-- Modal de débogage (affiché en cas d'erreur) -->
<div class="modal-overlay hidden" id="debugModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>
                <i class="fas fa-bug"></i> Informations de débogage
            </h3>
            <button class="btn-close" onclick="closeDebugModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="debug-info">
                <h4>Données envoyées :</h4>
                <pre id="debugData"></pre>
                
                <h4>Réponse du serveur :</h4>
                <pre id="debugResponse"></pre>
                
                <h4>Statut HTTP :</h4>
                <pre id="debugStatus"></pre>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeDebugModal()">
                <i class="fas fa-times"></i> Fermer
            </button>
        </div>
    </div>
</div>

<style>
/* Styles pour la page des arbitres */
.referees-container {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

.referees-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 2px solid #e8ecff;
}

.referees-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 20px;
    font-weight: 600;
    color: #232c5a;
}

.referee-count {
    font-size: 14px;
    color: #666;
    font-weight: normal;
}

.btn-add {
    background: linear-gradient(135deg, #232c5a 0%, #1a2147 100%);
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    text-decoration: none;
}

.btn-add:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(35, 44, 90, 0.3);
}

.referees-table-container {
    overflow-x: auto;
}

.referees-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.referees-table th {
    background: linear-gradient(135deg, #232c5a 0%, #1a2147 100%);
    color: white;
    padding: 15px 12px;
    text-align: left;
    font-weight: 600;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.referees-table td {
    padding: 15px 12px;
    border-bottom: 1px solid #eee;
    vertical-align: middle;
}

.referees-table tbody tr:hover {
    background-color: #f8f9ff;
}

.referee-name, .referee-firstname {
    font-weight: 500;
    color: #333;
}

.login-code, .password-code {
    font-family: 'Courier New', monospace;
    background: #f8f9ff;
    padding: 4px 8px;
    border-radius: 4px;
    border: 1px solid #e1e5e9;
    font-weight: 600;
    color: #232c5a;
    min-width: 60px;
    text-align: center;
}

.copy-btn {
    background: none;
    border: 1px solid #ddd;
    color: #666;
    padding: 4px 6px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
    transition: all 0.3s ease;
}

.copy-btn:hover {
    background: #232c5a;
    color: white;
    border-color: #232c5a;
}

.copy-btn.copied {
    background: #28a745;
    color: white;
    border-color: #28a745;
}

.games-count {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    color: #666;
    background-color: #f8f9ff;
    padding: 4px 8px;
    border-radius: 12px;
}

.games-count.no-games {
    background-color: #fff5f5;
    color: #c53030;
}

.games-count.many-games {
    background-color: #f0fff4;
    color: #38a169;
}

.referee-actions {
    display: flex;
    gap: 8px;
}

.btn-edit, .btn-delete {
    padding: 8px 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s ease;
}

.btn-edit {
    background: #ffc107;
    color: #333;
}

.btn-edit:hover {
    background: #e0a800;
}

.btn-delete {
    background: #dc3545;
    color: white;
}

.btn-delete:hover {
    background: #c82333;
}

/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 10000;
    display: flex;
    justify-content: center;
    align-items: center;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.modal-overlay:not(.hidden) {
    opacity: 1;
    visibility: visible;
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

.modal-overlay:not(.hidden) .modal-content {
    transform: scale(1);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 25px 30px;
    border-bottom: 2px solid #e8ecff;
    background: linear-gradient(135deg, #f8f9ff 0%, #e8ecff 100%);
    border-radius: 15px 15px 0 0;
}

.modal-header h3 {
    color: #232c5a;
    font-size: 20px;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #666;
    padding: 5px;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.btn-close:hover {
    background: #f8f9ff;
    color: #232c5a;
}

.modal-body {
    padding: 30px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #333;
    font-weight: 600;
    font-size: 14px;
}

.form-group input {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e1e5e9;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.3s ease;
    box-sizing: border-box;
}

.form-group input:focus {
    outline: none;
    border-color: #232c5a;
    box-shadow: 0 0 0 3px rgba(35, 44, 90, 0.1);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.form-section {
    background: #f8f9ff;
    padding: 20px;
    border-radius: 8px;
    margin: 20px 0;
    border: 1px solid #e8ecff;
}

.form-section h4 {
    margin: 0 0 10px 0;
    color: #232c5a;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 16px;
}

.form-description {
    color: #666;
    font-size: 14px;
    margin-bottom: 15px;
    line-height: 1.4;
}

.input-with-button {
    display: flex;
    gap: 8px;
}

.input-with-button input {
    flex: 1;
}

.btn-generate {
    background: #6c757d;
    color: white;
    border: none;
    padding: 12px 15px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 12px;
    white-space: nowrap;
    transition: all 0.3s ease;
    font-weight: 600;
}

.btn-generate:hover {
    background: #5a6268;
    transform: translateY(-1px);
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    padding: 25px 30px;
    border-top: 2px solid #e8ecff;
    background: #f8f9ff;
    border-radius: 0 0 15px 15px;
}

.btn-cancel, .btn-submit {
    padding: 12px 24px;
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
    background: #6c757d;
    color: white;
}

.btn-cancel:hover {
    background: #5a6268;
    transform: translateY(-1px);
}

.btn-submit {
    background: linear-gradient(135deg, #232c5a 0%, #1a2147 100%);
    color: white;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(35, 44, 90, 0.3);
}

.btn-submit:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* États vides */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #666;
}

.empty-icon {
    font-size: 64px;
    color: #ddd;
    margin-bottom: 20px;
}

.empty-message {
    font-size: 20px;
    font-weight: 500;
    margin-bottom: 10px;
    color: #333;
}

.empty-description {
    font-size: 16px;
    margin-bottom: 30px;
    color: #999;
}

/* Debug modal styles */
.debug-info h4 {
    color: #232c5a;
    margin: 15px 0 5px 0;
    font-size: 14px;
}

.debug-info pre {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 5px;
    padding: 10px;
    font-size: 12px;
    overflow-x: auto;
    max-height: 200px;
    overflow-y: auto;
}

.error-details {
    background: #fff5f5;
    border: 1px solid #fed7d7;
    border-radius: 8px;
    padding: 15px;
    margin: 15px 0;
}

.error-details h4 {
    color: #c53030;
    margin-bottom: 10px;
}

/* Responsive */
@media (max-width: 768px) {
    .referees-header {
        flex-direction: column;
        gap: 15px;
        align-items: stretch;
    }

    .referees-table th,
    .referees-table td {
        padding: 10px 8px;
        font-size: 12px;
    }

    .referee-actions {
        flex-direction: column;
        gap: 4px;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .input-with-button {
        flex-direction: column;
    }

    .modal-content {
        width: 95%;
        margin: 10px;
    }

    .modal-header,
    .modal-body,
    .modal-footer {
        padding: 20px;
    }

    .modal-footer {
        flex-direction: column-reverse;
    }

    .btn-cancel,
    .btn-submit {
        width: 100%;
        justify-content: center;
    }
}

/* Animation pour les éléments copiés */
@keyframes copySuccess {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.copy-btn.copied {
    animation: copySuccess 0.3s ease;
}

/* Styles pour les états de chargement */
.btn-submit:disabled i.fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script>
let refereesData = <?= json_encode($referees) ?>;

// Fonction pour ouvrir le modal d'ajout
function openRefereeModal() {
    const modal = document.getElementById('refereeModal');
    const form = document.getElementById('refereeForm');
    const title = document.getElementById('refereeModalTitle');
    const submitBtn = document.querySelector('.btn-submit');
    
    // Reset form
    form.reset();
    document.getElementById('refereeId').value = '';
    
    // Générer automatiquement les identifiants
    generateLoginUUID();
    generatePassword();
    
    // Mode ajout
    title.innerHTML = '<i class="fas fa-plus"></i> Ajouter un arbitre';
    submitBtn.innerHTML = '<i class="fas fa-save"></i> Ajouter';
    
    modal.classList.remove('hidden');
}

// Fonction pour fermer le modal
function closeRefereeModal() {
    document.getElementById('refereeModal').classList.add('hidden');
}

// Fonction pour fermer le modal de debug
function closeDebugModal() {
    document.getElementById('debugModal').classList.add('hidden');
}

// Fonction pour afficher le modal de debug
function showDebugModal(requestData, responseData, status) {
    document.getElementById('debugData').textContent = JSON.stringify(requestData, null, 2);
    document.getElementById('debugResponse').textContent = JSON.stringify(responseData, null, 2);
    document.getElementById('debugStatus').textContent = status;
    document.getElementById('debugModal').classList.remove('hidden');
}

// Fonction pour éditer un arbitre
function editReferee(refereeId, refereeData) {
    const modal = document.getElementById('refereeModal');
    const form = document.getElementById('refereeForm');
    const title = document.getElementById('refereeModalTitle');
    const submitBtn = document.querySelector('.btn-submit');
    
    // Remplir le formulaire
    document.getElementById('refereeId').value = refereeId;
    document.getElementById('refereeFirstName').value = refereeData.first_name || '';
    document.getElementById('refereeLastName').value = refereeData.last_name || '';
    document.getElementById('refereeLoginUUID').value = refereeData.loginUUID || '';
    document.getElementById('refereePassword').value = refereeData.password || '';
    
    // Mode édition
    title.innerHTML = '<i class="fas fa-edit"></i> Modifier l\'arbitre';
    submitBtn.innerHTML = '<i class="fas fa-save"></i> Modifier';
    
    modal.classList.remove('hidden');
}

// Fonction pour générer un identifiant
function generateLoginUUID() {
    const uuid = Math.random().toString(36).substr(2, 6).toUpperCase();
    document.getElementById('refereeLoginUUID').value = uuid;
}

// Fonction pour générer un mot de passe
function generatePassword() {
    const password = Math.floor(1000 + Math.random() * 9000);
    document.getElementById('refereePassword').value = password;
}

// Fonction pour copier dans le presse-papiers
async function copyToClipboard(text, button) {
    try {
        await navigator.clipboard.writeText(text);
        
        // Animation de confirmation
        const originalContent = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check"></i>';
        button.classList.add('copied');
        
        setTimeout(() => {
            button.innerHTML = originalContent;
            button.classList.remove('copied');
        }, 1500);
        
    } catch (err) {
        console.error('Erreur lors de la copie:', err);
        alert('Impossible de copier. Veuillez sélectionner et copier manuellement.');
    }
}

// Fonction pour soumettre le formulaire
async function submitRefereeForm(event) {
    event.preventDefault();
    
    const form = document.getElementById('refereeForm');
    const formData = new FormData(form);
    const submitBtn = document.querySelector('.btn-submit');
    const tournamentId = document.querySelector('.referees-container').dataset.tournamentId;
    const refereeId = document.getElementById('refereeId').value;
    const isEditing = !!refereeId;
    
    // Validation côté client
    const firstName = formData.get('first_name');
    const lastName = formData.get('last_name');
    const loginUUID = formData.get('loginUUID');
    const password = formData.get('password');
    
    if (!firstName || !lastName || !loginUUID || !password) {
        alert('Tous les champs sont requis');
        return;
    }
    
    // Validation de la longueur des champs
    if (firstName.trim().length < 2) {
        alert('Le prénom doit contenir au moins 2 caractères');
        return;
    }
    
    if (lastName.trim().length < 2) {
        alert('Le nom doit contenir au moins 2 caractères');
        return;
    }
    
    if (loginUUID.trim().length < 4) {
        alert('L\'identifiant doit contenir au moins 4 caractères');
        return;
    }
    
    if (password.trim().length < 4) {
        alert('Le mot de passe doit contenir au moins 4 caractères');
        return;
    }
    
    // Désactiver le bouton
    submitBtn.disabled = true;
    submitBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> ${isEditing ? 'Modification...' : 'Ajout...'}`;
    
    try {
        // Préparer les données exactement comme l'API les attend
        const refereeData = {
            first_name: firstName.trim(),
            last_name: lastName.trim(),
            loginUUID: loginUUID.trim(),
            password: password.trim()
        };
        
        console.log('=== DÉBUT CRÉATION ARBITRE ===');
        console.log('Tournament ID:', tournamentId);
        console.log('Is Editing:', isEditing);
        console.log('Referee ID:', refereeId);
        console.log('Données du formulaire:', refereeData);
        
        const route = isEditing ? 
            `index.php?route=update-referee&tournament_id=${tournamentId}&referee_id=${refereeId}` : 
            `index.php?route=create-referee&tournament_id=${tournamentId}`;
        
        const method = isEditing ? 'PUT' : 'POST';
        
        console.log('URL:', route);
        console.log('Méthode:', method);
        
        const response = await fetch(route, {
            method: method,
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(refereeData)
        });
        
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        let result;
        const contentType = response.headers.get('content-type');
        
        if (contentType && contentType.includes('application/json')) {
            result = await response.json();
        } else {
            const textResponse = await response.text();
            console.log('Response as text:', textResponse);
            result = { error: 'Réponse non-JSON du serveur: ' + textResponse };
        }
        
        console.log('Response data:', result);
        console.log('=== FIN REQUÊTE ===');
        
        if (response.ok && result.success) {
            closeRefereeModal();
            alert(`Arbitre ${isEditing ? 'modifié' : 'ajouté'} avec succès !`);
            window.location.reload();
        } else {
            console.error('Erreur API:', result);
            
            // Afficher le modal de debug en cas d'erreur
            showDebugModal(refereeData, result, `${response.status} ${response.statusText}`);
            
            throw new Error(result.error || `Erreur ${response.status}: ${response.statusText}`);
        }
        
    } catch (error) {
        console.error('=== ERREUR CRÉATION ARBITRE ===');
        console.error('Error message:', error.message);
        console.error('Error stack:', error.stack);
        console.error('================================');
        
        let errorMessage = `Erreur lors de ${isEditing ? 'la modification' : 'l\'ajout'} de l'arbitre: `;
        
        if (error.message.includes('500')) {
            errorMessage += 'Erreur interne du serveur. Veuillez réessayer ou contacter l\'administrateur.';
        } else if (error.message.includes('400')) {
            errorMessage += 'Données invalides. Vérifiez les informations saisies.';
        } else {
            errorMessage += error.message;
        }
        
        alert(errorMessage);
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = `<i class="fas fa-save"></i> ${isEditing ? 'Modifier' : 'Ajouter'}`;
    }
}

// Fonction pour supprimer un arbitre
async function deleteReferee(refereeId, refereeName) {
    if (!confirm(`Êtes-vous sûr de vouloir supprimer l'arbitre "${refereeName}" ?\n\nCette action est irréversible.`)) {
        return;
    }
    
    try {
        const response = await fetch(`index.php?route=delete-referee&referee_id=${refereeId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (response.ok && result.success) {
            alert(`Arbitre "${refereeName}" supprimé avec succès !`);
            window.location.reload();
        } else {
            throw new Error(result.error || 'Erreur lors de la suppression de l\'arbitre');
        }
        
    } catch (error) {
        console.error('Error deleting referee:', error);
        alert('Erreur lors de la suppression de l\'arbitre: ' + error.message);
    }
}

// Gestion de la recherche
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('.referees-table tbody tr');
    
    rows.forEach(row => {
        const name = row.querySelector('.referee-name').textContent.toLowerCase();
        const firstname = row.querySelector('.referee-firstname').textContent.toLowerCase();
        
        if (name.includes(searchTerm) || firstname.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Fermer le modal en cliquant à l'extérieur
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-overlay')) {
        closeRefereeModal();
        closeDebugModal();
    }
});

// Fermer le modal avec la touche Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeRefereeModal();
        closeDebugModal();
    }
});
</script>
