<div class="main-content">
    <div class="header">
        <div class="search-bar">
            <input type="text" placeholder="Rechercher une poule" id="searchInput">
            <button type="submit" id="searchButton"><i class="fas fa-search"></i></button>
        </div>
        <div class="profile">
            <img src="img/logo.png" alt="Profil">
        </div>
    </div>

    <div class="welcome">
        Gestion des poules
    </div>

    <div class="pools-container">
        <div class="pools-header">
            <div class="pools-title">
                <i class="fas fa-layer-group"></i>
                <span>Poules du tournoi</span>
            </div>
            <div class="header-actions">
                <button class="btn-add-phase" id="addPhaseBtn">
                    <i class="fas fa-plus"></i> Ajouter une phase
                </button>
                <button class="btn-add" id="addPoolBtn">
                    <i class="fas fa-plus"></i> Créer une poule
                </button>
            </div>
        </div>

        <!-- Filtres par catégorie et phase -->
        <div class="filters-container">
            <?php if (!empty($categories)): ?>
                <div class="filter-group">
                    <label>Catégories:</label>
                    <div class="category-filter">
                        <button class="category-btn all active" data-category="all">Toutes</button>
                        <?php foreach ($categories as $category): ?>
                            <button class="category-btn <?= htmlspecialchars($category['name']) ?>" 
                                    data-category="<?= htmlspecialchars($category['Category_Id']) ?>">
                                <?= htmlspecialchars(strtoupper($category['name'])) ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($phases)): ?>
                <div class="filter-group">
                    <label>Phases:</label>
                    <div class="phase-filter">
                        <button class="phase-btn all active" data-phase="all">Toutes</button>
                        <?php foreach ($phases as $phase): ?>
                            <button class="phase-btn" data-phase="<?= $phase['Phase_Id'] ?>">
                                <?= htmlspecialchars($phase['name']) ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Grouper les poules par phase puis par catégorie -->
        <?php if (empty($pools)): ?>
            <div class="empty-state">
                <i class="fas fa-layer-group empty-icon"></i>
                <p>Aucune poule n'a été créée pour ce tournoi</p>
                <button class="btn-add" onclick="document.getElementById('addPoolBtn').click()">
                    Créer la première poule
                </button>
            </div>
        <?php else: ?>
            <?php
            // Créer un index des phases pour accès rapide
            $phasesById = [];
            foreach ($phases as $phase) {
                $phasesById[$phase['Phase_Id']] = $phase;
            }

            // Grouper les poules par phase puis par catégorie
            $poolsByPhase = [];
            foreach ($pools as $pool) {
                $phaseId = $pool['Phase_Id'] ?? 'unknown';
                $categoryId = $pool['Category_Id'] ?? 'unknown';
                $categoryName = $pool['category_name'] ?? 'Sans catégorie';
                
                if (!isset($poolsByPhase[$phaseId])) {
                    $poolsByPhase[$phaseId] = [
                        'phase' => $phasesById[$phaseId] ?? ['name' => 'Phase inconnue', 'Phase_Id' => $phaseId],
                        'categories' => []
                    ];
                }
                
                if (!isset($poolsByPhase[$phaseId]['categories'][$categoryId])) {
                    $poolsByPhase[$phaseId]['categories'][$categoryId] = [
                        'name' => $categoryName,
                        'pools' => []
                    ];
                }
                
                $poolsByPhase[$phaseId]['categories'][$categoryId]['pools'][] = $pool;
            }

            // Trier les phases par ID
            ksort($poolsByPhase);
            ?>

            <div class="pools-by-phase">
                <?php foreach ($poolsByPhase as $phaseId => $phaseData): ?>
                    <div class="phase-section" data-phase="<?= $phaseId ?>">
                        <div class="phase-header">
                            <h2>
                                <i class="fas fa-layer-group"></i>
                                <?= htmlspecialchars($phaseData['phase']['name']) ?>
                                <span class="phase-count">
                                    (<?= array_sum(array_map(function($cat) { return count($cat['pools']); }, $phaseData['categories'])) ?> poule<?= array_sum(array_map(function($cat) { return count($cat['pools']); }, $phaseData['categories'])) > 1 ? 's' : '' ?>)
                                </span>
                            </h2>
                        </div>

                        <?php foreach ($phaseData['categories'] as $categoryId => $categoryData): ?>
                            <div class="category-section" data-category="<?= $categoryId ?>">
                                <div class="category-header">
                                    <h3>
                                        <i class="fas fa-tag"></i>
                                        <?= htmlspecialchars($categoryData['name']) ?>
                                        <span class="pools-count">(<?= count($categoryData['pools']) ?> poule<?= count($categoryData['pools']) > 1 ? 's' : '' ?>)</span>
                                    </h3>
                                </div>

                                <div class="pools-grid">
                                    <?php foreach ($categoryData['pools'] as $pool): ?>
                                        <div class="pool-card">
                                            <div class="pool-header">
                                                <h4 class="pool-name"><?= htmlspecialchars($pool['name']) ?></h4>
                                                <span class="pool-id">ID: <?= $pool['Pool_Id'] ?></span>
                                            </div>

                                            <div class="pool-info">
                                                <div class="pool-meta">
                                                    <span class="badge <?= htmlspecialchars($categoryData['name']) ?>">
                                                        <?= htmlspecialchars(strtoupper($categoryData['name'])) ?>
                                                    </span>
                                                    <span class="phase-info">
                                                        <?= htmlspecialchars($phaseData['phase']['name']) ?>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="pool-actions">
                                                <button class="teams-btn" 
                                                        onclick="managePoolTeams(<?= $pool['Pool_Id'] ?>, '<?= htmlspecialchars(addslashes($pool['name'])) ?>')"
                                                        title="Gérer les équipes">
                                                    <i class="fas fa-users"></i>
                                                </button>
                                                <button class="edit-btn" 
                                                        onclick="editPool(<?= $pool['Pool_Id'] ?>, '<?= htmlspecialchars(addslashes($pool['name'])) ?>', <?= $pool['Category_Id'] ?>, <?= $pool['Phase_Id'] ?>)"
                                                        title="Modifier la poule">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="delete-btn" 
                                                        onclick="deletePool(<?= $pool['Pool_Id'] ?>, '<?= htmlspecialchars(addslashes($pool['name'])) ?>')"
                                                        title="Supprimer la poule">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal pour ajouter une phase -->
