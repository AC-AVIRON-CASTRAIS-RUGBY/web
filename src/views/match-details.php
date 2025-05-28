<div class="main-content">
    <div class="header">
        <div class="search-bar">
            <a href="javascript:history.back()" class="btn-back">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
        <div class="profile">
            <img src="img/logo.png" alt="Profil">
        </div>
    </div>

    <div class="welcome">
        <i class="fas fa-gamepad"></i> Détails du match
    </div>

    <?php if (!empty($match)): ?>
        <div class="match-details-container">
            <!-- En-tête du match -->
            <div class="match-header">
                <div class="match-status">
                    <?php if ($match['is_completed']): ?>
                        <span class="status-badge finished">
                            <i class="fas fa-check-circle"></i> Terminé
                        </span>
                    <?php else: ?>
                        <span class="status-badge pending">
                            <i class="fas fa-clock"></i> En attente
                        </span>
                    <?php endif; ?>
                </div>
                <div class="match-datetime">
                    <?php 
                    $startTime = new DateTime($match['start_time']);
                    $formattedDate = $startTime->format('d/m/Y');
                    $formattedTime = $startTime->format('H:i');
                    ?>
                    <div class="match-date">
                        <i class="fas fa-calendar"></i> <?= $formattedDate ?>
                    </div>
                    <div class="match-time">
                        <i class="fas fa-clock"></i> <?= $formattedTime ?>
                    </div>
                </div>
            </div>

            <!-- Score principal -->
            <div class="match-score-section">
                <div class="team-score">
                    <div class="team-info">
                        <?php if (!empty($match['team1']['logo'])): ?>
                            <img src="/api/teams/<?= $match['team1']['Team_Id'] ?>/logo" alt="Logo" class="team-logo-large">
                        <?php else: ?>
                            <div class="team-logo-large" style="background: linear-gradient(135deg, #232c5a, #1a2147); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                <?= strtoupper(substr($match['team1']['name'], 0, 2)) ?>
                            </div>
                        <?php endif; ?>
                        <div class="team-details">
                            <h3><?= htmlspecialchars($match['team1']['name']) ?></h3>
                            <span class="team-category"><?= strtoupper($match['team1']['age_category'] ?? '') ?></span>
                        </div>
                    </div>
                    <div class="score">
                        <?= $match['Team1_Score'] ?? '-' ?>
                    </div>
                </div>

                <div class="vs-separator">
                    <span>VS</span>
                </div>

                <div class="team-score">
                    <div class="score">
                        <?= $match['Team2_Score'] ?? '-' ?>
                    </div>
                    <div class="team-info">
                        <div class="team-details right">
                            <h3><?= htmlspecialchars($match['team2']['name']) ?></h3>
                            <span class="team-category"><?= strtoupper($match['team2']['age_category'] ?? '') ?></span>
                        </div>
                        <?php if (!empty($match['team2']['logo'])): ?>
                            <img src="/api/teams/<?= $match['team2']['Team_Id'] ?>/logo" alt="Logo" class="team-logo-large">
                        <?php else: ?>
                            <div class="team-logo-large" style="background: linear-gradient(135deg, #232c5a, #1a2147); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                <?= strtoupper(substr($match['team2']['name'], 0, 2)) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Informations du match -->
            <div class="match-info-grid">
                <div class="info-card">
                    <div class="info-header">
                        <i class="fas fa-user-tie"></i>
                        <h4>Arbitre</h4>
                    </div>
                    <div class="info-content">
                        <span class="referee-name"><?= htmlspecialchars($match['referee']['last_name'] . ' ' . $match['referee']['first_name']) ?></span>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-header">
                        <i class="fas fa-layer-group"></i>
                        <h4>Poule</h4>
                    </div>
                    <div class="info-content">
                        <span class="pool-name"><?= htmlspecialchars($match['pool']['name'] ?? 'Poule ' . $match['Pool_Id']) ?></span>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-header">
                        <i class="fas fa-map-marker-alt"></i>
                        <h4>Terrain</h4>
                    </div>
                    <div class="info-content">
                        <span class="field-name"><?= htmlspecialchars($match['field']['name'] ?? 'Non défini') ?></span>
                    </div>
                </div>
            </div>

            <!-- Actions du match -->
            <div class="match-actions">
                <?php if (!$match['is_completed']): ?>
                    <button class="btn-action primary" onclick="editScore()">
                        <i class="fas fa-edit"></i> Modifier le score
                    </button>
                    <button class="btn-action success" onclick="finishMatch()">
                        <i class="fas fa-flag-checkered"></i> Terminer le match
                    </button>
                <?php else: ?>
                    <button class="btn-action secondary" onclick="editScore()">
                        <i class="fas fa-edit"></i> Modifier le score
                    </button>
                    <button class="btn-action warning" onclick="reopenMatch()">
                        <i class="fas fa-undo"></i> Rouvrir le match
                    </button>
                <?php endif; ?>
            </div>

            <!-- Statistiques additionnelles -->
            <div class="match-stats">
                <div class="stats-header">
                    <h4><i class="fas fa-chart-bar"></i> Statistiques</h4>
                </div>
                <div class="stats-grid">
                    <div class="stat-item">
                        <span class="stat-label">Résultat</span>
                        <span class="stat-value">
                            <?php if ($match['is_completed']): ?>
                                <?php if ($match['Team1_Score'] > $match['Team2_Score']): ?>
                                    <span class="winner">Victoire <?= htmlspecialchars($match['team1']['name']) ?></span>
                                <?php elseif ($match['Team2_Score'] > $match['Team1_Score']): ?>
                                    <span class="winner">Victoire <?= htmlspecialchars($match['team2']['name']) ?></span>
                                <?php else: ?>
                                    <span class="draw">Match nul</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="pending">En attente</span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Points attribués</span>
                        <span class="stat-value">
                            <?php if ($match['is_completed']): ?>
                                <?php if ($match['Team1_Score'] > $match['Team2_Score']): ?>
                                    3 - 0
                                <?php elseif ($match['Team2_Score'] > $match['Team1_Score']): ?>
                                    0 - 3
                                <?php else: ?>
                                    1 - 1
                                <?php endif; ?>
                            <?php else: ?>
                                - - -
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal d'édition des scores -->
        <div class="modal-overlay" id="scoreModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3><i class="fas fa-edit"></i> Modifier le score</h3>
                    <button class="btn-close" onclick="closeScoreModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="match-teams-display">
                        <div class="team-display">
                            <?php if (!empty($match['team1']['logo'])): ?>
                                <img src="/api/teams/<?= $match['team1']['Team_Id'] ?>/logo" alt="Logo" class="team-logo-modal">
                            <?php else: ?>
                                <div class="team-logo-modal" style="background: linear-gradient(135deg, #232c5a, #1a2147); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                    <?= strtoupper(substr($match['team1']['name'], 0, 2)) ?>
                                </div>
                            <?php endif; ?>
                            <span class="team-name"><?= htmlspecialchars($match['team1']['name']) ?></span>
                        </div>

                        <div class="vs-modal">VS</div>

                        <div class="team-display">
                            <?php if (!empty($match['team2']['logo'])): ?>
                                <img src="/api/teams/<?= $match['team2']['Team_Id'] ?>/logo" alt="Logo" class="team-logo-modal">
                            <?php else: ?>
                                <div class="team-logo-modal" style="background: linear-gradient(135deg, #232c5a, #1a2147); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                    <?= strtoupper(substr($match['team2']['name'], 0, 2)) ?>
                                </div>
                            <?php endif; ?>
                            <span class="team-name"><?= htmlspecialchars($match['team2']['name']) ?></span>
                        </div>
                    </div>

                    <div class="score-inputs">
                        <div class="score-input-group">
                            <label for="team1Score"><?= htmlspecialchars($match['team1']['name']) ?></label>
                            <input type="number" id="team1Score" min="0" max="99" value="<?= $match['Team1_Score'] ?? 0 ?>">
                        </div>

                        <div class="score-separator">-</div>

                        <div class="score-input-group">
                            <label for="team2Score"><?= htmlspecialchars($match['team2']['name']) ?></label>
                            <input type="number" id="team2Score" min="0" max="99" value="<?= $match['Team2_Score'] ?? 0 ?>">
                        </div>
                    </div>

                    <div class="modal-options">
                        <div class="checkbox-option">
                            <input type="checkbox" id="markAsCompleted" <?= $match['is_completed'] ? 'checked' : '' ?>>
                            <label for="markAsCompleted">Marquer le match comme terminé</label>
                        </div>
                        
                        <!-- Avertissement pour fermeture définitive -->
                        <div class="warning-box" id="finalizationWarning" style="display: none;">
                            <div class="warning-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="warning-content">
                                <strong>Attention !</strong>
                                <p>Une fois le match terminé et les scores validés, cette action sera <strong>définitive</strong>. Vous ne pourrez plus modifier les scores de ce match.</p>
                                <p class="warning-note">Assurez-vous que les scores sont corrects avant de valider.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn-cancel" onclick="closeScoreModal()">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                    <button class="btn-save" onclick="saveScore()">
                        <i class="fas fa-save"></i> <span id="saveButtonText">Enregistrer</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal de confirmation -->
        <div class="modal-overlay" id="confirmModal">
            <div class="modal-content small">
                <div class="modal-header">
                    <h3><i class="fas fa-check-circle"></i> Score mis à jour</h3>
                </div>
                <div class="modal-body">
                    <p>Le score du match a été mis à jour avec succès !</p>
                </div>
                <div class="modal-footer">
                    <button class="btn-success" onclick="closeConfirmModal()">
                        <i class="fas fa-check"></i> OK
                    </button>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-exclamation-triangle empty-icon"></i>
            <h3>Match introuvable</h3>
            <p>Le match demandé n'existe pas ou n'est plus disponible.</p>
            <a href="javascript:history.back()" class="btn-add">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    <?php endif; ?>
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

