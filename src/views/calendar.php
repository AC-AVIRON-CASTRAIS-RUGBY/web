<div class="main-content">
    <div class="header">
        <div class="search-bar">
            <input type="text" placeholder="Rechercher un match" id="searchInput">
            <button type="submit" id="searchButton"><i class="fas fa-search"></i></button>
        </div>
        <div class="profile">
            <img src="img/logo.png" alt="Profil">
        </div>
    </div>

    <div class="welcome">
        Calendrier des matchs
    </div>

    <div class="calendar-container">
        <div class="calendar-header">
            <div class="calendar-title">
                <i class="far fa-calendar-alt"></i>
                <span>Planning des matchs</span>
            </div>
            <button class="btn-add-match" onclick="openCreateMatchModal()">
                <i class="fas fa-plus"></i> Créer un match
            </button>
        </div>

        <!-- Filtres -->
        <div class="calendar-filters">
            <div class="filter-group">
                <label for="statusFilter">
                    <i class="fas fa-filter"></i> Statut:
                </label>
                <select id="statusFilter" class="filter-select">
                    <option value="all">Tous les matchs</option>
                    <option value="completed">Terminés</option>
                    <option value="pending">En attente</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="refereeFilter">
                    <i class="fas fa-user-tie"></i> Arbitre:
                </label>
                <select id="refereeFilter" class="filter-select">
                    <option value="all">Tous les arbitres</option>
                    <?php 
                    $uniqueReferees = [];
                    if (!empty($schedule) && isset($schedule['schedule'])) {
                        foreach ($schedule['schedule'] as $poolName => $games) {
                            foreach ($games as $game) {
                                if (!in_array($game['referee'], $uniqueReferees)) {
                                    $uniqueReferees[] = $game['referee'];
                                }
                            }
                        }
                        sort($uniqueReferees);
                        foreach ($uniqueReferees as $referee): ?>
                            <option value="<?= htmlspecialchars($referee) ?>"><?= htmlspecialchars($referee) ?></option>
                        <?php endforeach;
                    } ?>
                </select>
            </div>

            <div class="filter-group">
                <label for="poolFilter">
                    <i class="fas fa-layer-group"></i> Poule:
                </label>
                <select id="poolFilter" class="filter-select">
                    <option value="all">Toutes les poules</option>
                    <?php 
                    if (!empty($schedule) && isset($schedule['schedule'])) {
                        foreach (array_keys($schedule['schedule']) as $poolName): ?>
                            <option value="<?= htmlspecialchars($poolName) ?>"><?= htmlspecialchars($poolName) ?></option>
                        <?php endforeach;
                    } ?>
                </select>
            </div>

            <button class="reset-filters-btn" onclick="resetFilters()">
                <i class="fas fa-undo"></i> Réinitialiser
            </button>
        </div>

        <!-- Statistiques des filtres -->
        <div class="filter-stats">
            <span id="matchCount">0 match(s) affiché(s)</span>
            <span id="completedCount">0 terminé(s)</span>
            <span id="pendingCount">0 en attente</span>
        </div>

        <div class="calendar-items">
            <?php if (!empty($schedule) && isset($schedule['schedule'])): ?>
                <?php
                $allGames = [];
                foreach ($schedule['schedule'] as $poolName => $games) {
                    foreach ($games as $game) {
                        $game['poolName'] = $poolName;
                        $allGames[] = $game;
                    }
                }

                usort($allGames, function($a, $b) {
                    return strtotime($a['startTime']) - strtotime($b['startTime']);
                });
                ?>
                <?php foreach ($allGames as $game): ?>
                    <?php
                    $startTime = new DateTime($game['startTime']);
                    $formattedTime = $startTime->format('H:i');
                    //isCompleted 1 ou 0
                    $isCompleted = isset($game['isCompleted']) ? (bool)$game['isCompleted'] : false;
                    ?>
                    <div class="calendar-item clickable-match" 
                         data-match-id="<?= $game['Game_Id'] ?? $game['gameId'] ?>" 
                         data-tournament-id="<?= $_GET['tournament_id'] ?>"
                         data-status="<?= $isCompleted ? 'completed' : 'pending' ?>"
                         data-referee="<?= htmlspecialchars($game['referee']) ?>"
                         data-pool="<?= htmlspecialchars($game['poolName']) ?>">
                        
                        <div class="match-status-indicator">
                            <?php if ($isCompleted): ?>
                                <span class="status-badge completed">
                                    <i class="fas fa-check-circle"></i> Terminé
                                </span>
                            <?php else: ?>
                                <span class="status-badge pending">
                                    <i class="fas fa-clock"></i> En attente
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="time"><?= $formattedTime ?></div>
                        <div class="match">
                            <div class="team">
                                <img src="/api/placeholder/30/30" alt="Logo" class="team-logo">
                                <span><?= htmlspecialchars($game['team1']['name']) ?></span>
                            </div>
                            <div class="vs">
                                <?php if ($isCompleted && isset($game['Team1_Score']) && isset($game['Team2_Score'])): ?>
                                    <span class="score-display"><?= $game['Team1_Score'] ?> - <?= $game['Team2_Score'] ?></span>
                                <?php else: ?>
                                    vs
                                <?php endif; ?>
                            </div>
                            <div class="team">
                                <img src="/api/placeholder/30/30" alt="Logo" class="team-logo">
                                <span><?= htmlspecialchars($game['team2']['name']) ?></span>
                            </div>
                        </div>
                        <div class="referee">
                            <i class="fas fa-bullhorn"></i>
                            <span><?= htmlspecialchars($game['referee']) ?></span>
                        </div>
                        <div class="pool-info">
                            <span class="pool-name"><?= htmlspecialchars($game['poolName']) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-calendar">
                    <i class="far fa-calendar-alt empty-icon"></i>
                    <p>Aucun match planifié</p>
                    <button class="btn-add" onclick="openCreateMatchModal()">
                        <i class="fas fa-plus"></i> Créer le premier match
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal de création de match -->
<div class="modal-overlay" id="createMatchModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-plus"></i> Créer un nouveau match</h3>
            <button class="btn-close" onclick="closeCreateMatchModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="createMatchForm">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="matchDateTime">
                            <i class="fas fa-calendar-alt"></i> Date et heure
                        </label>
                        <input type="datetime-local" id="matchDateTime" name="start_time" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="team1Select">
                            <i class="fas fa-users"></i> Équipe 1
                        </label>
                        <select id="team1Select" name="Team1_Id" required>
                            <option value="">Sélectionner une équipe</option>
                            <?php if (!empty($teams)): ?>
                                <?php foreach ($teams as $team): ?>
                                    <option value="<?= $team['Team_Id'] ?>"><?= htmlspecialchars($team['name']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="vs-indicator">VS</div>

                    <div class="form-group">
                        <label for="team2Select">
                            <i class="fas fa-users"></i> Équipe 2
                        </label>
                        <select id="team2Select" name="Team2_Id" required>
                            <option value="">Sélectionner une équipe</option>
                            <?php if (!empty($teams)): ?>
                                <?php foreach ($teams as $team): ?>
                                    <option value="<?= $team['Team_Id'] ?>"><?= htmlspecialchars($team['name']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="refereeSelect">
                            <i class="fas fa-user-tie"></i> Arbitre
                        </label>
                        <select id="refereeSelect" name="Referee_Id" required>
                            <option value="">Sélectionner un arbitre</option>
                            <?php if (!empty($referees)): ?>
                                <?php foreach ($referees as $referee): ?>
                                    <option value="<?= $referee['Referee_Id'] ?>">
                                        <?= htmlspecialchars($referee['last_name'] . ' ' . $referee['first_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="poolSelect">
                            <i class="fas fa-layer-group"></i> Poule
                        </label>
                        <select id="poolSelect" name="Pool_Id" required>
                            <option value="">Sélectionner une poule</option>
                            <?php if (!empty($pools)): ?>
                                <?php foreach ($pools as $pool): ?>
                                    <option value="<?= $pool['Pool_Id'] ?>">
                                        <?= htmlspecialchars($pool['name'] ?? 'Poule ' . $pool['Pool_Id']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="fieldSelect">
                            <i class="fas fa-map-marker-alt"></i> Terrain (optionnel)
                        </label>
                        <select id="fieldSelect" name="Field_Id">
                            <option value="">Aucun terrain spécifique</option>
                            <?php if (!empty($fields)): ?>
                                <?php foreach ($fields as $field): ?>
                                    <option value="<?= $field['Field_Id'] ?>">
                                        <?= htmlspecialchars($field['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="form-notice">
                    <i class="fas fa-info-circle"></i>
                    <p>Le match sera créé avec un score initial de 0-0 et sera marqué comme "En attente".</p>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeCreateMatchModal()">
                    <i class="fas fa-times"></i> Annuler
                </button>
                <button type="submit" class="btn-save">
                    <i class="fas fa-plus"></i> Créer le match
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal de confirmation -->
<div class="modal-overlay" id="matchCreatedModal">
    <div class="modal-content small">
        <div class="modal-header">
            <h3><i class="fas fa-check-circle"></i> Match créé</h3>
        </div>
        <div class="modal-body">
            <p>Le match a été créé avec succès !</p>
        </div>
        <div class="modal-footer">
            <button class="btn-success" onclick="closeMatchCreatedModal()">
                <i class="fas fa-check"></i> OK
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const refereeFilter = document.getElementById('refereeFilter');
    const poolFilter = document.getElementById('poolFilter');

    // Gestion de la recherche
    searchInput.addEventListener('input', function() {
        applyFilters();
    });

    // Gestion des filtres
    statusFilter.addEventListener('change', applyFilters);
    refereeFilter.addEventListener('change', applyFilters);
    poolFilter.addEventListener('change', applyFilters);

    // Appliquer les filtres
    function applyFilters() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const refereeValue = refereeFilter.value;
        const poolValue = poolFilter.value;
        const items = document.querySelectorAll('.calendar-item');

        let visibleCount = 0;
        let completedCount = 0;
        let pendingCount = 0;

        items.forEach(item => {
            const team1 = item.querySelector('.team:first-of-type span').textContent.toLowerCase();
            const team2 = item.querySelector('.team:last-of-type span').textContent.toLowerCase();
            const referee = item.dataset.referee.toLowerCase();
            const status = item.dataset.status;
            const pool = item.dataset.pool;

            let showItem = true;

            // Filtre de recherche
            if (searchTerm && !team1.includes(searchTerm) && !team2.includes(searchTerm) && !referee.includes(searchTerm)) {
                showItem = false;
            }

            // Filtre de statut
            if (statusValue !== 'all' && status !== statusValue) {
                showItem = false;
            }

            // Filtre d'arbitre
            if (refereeValue !== 'all' && item.dataset.referee !== refereeValue) {
                showItem = false;
            }

            // Filtre de poule
            if (poolValue !== 'all' && pool !== poolValue) {
                showItem = false;
            }

            if (showItem) {
                item.style.display = 'flex';
                visibleCount++;
                if (status === 'completed') {
                    completedCount++;
                } else {
                    pendingCount++;
                }
            } else {
                item.style.display = 'none';
            }
        });

        // Mettre à jour les statistiques
        updateFilterStats(visibleCount, completedCount, pendingCount);

        // Gérer l'état vide
        const emptyState = document.querySelector('.empty-calendar');
        const calendarItems = document.querySelector('.calendar-items');
        
        if (visibleCount === 0 && items.length > 0) {
            if (!document.querySelector('.no-results')) {
                const noResults = document.createElement('div');
                noResults.className = 'no-results';
                noResults.innerHTML = `
                    <i class="fas fa-search empty-icon"></i>
                    <p>Aucun match ne correspond aux filtres sélectionnés</p>
                    <button class="btn-add" onclick="resetFilters()">
                        <i class="fas fa-undo"></i> Réinitialiser les filtres
                    </button>
                `;
                calendarItems.appendChild(noResults);
            }
        } else {
            const noResults = document.querySelector('.no-results');
            if (noResults) {
                noResults.remove();
            }
        }
    }

    function updateFilterStats(total, completed, pending) {
        document.getElementById('matchCount').textContent = `${total} match(s) affiché(s)`;
        document.getElementById('completedCount').textContent = `${completed} terminé(s)`;
        document.getElementById('pendingCount').textContent = `${pending} en attente`;
    }

    // Gestion des clics sur les matchs
    document.querySelectorAll('.clickable-match').forEach(item => {
        item.addEventListener('click', function() {
            const matchId = this.dataset.matchId;
            const tournamentId = this.dataset.tournamentId;
            window.location.href = `index.php?route=match-details&tournament_id=${tournamentId}&match_id=${matchId}`;
        });
    });

    // Initialiser les statistiques
    applyFilters();
});

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = 'all';
    document.getElementById('refereeFilter').value = 'all';
    document.getElementById('poolFilter').value = 'all';
    
    // Réappliquer les filtres
    const event = new Event('input');
    document.getElementById('searchInput').dispatchEvent(event);
}

function openCreateMatchModal() {
    document.getElementById('createMatchModal').classList.add('show');
    document.body.style.overflow = 'hidden';
    
    // Set default date/time to current time + 1 hour
    const now = new Date();
    now.setHours(now.getHours() + 1);
    const defaultDateTime = now.toISOString().slice(0, 16);
    document.getElementById('matchDateTime').value = defaultDateTime;
}

function closeCreateMatchModal() {
    document.getElementById('createMatchModal').classList.remove('show');
    document.body.style.overflow = 'auto';
    document.getElementById('createMatchForm').reset();
}

function closeMatchCreatedModal() {
    document.getElementById('matchCreatedModal').classList.remove('show');
    document.body.style.overflow = 'auto';
    // Reload page to show new match
    window.location.reload();
}

// Handle create match form submission
document.getElementById('createMatchForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const team1Id = formData.get('Team1_Id');
    const team2Id = formData.get('Team2_Id');
    
    // Validation: teams can't be the same
    if (team1Id === team2Id) {
        alert('Les deux équipes doivent être différentes');
        return;
    }
    
    // Convert datetime-local to ISO format
    const startTime = formData.get('start_time');
    const isoDateTime = new Date(startTime).toISOString();
    
    const matchData = {
        start_time: isoDateTime,
        Team1_Id: parseInt(team1Id),
        Team2_Id: parseInt(team2Id),
        Team1_Score: 0,
        Team2_Score: 0,
        is_completed: false,
        Referee_Id: parseInt(formData.get('Referee_Id')),
        Pool_Id: parseInt(formData.get('Pool_Id')),
        Tournament_Id: <?= $_GET['tournament_id'] ?>
    };
    
    // Add Field_Id if selected
    const fieldId = formData.get('Field_Id');
    if (fieldId) {
        matchData.Field_Id = parseInt(fieldId);
    }
    
    const submitBtn = this.querySelector('.btn-save');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Création...';
    
    try {
        const response = await fetch(`index.php?route=create-match&tournament_id=<?= $_GET['tournament_id'] ?>`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(matchData)
        });
        
        const result = await response.json();
        
        if (response.ok && result.success) {
            closeCreateMatchModal();
            setTimeout(() => {
                document.getElementById('matchCreatedModal').classList.add('show');
            }, 300);
        } else {
            throw new Error(result.error || 'Erreur lors de la création du match');
        }
    } catch (error) {
        console.error('Error creating match:', error);
        alert('Erreur lors de la création du match: ' + error.message);
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

// Prevent selecting same team for both sides
document.getElementById('team1Select').addEventListener('change', function() {
    const team2Select = document.getElementById('team2Select');
    const selectedTeam1 = this.value;
    
    // Re-enable all options in team2
    Array.from(team2Select.options).forEach(option => {
        option.disabled = false;
    });
    
    // Disable selected team1 in team2
    if (selectedTeam1) {
        const team2Option = team2Select.querySelector(`option[value="${selectedTeam1}"]`);
        if (team2Option) {
            team2Option.disabled = true;
        }
        
        // If team2 currently has the same value, reset it
        if (team2Select.value === selectedTeam1) {
            team2Select.value = '';
        }
    }
});

document.getElementById('team2Select').addEventListener('change', function() {
    const team1Select = document.getElementById('team1Select');
    const selectedTeam2 = this.value;
    
    // Re-enable all options in team1
    Array.from(team1Select.options).forEach(option => {
        option.disabled = false;
    });
    
    // Disable selected team2 in team1
    if (selectedTeam2) {
        const team1Option = team1Select.querySelector(`option[value="${selectedTeam2}"]`);
        if (team1Option) {
            team1Option.disabled = true;
        }
        
        // If team1 currently has the same value, reset it
        if (team1Select.value === selectedTeam2) {
            team1Select.value = '';
        }
    }
});
</script>

<style>
.pool-info {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 5px;
    min-width: 120px;
}

.pool-name {
    font-size: 12px;
    color: #666;
    font-weight: 500;
}

.clickable-match {
    cursor: pointer;
    transition: all 0.3s ease;
}

.clickable-match:hover {
    background-color: #e8f4ff !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(35, 44, 90, 0.15);
}

/* Styles pour les filtres */
.calendar-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    align-items: center;
    margin-bottom: 20px;
    padding: 20px;
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.filter-group {
    display: flex;
    align-items: center;
    gap: 8px;
}

.filter-group label {
    font-weight: 600;
    color: #333;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.filter-group label i {
    color: #232c5a;
}

.filter-select {
    padding: 8px 12px;
    border: 2px solid #e1e5e9;
    border-radius: 6px;
    font-size: 14px;
    background-color: white;
    color: #333;
    min-width: 150px;
    transition: border-color 0.3s ease;
}

.filter-select:focus {
    outline: none;
    border-color: #232c5a;
    box-shadow: 0 0 0 3px rgba(35, 44, 90, 0.1);
}

.reset-filters-btn {
    padding: 8px 15px;
    background-color: #6c757d;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: all 0.3s ease;
    margin-left: auto;
}

.reset-filters-btn:hover {
    background-color: #5a6268;
    transform: translateY(-1px);
}

/* Statistiques des filtres */
.filter-stats {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding: 10px 15px;
    background-color: #f8f9ff;
    border-radius: 8px;
    font-size: 13px;
    color: #666;
}

.filter-stats span {
    display: flex;
    align-items: center;
    gap: 5px;
}

#matchCount {
    font-weight: 600;
    color: #232c5a;
}

#completedCount {
    color: #2e7d32;
}

#pendingCount {
    color: #f57c00;
}

/* Indicateur de statut du match */
.match-status-indicator {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 1;
}

.calendar-item {
    position: relative;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 15px;
    font-size: 11px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.status-badge.completed {
    background-color: #e8f5e8;
    color: #2e7d32;
    border: 1px solid #c8e6c9;
}

.status-badge.pending {
    background-color: #fff3e0;
    color: #f57c00;
    border: 1px solid #ffcc80;
}

.status-badge i {
    font-size: 10px;
}

/* Score affiché dans le vs */
.score-display {
    font-weight: bold;
    color: #232c5a;
    background-color: #f8f9ff;
    padding: 4px 8px;
    border-radius: 5px;
    font-size: 13px;
}

/* État "Aucun résultat" */
.no-results {
    text-align: center;
    padding: 40px 20px;
    color: #666;
}

.no-results .empty-icon {
    font-size: 48px;
    color: #ccc;
    margin-bottom: 15px;
    display: block;
}

.no-results p {
    font-size: 16px;
    margin-bottom: 20px;
}

/* Create match button */
.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.btn-add-match {
    background: linear-gradient(135deg, #2e7d32, #1b5e20);
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(46, 125, 50, 0.3);
}

.btn-add-match:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(46, 125, 50, 0.4);
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

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 20px 25px;
    border-top: 1px solid #e0e0e0;
    background-color: #f8f9fa;
    border-radius: 0 0 15px 15px;
}

/* Form styles for create match modal */
.form-row {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
    align-items: end;
}

.form-group {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group label {
    font-weight: 600;
    color: #333;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-group label i {
    color: #232c5a;
    width: 16px;
    text-align: center;
}

.form-group input,
.form-group select {
    padding: 12px;
    border: 2px solid #e1e5e9;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.3s ease;
    background-color: white;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #232c5a;
    box-shadow: 0 0 0 3px rgba(35, 44, 90, 0.1);
}

.form-group select option:disabled {
    color: #ccc;
    background-color: #f5f5f5;
}

.vs-indicator {
    background: linear-gradient(135deg, #232c5a, #1a2147);
    color: white;
    padding: 12px 16px;
    border-radius: 25px;
    font-weight: bold;
    font-size: 14px;
    align-self: center;
    margin-bottom: 8px;
    white-space: nowrap;
}

.form-notice {
    background-color: #e3f2fd;
    border: 1px solid #90caf9;
    border-radius: 8px;
    padding: 15px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-top: 20px;
}

.form-notice i {
    color: #1976d2;
    margin-top: 2px;
    flex-shrink: 0;
}

.form-notice p {
    margin: 0;
    font-size: 13px;
    color: #1565c0;
    line-height: 1.4;
}

/* Modal buttons */
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

.btn-save:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
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

/* Empty state button */
.btn-add {
    background: linear-gradient(135deg, #232c5a, #1a2147);
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    text-decoration: none;
}

.btn-add:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(35, 44, 90, 0.3);
    color: white;
    text-decoration: none;
}

/* Responsive pour les filtres */
@media (max-width: 768px) {
    .calendar-filters {
        flex-direction: column;
        gap: 15px;
        align-items: stretch;
    }

    .filter-group {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }

    .filter-select {
        width: 100%;
        min-width: unset;
    }

    .reset-filters-btn {
        margin-left: 0;
        justify-content: center;
    }

    .filter-stats {
        flex-direction: column;
        gap: 8px;
        text-align: center;
    }

    .match-status-indicator {
        position: static;
        margin-bottom: 10px;
        text-align: center;
    }

    .calendar-item {
        padding-top: 10px;
    }

    /* Mobile responsive for modal and form */
    .calendar-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .form-row {
        flex-direction: column;
        gap: 15px;
    }
    
    .vs-indicator {
        order: -1;
        align-self: center;
        margin-bottom: 0;
    }
    
    .form-group {
        width: 100%;
    }

    .modal-content {
        width: 95%;
        margin: 10px;
        max-height: 95vh;
    }

    .modal-footer {
        flex-direction: column;
        gap: 10px;
    }

    .btn-cancel, .btn-save {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .calendar-filters {
        padding: 15px;
    }

    .filter-group label {
        font-size: 13px;
    }

    .filter-select {
        font-size: 13px;
        padding: 6px 10px;
    }

    .status-badge {
        font-size: 10px;
        padding: 3px 6px;
    }

    .btn-add-match {
        width: 100%;
        justify-content: center;
    }
    
    .modal-content {
        margin: 5px;
        width: calc(100% - 10px);
    }

    .modal-header {
        padding: 15px 20px;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-footer {
        padding: 15px 20px;
    }

    .form-group label {
        font-size: 13px;
    }

    .form-group input,
    .form-group select {
        padding: 10px;
        font-size: 14px;
    }

    .vs-indicator {
        padding: 10px 14px;
        font-size: 12px;
    }
}
</style>