<div id="addPhaseModal" class="modal hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-layer-group"></i> Ajouter une nouvelle phase</h3>
            <button class="modal-close" onclick="closePhaseModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="addPhaseForm" class="modal-form">
            <div class="form-group">
                <label for="phaseName">
                    <i class="fas fa-tag"></i> Nom de la phase
                </label>
                <input type="text" 
                       id="phaseName" 
                       name="phaseName" 
                       placeholder="Ex: Phase 1, Qualifications, Finale..." 
                       required 
                       maxlength="50">
                <div class="input-hint">Le nom de la phase doit être unique dans ce tournoi</div>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn-cancel" onclick="closePhaseModal()">
                    <i class="fas fa-times"></i> Annuler
                </button>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-plus"></i> Créer la phase
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal pour modifier une poule -->
<div id="editPoolModal" class="modal hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-edit"></i> Modifier la poule</h3>
            <button class="modal-close" onclick="closeEditPoolModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="editPoolForm" class="modal-form">
            <input type="hidden" id="editPoolId" name="poolId">
            
            <div class="form-group">
                <label for="editPoolName">
                    <i class="fas fa-layer-group"></i> Nom de la poule
                </label>
                <input type="text" 
                       id="editPoolName" 
                       name="poolName" 
                       placeholder="Ex: Poule A, Groupe 1..." 
                       required 
                       maxlength="50">
                <div class="input-hint">Le nom de la poule doit être unique dans ce tournoi</div>
            </div>

            <div class="form-group">
                <label for="editPoolCategory">
                    <i class="fas fa-tag"></i> Catégorie
                </label>
                <select id="editPoolCategory" name="categoryId" required>
                    <option value="">Sélectionner une catégorie</option>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['Category_Id'] ?>">
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <div class="input-hint">La catégorie détermine quelles équipes peuvent participer</div>
            </div>

            <div class="form-group">
                <label for="editPoolPhase">
                    <i class="fas fa-calendar-check"></i> Phase
                </label>
                <select id="editPoolPhase" name="phaseId" required>
                    <option value="">Sélectionner une phase</option>
                    <?php if (!empty($phases)): ?>
                        <?php foreach ($phases as $phase): ?>
                            <option value="<?= $phase['Phase_Id'] ?>">
                                <?= htmlspecialchars($phase['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <div class="input-hint">La phase organise les poules dans le temps</div>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn-cancel" onclick="closeEditPoolModal()">
                    <i class="fas fa-times"></i> Annuler
                </button>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Sauvegarder
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal pour gérer les équipes d'une poule -->
<div id="manageTeamsModal" class="modal hidden">
    <div class="modal-content large">
        <div class="modal-header">
            <h3><i class="fas fa-users"></i> Gérer les équipes - <span id="poolNameDisplay"></span></h3>
            <button class="modal-close" onclick="closeManageTeamsModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <input type="hidden" id="currentPoolId" name="poolId">
            
            <!-- Section équipes dans la poule -->
            <div class="teams-section">
                <div class="section-header">
                    <h4><i class="fas fa-check-circle"></i> Équipes dans la poule</h4>
                    <span class="teams-count" id="poolTeamsCount">0 équipe(s)</span>
                </div>
                
                <div id="poolTeamsList" class="teams-list">
                    <!-- Liste des équipes dans la poule -->
                </div>
            </div>
            
            <!-- Section équipes disponibles -->
            <div class="teams-section">
                <div class="section-header">
                    <h4><i class="fas fa-plus-circle"></i> Équipes disponibles</h4>
                    <div class="available-filters">
                        <select id="categoryFilter" class="filter-select">
                            <option value="">Toutes les catégories</option>
                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['Category_Id'] ?>">
                                        <?= htmlspecialchars($category['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                
                <div id="availableTeamsList" class="teams-list">
                    <!-- Liste des équipes disponibles -->
                </div>
            </div>
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeManageTeamsModal()">
                <i class="fas fa-times"></i> Fermer
            </button>
        </div>
    </div>
</div>

<script>
let isLoading = false; // Global loading flag
let allTeams = [];
let poolTeams = [];

document.addEventListener('DOMContentLoaded', function() {
    const categoryButtons = document.querySelectorAll('.category-btn');
    const phaseButtons = document.querySelectorAll('.phase-btn');
    const phaseSections = document.querySelectorAll('.phase-section');
    const categorySections = document.querySelectorAll('.category-section');


    // Gestion des filtres de catégorie
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (isLoading) return;
            categoryButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            applyFilters();
        });
    });

    // Gestion des filtres de phase
    phaseButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (isLoading) return;
            phaseButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            applyFilters();
        });
    });

    // Gestion du bouton "Ajouter une phase"
    const addPhaseBtn = document.getElementById('addPhaseBtn');
    const addPhaseModal = document.getElementById('addPhaseModal');
    const addPhaseForm = document.getElementById('addPhaseForm');
    
    if (addPhaseBtn && addPhaseModal && addPhaseForm) {
        addPhaseBtn.addEventListener('click', function() {
            openPhaseModal();
        });
        
        // Soumission du formulaire de phase
        addPhaseForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!isLoading) {
                createPhase();
            }
        });
    }
    
    // Gestion du formulaire de modification de poule
    const editPoolForm = document.getElementById('editPoolForm');
    if (editPoolForm) {
        editPoolForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!isLoading) {
                updatePool();
            }
        });
    }
    
    // Gestion des filtres d'équipes
    const categoryFilter = document.getElementById('categoryFilter');
    
    if (categoryFilter) {
        categoryFilter.addEventListener('change', filterAvailableTeams);
    }
    
    // Fermer les modals avec Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closePhaseModal();
            closeEditPoolModal();
            closeManageTeamsModal();
        }
    });
    
    // Fermer les modals en cliquant à l'extérieur
    [document.getElementById('addPhaseModal'), document.getElementById('editPoolModal'), document.getElementById('manageTeamsModal')].forEach(modal => {
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    if (modal.id === 'addPhaseModal') closePhaseModal();
                    else if (modal.id === 'editPoolModal') closeEditPoolModal();
                    else if (modal.id === 'manageTeamsModal') closeManageTeamsModal();
                }
            });
        }
    });

    function applyFilters() {
        if (!searchInput) return;
        
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = document.querySelector('.category-btn.active')?.dataset.category || 'all';
        const selectedPhase = document.querySelector('.phase-btn.active')?.dataset.phase || 'all';

        let visiblePhasesCount = 0;

        phaseSections.forEach(phaseSection => {
            const phaseId = phaseSection.dataset.phase;
            let hasVisibleCategories = false;

            // Filtrer par phase
            if (selectedPhase !== 'all' && phaseId !== selectedPhase) {
                phaseSection.style.display = 'none';
                return;
            }

            // Vérifier les catégories dans cette phase
            const categoriesInPhase = phaseSection.querySelectorAll('.category-section');
            categoriesInPhase.forEach(categorySection => {
                const categoryId = categorySection.dataset.category;
                let hasVisiblePools = false;

                // Filtrer par catégorie
                if (selectedCategory !== 'all' && categoryId !== selectedCategory) {
                    categorySection.style.display = 'none';
                    return;
                }

                // Vérifier les poules dans cette catégorie
                const pools = categorySection.querySelectorAll('.pool-card');
                pools.forEach(pool => {
                    const poolName = pool.querySelector('.pool-name').textContent.toLowerCase();
                    
                    if (!searchTerm || poolName.includes(searchTerm)) {
                        pool.style.display = 'block';
                        hasVisiblePools = true;
                    } else {
                        pool.style.display = 'none';
                    }
                });

                if (hasVisiblePools) {
                    categorySection.style.display = 'block';
                    hasVisibleCategories = true;
                } else {
                    categorySection.style.display = 'none';
                }
            });

            if (hasVisibleCategories) {
                phaseSection.style.display = 'block';
                visiblePhasesCount++;
            } else {
                phaseSection.style.display = 'none';
            }
        });

        // Gérer l'état vide
        const poolsByPhase = document.querySelector('.pools-by-phase');
        if (poolsByPhase) {
            const existingNoResults = document.querySelector('.no-results');
            if (existingNoResults) {
                existingNoResults.remove();
            }
            
            if (visiblePhasesCount === 0 && phaseSections.length > 0) {
                const noResults = document.createElement('div');
                noResults.className = 'no-results';
                noResults.innerHTML = `
                    <i class="fas fa-search empty-icon"></i>
                    <p>Aucune poule ne correspond aux filtres sélectionnés</p>
                    <button class="btn-add" onclick="resetFilters()">
                        <i class="fas fa-undo"></i> Réinitialiser les filtres
                    </button>
                `;
                poolsByPhase.appendChild(noResults);
            }
        }
    }

    // Fonction globale pour réinitialiser les filtres
    window.resetFilters = function() {
        if (searchInput) searchInput.value = '';
        categoryButtons.forEach(btn => btn.classList.remove('active'));
        phaseButtons.forEach(btn => btn.classList.remove('active'));
        
        if (categoryButtons.length > 0) categoryButtons[0].classList.add('active');
        if (phaseButtons.length > 0) phaseButtons[0].classList.add('active');
        
        applyFilters();
    };
});

