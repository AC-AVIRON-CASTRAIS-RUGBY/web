<div class="main-content">
    <div class="header">
        <div class="search-bar">
            <input type="text" placeholder="Rechercher une équipe, un arbitre ou un match" id="searchInput">
            <button type="submit" id="searchButton"><i class="fas fa-search"></i></button>
        </div>
        <div class="profile">
            <img src="img/logo.png" alt="Profil">
        </div>
    </div>

    <div class="welcome">
        Bonjour, Sylvain
    </div>

    <div class="calendar-container">
        <div class="calendar-header">
            <div class="calendar-title">
                <i class="far fa-calendar-alt"></i>
                <span>Prochains matchs</span>
            </div>
            <a href="index.php?route=calendar&tournament_id=<?= $_GET['tournament_id'] ?>" class="btn-consult">Consulter</a>
        </div>

        <div class="calendar-items" id="calendarItems">
            <?php if (!empty($schedule) && isset($schedule['schedule'])): ?>
                <?php
                $allGames = [];
                // Extraire tous les matchs dans un tableau plat
                foreach ($schedule['schedule'] as $poolName => $games) {
                    foreach ($games as $game) {
                        $game['poolName'] = $poolName;
                        $allGames[] = $game;
                    }
                }

                // Trier les matchs par heure de début
                usort($allGames, function($a, $b) {
                    return strtotime($a['startTime']) - strtotime($b['startTime']);
                });

                // Afficher seulement les 5 prochains matchs
                $upcomingGames = array_slice($allGames, 0, 5);
                
                foreach ($upcomingGames as $game):
                    $startTime = new DateTime($game['startTime']);
                    $formattedTime = $startTime->format('H:i');
                    ?>
                    <div class="calendar-item clickable-match" 
                         data-match-id="<?= $game['Game_Id'] ?? $game['gameId'] ?>" 
                         data-tournament-id="<?= $_GET['tournament_id'] ?>">                        <div class="time"><?= $formattedTime ?></div>
                        <div class="match">
                            <div class="team">
                                <img src="<?= !empty($game['team1']['logo']) ? $game['team1']['logo'] : 'img/placeholder-team.svg' ?>" alt="Logo" class="team-logo">
                                <span><?= htmlspecialchars($game['team1']['name']) ?></span>
                            </div>
                            <div class="vs">vs</div>
                            <div class="team">
                                <img src="<?= !empty($game['team2']['logo']) ? $game['team2']['logo'] : 'img/placeholder-team.svg' ?>" alt="Logo" class="team-logo">
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
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="far fa-calendar-alt"></i>
                    </div>
                    <p class="empty-message">Aucun match planifié</p>
                    <p class="empty-description">Les prochains matchs apparaîtront ici une fois le calendrier établi</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="cards-container">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-users"></i>
                    <span>Équipes</span>
                </div>
                <a href="index.php?route=teams&tournament_id=<?= $_GET['tournament_id'] ?>" class="btn-add" id="addTeamBtn">
                    Ajouter <i class="fas fa-plus"></i>
                </a>
            </div>
            
            <div class="category-filter">                <button class="category-btn all active" data-category="all" data-target="teams">Toutes</button>
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                        <button class="category-btn <?= htmlspecialchars($category['name']) ?>" 
                                data-category="<?= htmlspecialchars($category['Category_Id'] ?? $category['id'] ?? $category['categoryId'] ?? 'unknown') ?>" 
                                data-target="teams">
                            <?= htmlspecialchars(strtoupper($category['name'])) ?>
                        </button>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
              <div class="teams-list">
                <?php if (!empty($teams)): ?>
                    <div class="teams-grid" id="teamsGrid">
                        <?php foreach ($teams as $team): 
                            $teamCategory = $team['Category_Id'] ?? 'unknown';
                        ?>
                            <div class="team-item" data-category="<?= htmlspecialchars($teamCategory) ?>">
                                <img src="<?php echo (!empty($team['logo'])) ? $team['logo'] : 'img/placeholder-team.svg'; ?>" alt="Logo de l'équipe" style="width: 30px; height: 30px; border-radius: 50%;">
                                <span><?php echo $team['name']; ?></span>
                                <span class="badge <?= htmlspecialchars($teamCategory) ?>"><?= strtoupper($teamCategory) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="pagination-container">
                        <div class="pagination" id="teamsPagination">
                            <!-- Pagination buttons will be added by JavaScript -->
                        </div>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <p class="empty-message">Aucune équipe inscrite</p>
                        <p class="empty-description">Commencez par ajouter des équipes à votre tournoi</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-gavel"></i>
                    <span>Arbitres</span>
                </div>
                <a href="index.php?route=referees&tournament_id=<?= $_GET['tournament_id'] ?>" class="btn-add" id="addRefereeBtn">
                    Ajouter <i class="fas fa-plus"></i>
                </a>
            </div>
            
            <?php
            // Filtrer les doublons d'arbitres (garder un seul par Referee_Id)
            $uniqueReferees = [];
            foreach ($referees as $r) {
                $uniqueReferees[$r['Referee_Id']] = $r;
            }
            $referees = array_values($uniqueReferees);
            ?>
            <div class="referees-list" id="refereesList">
                <?php if (!empty($referees)): ?>
                    <?php foreach ($referees as $referee): ?>
                        <div class="referee-item">
                            <div class="referee-info">
                                <div class="referee-name">
                                    <?php echo htmlspecialchars($referee['last_name']); ?> <?php echo htmlspecialchars($referee['first_name']); ?>
                                </div>
                                <div class="referee-stats">
                                    <?php 
                                    $gamesCount = $referee['games_count'];
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
                                </div>
                            </div>
                            <div class="edit-btn"><i class="fas fa-pen"></i></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-gavel"></i>
                        </div>
                        <p class="empty-message">Aucun arbitre assigné</p>
                        <p class="empty-description">Ajoutez des arbitres pour gérer vos matchs</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des clics sur les matchs
    document.querySelectorAll('.clickable-match').forEach(item => {
        item.addEventListener('click', function() {
            const matchId = this.dataset.matchId;
            const tournamentId = this.dataset.tournamentId;
            window.location.href = `index.php?route=match-details&tournament_id=${tournamentId}&match_id=${matchId}`;
        });
    });
      // Débogage des données de catégories et d'équipes
    console.log('Catégories:', <?= json_encode($categories) ?>);
    console.log('Équipes:', <?= json_encode($teams) ?>);
    
    // Configuration de la pagination
    const itemsPerPage = 6; // Nombre d'équipes par page
    let currentPage = 1;
    let filteredTeams = [...document.querySelectorAll('.team-item')];
      // Fonction pour gérer la pagination
    function displayTeams() {
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const teamsGrid = document.getElementById('teamsGrid');
        
        // Ajouter une animation de transition
        teamsGrid.style.opacity = '0';
        teamsGrid.style.transform = 'translateY(10px)';
        
        setTimeout(() => {
            // Masquer toutes les équipes
            filteredTeams.forEach(item => {
                item.style.display = 'none';
            });
            
            // Afficher uniquement les équipes de la page courante
            filteredTeams.slice(startIndex, endIndex).forEach(item => {
                item.style.display = 'flex';
            });
            
            // Animation de réapparition
            teamsGrid.style.opacity = '1';
            teamsGrid.style.transform = 'translateY(0)';
            
            // Mettre à jour la pagination
            updatePagination();
        }, 150);
    }
    
    // Mettre à jour les boutons de pagination
    function updatePagination() {
        const totalPages = Math.ceil(filteredTeams.length / itemsPerPage);
        const paginationContainer = document.getElementById('teamsPagination');
        paginationContainer.innerHTML = '';
        
        if (totalPages <= 1) {
            paginationContainer.style.display = 'none';
            return;
        }
        
        paginationContainer.style.display = 'flex';
        
        // Bouton précédent
        if (currentPage > 1) {
            const prevButton = document.createElement('button');
            prevButton.innerHTML = '<i class="fas fa-chevron-left"></i>';
            prevButton.classList.add('pagination-btn', 'prev');            prevButton.addEventListener('click', () => {
                currentPage--;
                paginationContainer.classList.add('pagination-loading');
                setTimeout(() => {
                    displayTeams();
                    paginationContainer.classList.remove('pagination-loading');
                }, 100);
            });
            paginationContainer.appendChild(prevButton);
        }
        
        // Numéros de page
        const maxButtons = 5; // Nombre maximum de boutons numérotés à afficher
        let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
        let endPage = Math.min(totalPages, startPage + maxButtons - 1);
        
        if (endPage - startPage + 1 < maxButtons) {
            startPage = Math.max(1, endPage - maxButtons + 1);
        }
        
        for (let i = startPage; i <= endPage; i++) {
            const pageButton = document.createElement('button');
            pageButton.textContent = i;
            pageButton.classList.add('pagination-btn');
            if (i === currentPage) {
                pageButton.classList.add('active');
            }            pageButton.addEventListener('click', () => {
                currentPage = i;
                paginationContainer.classList.add('pagination-loading');
                setTimeout(() => {
                    displayTeams();
                    paginationContainer.classList.remove('pagination-loading');
                }, 100);
            });
            paginationContainer.appendChild(pageButton);
        }
        
        // Bouton suivant
        if (currentPage < totalPages) {
            const nextButton = document.createElement('button');
            nextButton.innerHTML = '<i class="fas fa-chevron-right"></i>';
            nextButton.classList.add('pagination-btn', 'next');            nextButton.addEventListener('click', () => {
                currentPage++;
                paginationContainer.classList.add('pagination-loading');
                setTimeout(() => {
                    displayTeams();
                    paginationContainer.classList.remove('pagination-loading');
                }, 100);
            });
            paginationContainer.appendChild(nextButton);
        }
    }
    
    // Gestion des filtres de catégorie
    const categoryButtons = document.querySelectorAll('.category-btn[data-target="teams"]');
    const teamItems = document.querySelectorAll('.team-item');
    
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Retirer active de tous les boutons
            categoryButtons.forEach(btn => btn.classList.remove('active'));
            // Ajouter active au bouton cliqué
            this.classList.add('active');
            
            const selectedCategory = this.dataset.category;
            
            // Filtrer les équipes par catégorie
            if (selectedCategory === 'all') {
                filteredTeams = [...teamItems];
            } else {
                filteredTeams = [...teamItems].filter(item => item.dataset.category === selectedCategory);
            }
            
            // Réinitialiser à la première page et afficher les équipes
            currentPage = 1;
            displayTeams();
        });
    });
    
    // Initialiser l'affichage des équipes
    filteredTeams = [...teamItems];
    displayTeams();
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

