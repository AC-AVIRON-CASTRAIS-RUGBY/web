<div class="main-content">
    <div class="header">
        <div class="search-bar">
            <input type="text" placeholder="Rechercher un tournoi" id="searchTournament">
            <button type="submit" id="searchButton"><i class="fas fa-search"></i></button>
        </div>
        <div class="profile">
            <img src="img/logo.png" alt="Profil">
        </div>
    </div>

    <div class="welcome">
        Bonjour, <?= htmlspecialchars($_SESSION['username']) ?>
    </div>

    <!-- Section des tournois -->
    <div class="tournaments-container">
        <div class="tournaments-header">
            <h2><i class="fas fa-trophy"></i> Mes tournois</h2>
            <button class="btn-add" id="createTournamentBtn">
                Créer un tournoi <i class="fas fa-plus"></i>
            </button>
        </div>

        <?php if (empty($tournaments)): ?>
            <div class="empty-state">
                <i class="fas fa-trophy empty-icon"></i>
                <p>Vous n'avez pas encore créé de tournoi</p>
                <button class="btn-add" id="createFirstTournamentBtn">
                    Créer mon premier tournoi
                </button>
            </div>
        <?php else: ?>
            <div class="tournaments-grid">
                <?php foreach ($tournaments as $tournament): ?>
                    <div class="tournament-card">
                        <div class="tournament-header">
                            <h3><?= htmlspecialchars($tournament['name']) ?></h3>
                            <span class="tournament-date"><?= date('d/m/Y', strtotime($tournament['start_date'])) ?></span>
                        </div>

                        <div class="tournament-info">
                            <div class="info-item">
                                <i class="fas fa-users"></i>
                                <span><?= $tournament['teams_count'] ?? 0 ?> équipes</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-layer-group"></i>
                                <span><?= $tournament['pools_count'] ?? 0 ?> poules</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-gamepad"></i>
                                <span><?= $tournament['games_count'] ?? 0 ?> matchs</span>
                            </div>
                        </div>

                        <div class="tournament-actions">
                            <a href="index.php?route=bigeye&tournament_id=<?= $tournament['Tournament_Id'] ?>" class="btn-view">
                                <i class="fas fa-eye"></i> Consulter
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>