// Global functions that need to be accessible from onclick handlers
function openPhaseModal() {
    const modal = document.getElementById('addPhaseModal');
    const form = document.getElementById('addPhaseForm');
    
    if (modal && form) {
        // Réinitialiser le formulaire
        form.reset();
        clearFormErrors();
        
        // Afficher le modal
        modal.classList.remove('hidden');
        
        // Focus sur le champ nom
        setTimeout(() => {
            const phaseNameInput = document.getElementById('phaseName');
            if (phaseNameInput) phaseNameInput.focus();
        }, 100);
    }
}

function closePhaseModal() {
    const modal = document.getElementById('addPhaseModal');
    if (modal) {
        modal.classList.add('hidden');
        clearFormErrors();
    }
}

async function createPhase() {
    if (isLoading) return;
    
    const submitBtn = document.querySelector('#addPhaseForm .btn-submit');
    const originalText = submitBtn.innerHTML;
    const phaseName = document.getElementById('phaseName').value.trim();
    
    if (!phaseName) {
        showFormError('phaseName', 'Le nom de la phase est requis');
        return;
    }
    
    if (phaseName.length < 2 || phaseName.length > 50) {
        showFormError('phaseName', 'Le nom de la phase doit contenir entre 2 et 50 caractères');
        return;
    }
    
    try {
        isLoading = true;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Création...';
        
        const response = await fetch(`https://api.avironcastrais.fr/phases/tournaments/<?= $_GET['tournament_id'] ?>`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                name: phaseName
            })
        });
        
        const result = await response.json();
        
        if (response.ok) {
            showSuccessMessage(`Phase "${phaseName}" créée avec succès`);
            closePhaseModal();
            
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            const errorMessage = result.message || result.error || 'Erreur lors de la création de la phase';
            showFormError('phaseName', errorMessage);
        }
        
    } catch (error) {
        console.error('Erreur lors de la création de la phase:', error);
        showFormError('phaseName', 'Erreur de connexion. Veuillez réessayer.');
    } finally {
        isLoading = false;
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
}