.match-details-container {
    display: flex;
    flex-direction: column;
    gap: 25px;
    max-width: 1000px;
    margin: 0 auto;
}

.match-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.status-badge {
    padding: 8px 15px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.status-badge.finished {
    background-color: #e8f5e8;
    color: #2e7d32;
}

.status-badge.pending {
    background-color: #fff3e0;
    color: #f57c00;
}

.match-datetime {
    text-align: right;
}

.match-date, .match-time {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 5px;
    color: #666;
    font-size: 14px;
}

.match-score-section {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 30px;
}

.team-score {
    display: flex;
    align-items: center;
    gap: 20px;
    flex: 1;
}

.team-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.team-logo-large {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: 3px solid #e0e0e0;
    object-fit: cover;
}

.team-details h3 {
    margin: 0;
    font-size: 18px;
    color: #232c5a;
}

.team-details.right {
    text-align: right;
}

.team-category {
    font-size: 12px;
    background-color: #232c5a;
    color: white;
    padding: 2px 8px;
    border-radius: 10px;
}

.score {
    font-size: 48px;
    font-weight: bold;
    color: #232c5a;
    min-width: 80px;
    text-align: center;
}

.vs-separator {
    background: linear-gradient(135deg, #232c5a, #1a2147);
    color: white;
    padding: 15px 20px;
    border-radius: 50px;
    font-weight: bold;
    font-size: 16px;
}

.match-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
}

.info-card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.info-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
    color: #232c5a;
}

