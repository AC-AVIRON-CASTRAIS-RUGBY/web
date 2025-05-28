<div class="main-content">
    <div class="header">
        <div class="search-bar">
            <input type="text" placeholder="Rechercher un paramètre" id="searchInput">
            <button type="submit" id="searchButton"><i class="fas fa-search"></i></button>
        </div>
        <div class="profile">
            <img src="img/logo.png" alt="Profil">
        </div>
    </div>

    <div class="welcome">
        <i class="fas fa-cog"></i> Paramètres du tournoi
    </div>

    <div class="settings-container">
        <!-- Informations générales -->
        <div class="settings-section">
            <div class="section-header">
                <h3><i class="fas fa-info-circle"></i> Informations générales</h3>
                <button class="btn-edit-section" data-section="general">
                    <i class="fas fa-edit"></i> Modifier
                </button>
            </div>
            <div class="settings-grid">
                <div class="setting-item">
                    <label>Nom du tournoi</label>
                    <div class="setting-value">
                        <span id="tournament-name"><?= htmlspecialchars($tournament['name'] ?? 'Non défini') ?></span>
                        <input type="text" id="edit-tournament-name" class="hidden" value="<?= htmlspecialchars($tournament['name'] ?? '') ?>">
                    </div>
                </div>
                <div class="setting-item">
                    <label>Date de début</label>
                    <div class="setting-value">
                        <span id="start-date"><?= isset($tournament['start_date']) ? date('d/m/Y', strtotime($tournament['start_date'])) : 'Non définie' ?></span>
                        <input type="date" id="edit-start-date" class="hidden" value="<?= $tournament['start_date'] ?? '' ?>">
                    </div>
                </div>
                <div class="setting-item">
                    <label>Date de fin</label>
                    <div class="setting-value">
                        <span id="end-date"><?= isset($tournament['end_date']) ? date('d/m/Y', strtotime($tournament['end_date'])) : 'Non définie' ?></span>
                        <input type="date" id="edit-end-date" class="hidden" value="<?= $tournament['end_date'] ?? '' ?>">
                    </div>
                </div>
                <div class="setting-item">
                    <label>Lieu</label>
                    <div class="setting-value">
                        <span id="location"><?= htmlspecialchars($tournament['location'] ?? 'Non défini') ?></span>
                        <input type="text" id="edit-location" class="hidden" value="<?= htmlspecialchars($tournament['location'] ?? '') ?>">
                    </div>
                </div>
                <div class="setting-item full-width">
                    <label>Description</label>
                    <div class="setting-value">
                        <span id="description"><?= htmlspecialchars($tournament['description'] ?? 'Aucune description') ?></span>
                        <textarea id="edit-description" class="hidden" rows="3"><?= htmlspecialchars($tournament['description'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>
            <div class="section-actions hidden" id="general-actions">
                <button class="btn-save" onclick="saveSection('general')">
                    <i class="fas fa-save"></i> Sauvegarder
                </button>
                <button class="btn-cancel" onclick="cancelEdit('general')">
                    <i class="fas fa-times"></i> Annuler
                </button>
            </div>
        </div>

        <!-- Paramètres de jeu -->
        <div class="settings-section">
            <div class="section-header">
                <h3><i class="fas fa-gamepad"></i> Paramètres de jeu</h3>
                <button class="btn-edit-section" data-section="game">
                    <i class="fas fa-edit"></i> Modifier
                </button>
            </div>
            <div class="settings-grid">
                <div class="setting-item">
                    <label>Durée d'un match (minutes)</label>
                    <div class="setting-value">
                        <span id="match-duration"><?= $tournament['match_duration'] ?? 15 ?></span>
                        <input type="number" id="edit-match-duration" class="hidden" value="<?= $tournament['match_duration'] ?? 15 ?>" min="5" max="120">
                    </div>
                </div>
                <div class="setting-item">
                    <label>Pause entre matchs (minutes)</label>
                    <div class="setting-value">
                        <span id="break-duration"><?= $tournament['break_duration'] ?? 5 ?></span>
                        <input type="number" id="edit-break-duration" class="hidden" value="<?= $tournament['break_duration'] ?? 5 ?>" min="0" max="60">
                    </div>
                </div>
                <div class="setting-item">
                    <label>Points par victoire</label>
                    <div class="setting-value">
                        <span id="points-win"><?= $tournament['points_win'] ?? 3 ?></span>
                        <input type="number" id="edit-points-win" class="hidden" value="<?= $tournament['points_win'] ?? 3 ?>" min="1" max="10">
                    </div>
                </div>
                <div class="setting-item">
                    <label>Points par match nul</label>
                    <div class="setting-value">
                        <span id="points-draw"><?= $tournament['points_draw'] ?? 1 ?></span>
                        <input type="number" id="edit-points-draw" class="hidden" value="<?= $tournament['points_draw'] ?? 1 ?>" min="0" max="5">
                    </div>
                </div>
                <div class="setting-item">
                    <label>Nombre maximum d'équipes par poule</label>
                    <div class="setting-value">
                        <span id="max-teams-pool"><?= $tournament['max_teams_per_pool'] ?? 6 ?></span>
                        <input type="number" id="edit-max-teams-pool" class="hidden" value="<?= $tournament['max_teams_per_pool'] ?? 6 ?>" min="3" max="12">
                    </div>
                </div>
                <div class="setting-item">
                    <label>Format de tournoi</label>
                    <div class="setting-value">
                        <span id="tournament-format"><?= $tournament['format'] ?? 'Poules + Élimination directe' ?></span>
                        <select id="edit-tournament-format" class="hidden">
                            <option value="pools" <?= ($tournament['format'] ?? '') === 'pools' ? 'selected' : '' ?>>Poules uniquement</option>
                            <option value="knockout" <?= ($tournament['format'] ?? '') === 'knockout' ? 'selected' : '' ?>>Élimination directe</option>
                            <option value="pools_knockout" <?= ($tournament['format'] ?? '') === 'pools_knockout' ? 'selected' : '' ?>>Poules + Élimination directe</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="section-actions hidden" id="game-actions">
                <button class="btn-save" onclick="saveSection('game')">
                    <i class="fas fa-save"></i> Sauvegarder
                </button>
                <button class="btn-cancel" onclick="cancelEdit('game')">
                    <i class="fas fa-times"></i> Annuler
                </button>
            </div>
        </div>

        <!-- Paramètres d'affichage -->
        <div class="settings-section">
            <div class="section-header">
                <h3><i class="fas fa-eye"></i> Paramètres d'affichage</h3>
                <button class="btn-edit-section" data-section="display">
                    <i class="fas fa-edit"></i> Modifier
                </button>
            </div>
            <div class="settings-grid">
                <div class="setting-item">
                    <label>Afficher les logos des équipes</label>
                    <div class="setting-value">
                        <span id="show-logos"><?= ($tournament['show_logos'] ?? true) ? 'Oui' : 'Non' ?></span>
                        <select id="edit-show-logos" class="hidden">
                            <option value="1" <?= ($tournament['show_logos'] ?? true) ? 'selected' : '' ?>>Oui</option>
                            <option value="0" <?= !($tournament['show_logos'] ?? true) ? 'selected' : '' ?>>Non</option>
                        </select>
                    </div>
                </div>
                <div class="setting-item">
                    <label>Afficher les arbitres publiquement</label>
                    <div class="setting-value">
                        <span id="show-referees"><?= ($tournament['show_referees'] ?? true) ? 'Oui' : 'Non' ?></span>
                        <select id="edit-show-referees" class="hidden">
                            <option value="1" <?= ($tournament['show_referees'] ?? true) ? 'selected' : '' ?>>Oui</option>
                            <option value="0" <?= !($tournament['show_referees'] ?? true) ? 'selected' : '' ?>>Non</option>
                        </select>
                    </div>
                </div>
                <div class="setting-item">
                    <label>Couleur principale</label>
                    <div class="setting-value">
                        <span id="primary-color" style="display: inline-block; width: 20px; height: 20px; background-color: <?= $tournament['primary_color'] ?? '#232c5a' ?>; border-radius: 3px; vertical-align: middle;"></span>
                        <input type="color" id="edit-primary-color" class="hidden" value="<?= $tournament['primary_color'] ?? '#232c5a' ?>">
                    </div>
                </div>
            </div>
            <div class="section-actions hidden" id="display-actions">
                <button class="btn-save" onclick="saveSection('display')">
                    <i class="fas fa-save"></i> Sauvegarder
                </button>
                <button class="btn-cancel" onclick="cancelEdit('display')">
                    <i class="fas fa-times"></i> Annuler
                </button>
            </div>
        </div>

        <!-- Actions dangereuses -->
        <div class="settings-section danger-zone">
            <div class="section-header">
                <h3><i class="fas fa-exclamation-triangle"></i> Zone dangereuse</h3>
            </div>
            <div class="danger-actions">
                <div class="danger-item">
                    <div class="danger-info">
                        <h4>Réinitialiser les résultats</h4>
                        <p>Supprime tous les résultats et remet les classements à zéro</p>
                    </div>
                    <button class="btn-danger" onclick="resetResults()">
                        <i class="fas fa-undo"></i> Réinitialiser
                    </button>
                </div>
                <div class="danger-item">
                    <div class="danger-info">
                        <h4>Supprimer le tournoi</h4>
                        <p>Supprime définitivement le tournoi et toutes ses données</p>
                    </div>
                    <button class="btn-danger" onclick="deleteTournament()">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.settings-container {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.settings-section {
    background-color: white;
    border-radius: 10px;
    padding: 25px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
}

.section-header h3 {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #232c5a;
    margin: 0;
    font-size: 18px;
}

.btn-edit-section {
    background-color: #e3f2fd;
    color: #1565c0;
    border: 1px solid #bbdefb;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: all 0.3s ease;
}

.btn-edit-section:hover {
    background-color: #1565c0;
    color: white;
}

.settings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.setting-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.setting-item.full-width {
    grid-column: 1 / -1;
}

.setting-item label {
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

.setting-value {
    position: relative;
}

.setting-value span {
    display: block;
    padding: 10px 15px;
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 5px;
    color: #333;
    min-height: 42px;
    display: flex;
    align-items: center;
}

.setting-value input,
.setting-value select,
.setting-value textarea {
    width: 100%;
    padding: 10px 15px;
    border: 2px solid #e1e5e9;
    border-radius: 5px;
    font-size: 14px;
    transition: border-color 0.3s ease;
}

.setting-value input:focus,
.setting-value select:focus,
.setting-value textarea:focus {
    outline: none;
    border-color: #232c5a;
}

.setting-value textarea {
    resize: vertical;
    min-height: 80px;
}

.section-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    padding-top: 15px;
    border-top: 1px solid #f0f0f0;
}

.btn-save, .btn-cancel {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: all 0.3s ease;
}

.btn-save {
    background-color: #e8f5e8;
    color: #2e7d32;
    border: 1px solid #c8e6c9;
}

.btn-save:hover {
    background-color: #2e7d32;
    color: white;
}

.btn-cancel {
    background-color: #ffebee;
    color: #c62828;
    border: 1px solid #ffcdd2;
}

.btn-cancel:hover {
    background-color: #c62828;
    color: white;
}

.danger-zone {
    border: 2px solid #ffcdd2;
    background-color: #ffebee;
}

.danger-zone .section-header h3 {
    color: #c62828;
}

.danger-actions {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.danger-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background-color: white;
    border-radius: 8px;
    border: 1px solid #ffcdd2;
}

.danger-info h4 {
    color: #c62828;
    margin: 0 0 5px 0;
    font-size: 16px;
}

.danger-info p {
    color: #666;
    margin: 0;
    font-size: 14px;
}

.btn-danger {
    background-color: #c62828;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: all 0.3s ease;
}

.btn-danger:hover {
    background-color: #b71c1c;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(183, 28, 28, 0.3);
}

@media (max-width: 768px) {
    .settings-grid {
        grid-template-columns: 1fr;
    }
    
    .section-header {
        flex-direction: column;
        gap: 10px;
        align-items: flex-start;
    }
    
    .danger-item {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }
    
    .section-actions {
        flex-direction: column;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des boutons d'édition
    document.querySelectorAll('.btn-edit-section').forEach(btn => {
        btn.addEventListener('click', function() {
            const section = this.dataset.section;
            toggleEditMode(section, true);
        });
    });
});

function toggleEditMode(section, isEdit) {
    const sectionElement = document.querySelector(`[data-section="${section}"]`).closest('.settings-section');
    const spans = sectionElement.querySelectorAll('.setting-value span');
    const inputs = sectionElement.querySelectorAll('.setting-value input, .setting-value select, .setting-value textarea');
    const editBtn = sectionElement.querySelector('.btn-edit-section');
    const actions = sectionElement.querySelector(`#${section}-actions`);

    if (isEdit) {
        spans.forEach(span => span.classList.add('hidden'));
        inputs.forEach(input => input.classList.remove('hidden'));
        editBtn.style.display = 'none';
        actions.classList.remove('hidden');
    } else {
        spans.forEach(span => span.classList.remove('hidden'));
        inputs.forEach(input => input.classList.add('hidden'));
        editBtn.style.display = 'flex';
        actions.classList.add('hidden');
    }
}

function saveSection(section) {
    // TODO: Sauvegarder les modifications via l'API
    console.log('Sauvegarder section:', section);
    
    // Simuler la sauvegarde et mettre à jour l'affichage
    const sectionElement = document.querySelector(`[data-section="${section}"]`).closest('.settings-section');
    const inputs = sectionElement.querySelectorAll('.setting-value input, .setting-value select, .setting-value textarea');
    const spans = sectionElement.querySelectorAll('.setting-value span');

    inputs.forEach((input, index) => {
        if (input.type === 'color') {
            spans[index].style.backgroundColor = input.value;
        } else if (input.tagName === 'SELECT') {
            const selectedOption = input.options[input.selectedIndex];
            spans[index].textContent = selectedOption.textContent;
        } else {
            spans[index].textContent = input.value;
        }
    });

    toggleEditMode(section, false);
    
    // Afficher un message de succès
    showNotification('Paramètres sauvegardés avec succès', 'success');
}

function cancelEdit(section) {
    toggleEditMode(section, false);
}

function resetResults() {
    if (confirm('Êtes-vous sûr de vouloir réinitialiser tous les résultats ? Cette action est irréversible.')) {
        // TODO: Appeler l'API pour réinitialiser les résultats
        console.log('Réinitialiser les résultats');
        showNotification('Résultats réinitialisés', 'success');
    }
}

function deleteTournament() {
    const confirmText = prompt('Tapez "SUPPRIMER" pour confirmer la suppression définitive du tournoi :');
    if (confirmText === 'SUPPRIMER') {
        // TODO: Appeler l'API pour supprimer le tournoi
        console.log('Supprimer le tournoi');
        window.location.href = 'index.php?route=home';
    }
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 5px;
        color: white;
        font-weight: 500;
        z-index: 10000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
    `;
    
    if (type === 'success') {
        notification.style.backgroundColor = '#2e7d32';
    } else if (type === 'error') {
        notification.style.backgroundColor = '#c62828';
    } else {
        notification.style.backgroundColor = '#1565c0';
    }
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}
</script>
