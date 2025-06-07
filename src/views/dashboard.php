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

                    var_dump($game);
                    ?>
                    <div class="calendar-item clickable-match" 
                         data-match-id="<?= $game['Game_Id'] ?? $game['gameId'] ?>" 
                         data-tournament-id="<?= $_GET['tournament_id'] ?>">
                        <div class="time"><?= $formattedTime ?></div>
                        <div class="match">
                            <div class="team">
                                <img src="<?= game['logo'] ?>" alt="Logo" class="team-logo">
                                <span><?= htmlspecialchars($game['team1']['name']) ?></span>
                            </div>
                            <div class="vs">vs</div>
                            <div class="team">
                                <img src="<?= game['logo'] ?>" alt="Logo" class="team-logo">
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
            
            <div class="category-filter">
                <button class="category-btn all active" data-category="all" data-target="teams">Toutes</button>
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                        <button class="category-btn <?= htmlspecialchars($category['name']) ?>" 
                                data-category="<?= htmlspecialchars($category['name']) ?>" 
                                data-target="teams">
                            <?= htmlspecialchars(strtoupper($category['name'])) ?>
                        </button>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div class="teams-list">
                <?php if (!empty($teams)): ?>
                    <?php foreach ($teams as $team): 
                        $teamCategory = $team['Category_Id'] ?? 'unknown';
                    ?>
                        <div class="team-item" data-category="<?= htmlspecialchars($teamCategory) ?>">
                            <img src="<?php echo $team['logo']; ?>" alt="Logo de l'équipe" style="width: 30px; height: 30px; border-radius: 50%;">
                            <span><?php echo $team['name']; ?></span>
                            <span class="badge <?= htmlspecialchars($teamCategory) ?>"><?= strtoupper($teamCategory) ?></span>
                        </div>
                    <?php endforeach; ?>
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
            
            teamItems.forEach(item => {
                if (selectedCategory === 'all' || item.dataset.category === selectedCategory) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
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

.category-btn.all:hover,
.category-btn.all.active {
    background-color: #1a2147;
    box-shadow: 0 0 8px rgba(35, 44, 90, 0.4);
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

/* Responsive pour les états vides */
@media (max-width: 768px) {
    .empty-state {
        padding: 30px 15px;
        min-height: 150px;
    }
    
    .empty-icon {
        font-size: 36px;
        margin-bottom: 15px;
    }
    
    .empty-message {
        font-size: 16px;
    }
    
    .empty-description {
        font-size: 13px;
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