.info-header h4 {
    margin: 0;
    font-size: 16px;
}

.info-content {
    font-size: 15px;
    font-weight: 500;
    color: #333;
}

.match-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-action {
    padding: 12px 20px;
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

.btn-action.primary {
    background-color: #232c5a;
    color: white;
}

.btn-action.success {
    background-color: #2e7d32;
    color: white;
}

.btn-action.warning {
    background-color: #f57c00;
    color: white;
}

.btn-action.secondary {
    background-color: #6c757d;
    color: white;
}

.btn-action.info {
    background-color: #1976d2;
    color: white;
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.match-stats {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.stats-header {
    margin-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 15px;
}

.stats-header h4 {
    margin: 0;
    color: #232c5a;
    display: flex;
    align-items: center;
    gap: 10px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background-color: #f8f9fa;
    border-radius: 8px;
}

.stat-label {
    font-weight: 600;
    color: #666;
}

.stat-value .winner {
    color: #2e7d32;
    font-weight: bold;
}

.stat-value .draw {
    color: #f57c00;
    font-weight: bold;
}

.stat-value .pending {
    color: #666;
    font-style: italic;
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
    max-height: 90vh;
    overflow-y: auto;
    transform: scale(0.7);
    transition: transform 0.3s ease;
}

.modal-overlay.show .modal-content {
    transform: scale(1);
}

.modal-content.small {
    max-width: 400px;
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

.match-teams-display {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding: 15px;
    background-color: #f8f9ff;
    border-radius: 10px;
}

.team-display {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    flex: 1;
}

.team-logo-modal {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 2px solid #e0e0e0;
    object-fit: cover;
}

.team-name {
    font-weight: 600;
    color: #232c5a;
    text-align: center;
    font-size: 14px;
}

.vs-modal {
    background: linear-gradient(135deg, #232c5a, #1a2147);
    color: white;
    padding: 10px 15px;
    border-radius: 25px;
    font-weight: bold;
    font-size: 14px;
}

.score-inputs {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 25px;
}

.score-input-group {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    flex: 1;
}

.score-input-group label {
    font-weight: 600;
    color: #333;
    font-size: 14px;
    text-align: center;
}

.score-input-group input {
    width: 80px;
    height: 60px;
    border: 2px solid #e1e5e9;
    border-radius: 10px;
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    color: #232c5a;
    transition: border-color 0.3s ease;
}

.score-input-group input:focus {
    outline: none;
    border-color: #232c5a;
    box-shadow: 0 0 0 3px rgba(35, 44, 90, 0.1);
}

.score-separator {
    font-size: 24px;
    font-weight: bold;
    color: #666;
}

.modal-options {
    padding: 15px;
    background-color: #f8f9ff;
    border-radius: 8px;
    margin-bottom: 20px;
}

.checkbox-option {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
}

.checkbox-option input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.checkbox-option label {
    font-size: 14px;
    color: #333;
    cursor: pointer;
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

.btn-cancel, .btn-save, .btn-success {
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

.btn-success {
    background-color: #2e7d32;
    color: white;
    width: 100%;
    justify-content: center;
}

.btn-success:hover {
    background-color: #1b5e20;
    transform: translateY(-1px);
}

.btn-save:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* Warning box styles */
.warning-box {
    margin-top: 15px;
    padding: 15px;
    background-color: #fff3cd;
    border: 2px solid #ffc107;
    border-radius: 8px;
    display: flex;
    gap: 12px;
    animation: slideDown 0.3s ease-out;
}

.warning-icon {
    color: #856404;
    font-size: 20px;
    margin-top: 2px;
    flex-shrink: 0;
}

.warning-content {
    flex: 1;
}

.warning-content strong {
    color: #856404;
    font-size: 14px;
    display: block;
    margin-bottom: 8px;
}

.warning-content p {
    margin: 0 0 8px 0;
    font-size: 13px;
    color: #856404;
    line-height: 1.4;
}

.warning-content p:last-child {
    margin-bottom: 0;
}

.warning-note {
    font-style: italic;
    font-weight: 500;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
        max-height: 0;
    }
    to {
        opacity: 1;
        transform: translateY(0);
        max-height: 200px;
    }
}

/* Modification du bouton quand le match va être finalisé */
.btn-save.finalizing {
    background-color: #dc3545;
    animation: pulse 2s infinite;
}

.btn-save.finalizing:hover {
    background-color: #c82333;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
    }
}

@media (max-width: 768px) {
    .match-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .match-score-section {
        flex-direction: column;
        gap: 20px;
        padding: 20px;
    }
    
    .team-score {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }
    
    .team-details.right {
        text-align: center;
    }
    
    .vs-separator {
        order: -1;
    }
    
    .match-actions {
        flex-direction: column;
    }
    
    .btn-action {
        justify-content: center;
    }
    
    .modal-content {
        width: 95%;
        margin: 10px;
    }
    
    .match-teams-display {
        flex-direction: column;
        gap: 15px;
    }
    
    .vs-modal {
        order: -1;
    }
    
    .score-inputs {
        flex-direction: column;
        gap: 15px;
    }
    
    .score-separator {
        display: none;
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
function editScore() {
    document.getElementById('scoreModal').classList.add('show');
    document.body.style.overflow = 'hidden';
    updateModalState();
}

function closeScoreModal() {
    document.getElementById('scoreModal').classList.remove('show');
    document.body.style.overflow = 'auto';
}

function closeConfirmModal() {
    document.getElementById('confirmModal').classList.remove('show');
    document.body.style.overflow = 'auto';
    // Recharger la page pour afficher les nouvelles données
    window.location.reload();
}

function updateModalState() {
    const checkbox = document.getElementById('markAsCompleted');
    const warning = document.getElementById('finalizationWarning');
    const saveBtn = document.querySelector('.btn-save');
    const saveButtonText = document.getElementById('saveButtonText');
    const isCurrentlyCompleted = <?= $match['is_completed'] ? 'true' : 'false' ?>;
    
    if (checkbox.checked && !isCurrentlyCompleted) {
        // Match va être finalisé
        warning.style.display = 'flex';
        saveBtn.classList.add('finalizing');
        saveButtonText.innerHTML = '<i class="fas fa-flag-checkered"></i> Finaliser le match';
    } else if (checkbox.checked && isCurrentlyCompleted) {
        // Match déjà terminé, juste mise à jour des scores
        warning.style.display = 'none';
        saveBtn.classList.remove('finalizing');
        saveButtonText.innerHTML = '<i class="fas fa-save"></i> Enregistrer';
    } else {
        // Match pas terminé
        warning.style.display = 'none';
        saveBtn.classList.remove('finalizing');
        saveButtonText.innerHTML = '<i class="fas fa-save"></i> Enregistrer';
    }
}

async function saveScore() {
    const team1Score = parseInt(document.getElementById('team1Score').value) || 0;
    const team2Score = parseInt(document.getElementById('team2Score').value) || 0;
    const isCompleted = document.getElementById('markAsCompleted').checked;
    const saveBtn = document.querySelector('.btn-save');
    const isCurrentlyCompleted = <?= $match['is_completed'] ? 'true' : 'false' ?>;
    
    // Confirmation supplémentaire pour la finalisation
    if (isCompleted && !isCurrentlyCompleted) {
        const confirmFinalization = confirm(
            'ATTENTION : Vous êtes sur le point de finaliser ce match.\n\n' +
            'Cette action est DÉFINITIVE et vous ne pourrez plus modifier les scores par la suite.\n\n' +
            'Scores finaux :\n' +
            '<?= htmlspecialchars($match['team1']['name']) ?> : ' + team1Score + '\n' +
            '<?= htmlspecialchars($match['team2']['name']) ?> : ' + team2Score + '\n\n' +
            'Êtes-vous absolument certain de vouloir finaliser ce match ?'
        );
        
        if (!confirmFinalization) {
            return;
        }
    }
    
    // Désactiver le bouton pendant la requête
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';
    
    try {
        const matchData = {
            start_time: "<?= $match['start_time'] ?>",
            Team1_Id: <?= $match['Team1_Id'] ?>,
            Team2_Id: <?= $match['Team2_Id'] ?>,
            Team1_Score: team1Score,
            Team2_Score: team2Score,
            is_completed: isCompleted,
            Referee_Id: <?= $match['Referee_Id'] ?>,
            Pool_Id: <?= $match['Pool_Id'] ?>,
            Field_Id: <?= $match['Field_Id'] ?? 'null' ?>
        };

        // Essayer d'abord l'API directe, puis le proxy en cas d'échec
        let response;
        try {
            response = await fetch('http://localhost:3000/api/games/<?= $match['Game_Id'] ?>', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(matchData)
            });
        } catch (corsError) {
            // Si CORS bloque, utiliser le proxy PHP
            response = await fetch('api-proxy.php/games/<?= $match['Game_Id'] ?>', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(matchData)
            });
        }

        if (response.ok) {
            closeScoreModal();
            setTimeout(() => {
                document.getElementById('confirmModal').classList.add('show');
            }, 300);
        } else {
            const errorData = await response.text();
            throw new Error('Erreur lors de la mise à jour: ' + response.status);
        }
    } catch (error) {
        console.error('Erreur:', error);
        alert('Erreur lors de la mise à jour du score. Veuillez réessayer.');
    } finally {
        // Réactiver le bouton
        saveBtn.disabled = false;
        updateModalState();
    }
}

function finishMatch() {
    const matchId = <?= json_encode($match['Game_Id']) ?>;
    if (confirm('Êtes-vous sûr de vouloir terminer ce match de façon définitive ?')) {
        // Ouvrir le modal avec la case cochée
        document.getElementById('markAsCompleted').checked = true;
        editScore();
    }
}

function reopenMatch() {
    // Cette fonction ne devrait plus être accessible pour les matchs terminés
    alert('Les matchs terminés ne peuvent pas être rouverts. La finalisation est définitive.');
}

// Écouter les changements sur la checkbox
document.addEventListener('DOMContentLoaded', function() {
    const checkbox = document.getElementById('markAsCompleted');
    if (checkbox) {
        checkbox.addEventListener('change', updateModalState);
    }
});

// Fermer le modal en cliquant à l'extérieur
document.addEventListener('click', function(e) {
    const scoreModal = document.getElementById('scoreModal');
    const confirmModal = document.getElementById('confirmModal');
    
    if (e.target === scoreModal) {
        closeScoreModal();
    }
    if (e.target === confirmModal) {
        closeConfirmModal();
    }
});

// Fermer le modal avec la touche Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeScoreModal();
        closeConfirmModal();
    }
});

// Validation des scores
document.getElementById('team1Score')?.addEventListener('input', function() {
    if (this.value < 0) this.value = 0;
    if (this.value > 99) this.value = 99;
});

document.getElementById('team2Score')?.addEventListener('input', function() {
    if (this.value < 0) this.value = 0;
    if (this.value > 99) this.value = 99;
});
</script>
