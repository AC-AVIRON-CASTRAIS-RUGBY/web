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
        Gestion des équipes et des catégories
    </div>

    <div class="teams-container" data-tournament-id="<?= $_GET['tournament_id'] ?>">
        <!-- Navigation par onglets -->
        <div class="tabs-container">
            <div class="tabs-nav">
                <button class="tab-btn active" data-tab="teams">
                    <i class="fas fa-users"></i> Équipes
                </button>
                <button class="tab-btn" data-tab="categories">
                    <i class="fas fa-tags"></i> Catégories
                </button>
            </div>
        </div>

        <!-- Onglet Équipes -->
        <div class="tab-content active" id="teams-tab">
            <div class="teams-header">
                <div class="teams-title">
                    <i class="fas fa-users"></i>
                    <span>Équipes du tournoi</span>
                </div>
                <button class="btn-add" id="addTeamBtn">
                    Ajouter une équipe <i class="fas fa-plus"></i>
                </button>
            </div>

            <!-- Filtres par catégorie -->            <div class="category-filter">
                <button class="category-btn all active" data-category="all">Toutes</button>
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                        <button class="category-btn <?= htmlspecialchars($category['name']) ?>" 
                                data-category="<?= htmlspecialchars($category['Category_Id'] ?? $category['id'] ?? $category['categoryId'] ?? 'unknown') ?>">
                            <?= htmlspecialchars(strtoupper($category['name'])) ?>
                        </button>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Liste des équipes -->
            <?php if (empty($teams)): ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <p class="empty-message">Aucune équipe n'a été ajoutée à ce tournoi</p>
                    <p class="empty-description">Commencez par ajouter des équipes pour organiser votre tournoi</p>
                </div>
            <?php else: ?>
                <div class="teams-grid">
                    <?php foreach ($teams as $team): 
                        $teamCategory = $team['Category_Id'] ?? 'unknown';
                    ?>
                        <div class="team-card" data-category="<?= htmlspecialchars($teamCategory) ?>">
                            <div class="team-header">
                                <?php if (!empty($team['logo'])): ?>
                                    <img src="<?= htmlspecialchars($team['logo']) ?>" alt="Logo" class="team-logo">
                                <?php else: ?>
                                    <div class="team-logo-placeholder">
                                        <?= strtoupper(substr($team['name'], 0, 2)) ?>
                                    </div>
                                <?php endif; ?>
                                <div class="team-info">
                                    <h3><?= htmlspecialchars($team['name']) ?></h3>
                                    <?php if (!empty($teamCategory) && $teamCategory !== 'unknown'): ?>
                                        <span class="team-category"><?= strtoupper($teamCategory) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="team-actions">
                                <button class="btn-edit" onclick="editTeam(<?= $team['Team_Id'] ?>, '<?= htmlspecialchars(addslashes($team['name'])) ?>')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-delete" onclick="deleteTeam(<?= $team['Team_Id'] ?>, '<?= htmlspecialchars(addslashes($team['name'])) ?>')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Onglet Catégories -->
        <div class="tab-content" id="categories-tab">
            <div class="categories-header">
                <div class="categories-title">
                    <i class="fas fa-tags"></i>
                    <span>Catégories du tournoi</span>
                </div>
                <button class="btn-add" id="addCategoryBtn">
                    Ajouter une catégorie <i class="fas fa-plus"></i>
                </button>
            </div>

            <!-- Liste des catégories -->
            <div class="categories-list" id="categoriesList">
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                        <div class="category-card">
                            <div class="category-header">
                                <h3><?= htmlspecialchars($category['name']) ?></h3>
                                <div class="category-actions">
                                    <button class="btn-edit-cat" 
                                            onclick="editCategory(<?= $category['Category_Id'] ?>, <?= htmlspecialchars(json_encode($category)) ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-delete-cat" onclick="deleteCategory(<?= $category['Category_Id'] ?>, '<?= htmlspecialchars(addslashes($category['name'])) ?>')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="category-info">
                                <div class="age-range">
                                    <i class="fas fa-birthday-cake"></i>
                                    <span><?= $category['age_min'] ?? 0 ?> - <?= $category['age_max'] ?? 0 ?> ans</span>
                                </div>
                                <?php if (!empty($category['description'])): ?>
                                    <div class="category-description">
                                        <i class="fas fa-info-circle"></i>
                                        <span><?= htmlspecialchars($category['description']) ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if (isset($category['game_duration'])): ?>
                                    <div class="game-duration">
                                        <i class="fas fa-clock"></i>
                                        <span><?= $category['game_duration'] ?> min par match</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-tags"></i>
                        </div>
                        <p class="empty-message">Aucune catégorie n'a été créée</p>
                        <p class="empty-description">Créez des catégories pour organiser vos équipes par âge</p>
                        <button class="btn-add" onclick="document.getElementById('addCategoryBtn').click()">
                            Créer la première catégorie
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour ajouter/modifier une équipe -->
<div id="teamModal" class="modal hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="teamModalTitle">
                <i class="fas fa-users"></i> Modifier l'équipe
            </h3>
            <button class="modal-close" onclick="closeTeamModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="teamForm" class="modal-form">
            <input type="hidden" id="teamId" name="teamId" value="">
            
            <div class="form-group">
                <label for="teamName">
                    <i class="fas fa-users"></i> Nom de l'équipe *
                </label>
                <input type="text" 
                       id="teamName" 
                       name="name" 
                       placeholder="Nom de l'équipe..." 
                       required 
                       maxlength="100">
            </div>

            <div class="form-group">
                <label for="teamCategory">
                    <i class="fas fa-tag"></i> Catégorie
                </label>
                <select id="teamCategory" name="Category_Id">
                    <option value="">Sélectionner une catégorie...</option>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category['Category_Id']) ?>">
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="teamLogo">
                    <i class="fas fa-image"></i> Logo de l'équipe
                </label>
                <input type="file" 
                       id="teamLogo" 
                       name="logo" 
                       accept="image/*">
                <div class="input-hint">Formats acceptés: JPG, PNG, GIF (max 5MB)</div>
                <div id="currentLogo" class="current-logo" style="display: none;">
                    <img id="currentLogoImg" src="" alt="Logo actuel" style="width: 50px; height: 50px; border-radius: 50%; margin-top: 10px;">
                    <span>Logo actuel</span>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn-cancel" onclick="closeTeamModal()">
                    <i class="fas fa-times"></i> Annuler
                </button>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Modifier
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal pour ajouter/modifier une catégorie -->
<div id="categoryModal" class="modal hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="categoryModalTitle">
                <i class="fas fa-tags"></i> Nouvelle catégorie
            </h3>
            <button class="modal-close" onclick="closeCategoryModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="categoryForm" class="modal-form">
            <input type="hidden" id="categoryId" name="categoryId" value="">
            
            <div class="form-group">
                <label for="categoryName">
                    <i class="fas fa-tag"></i> Nom de la catégorie *
                </label>
                <input type="text" 
                       id="categoryName" 
                       name="name" 
                       placeholder="Ex: U10, Juniors, Seniors..." 
                       required 
                       maxlength="50">
                <div class="input-hint">Le nom doit être unique dans ce tournoi</div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="ageMin">
                        <i class="fas fa-child"></i> Âge minimum
                    </label>
                    <input type="number" 
                           id="ageMin" 
                           name="age_min" 
                           min="0" 
                           max="100" 
                           value="0">
                </div>
                
                <div class="form-group">
                    <label for="ageMax">
                        <i class="fas fa-user"></i> Âge maximum
                    </label>
                    <input type="number" 
                           id="ageMax" 
                           name="age_max" 
                           min="0" 
                           max="100" 
                           value="0">
                </div>
            </div>

            <div class="form-group">
                <label for="gameDuration">
                    <i class="fas fa-clock"></i> Durée des matchs (minutes)
                </label>
                <input type="number" 
                       id="gameDuration" 
                       name="game_duration" 
                       min="1" 
                       max="120" 
                       value="10">
                <div class="input-hint">Durée par défaut des matchs pour cette catégorie</div>
            </div>

            <div class="form-group">
                <label for="categoryDescription">
                    <i class="fas fa-info-circle"></i> Description (optionnel)
                </label>
                <textarea id="categoryDescription" 
                          name="description" 
                          placeholder="Description de la catégorie..." 
                          rows="3" 
                          maxlength="255"></textarea>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn-cancel" onclick="closeCategoryModal()">
                    <i class="fas fa-times"></i> Annuler
                </button>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> <span id="submitButtonText">Créer</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Store categories data for easy access
