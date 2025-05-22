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
                <span>Calendrier</span>
            </div>
            <button class="btn-consult">Consulter</button>
        </div>

        <div class="category-filter">
            <button class="category-btn u6" data-category="u6">U6</button>
            <button class="category-btn u8" data-category="u8">U8</button>
            <button class="category-btn u10" data-category="u10">U10</button>
            <button class="category-btn u12" data-category="u12">U12</button>
            <button class="category-btn u14" data-category="u14">U14</button>
        </div>

        <div class="calendar-items" id="calendarItems">
            <?php if (!empty($schedule) && isset($schedule['schedule'])): ?>
                <?php
                $allGames = [];
                // Extraire tous les matchs dans un tableau plat
                foreach ($schedule['schedule'] as $poolName => $games) {
                    foreach ($games as $game) {
                        $game['poolName'] = $poolName; // Ajouter le nom de la poule au match
                        $allGames[] = $game;
                    }
                }

                // Trier les matchs par heure de début si nécessaire
                usort($allGames, function($a, $b) {
                    return strtotime($a['startTime']) - strtotime($b['startTime']);
                });

                // Afficher tous les matchs
                foreach ($allGames as $game):
                    $startTime = new DateTime($game['startTime']);
                    $formattedTime = $startTime->format('H:i');
                    ?>
                    <div class="calendar-item">
                        <div class="time"><?= $formattedTime ?></div>
                        <div class="match">
                            <div class="team">
                                <img src="/api/placeholder/30/30" alt="Logo" class="team-logo">
                                <span><?= htmlspecialchars($game['team1']['name']) ?></span>
                            </div>
                            <div class="vs">vs</div>
                            <div class="team">
                                <img src="/api/placeholder/30/30" alt="Logo" class="team-logo">
                                <span><?= htmlspecialchars($game['team2']['name']) ?></span>
                            </div>
                        </div>
                        <div class="referee">
                            <i class="fas fa-bullhorn"></i>
                            <span><?= htmlspecialchars($game['referee']) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-calendar">
                    <p>Aucun match planifié</p>
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
                <button class="btn-add" id="addTeamBtn">
                    Ajouter <i class="fas fa-plus"></i>
                </button>
            </div>
            <div class="teams-list">
                <?php

                foreach ($teams as $team) {
                    ?>
                    <div class="team-item">
                        <img src="/api/teams/<?php echo $team['Team_Id']; ?>/logo" alt="Logo de l'équipe">
                        <span>
                            <?php echo $team['name']; ?>
                        </span>
                    </div>
                    <?php
                }

                ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-gavel"></i>
                    <span>Arbitres</span>
                </div>
                <button class="btn-add" id="addRefereeBtn">
                    Ajouter <i class="fas fa-plus"></i>
                </button>
            </div>
            <div class="referees-list" id="refereesList">
                <?php

                foreach ($referees as $referee) {
                    ?>
                    <div class="referee-item">
                        <div class="referee-name">
                            <?php echo $referee['last_name']; ?> <?php echo $referee['first_name']; ?>
                        </div>
                        <div class="edit-btn"><i class="fas fa-pen"></i></div>
                    </div>
                    <?php
                }

                ?>
            </div>
        </div>
    </div>

    <div class="card classification">
        <div class="classification-header">
            <div class="card-title">
                <i class="fas fa-list-ol"></i>
                <span>Classement général</span>
            </div>
            <button class="btn-consult">Consulter</button>
        </div>

        <!--<div class="classification-filters">
            <button class="category-btn u6" data-category="u6">U6</button>
            <button class="category-btn u8" data-category="u8">U8</button>
            <button class="category-btn u10" data-category="u10">U10</button>
            <button class="category-btn u12" data-category="u12">U12</button>
            <button class="category-btn u14" data-category="u14">U14</button>
        </div>-->

        <table class="classification-table">
            <thead>
            <tr>
                <th></th>
                <th>V</th>
                <th>D</th>
                <th>N</th>
            </tr>
            </thead>
            <tbody id="classificationTableBody">

            <?php
            if (!empty($standing) && is_array($standing)) {
                // Trier par rang (utilise déjà le rang fourni par l'API)
                usort($standing, function($a, $b) {
                    return $a['rank'] - $b['rank'];
                });

                // Afficher les équipes
                foreach ($standing as $index => $team) {
                    ?>
                    <tr class="team-row" data-team-id="<?= $team['Team_Id'] ?>">
                        <td class="team-cell">
                            <span><?= $team['rank'] ?></span>
                            <?php if ($team['logo']): ?>
                                <img src="/api/teams/<?= $team['Team_Id'] ?>/logo" alt="Logo" class="team-logo">
                            <?php else: ?>
                                <div class="team-logo"></div>
                            <?php endif; ?>
                            <span><?= htmlspecialchars($team['name']) ?></span>
                        </td>
                        <td><?= $team['wins'] ?></td>
                        <td><?= $team['losses'] ?></td>
                        <td><?= $team['draws'] ?></td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="4">Aucune donnée de classement disponible</td>
                </tr>
                <?php
            }
            ?>

            </tbody>
        </table>
    </div>
</div>