function editPool(poolId, poolName, categoryId, phaseId) {
    const modal = document.getElementById('editPoolModal');
    const form = document.getElementById('editPoolForm');
    
    if (modal && form) {
        // Réinitialiser le formulaire
        form.reset();
        clearFormErrors();
        
        // Remplir les champs avec les données actuelles
        document.getElementById('editPoolId').value = poolId;
        document.getElementById('editPoolName').value = poolName;
        document.getElementById('editPoolCategory').value = categoryId;
        document.getElementById('editPoolPhase').value = phaseId;
        
        // Afficher le modal
        modal.classList.remove('hidden');
        
        // Focus sur le champ nom
        setTimeout(() => {
            document.getElementById('editPoolName').focus();
        }, 100);
    }
}

function closeEditPoolModal() {
    const modal = document.getElementById('editPoolModal');
    if (modal) {
        modal.classList.add('hidden');
        clearFormErrors();
    }
}

async function updatePool() {
    if (isLoading) return;
    
    const submitBtn = document.querySelector('#editPoolForm .btn-submit');
    const originalText = submitBtn.innerHTML;
    const poolId = document.getElementById('editPoolId').value;
    const poolName = document.getElementById('editPoolName').value.trim();
    const categoryId = document.getElementById('editPoolCategory').value;
    const phaseId = document.getElementById('editPoolPhase').value;
    
    // Validation
    if (!poolName || poolName.length < 2 || poolName.length > 50) {
        showFormError('editPoolName', 'Le nom de la poule doit contenir entre 2 et 50 caractères');
        return;
    }
    
    if (!categoryId) {
        showFormError('editPoolCategory', 'Veuillez sélectionner une catégorie');
        return;
    }
    
    if (!phaseId) {
        showFormError('editPoolPhase', 'Veuillez sélectionner une phase');
        return;
    }
    
    try {
        isLoading = true;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Modification...';
        
        const response = await fetch(`https://api.avironcastrais.fr/pools/tournaments/<?= $_GET['tournament_id'] ?>/${poolId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                name: poolName,
                categoryId: parseInt(categoryId),
                phaseId: parseInt(phaseId)
            })
        });
        
        const result = await response.json();
        
        if (response.ok) {
            showSuccessMessage(`Poule "${poolName}" modifiée avec succès`);
            closeEditPoolModal();
            
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            const errorMessage = result.message || result.error || 'Erreur lors de la modification de la poule';
            showFormError('editPoolName', errorMessage);
        }
        
    } catch (error) {
        console.error('Erreur lors de la modification de la poule:', error);
        showFormError('editPoolName', 'Erreur de connexion. Veuillez réessayer.');
    } finally {
        isLoading = false;
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
}

function deletePool(poolId, poolName) {
    if (confirm(`Êtes-vous sûr de vouloir supprimer la poule "${poolName}" ?\n\nCette action est irréversible et supprimera également tous les matchs associés.`)) {
        deletePoolConfirmed(poolId, poolName);
    }
}

async function deletePoolConfirmed(poolId, poolName) {
    if (isLoading) return;
    
    try {
        isLoading = true;
        showSuccessMessage('Suppression en cours...');
        
        const response = await fetch(`https://api.avironcastrais.fr/pools/tournaments/<?= $_GET['tournament_id'] ?>/${poolId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (response.ok) {
            showSuccessMessage(`Poule "${poolName}" supprimée avec succès`);
            
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            const errorMessage = result.message || result.error || 'Erreur lors de la suppression de la poule';
            alert('Erreur: ' + errorMessage);
        }
        
    } catch (error) {
        console.error('Erreur lors de la suppression de la poule:', error);
        alert('Erreur de connexion. Veuillez réessayer.');
    } finally {
        isLoading = false;
    }
}

async function managePoolTeams(poolId, poolName) {
    if (isLoading) return;
    
    const modal = document.getElementById('manageTeamsModal');
    const poolNameDisplay = document.getElementById('poolNameDisplay');
    const currentPoolIdInput = document.getElementById('currentPoolId');
    
    if (modal && poolNameDisplay && currentPoolIdInput) {
        // Initialiser le modal
        poolNameDisplay.textContent = poolName;
        currentPoolIdInput.value = poolId;
        
        // Afficher le modal
        modal.classList.remove('hidden');
        
        // Charger les données
        await loadTeamsData(poolId);
    }
}