.category-filter {
    display: flex;
    gap: 8px;
    margin-bottom: 15px;
    flex-wrap: wrap;
}

.category-btn.all {
    background-color: #232c5a;
    color: white;
}

.category-btn {
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: 500;
    background-color: #f0f0f0;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.category-btn:hover {
    background-color: #e0e0e0;
}

.category-btn.active {
    background-color: #232c5a;
    color: white;
}

<?php if (!empty($categories)): ?>
    <?php foreach ($categories as $index => $category): ?>
        .category-btn.<?= htmlspecialchars($category['name']) ?> {
            background-color: <?= $this->getCategoryColor($index) ?>;
            color: <?= $this->getCategoryTextColor($index) ?>;
        }
        
        .badge.<?= htmlspecialchars($category['name']) ?> { 
            background-color: <?= $this->getCategoryColor($index) ?>; 
        }
    <?php endforeach; ?>
<?php endif; ?>

.category-badge {
    margin-left: auto;
}

.badge {
    padding: 2px 6px;
    border-radius: 10px;
    font-size: 10px;
    font-weight: bold;
    color: white;
}

.badge.U6 { background-color: #ff6b6b; }
.badge.u8 { background-color: #ffa726; }
.badge.u10 { background-color: #42a5f5; }
.badge.u12 { background-color: #66bb6a; }
.badge.u14 { background-color: #ab47bc; }
.badge.unknown { background-color: #78909c; }

.calendar-item {
    position: relative;
}

.team-cell .badge {
    margin-left: 10px;
}

.referee-item {
    display: flex;
    align-items: center;
    padding: 15px;
    border-radius: 5px;
    background-color: white;
    transition: background-color 0.3s ease;
    justify-content: space-between;
}

.referee-info {
    display: flex;
    flex-direction: column;
    gap: 8px;
    flex: 1;
}

.referee-name {
    font-weight: 500;
    font-size: 15px;
    color: #333;
}

.referee-stats {
    display: flex;
    align-items: center;
    gap: 10px;
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
    transition: all 0.3s ease;
}

.games-count i {
    color: #232c5a;
    font-size: 11px;
}

.games-count.no-games {
    background-color: #fff5f5;
    color: #c53030;
}

.games-count.no-games i {
    color: #c53030;
}

.games-count.many-games {
    background-color: #f0fff4;
    color: #38a169;
    font-weight: 600;
}

.games-count.many-games i {
    color: #38a169;
}

.games-count .count {
    font-weight: 600;
    min-width: 15px;
    text-align: center;
}

/* Animation pour le chargement */
.games-count .count:empty::after {
    content: "...";
    opacity: 0.6;
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 0.6; }
    50% { opacity: 1; }
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

/* Styles pour les états vides */
.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
    text-align: center;
    color: #666;
    min-height: 200px;
}

.empty-icon {
    font-size: 48px;
    color: #ccc;
    margin-bottom: 20px;
}

.empty-message {
    font-size: 18px;
    font-weight: 500;
    margin: 0 0 8px 0;
    color: #333;
}

.empty-description {
    font-size: 14px;
    margin: 0;
    color: #999;
    max-width: 300px;
    line-height: 1.4;
}

/* Style spécifique pour l'état vide du calendrier */
.calendar-items .empty-state {
    min-height: 150px;
    padding: 30px 20px;
}

.calendar-items .empty-icon {
    font-size: 36px;
}

/* Styles pour la pagination */
.pagination-container {
    display: flex;
    justify-content: center;
    margin-top: 25px;
    margin-bottom: 10px;
}

.pagination {
    display: flex;
    gap: 10px;
    align-items: center;
    padding: 12px 16px;
    background: linear-gradient(145deg, #f8f9fa, #e9ecef);
    border-radius: 40px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.07), 
                inset 0 -2px 5px rgba(255, 255, 255, 0.7), 
                inset 0 2px 5px rgba(0, 0, 0, 0.05);
    position: relative;
    overflow: hidden;
}

.pagination::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%);
    opacity: 0.6;
    pointer-events: none;
}

.pagination-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(145deg, #ffffff, #f0f0f0);
    border: none;
    color: #495057;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1), 
                inset 0 -2px 5px rgba(0, 0, 0, 0.05), 
                inset 0 2px 5px rgba(255, 255, 255, 0.7);
    position: relative;
    overflow: hidden;
    -webkit-tap-highlight-color: transparent;
}

.pagination-btn::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255, 255, 255, 0.4);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    opacity: 0;
    transition: width 0.6s ease-out, height 0.6s ease-out, opacity 0.6s ease-out;
}

.pagination-btn:active::after {
    width: 120px;
    height: 120px;
    opacity: 1;
    transition: width 0s, height 0s, opacity 0.3s ease-out;
}

.pagination-btn:hover {
    background: linear-gradient(145deg, #232c5a, #2d3875);
    color: white;
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 5px 15px rgba(35, 44, 90, 0.4),
                inset 0 -2px 5px rgba(0, 0, 0, 0.2),
                inset 0 2px 5px rgba(255, 255, 255, 0.2);
}

.pagination-btn.active {
    background: linear-gradient(145deg, #232c5a, #2d3875);
    color: white;
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(35, 44, 90, 0.4),
                inset 0 -2px 5px rgba(0, 0, 0, 0.2),
                inset 0 2px 5px rgba(255, 255, 255, 0.2);
    position: relative;
}

.pagination-btn.active::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: 50%;
    border: 2px solid rgba(255, 255, 255, 0.5);
    animation: pulse-border 1.5s infinite;
}

@keyframes pulse-border {
    0% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.1); opacity: 0.7; }
    100% { transform: scale(1); opacity: 1; }
}

