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

    <div class="teams-container" data-tournament-id="<?= $_GET['tournament_id'] ?>">
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
                            <?php if (!empty($team['logo'])): ?>
                                <img src="<?= htmlspecialchars($team['logo']) ?>" 
                                     alt="Logo de <?= htmlspecialchars($team['name']) ?>" 
                                     class="team-logo">
                            <?php else: ?>
                                <div class="team-logo-placeholder">
                                    <?= strtoupper(substr(htmlspecialchars($team['name']), 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="team-info">
                            <h3 class="team-name"><?= htmlspecialchars($team['name']) ?></h3>
                            <div class="team-meta">
                                <span class="badge <?= htmlspecialchars($teamCategory) ?>">
                                    <?= htmlspecialchars(strtoupper($teamCategory)) ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="team-actions">
                            <button class="edit-btn" 
                                    onclick="openEditTeamModal(
                                        <?= $team['Team_Id'] ?>,
                                        '<?= addslashes($team['name']) ?>',
                                        '<?= htmlspecialchars($teamCategory) ?>',
                                        '<?= htmlspecialchars($team['logo'] ?? '') ?>'
                                    )"
                                    title="Modifier l'équipe">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="delete-btn" 
                                    onclick="deleteTeam(<?= $team['Team_Id'] ?>, '<?= addslashes($team['name']) ?>')"
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

<!-- Modal de modification d'équipe -->
<div id="editTeamModal" class="modal hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-edit"></i> Modifier l'équipe</h3>
            <button class="modal-close" onclick="closeEditTeamModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="editTeamForm" class="modal-form">
            <input type="hidden" id="editTeamId" name="teamId">
            
            <div class="form-group">
                <label for="editTeamName"><i class="fas fa-tag"></i> Nom de l'équipe</label>
                <input type="text" id="editTeamName" name="teamName" required maxlength="50">
            </div>
            
            <div class="form-group">
                <label for="editTeamCategory"><i class="fas fa-tags"></i> Catégorie d'âge</label>
                <select id="editTeamCategory" name="age_category">
                    <option value="">Sélectionner une catégorie</option>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat['name']) ?>">
                                <?= htmlspecialchars(strtoupper($cat['name'])) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="editTeamLogo"><i class="fas fa-image"></i> Logo de l'équipe</label>
                <input type="file" id="editTeamLogo" name="team_logo" accept="image/*">
                <small class="form-hint">Formats acceptés : JPG, PNG, GIF, WEBP (max 5MB)</small>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn-cancel" onclick="closeEditTeamModal()">
                    <i class="fas fa-times"></i> Annuler
                </button>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Sauvegarder
                </button>
            </div>
        </form>
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

// Fonctions pour le modal de modification
function openEditTeamModal(id, name, category, logo) {
    document.getElementById('editTeamId').value = id;
    document.getElementById('editTeamName').value = name;
    document.getElementById('editTeamCategory').value = category;
    document.getElementById('editTeamLogo').value = '';
    document.getElementById('editTeamModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditTeamModal() {
    document.getElementById('editTeamModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Soumission du formulaire de modification
document.getElementById('editTeamForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const form = e.target;
    const submitBtn = form.querySelector('.btn-submit');
    const originalText = submitBtn.innerHTML;
    
    // Désactiver le bouton et afficher le loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sauvegarde...';
    
    try {
        const teamId = form.teamId.value;
        const name = form.teamName.value.trim();
        const category = form.age_category.value;
        const fileInput = form.team_logo;
        const tournamentId = document.querySelector('.teams-container').dataset.tournamentId;
        
        let logoUrl = null;
        
        // Si un nouveau fichier est sélectionné, l'uploader d'abord
        if (fileInput.files.length > 0) {
            const formData = new FormData();
            formData.append('image', fileInput.files[0]);
            
            const uploadResponse = await fetch('http://localhost:3000/api/upload/image', {
                method: 'POST',
                body: formData
            });
            
            if (!uploadResponse.ok) {
                const errorData = await uploadResponse.json();
                throw new Error('Erreur upload: ' + (errorData.message || errorData.error || 'Erreur inconnue'));
            }
            
            const uploadResult = await uploadResponse.json();
            logoUrl = uploadResult.url;
        }
        
        // Préparer les données de l'équipe
        const teamData = { name };
        if (category) teamData.age_category = category;
        if (logoUrl) teamData.logo = logoUrl;
        
        // Envoyer la requête PUT pour modifier l'équipe
        const response = await fetch(`http://localhost:3000/api/teams/${tournamentId}/teams/${teamId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: {
                "name": teamData.name,
                "logo": teamData.logo,
                "age_category": teamData.age_category
            }
        });
        
        if (response.ok) {
            // Succès - recharger la page
            window.location.reload();
        } else {
            const errorData = await response.text();
            throw new Error('Erreur mise à jour: ' + errorData);
        }
        
    } catch (error) {
        alert('Erreur: ' + error.message);
        console.error('Erreur lors de la modification:', error);
    } finally {
        // Réactiver le bouton
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

// Fonction pour supprimer une équipe (à implémenter si nécessaire)
function deleteTeam(teamId, teamName) {
    if (confirm(`Êtes-vous sûr de vouloir supprimer l'équipe "${teamName}" ?`)) {
        // TODO: Implémenter la suppression
        console.log('Supprimer équipe:', teamId);
    }
}
</script>