function closeManageTeamsModal() {
    const modal = document.getElementById('manageTeamsModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

async function loadTeamsData(poolId) {
    if (isLoading) return;
    
    try {
        isLoading = true;
        showTeamsLoading();
        
        // Charger toutes les équipes du tournoi et les équipes de la poule en parallèle
        const [teamsResponse, poolTeamsResponse] = await Promise.all([
            fetch(`https://api.avironcastrais.fr/teams/<?= $_GET['tournament_id'] ?>`),
            fetch(`https://api.avironcastrais.fr/pools/tournaments/<?= $_GET['tournament_id'] ?>/${poolId}/teams`)
        ]);
        
        allTeams = await teamsResponse.json();
        poolTeams = await poolTeamsResponse.json();
        
        updateTeamsDisplay();
        
    } catch (error) {
        console.error('Erreur lors du chargement des équipes:', error);
        showTeamsError('Erreur lors du chargement des équipes');
    } finally {
        isLoading = false;
    }
}

function showTeamsLoading() {
    const loadingHtml = '<div class="loading-state"><i class="fas fa-spinner fa-spin"></i> Chargement...</div>';
    const poolTeamsList = document.getElementById('poolTeamsList');
    const availableTeamsList = document.getElementById('availableTeamsList');
    
    if (poolTeamsList) poolTeamsList.innerHTML = loadingHtml;
    if (availableTeamsList) availableTeamsList.innerHTML = loadingHtml;
}

function showTeamsError(message) {
    const errorHtml = `<div class="error-state"><i class="fas fa-exclamation-triangle"></i> ${message}</div>`;
    const poolTeamsList = document.getElementById('poolTeamsList');
    const availableTeamsList = document.getElementById('availableTeamsList');
    
    if (poolTeamsList) poolTeamsList.innerHTML = errorHtml;
    if (availableTeamsList) availableTeamsList.innerHTML = errorHtml;
}

function updateTeamsDisplay() {
    updatePoolTeamsList();
    updateAvailableTeamsList();
    updateTeamsCount();
}

function updatePoolTeamsList() {
    const container = document.getElementById('poolTeamsList');
    if (!container) return;
    
    if (!poolTeams || poolTeams.length === 0) {
        container.innerHTML = '<div class="empty-teams"><i class="fas fa-users"></i> Aucune équipe dans cette poule</div>';
        return;
    }
    
    const html = poolTeams.map(team => `
        <div class="team-item" data-team-id="${team.Team_Id}">
            <div class="team-info">
                <img src="${team.logo}" alt="Logo" class="team-logo">
                <div class="team-details">
                    <span class="team-name">${escapeHtml(team.name)}</span>
                    <span class="team-category">${escapeHtml(team.age_category || 'Non définie')}</span>
                </div>
            </div>
            <button class="remove-team-btn" onclick="removeTeamFromPool(${team.Team_Id}, '${escapeHtml(team.name)}')" title="Retirer de la poule">
                <i class="fas fa-minus-circle"></i>
            </button>
        </div>
    `).join('');
    
    container.innerHTML = html;
}

function updateAvailableTeamsList() {
    const container = document.getElementById('availableTeamsList');
    if (!container) return;
    
    // Équipes disponibles = toutes les équipes - équipes dans la poule
    const poolTeamIds = poolTeams.map(team => team.Team_Id);
    const availableTeams = allTeams.filter(team => !poolTeamIds.includes(team.Team_Id));
    
    if (availableTeams.length === 0) {
        container.innerHTML = '<div class="empty-teams"><i class="fas fa-check-circle"></i> Toutes les équipes sont déjà assignées</div>';
        return;
    }
    
    const html = availableTeams.map(team => `
        <div class="team-item available" data-team-id="${team.Team_Id}" data-category="${team.Category_Id || ''}">
            <div class="team-info">
                <img src="${team.logo}" alt="Logo" class="team-logo">
                <div class="team-details">
                    <span class="team-name">${escapeHtml(team.name)}</span>
                    <span class="team-category">${escapeHtml(team.age_category || 'Non définie')}</span>
                </div>
            </div>
            <button class="add-team-btn" onclick="addTeamToPool(${team.Team_Id}, '${escapeHtml(team.name)}')" title="Ajouter à la poule">
                <i class="fas fa-plus-circle"></i>
            </button>
        </div>
    `).join('');
    
    container.innerHTML = html;
    
    // Appliquer les filtres
    filterAvailableTeams();
}

function updateTeamsCount() {
    const teamsCountElement = document.getElementById('poolTeamsCount');
    if (teamsCountElement) {
        teamsCountElement.textContent = `${poolTeams.length} équipe(s)`;
    }
}

function filterAvailableTeams() {
    const categoryFilter = document.getElementById('categoryFilter');
    
    if (!categoryFilter) return;
    
    const categoryValue = categoryFilter.value;
    const availableItems = document.querySelectorAll('#availableTeamsList .team-item');
    
    availableItems.forEach(item => {
        const teamCategory = item.dataset.category;
        
        let show = true;
        
        // Filtre par catégorie
        if (categoryValue && teamCategory !== categoryValue) {
            show = false;
        }
        
        item.style.display = show ? 'flex' : 'none';
    });
}

async function addTeamToPool(teamId, teamName) {
    if (isLoading) return;
    
    const poolId = document.getElementById('currentPoolId').value;
    
    try {
        isLoading = true;
        const response = await fetch(`https://api.avironcastrais.fr/pools/tournaments/<?= $_GET['tournament_id'] ?>/${poolId}/teams`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                teamId: parseInt(teamId)
            })
        });
        
        const result = await response.json();
        
        if (response.ok) {
            showSuccessMessage(`Équipe "${teamName}" ajoutée à la poule`);
            
            // Recharger les données
            await loadTeamsData(poolId);
        } else {
            const errorMessage = result.message || result.error || 'Erreur lors de l\'ajout de l\'équipe';
            alert('Erreur: ' + errorMessage);
        }
        
    } catch (error) {
        console.error('Erreur lors de l\'ajout de l\'équipe:', error);
        alert('Erreur de connexion. Veuillez réessayer.');
    } finally {
        isLoading = false;
    }
}