const categoriesData = <?= json_encode($categories ?? []) ?>;
const teamsData = <?= json_encode($teams ?? []) ?>;

document.addEventListener('DOMContentLoaded', function() {
    // Gestion des onglets
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.dataset.tab;
            
            // Retirer active de tous les boutons et contenus
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Ajouter active au bouton cliqué et au contenu correspondant
            this.classList.add('active');
            document.getElementById(targetTab + '-tab').classList.add('active');
        });
    });

    // Gestion des filtres de catégorie pour les équipes
    const categoryButtons = document.querySelectorAll('.category-btn');
    const teamCards = document.querySelectorAll('.team-card');
    
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            categoryButtons.forEach(btn => btn.classList.remove('active'));
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

    // Gestion de l'ajout de catégorie
    document.getElementById('addCategoryBtn')?.addEventListener('click', function() {
        openCategoryModal();
    });

    // Gestion de l'ajout d'équipe
    document.getElementById('addTeamBtn')?.addEventListener('click', function() {
        openTeamModal();
    });

    // Gestion du formulaire de catégorie
    document.getElementById('categoryForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        submitCategoryForm();
    });

    // Gestion du formulaire d'équipe
    document.getElementById('teamForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        submitTeamForm();
    });
});

function openTeamModal(teamId, teamData) {
    const modal = document.getElementById('teamModal');
    const form = document.getElementById('teamForm');
    const title = document.getElementById('teamModalTitle');
    const submitBtn = form.querySelector('.btn-submit');
    
    // Reset form
    form.reset();
    document.getElementById('teamId').value = teamId || '';
    
    if (teamId && teamData) {
        // Editing mode
        title.innerHTML = '<i class="fas fa-edit"></i> Modifier l\'équipe';
        submitBtn.innerHTML = '<i class="fas fa-save"></i> Modifier';
        
        // Fill form with team data
        document.getElementById('teamName').value = teamData.name || '';
        
        // Sélectionner la catégorie par son ID
        if (teamData.Category_Id) {
            document.getElementById('teamCategory').value = teamData.Category_Id;
        }
        
        // Show current logo if exists
        const currentLogo = document.getElementById('currentLogo');
        const currentLogoImg = document.getElementById('currentLogoImg');
        
        if (teamData.logo) {
            currentLogoImg.src = teamData.logo;
            currentLogo.style.display = 'block';
        } else {
            currentLogo.style.display = 'none';
        }
    } else {
        // Adding mode
        title.innerHTML = '<i class="fas fa-plus"></i> Ajouter une équipe';
        submitBtn.innerHTML = '<i class="fas fa-save"></i> Ajouter';
        
        // Hide current logo section
        document.getElementById('currentLogo').style.display = 'none';
    }
    
    modal.classList.remove('hidden');
}

