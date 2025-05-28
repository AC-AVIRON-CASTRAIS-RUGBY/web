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

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-user-tie"></i>
                <span>Arbitres du tournoi</span>
            </div>
            <button class="btn-add" id="addRefereeBtn">
                Ajouter un arbitre <i class="fas fa-plus"></i>
            </button>
        </div>
        
        <div class="referees-list">
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
                        <div class="referee-actions">
                            <button class="edit-btn" title="Modifier l'arbitre">
                                <i class="fas fa-pen"></i>
                            </button>
                            <button class="delete-btn" title="Supprimer l'arbitre">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-user-tie empty-icon"></i>
                    <p>Aucun arbitre dans ce tournoi</p>
                    <button class="btn-add" onclick="document.getElementById('addRefereeBtn').click()">
                        Ajouter le premier arbitre <i class="fas fa-plus"></i>
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.referee-item {
    display: flex;
    align-items: center;
    padding: 15px;
    border-radius: 8px;
    background-color: white;
    transition: all 0.3s ease;
    justify-content: space-between;
    border: 1px solid #e8ecff;
}

.referee-item:hover {
    background-color: #f8f9ff;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(35, 44, 90, 0.08);
}

.referee-item:nth-child(even) {
    background-color: #fafbff;
}

.referee-item:nth-child(even):hover {
    background-color: #f8f9ff;
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

.referee-actions {
    display: flex;
    gap: 8px;
    align-items: center;
}

@media (max-width: 768px) {
    .referee-info {
        gap: 5px;
    }
    
    .games-count {
        font-size: 11px;
        padding: 3px 6px;
    }
    
    .referee-actions {
        flex-direction: column;
        gap: 5px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion de la recherche
    document.getElementById('searchInput').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const items = document.querySelectorAll('.referee-item');
        
        items.forEach(item => {
            const refereeName = item.querySelector('.referee-name').textContent.toLowerCase();
            
            if (refereeName.includes(searchTerm)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });
});
</script>
