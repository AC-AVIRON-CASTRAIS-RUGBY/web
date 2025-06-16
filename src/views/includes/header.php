<!DOCTYPE html>
<html lang="fr">
<head>    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Aviron Castrais</title>
    <link rel="icon" href="img/favicon/favicon.ico">
    <link rel="icon" type="image/svg+xml" href="img/favicon/favicon.svg">
    <link rel="apple-touch-icon" href="img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicon/favicon-16x16.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/teams.css">
    <link rel="stylesheet" href="css/pagination.css">
</head>
<body>
<!-- Bouton burger pour mobile -->
<button class="burger-btn" id="burgerBtn">
    <div class="burger-line"></div>
    <div class="burger-line"></div>
    <div class="burger-line"></div>
</button>

<!-- Overlay pour fermer le menu -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="sidebar" id="sidebar">
    <div class="logo">
        <img src="img/logo.png" alt="Logo Aviron">
        <a href="?route=home" class="logo-text">Aviron Castrais</a>
    </div>
    <div class="sidebar-menu">
        <?php $tournament_id = $_GET['tournament_id'] ?? ''; ?>
        <a href="?route=bigeye<?= $tournament_id ? '&tournament_id=' . $tournament_id : '' ?>" class="sidebar-item <?php if ($route == 'bigeye') echo 'active'; ?>" data-section="bigeye">
            <i class="fas fa-eye"></i>
            <span>Aperçu</span>
        </a>
        <a href="?route=teams<?= $tournament_id ? '&tournament_id=' . $tournament_id : '' ?>" class="sidebar-item <?php if ($route == 'teams') echo 'active'; ?>" data-section="teams">
            <i class="fas fa-users"></i>
            <span>Équipes</span>
        </a>
        <a href="?route=pools<?= $tournament_id ? '&tournament_id=' . $tournament_id : '' ?>" class="sidebar-item <?php if ($route == 'pools') echo 'active'; ?>" data-section="pools">
            <i class="fas fa-trophy"></i>
            <span>Poules</span>
        </a>
        <a href="?route=referees<?= $tournament_id ? '&tournament_id=' . $tournament_id : '' ?>" class="sidebar-item <?php if ($route == 'referees') echo 'active'; ?>" data-section="referees">
            <i class="fas fa-user-tie"></i>
            <span>Arbitres</span>
        </a>
        <a href="?route=ranking<?= $tournament_id ? '&tournament_id=' . $tournament_id : '' ?>" class="sidebar-item <?php if ($route == 'ranking') echo 'active'; ?>" data-section="ranking">
            <i class="fas fa-list-ol"></i>
            <span>Classement</span>
        </a>
        <a href="?route=calendar<?= $tournament_id ? '&tournament_id=' . $tournament_id : '' ?>" class="sidebar-item <?php if ($route == 'calendar') echo 'active'; ?>" data-section="calendar">
            <i class="far fa-calendar-alt"></i>
            <span>Calendrier</span>
        </a>
        <a href="?route=settings<?= $tournament_id ? '&tournament_id=' . $tournament_id : '' ?>" class="sidebar-item <?php if ($route == 'settings') echo 'active'; ?>" data-section="settings">
            <i class="fas fa-cog"></i>
            <span>Paramètres du tournoi</span>
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const burgerBtn = document.getElementById('burgerBtn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const sidebarItems = document.querySelectorAll('.sidebar-item');

    // Toggle du menu burger
    burgerBtn.addEventListener('click', function() {
        burgerBtn.classList.toggle('active');
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
        burgerBtn.classList.toggle('menu-open');
    });

    // Fermer le menu en cliquant sur l'overlay
    overlay.addEventListener('click', function() {
        closeMobileMenu();
    });

    // Fermer le menu en cliquant sur un item (mobile)
    sidebarItems.forEach(item => {
        item.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                closeMobileMenu();
            }
        });
    });

    // Fermer le menu avec la touche Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeMobileMenu();
        }
    });

    // Gérer le redimensionnement de la fenêtre
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            closeMobileMenu();
        }
    });

    function closeMobileMenu() {
        burgerBtn.classList.remove('active');
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        burgerBtn.classList.remove('menu-open');
    }
});
</script>
</body>
</html>