function closeTeamModal() {
    document.getElementById('teamModal').classList.add('hidden');
}

async function submitTeamForm() {
    const form = document.getElementById('teamForm');
    const formData = new FormData(form);
    const submitBtn = form.querySelector('.btn-submit');
    const tournamentId = document.querySelector('.teams-container').dataset.tournamentId;
    const teamId = document.getElementById('teamId').value;
    const isEditing = !!teamId;
    
    // Disable submit button
    submitBtn.disabled = true;
    submitBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> ${isEditing ? 'Modification...' : 'Ajout...'}`;
    
    try {
        // Debug des données du formulaire
        console.log('Form data before processing:');
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }

        // Récupérer l'ID de catégorie sélectionné
        const selectedCategoryId = formData.get('age_category');
        console.log('Selected category ID:', selectedCategoryId);

        // Préparer les données
        const teamName = formData.get('name');
        
        // Créer un nouveau FormData avec les bonnes clés
        const apiFormData = new FormData();
        apiFormData.append('team_name', teamName);
        
        // Ajouter l'ID de catégorie directement si sélectionné
        if (selectedCategoryId && selectedCategoryId !== '') {
            apiFormData.append('category_id', selectedCategoryId);
        }
        
        // Ajouter le logo si présent
        const logoFile = formData.get('logo');
        if (logoFile && logoFile.size > 0) {
            apiFormData.append('logo', logoFile);
        }

        if (isEditing) {
            apiFormData.append('team_id', teamId);
        }

        console.log('API Form data:');
        for (let [key, value] of apiFormData.entries()) {
            console.log(key, value);
        }

        const route = isEditing ? 
            `index.php?route=update-team&tournament_id=${tournamentId}&team_id=${teamId}` : 
            `index.php?route=create-team&tournament_id=${tournamentId}`;
            
        console.log('Making request to:', route);
        
        const response = await fetch(route, {
            method: 'POST',
            body: apiFormData
        });

        const result = await response.json();
        
        console.log('Response status:', response.status);
        console.log('Response data:', result);

        if (response.ok && result.success) {
            closeTeamModal();
            alert(`Équipe ${isEditing ? 'modifiée' : 'ajoutée'} avec succès !`);
            window.location.reload();
        } else {
            throw new Error(result.error || `Erreur lors de ${isEditing ? 'la modification' : 'l\'ajout'} de l'équipe`);
        }

    } catch (error) {
        console.error('Error with team:', error);
        alert(`Erreur lors de ${isEditing ? 'la modification' : 'l\'ajout'} de l'équipe: ` + error.message);
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = `<i class="fas fa-save"></i> ${isEditing ? 'Modifier' : 'Ajouter'}`;
    }
}