.pagination-btn.prev,
.pagination-btn.next {
    font-size: 12px;
    width: 45px;
    height: 45px;
    background: linear-gradient(145deg, #232c5a, #2d3875);
    color: white;
    font-weight: bold;
}

.pagination-btn.prev:hover,
.pagination-btn.next:hover {
    background: linear-gradient(145deg, #1a2244, #232c5a);
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 7px 20px rgba(26, 34, 68, 0.5);
}

/* Mise en page des équipes en grille */
.teams-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 16px;
    margin-top: 15px;
    transition: opacity 0.3s ease, transform 0.3s ease;
}

/* Style pour les éléments d'équipe */
.team-item {
    display: flex;
    align-items: center;
    padding: 14px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    gap: 12px;
    position: relative;
    overflow: hidden;
}

.team-item:hover {
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.12);
    transform: translateY(-3px);
}

.team-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 5px;
    height: 100%;
    background-color: #232c5a;
    opacity: 0.7;
}

.team-item img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #f8f9fa;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.team-item span {
    flex: 1;
    font-weight: 500;
    color: #333;
    font-size: 14px;
}

.team-item .badge {
    font-size: 10px;
    padding: 4px 8px;
    border-radius: 20px;
    font-weight: 600;
    text-transform: uppercase;
    background-color: #e9ecef;
    color: #495057;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Animation de chargement pour la pagination */
@keyframes pulseAnimation {
    0% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.05); opacity: 0.8; }
    100% { transform: scale(1); opacity: 1; }
}

.pagination-loading {
    animation: pulseAnimation 1.5s infinite ease-in-out;
}

@media (max-width: 768px) {
    .teams-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php
// Fonction helper pour les couleurs de catégories
function getCategoryColor($index) {
    $colors = ['#ff6b6b', '#ffa726', '#42a5f5', '#66bb6a', '#ab47bc', '#78909c'];
    return $colors[$index % count($colors)];
}

function getCategoryTextColor($index) {
    $textColors = ['#fff', '#333', '#333', '#333', '#333', '#fff'];
    return $textColors[$index % count($textColors)];
}
?>
