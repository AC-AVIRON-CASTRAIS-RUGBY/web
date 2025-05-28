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
                </div>
            <?php endif; ?>
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
}
</style>