function openCategoryModal(categoryId = null, categoryData = null) {
    const modal = document.getElementById('categoryModal');
    const title = document.getElementById('categoryModalTitle');
    const submitText = document.getElementById('submitButtonText');
    const form = document.getElementById('categoryForm');
    
    // Reset form
    form.reset();
    document.getElementById('categoryId').value = categoryId || '';
    
    if (categoryId && categoryData) {
        // Editing mode - pre-fill form
        title.innerHTML = '<i class="fas fa-edit"></i> Modifier la catégorie';
        submitText.textContent = 'Modifier';
        
        // Fill form with category data
        document.getElementById('categoryName').value = categoryData.name || '';
        document.getElementById('ageMin').value = categoryData.age_min || 0;
        document.getElementById('ageMax').value = categoryData.age_max || 0;
        document.getElementById('gameDuration').value = categoryData.game_duration || 10;
        document.getElementById('categoryDescription').value = categoryData.description || '';
    } else {
        // Creation mode
        title.innerHTML = '<i class="fas fa-tags"></i> Nouvelle catégorie';
        submitText.textContent = 'Créer';
        
        // Set default values
        document.getElementById('ageMin').value = 0;
        document.getElementById('ageMax').value = 0;
        document.getElementById('gameDuration').value = 10;
    }
    
    modal.classList.remove('hidden');
}

function closeCategoryModal() {
    document.getElementById('categoryModal').classList.add('hidden');
}

