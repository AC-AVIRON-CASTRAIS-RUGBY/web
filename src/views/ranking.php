<div class="main-content ranking-page">
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
        <i class="fas fa-trophy"></i> Classement général
    </div>

    <div class="card classification">
        <div class="classification-header">
            <div class="card-title">
                <i class="fas fa-list-ol"></i>
                <span>Classement du tournoi</span>
            </div>
            <div class="print-controls">
                <button class="btn-add" id="printBtn">
                    <i class="fas fa-print"></i> Imprimer
                </button>
            </div>
        </div>

        <?php if (!empty($standing) && is_array($standing)): ?>
            <table class="classification-table">
                <thead>
                <tr>
                    <th>Position</th>
                    <th>Équipe</th>
                    <th><i class="fas fa-gamepad" title="Matchs joués"></i> MJ</th>
                    <th><i class="fas fa-trophy" title="Victoires"></i> V</th>
                    <th><i class="fas fa-times" title="Défaites"></i> D</th>
                    <th><i class="fas fa-handshake" title="Nuls"></i> N</th>
                </tr>
                </thead>
                <tbody>
                <?php
                usort($standing, function($a, $b) {
                    return $a['rank'] - $b['rank'];
                });
                ?>
                <?php foreach ($standing as $team): ?>
                    <tr class="team-row" data-team-id="<?= $team['teamIdsString'] ?>">
                        <td><?= $team['rank'] ?></td>
                        <td class="team-cell">
                            <?php if ($team['logo']): ?>
                                <img src="<?= $team['logo'] ?>" alt="Logo" class="team-logo">
                            <?php else: ?>
                                <div class="team-logo" style="background: linear-gradient(135deg, #232c5a, #1a2147); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                    <?= strtoupper(substr($team['name'], 0, 2)) ?>
                                </div>
                            <?php endif; ?>
                            <span><?= htmlspecialchars($team['name']) ?></span>
                        </td>
                        <td><?= $team['wins'] + $team['losses'] + $team['draws'] ?></td>
                        <td><?= $team['wins'] ?></td>
                        <td><?= $team['losses'] ?></td>
                        <td><?= $team['draws'] ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-ranking">
                <i class="fas fa-chart-bar"></i>
                <h3>Aucun classement disponible</h3>
                <p>Les équipes doivent jouer des matchs pour apparaître dans le classement</p>
                <a href="index.php?route=calendar&tournament_id=<?= $_GET['tournament_id'] ?>" class="btn-add">
                    <i class="fas fa-calendar"></i> Voir le calendrier
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Footer invisible à l'écran mais visible à l'impression -->
    <div class="print-footer">
        <p>Document généré le <?= date('d/m/Y à H:i') ?></p>
        <p>Aviron Castrais - Gestion de tournois</p>
    </div>
</div>

<style>
/* Styles pour les contrôles d'impression */
.print-controls {
    display: flex;
    align-items: center;
    gap: 10px;
}

.orientation-select {
    padding: 5px 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 12px;
    background-color: white;
    color: #333;
}

.orientation-select:focus {
    outline: none;
    border-color: #232c5a;
}

/* Masquer le footer d'impression à l'écran */
.print-footer {
    display: none;
}

@media print {
    /* Masquer les éléments non nécessaires à l'impression */
    .sidebar,
    .header,
    .search-bar,
    .profile,
    .btn-add,
    .classification-header .btn-add,
    .print-controls,
    .orientation-select,
    .welcome {
        display: none !important;
    }
    
    /* Masquer complètement tous les en-têtes et pieds de page par défaut du navigateur */
    @page {
        margin: 1.5cm 1cm;
        @top-left { content: ""; }
        @top-center { content: ""; }
        @top-right { content: ""; }
        @bottom-left { content: ""; }
        @bottom-center { content: ""; }
        @bottom-right { content: ""; }
    }
    
    /* Orientation portrait par défaut */
    @page {
        size: A4 portrait;
    }
    
    /* Orientation paysage si la classe est appliquée */
    .landscape-mode @page {
        size: A4 landscape;
    }
    
    /* Masquer le titre de la page (titre du navigateur) */
    head title {
        display: none;
    }
    
    /* Ajuster la mise en page pour l'impression */
    .main-content {
        margin-left: 0;
        padding: 0;
        width: 100%;
    }
    
    .classification {
        background: white;
        box-shadow: none;
        margin: 0;
        padding: 0;
    }
    
    .classification-header {
        margin-bottom: 10px;
        padding: 0;
        border-bottom: 2px solid #232c5a;
        padding-bottom: 10px;
    }
    
    .card-title {
        justify-content: center;
        font-size: 18px;
        color: #000;
    }
    
    .classification-table {
        font-size: 12px;
        border: 2px solid #232c5a;
        margin-bottom: 30px;
    }
    
    .classification-table th {
        background: #232c5a !important;
        color: white !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    .classification-table td:nth-child(3) {
        background: rgba(35, 44, 90, 0.1) !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    .classification-table td:nth-child(4) {
        background: rgba(56, 161, 105, 0.1) !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    .classification-table td:nth-child(5) {
        background: rgba(229, 62, 62, 0.1) !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    .classification-table td:nth-child(6) {
        background: rgba(245, 166, 35, 0.1) !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    /* Forcer l'affichage des couleurs */
    .classification-table tr:nth-child(1) td:first-child::before,
    .classification-table tr:nth-child(2) td:first-child::before,
    .classification-table tr:nth-child(3) td:first-child::before {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    /* Ajout d'un en-tête d'impression */
    .main-content::before {
        content: "Classement Général - Aviron Castrais";
        display: block;
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 20px;
        border-bottom: 2px solid #232c5a;
        padding-bottom: 10px;
    }
    
    /* Afficher et styliser le footer d'impression */
    .print-footer {
        display: block !important;
        text-align: center;
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid #ddd;
        font-size: 11px;
        color: #666;
        page-break-inside: avoid;
    }
    
    .print-footer p {
        margin: 5px 0;
    }
    
    .print-footer p:first-child {
        font-weight: bold;
        color: #333;
    }
    
    /* Ajustements pour l'orientation paysage */
    .landscape-mode .classification-table {
        font-size: 14px;
    }
    
    .landscape-mode .classification-table th,
    .landscape-mode .classification-table td {
        padding: 12px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const printBtn = document.getElementById('printBtn');
    
    if (printBtn) {
        printBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            setTimeout(() => {
                window.print();
            }, 100);
        });
    }
    
    
    window.addEventListener('afterprint', function() {
        document.body.classList.remove('landscape-mode');
    });
});
</script>