async function removeTeamFromPool(teamId, teamName) {
    if (isLoading) return;
    
    const poolId = document.getElementById('currentPoolId').value;
    
    if (!confirm(`Êtes-vous sûr de vouloir retirer l'équipe "${teamName}" de cette poule ?`)) {
        return;
    }
    
    try {
        isLoading = true;
        const response = await fetch(`https://api.avironcastrais.fr/pools/tournaments/<?= $_GET['tournament_id'] ?>/${poolId}/teams/${teamId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (response.ok) {
            showSuccessMessage(`Équipe "${teamName}" retirée de la poule`);
            
            // Recharger les données
            await loadTeamsData(poolId);
        } else {
            const errorMessage = result.message || result.error || 'Erreur lors de la suppression de l\'équipe';
            alert('Erreur: ' + errorMessage);
        }
        
    } catch (error) {
        console.error('Erreur lors de la suppression de l\'équipe:', error);
        alert('Erreur de connexion. Veuillez réessayer.');
    } finally {
        isLoading = false;
    }
}

function showFormError(fieldName, message) {
    clearFormErrors();
    
    const field = document.getElementById(fieldName);
    if (!field) return;
    
    const formGroup = field.closest('.form-group');
    if (!formGroup) return;
    
    // Ajouter la classe d'erreur
    formGroup.classList.add('error');
    
    // Créer et ajouter le message d'erreur
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ${message}`;
    
    formGroup.appendChild(errorDiv);
    
    // Focus sur le champ en erreur
    field.focus();
}

function clearFormErrors() {
    const errorMessages = document.querySelectorAll('.error-message');
    const errorGroups = document.querySelectorAll('.form-group.error');
    
    errorMessages.forEach(msg => msg.remove());
    errorGroups.forEach(group => group.classList.remove('error'));
}

function showSuccessMessage(message) {
    // Supprimer les notifications existantes
    const existingNotifications = document.querySelectorAll('.success-notification');
    existingNotifications.forEach(notif => notif.remove());
    
    // Créer une notification de succès
    const notification = document.createElement('div');
    notification.className = 'success-notification';
    notification.innerHTML = `
        <i class="fas fa-check-circle"></i>
        <span>${message}</span>
    `;
    
    // Ajouter au body
    document.body.appendChild(notification);
    
    // Animer l'apparition
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    // Supprimer après 3 secondes
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>

<style>
.pools-container {
    background-color: #e8ecff;
    border-radius: 10px;
    padding: 20px;
}

.pools-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.pools-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: bold;
    font-size: 18px;
    color: #232c5a;
}

.header-actions {
    display: flex;
    gap: 10px;
    align-items: center;
}

.btn-add-phase {
    background-color: #28a745;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.btn-add-phase:hover {
    background-color: #218838;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.btn-add-phase:active {
    transform: translateY(0);
}

.filters-container {
    margin-bottom: 30px;
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.filter-group {
    margin-bottom: 15px;
}

.filter-group:last-child {
    margin-bottom: 0;
}

.filter-group label {
    display: block;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    font-size: 14px;
}

.category-filter, .phase-filter {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.phase-btn {
    padding: 6px 12px;
    border-radius: 6px;
    border: 2px solid #e1e5e9;
    cursor: pointer;
    font-size: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
    background-color: white;
    color: #333;
}

.phase-btn:hover {
    border-color: #232c5a;
    background-color: #f8f9ff;
}

.phase-btn.active {
    background-color: #232c5a;
    color: white;
    border-color: #232c5a;
    box-shadow: 0 2px 4px rgba(35, 44, 90, 0.2);
}

.phase-section {
    margin-bottom: 40px;
}

.phase-header {
    margin-bottom: 25px;
    border-bottom: 3px solid #232c5a;
    padding-bottom: 15px;
}

.phase-header h2 {
    display: flex;
    align-items: center;
    gap: 12px;
    color: #232c5a;
    font-size: 20px;
    margin: 0;
}

.phase-count {
    font-size: 14px;
    color: #666;
    font-weight: normal;
}

.category-section {
    margin-bottom: 25px;
}

.category-header {
    margin-bottom: 15px;
    border-bottom: 1px solid #ddd;
    padding-bottom: 8px;
}

.category-header h3 {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #555;
    font-size: 16px;
    margin: 0;
}

.pools-count {
    font-size: 12px;
    color: #666;
    font-weight: normal;
}

.pools-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 15px;
}

.pool-card {
    background-color: white;
    border-radius: 8px;
    padding: 15px;
    display: flex;
    flex-direction: column;
    gap: 15px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border-left: 4px solid #232c5a;
}

.pool-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.pool-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.pool-name {
    font-size: 16px;
    margin: 0;
    color: #232c5a;
    font-weight: 600;
}

.pool-id {
    font-size: 12px;
    color: #666;
    background-color: #f5f5f5;
    padding: 2px 6px;
    border-radius: 4px;
}

.pool-meta {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}

.phase-info {
    font-size: 12px;
    color: #232c5a;
    background-color: #f0f4ff;
    padding: 3px 8px;
    border-radius: 4px;
    font-weight: 500;
}

.pool-actions {
    display: flex;
    gap: 8px;
    justify-content: flex-end;
}

.teams-btn, .edit-btn, .delete-btn {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    transition: all 0.3s ease;
}

.teams-btn {
    background-color: #f0fff0;
    color: #2e7d32;
    border: 1px solid #c8e6c9;
}

.teams-btn:hover {
    background-color: #2e7d32;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(46, 125, 50, 0.2);
}

/* Modal styles */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 2000;
    backdrop-filter: blur(2px);
}

.modal.hidden {
    display: none;
}

.modal-content {
    background: white;
    border-radius: 12px;
    padding: 0;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    animation: modalSlideIn 0.3s ease-out;
}

.modal-content.large {
    max-width: 900px;
    width: 95%;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-50px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px;
    border-bottom: 1px solid #eee;
    background: linear-gradient(135deg, #f8f9ff 0%, #e8f4ff 100%);
    border-radius: 12px 12px 0 0;
}

.modal-header h3 {
    margin: 0;
    color: #232c5a;
    font-size: 18px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.modal-close {
    background: none;
    border: none;
    font-size: 18px;
    color: #666;
    cursor: pointer;
    padding: 5px;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.modal-close:hover {
    background-color: #f5f5f5;
    color: #333;
}

.modal-form {
    padding: 25px;
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

.form-group label i {
    color: #232c5a;
    width: 16px;
}

.form-group input {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e1e5e9;
    border-radius: 8px;
    font-size: 16px;
    transition: all 0.3s ease;
    background-color: white;
}

.form-group input:focus {
    outline: none;
    border-color: #232c5a;
    box-shadow: 0 0 0 3px rgba(35, 44, 90, 0.1);
}

.form-group.error input {
    border-color: #e53e3e;
    box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.1);
}

.form-group select {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e1e5e9;
    border-radius: 8px;
    font-size: 16px;
    transition: all 0.3s ease;
    background-color: white;
    cursor: pointer;
}

.form-group select:focus {
    outline: none;
    border-color: #232c5a;
    box-shadow: 0 0 0 3px rgba(35, 44, 90, 0.1);
}

.form-group.error select {
    border-color: #e53e3e;
    box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.1);
}

.form-group select option {
    padding: 10px;
    font-size: 14px;
}

.form-group select option:checked {
    background-color: #232c5a;
    color: white;
}

.input-hint {
    font-size: 12px;
    color: #666;
    margin-top: 5px;
}

.error-message {
    color: #e53e3e;
    font-size: 12px;
    margin-top: 8px;
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 8px 12px;
    background-color: #fff5f5;
    border: 1px solid #fed7d7;
    border-radius: 6px;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.btn-cancel {
    background: #6c757d;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-cancel:hover {
    background: #5a6268;
}

.btn-submit {
    background: #28a745;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-submit:hover {
    background: #218838;
}

.btn-submit:disabled {
    background: #6c757d;
    cursor: not-allowed;
    opacity: 0.8;
}

/* Success notification */
.success-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
    border-radius: 8px;
    padding: 15px 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    z-index: 3000;
    transform: translateX(100%);
    transition: all 0.3s ease;
    font-weight: 500;
}

.success-notification.show {
    transform: translateX(0);
}

.success-notification i {
    color: #28a745;
    font-size: 16px;
}

/* Loading state styles */
.loading-state, .error-state, .empty-teams {
    text-align: center;
    padding: 40px 20px;
    color: #666;
    background: white;
    border-radius: 8px;
    border: 2px dashed #e9ecef;
}

.loading-state i, .error-state i, .empty-teams i {
    font-size: 24px;
    margin-bottom: 10px;
    display: block;
}

.error-state {
    color: #e53e3e;
    border-color: #fed7d7;
    background: #fff5f5;
}

.empty-teams {
    color: #28a745;
    border-color: #c8e6c9;
    background: #f0fff0;
}

/* Form styles */
.form-group {
    margin-bottom: 20px;
}

.form-group.error input,
.form-group.error select {
    border-color: #e53e3e;
    box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.1);
}

.error-message {
    color: #e53e3e;
    font-size: 12px;
    margin-top: 8px;
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 8px 12px;
    background-color: #fff5f5;
    border: 1px solid #fed7d7;
    border-radius: 6px;
}

/* Team management styles */
.teams-btn {
    background-color: #f0fff0;
    color: #2e7d32;
    border: 1px solid #c8e6c9;
}

.teams-btn:hover {
    background-color: #2e7d32;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(46, 125, 50, 0.2);
}

.modal-body {
    padding: 25px;
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.teams-section {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 10px;
}

.section-header h4 {
    color: #232c5a;
    font-size: 16px;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.teams-count {
    background: #232c5a;
    color: white;
    padding: 4px 12px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: 600;
}

.available-filters {
    display: flex;
    gap: 10px;
    align-items: center;
}

.filter-select {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
    min-width: 150px;
}

.teams-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    min-height: 200px;
    max-height: 300px;
    overflow-y: auto;
}

.team-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 15px;
    background: white;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.team-item:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transform: translateY(-1px);
}

.team-info {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
}

.team-logo {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 2px solid #e9ecef;
    object-fit: cover;
}

.team-details {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.team-name {
    font-weight: 600;
    color: #232c5a;
    font-size: 14px;
}

.team-category {
    font-size: 12px;
    color: #666;
    background: #f1f3f4;
    padding: 2px 6px;
    border-radius: 10px;
}

.add-team-btn, .remove-team-btn {
    width: 36px;
    height: 36px;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    transition: all 0.3s ease;
}

.add-team-btn {
    background: #e8f5e8;
    color: #2e7d32;
    border: 1px solid #c8e6c9;
}

.add-team-btn:hover {
    background: #2e7d32;
    color: white;
    transform: scale(1.05);
}

.remove-team-btn {
    background: #fff5f5;
    color: #e53e3e;
    border: 1px solid #fed7d7;
}

.remove-team-btn:hover {
    background: #e53e3e;
    color: white;
    transform: scale(1.05);
}

.loading-state, .error-state, .empty-teams {
    text-align: center;
    padding: 40px 20px;
    color: #666;
    background: white;
    border-radius: 8px;
    border: 2px dashed #e9ecef;
}

.loading-state i, .error-state i, .empty-teams i {
    font-size: 24px;
    margin-bottom: 10px;
    display: block;
}

.error-state {
    color: #e53e3e;
    border-color: #fed7d7;
    background: #fff5f5;
}

.empty-teams {
    color: #28a745;
    border-color: #c8e6c9;
    background: #f0fff0;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    padding: 20px 25px;
    border-top: 1px solid #eee;
    background: #f8f9fa;
    border-radius: 0 0 12px 12px;
}

/* Scrollbar pour les listes d'équipes */
.teams-list::-webkit-scrollbar {
    width: 6px;
}

.teams-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.teams-list::-webkit-scrollbar-thumb {
    background: #232c5a;
    border-radius: 3px;
}

.teams-list::-webkit-scrollbar-thumb:hover {
    background: #1a2147;
}

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

@media (max-width: 768px) {
    .header-actions {
        flex-direction: column;
        gap: 8px;
        align-items: stretch;
    }
    
    .btn-add-phase,
    .btn-add {
        justify-content: center;
        padding: 10px 15px;
    }
    
    .modal-content {
        width: 95%;
        margin: 20px;
    }
    
    .modal-header {
        padding: 15px 20px;
    }
    
    .modal-form {
        padding: 20px;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .success-notification {
        right: 10px;
        left: 10px;
        transform: translateY(-100%);
    }
    
    .success-notification.show {
        transform: translateY(0);
    }
}

/* Modal large pour la gestion des équipes */
.modal-content.large {
    max-width: 900px;
    width: 95%;
}

.modal-body {
    padding: 25px;
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.teams-section {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 10px;
}

.section-header h4 {
    color: #232c5a;
    font-size: 16px;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.teams-count {
    background: #232c5a;
    color: white;
    padding: 4px 12px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: 600;
}

.available-filters {
    display: flex;
    gap: 10px;
    align-items: center;
}

.filter-select {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
    min-width: 150px;
}

.teams-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    min-height: 200px;
    max-height: 300px;
    overflow-y: auto;
}

.team-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 15px;
    background: white;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.team-item:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transform: translateY(-1px);
}

.team-info {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
}

.team-logo {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 2px solid #e9ecef;
    object-fit: cover;
}

.team-details {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.team-name {
    font-weight: 600;
    color: #232c5a;
    font-size: 14px;
}

.team-category {
    font-size: 12px;
    color: #666;
    background: #f1f3f4;
    padding: 2px 6px;
    border-radius: 10px;
}

.add-team-btn, .remove-team-btn {
    width: 36px;
    height: 36px;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    transition: all 0.3s ease;
}

.add-team-btn {
    background: #e8f5e8;
    color: #2e7d32;
    border: 1px solid #c8e6c9;
}

.add-team-btn:hover {
    background: #2e7d32;
    color: white;
    transform: scale(1.05);
}

.remove-team-btn {
    background: #fff5f5;
    color: #e53e3e;
    border: 1px solid #fed7d7;
}

.remove-team-btn:hover {
    background: #e53e3e;
    color: white;
    transform: scale(1.05);
}

.loading-state, .error-state, .empty-teams {
    text-align: center;
    padding: 40px 20px;
    color: #666;
    background: white;
    border-radius: 8px;
    border: 2px dashed #e9ecef;
}

.loading-state i, .error-state i, .empty-teams i {
    font-size: 24px;
    margin-bottom: 10px;
    display: block;
}

.error-state {
    color: #e53e3e;
    border-color: #fed7d7;
    background: #fff5f5;
}

.empty-teams {
    color: #28a745;
    border-color: #c8e6c9;
    background: #f0fff0;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    padding: 20px 25px;
    border-top: 1px solid #eee;
    background: #f8f9fa;
    border-radius: 0 0 12px 12px;
}

/* Scrollbar pour les listes d'équipes */
.teams-list::-webkit-scrollbar {
    width: 6px;
}

.teams-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.teams-list::-webkit-scrollbar-thumb {
    background: #232c5a;
    border-radius: 3px;
}

.teams-list::-webkit-scrollbar-thumb:hover {
    background: #1a2147;
}

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

@media (max-width: 768px) {
    .header-actions {
        flex-direction: column;
        gap: 8px;
        align-items: stretch;
    }
    
    .btn-add-phase,
    .btn-add {
        justify-content: center;
        padding: 10px 15px;
    }
    
    .modal-content {
        width: 95%;
        margin: 20px;
    }
    
    .modal-header {
        padding: 15px 20px;
    }
    
    .modal-form {
        padding: 20px;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .success-notification {
        right: 10px;
        left: 10px;
        transform: translateY(-100%);
    }
    
    .success-notification.show {
        transform: translateY(0);
    }
}

/* Modal large pour la gestion des équipes */
.modal-content.large {
    max-width: 900px;
    width: 95%;
}

.modal-body {
    padding: 25px;
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.teams-section {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 10px;
}

.section-header h4 {
    color: #232c5a;
    font-size: 16px;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.teams-count {
    background: #232c5a;
    color: white;
    padding: 4px 12px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: 600;
}

.available-filters {
    display: flex;
    gap: 10px;
    align-items: center;
}

.filter-select {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
    min-width: 150px;
}

.teams-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    min-height: 200px;
    max-height: 300px;
    overflow-y: auto;
}

.team-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 15px;
    background: white;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.team-item:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transform: translateY(-1px);
}

.team-info {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
}

.team-logo {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 2px solid #e9ecef;
    object-fit: cover;
}

.team-details {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.team-name {
    font-weight: 600;
    color: #232c5a;
    font-size: 14px;
}

.team-category {
    font-size: 12px;
    color: #666;
    background: #f1f3f4;
    padding: 2px 6px;
    border-radius: 10px;
}

.add-team-btn, .remove-team-btn {
    width: 36px;
    height: 36px;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    transition: all 0.3s ease;
}

.add-team-btn {
    background: #e8f5e8;
    color: #2e7d32;
    border: 1px solid #c8e6c9;
}

.add-team-btn:hover {
    background: #2e7d32;
    color: white;
    transform: scale(1.05);
}

.remove-team-btn {
    background: #fff5f5;
    color: #e53e3e;
    border: 1px solid #fed7d7;
}

.remove-team-btn:hover {
    background: #e53e3e;
    color: white;
    transform: scale(1.05);
}

.loading-state, .error-state, .empty-teams {
    text-align: center;
    padding: 40px 20px;
    color: #666;
    background: white;
    border-radius: 8px;
    border: 2px dashed #e9ecef;
}

.loading-state i, .error-state i, .empty-teams i {
    font-size: 24px;
    margin-bottom: 10px;
    display: block;
}

.error-state {
    color: #e53e3e;
    border-color: #fed7d7;
    background: #fff5f5;
}

.empty-teams {
    color: #28a745;
    border-color: #c8e6c9;
    background: #f0fff0;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    padding: 20px 25px;
    border-top: 1px solid #eee;
    background: #f8f9fa;
    border-radius: 0 0 12px 12px;
}

/* Scrollbar pour les listes d'équipes */
.teams-list::-webkit-scrollbar {
    width: 6px;
}

.teams-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.teams-list::-webkit-scrollbar-thumb {
    background: #232c5a;
    border-radius: 3px;
}

.teams-list::-webkit-scrollbar-thumb:hover {
    background: #1a2147;
}

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

@media (max-width: 768px) {
    .header-actions {
        flex-direction: column;
        gap: 8px;
        align-items: stretch;
    }
    
    .btn-add-phase,
    .btn-add {
        justify-content: center;
        padding: 10px 15px;
    }
    
    .modal-content {
        width: 95%;
        margin: 20px;
    }
    
    .modal-header {
        padding: 15px 20px;
    }
    
    .modal-form {
        padding: 20px;
    }
    
    .form-actions {
        flex-direction: column;
    }
}
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