async function submitCategoryForm() {
    const form = document.getElementById('categoryForm');
    const formData = new FormData(form);
    const submitBtn = form.querySelector('.btn-submit');
    const tournamentId = document.querySelector('.teams-container').dataset.tournamentId;
    const categoryId = document.getElementById('categoryId').value;
    const isEditing = !!categoryId;
    
    // Disable submit button
    submitBtn.disabled = true;
    submitBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> ${isEditing ? 'Modification...' : 'Création...'}`;
    
    try {
        const categoryData = {
            name: formData.get('name'),
            age_min: parseInt(formData.get('age_min')) || 0,
            age_max: parseInt(formData.get('age_max')) || 0,
            description: formData.get('description') || '',
            game_duration: parseInt(formData.get('game_duration')) || 10
        };

        let response;
        if (isEditing) {
            // Update existing category
            response = await fetch(`index.php?route=update-category&tournament_id=${tournamentId}&category_id=${categoryId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(categoryData)
            });
        } else {
            // Create new category
            response = await fetch(`index.php?route=create-category&tournament_id=${tournamentId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(categoryData)
            });
        }

        const result = await response.json();

        if (response.ok && result.success) {
            closeCategoryModal();
            alert(`Catégorie ${isEditing ? 'modifiée' : 'créée'} avec succès !`);
            window.location.reload();
        } else {
            throw new Error(result.error || `Erreur lors de la ${isEditing ? 'modification' : 'création'} de la catégorie`);
        }

    } catch (error) {
        console.error('Error with category:', error);
        alert(`Erreur lors de la ${isEditing ? 'modification' : 'création'} de la catégorie: ` + error.message);
    } finally {
        submitBtn.disabled = false;
        const submitText = document.getElementById('submitButtonText');
        submitBtn.innerHTML = `<i class="fas fa-save"></i> <span id="submitButtonText">${isEditing ? 'Modifier' : 'Créer'}</span>`;
    }
}

function editCategory(categoryId, categoryData) {
    openCategoryModal(categoryId, categoryData);
}

function deleteCategory(categoryId, categoryName) {
    if (confirm(`Êtes-vous sûr de vouloir supprimer la catégorie "${categoryName}" ?\n\nCette action est irréversible.`)) {
        deleteCategoryFromAPI(categoryId, categoryName);
    }
}

async function deleteCategoryFromAPI(categoryId, categoryName) {
    const tournamentId = document.querySelector('.teams-container').dataset.tournamentId;
    
    try {
        const response = await fetch(`https://api.avironcastrais.fr/categories/tournaments/${tournamentId}/${categoryId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        if (response.ok) {
            alert(`Catégorie "${categoryName}" supprimée avec succès !`);
            window.location.reload();
        } else if (response.status === 404) {
            alert('Catégorie non trouvée.');
        } else {
            throw new Error(`Erreur ${response.status}: ${response.statusText}`);
        }

    } catch (error) {
        console.error('Error deleting category:', error);
        alert('Erreur lors de la suppression de la catégorie: ' + error.message);
    }
}

function editTeam(teamId, teamName) {
    // Find team data
    const teamData = teamsData.find(team => team.Team_Id == teamId);
    if (teamData) {
        openTeamModal(teamId, teamData);
    } else {
        alert('Équipe non trouvée');
    }
}

function deleteTeam(teamId, teamName) {
    if (confirm(`Êtes-vous sûr de vouloir supprimer l'équipe "${teamName}" ?\n\nCette action est irréversible.`)) {
        deleteTeamFromAPI(teamId, teamName);
    }
}

