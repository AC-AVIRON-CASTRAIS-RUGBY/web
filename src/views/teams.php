<div class="main-content">
    <div class="header">
        <div class="search-bar">
            <input type="text" placeholder="Rechercher une équipe" id="searchInput">
            <button type="submit" id="searchButton"><i class="fas fa-search"></i></button>
        </div>
        <div class="profile">
            <img src="img/logo.png" alt="Profil">
        </div>
    </div>

    <div class="welcome">
        Gestion des équipes
    </div>

    <div class="teams-container">
        <div class="teams-header">
            <div class="teams-title">
                <i class="fas fa-users"></i>
                <span>Équipes du tournoi</span>
            </div>
            <button class="btn-add" id="addTeamBtn">
                Ajouter une équipe <i class="fas fa-plus"></i>
            </button>
        </div>

        <!-- Filtres par catégorie -->
        <div class="category-filter">
            <button class="category-btn all active" data-category="all">Toutes</button>
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <button class="category-btn <?= htmlspecialchars($category['name']) ?>" 
                            data-category="<?= htmlspecialchars($category['name']) ?>">
                        <?= htmlspecialchars(strtoupper($category['name'])) ?>
                    </button>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Liste des équipes -->
        <?php if (empty($teams)): ?>
            <div class="empty-state">
                <i class="fas fa-users empty-icon"></i>
                <p>Aucune équipe n'a été ajoutée à ce tournoi</p>
                <button class="btn-add" onclick="document.getElementById('addTeamBtn').click()">
                    Ajouter la première équipe
                </button>
            </div>
        <?php else: ?>
            <div class="teams-grid">
                <?php foreach ($teams as $team): 
                    $teamCategory = $team['age_category'] ?? 'unknown';
                ?>
                    <div class="team-card" data-category="<?= htmlspecialchars($teamCategory) ?>">
                        <div class="team-logo-section">
                            <img src="/api/teams/<?= $team['Team_Id'] ?>/logo" 
                                 alt="Logo de <?= htmlspecialchars($team['name']) ?>" 
                                 class="team-logo"
                                 onerror="this.src='img/default-team-logo.png'">
                        </div>
                        
                        <div class="team-info">
                            <h3 class="team-name"><?= htmlspecialchars($team['name']) ?></h3>
                            <div class="team-meta">
                                <span class="badge <?= htmlspecialchars($teamCategory) ?>">
                                    <?= htmlspecialchars(strtoupper($teamCategory)) ?>
                                </span>
                                <span class="team-id">ID: <?= $team['Team_Id'] ?></span>
                            </div>
                        </div>
                        
                        <div class="team-actions">
                            <button class="edit-btn" 
                                    onclick="editTeam(<?= $team['Team_Id'] ?>, '<?= htmlspecialchars($team['name']) ?>', '<?= htmlspecialchars($teamCategory) ?>')"
                                    title="Modifier l'équipe">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="delete-btn" 
                                    onclick="deleteTeam(<?= $team['Team_Id'] ?>, '<?= htmlspecialchars($team['name']) ?>')"
                                    title="Supprimer l'équipe">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des filtres de catégorie
    const categoryButtons = document.querySelectorAll('.category-btn');
    const teamCards = document.querySelectorAll('.team-card');
    
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Retirer active de tous les boutons
            categoryButtons.forEach(btn => btn.classList.remove('active'));
            // Ajouter active au bouton cliqué
            this.classList.add('active');
            
            const selectedCategory = this.dataset.category;
            
            teamCards.forEach(card => {
                if (selectedCategory === 'all' || card.dataset.category === selectedCategory) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});
</script>

<style>
<?php if (!empty($categories)): ?>
    <?php foreach ($categories as $index => $category): ?>
        .category-btn.<?= htmlspecialchars($category['name']) ?> {
            background-color: <?= getCategoryColor($index) ?>;
            color: <?= getCategoryTextColor($index) ?>;
        }
        
        .badge.<?= htmlspecialchars($category['name']) ?> { 
            background-color: <?= getCategoryColor($index) ?>; 
        }
    <?php endforeach; ?>
<?php endif; ?>
</style>

<?php
function getCategoryColor($index) {
    $colors = ['#ff6b6b', '#ffa726', '#42a5f5', '#66bb6a', '#ab47bc', '#78909c'];
    return $colors[$index % count($colors)];
}

function getCategoryTextColor($index) {
    $textColors = ['#fff', '#333', '#333', '#333', '#333', '#fff'];
    return $textColors[$index % count($textColors)];
}
?>