async function deleteTeamFromAPI(teamId, teamName) {
    const tournamentId = document.querySelector('.teams-container').dataset.tournamentId;
    
    try {
        const response = await fetch(`index.php?route=delete-team&tournament_id=${tournamentId}&team_id=${teamId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        const result = await response.json();

        if (response.ok && result.success) {
            alert(`Équipe "${teamName}" supprimée avec succès !`);
            window.location.reload();
        } else {
            throw new Error(result.error || 'Erreur lors de la suppression de l\'équipe');
        }

    } catch (error) {
        console.error('Error deleting team:', error);
        alert('Erreur lors de la suppression de l\'équipe: ' + error.message);
    }
}
</script>

<style>
/* Styles pour les onglets */
.tabs-container {
    margin-bottom: 25px;
}

.tabs-nav {
    display: flex;
    border-bottom: 2px solid #e0e0e0;
    background: white;
    border-radius: 10px 10px 0 0;
    overflow: hidden;
}

.tab-btn {
    flex: 1;
    padding: 15px 20px;
    background: #f8f9fa;
    border: none;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    color: #666;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.tab-btn:hover {
    background: #e9ecef;
    color: #333;
}

.tab-btn.active {
    background: #232c5a;
    color: white;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

/* Styles pour les catégories */
.categories-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding: 20px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.categories-title {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #232c5a;
    font-size: 18px;
    font-weight: 600;
}

.categories-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.category-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.category-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
}

.category-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #e0e0e0;
}

.category-header h3 {
    margin: 0;
    color: #232c5a;
    font-size: 18px;
}

.category-actions {
    display: flex;
    gap: 8px;
}

.btn-edit-cat, .btn-delete-cat {
    padding: 8px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 12px;
    transition: all 0.3s ease;
}

.btn-edit-cat {
    background: #232c5a;
    color: white;
}

.btn-edit-cat:hover {
    background: #1a2147;
}

.btn-delete-cat {
    background: #dc3545;
    color: white;
}

.btn-delete-cat:hover {
    background: #c82333;
}

.category-info {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.age-range, .category-description, .game-duration {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #666;
}

.age-range i {
    color: #ff6b6b;
}

.category-description i {
    color: #42a5f5;
}

.game-duration i {
    color: #66bb6a;
}

/* Styles pour les équipes */
.teams-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

.team-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.team-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
}

.team-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
}

.team-logo {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e0e0e0;
}

.team-logo-placeholder {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #232c5a, #1a2147);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 16px;
}

.team-info h3 {
    margin: 0 0 5px 0;
    color: #232c5a;
    font-size: 16px;
}

.team-category {
    font-size: 12px;
    background: #232c5a;
    color: white;
    padding: 2px 8px;
    border-radius: 10px;
}

.team-actions {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
}

.btn-edit, .btn-delete {
    padding: 8px 12px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 12px;
    transition: all 0.3s ease;
}

.btn-edit {
    background: #232c5a;
    color: white;
}

.btn-edit:hover {
    background: #1a2147;
}

.btn-delete {
    background: #dc3545;
    color: white;
}

.btn-delete:hover {
    background: #c82333;
}

/* Modal styles */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal.hidden {
    display: none;
}

.modal-content {
    background: white;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #e0e0e0;
    background: linear-gradient(135deg, #232c5a, #1a2147);
    color: white;
    border-radius: 10px 10px 0 0;
}

.modal-header h3 {
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.modal-close {
    background: none;
    border: none;
    color: white;
    font-size: 18px;
    cursor: pointer;
    padding: 5px;
    border-radius: 50%;
    transition: background 0.3s ease;
}

.modal-close:hover {
    background: rgba(255, 255, 255, 0.1);
}

.modal-form {
    padding: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    font-size: 14px;
}

.form-group input, .form-group textarea {
    width: 100%;
    padding: 12px;
    border: 2px solid #e1e5e9;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.3s ease;
}

.form-group input:focus, .form-group textarea:focus {
    outline: none;
    border-color: #232c5a;
    box-shadow: 0 0 0 3px rgba(35, 44, 90, 0.1);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.input-hint {
    font-size: 12px;
    color: #666;
    margin-top: 5px;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #e0e0e0;
}

.btn-cancel, .btn-submit {
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
    background: #6c757d;
    color: white;
}

.btn-cancel:hover {
    background: #5a6268;
}

.btn-submit {
    background: #232c5a;
    color: white;
}

.btn-submit:hover {
    background: #1a2147;
}

.btn-submit:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Additional styles for team modal */
.current-logo {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 10px;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e1e5e9;
}

.form-group select {
    width: 100%;
    padding: 12px;
    border: 2px solid #e1e5e9;
    border-radius: 8px;
    font-size: 14px;
    background: white;
    cursor: pointer;
    transition: border-color 0.3s ease;
}

.form-group select:focus {
    outline: none;
    border-color: #232c5a;
    box-shadow: 0 0 0 3px rgba(35, 44, 90, 0.1);
}

.form-group input[type="file"] {
    padding: 8px 12px;
    border: 2px dashed #e1e5e9;
    background: #f8f9fa;
}

.form-group input[type="file"]:focus {
    border-color: #232c5a;
    background: white;
}

@media (max-width: 768px) {
    .tab-btn {
        padding: 12px 15px;
        font-size: 13px;
    }

    .categories-list, .teams-grid {
        grid-template-columns: 1fr;
    }

    .category-header, .categories-header, .teams-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .modal-content {
        width: 95%;
        margin: 10px;
    }
}
</style>
