<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Aviron Castrais</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
<div class="sidebar">
    <div class="logo">
        <img src="img/logo.png" alt="Logo Aviron">
        <a href="?route=home" class="logo-text">Aviron Castrais</a>
    </div>
    <div class="sidebar-menu">
        <a href="?route=bigeye" class="sidebar-item <?php if ($route == 'bigeye') echo 'active'; ?>" data-section="bigeye">
            <i class="fas fa-eye"></i>
            <span>Aperçu</span>
        </a>
        <a href="?route=teams" class="sidebar-item <?php if ($route == 'teams') echo 'active'; ?>" data-section="teams">
            <i class="fas fa-users"></i>
            <span>Équipes</span>
        </a>
        <a href="?route=pools" class="sidebar-item <?php if ($route == 'pools') echo 'active'; ?>" data-section="pools">
            <i class="fas fa-trophy"></i>
            <span>Poules</span>
        </a>
        <a href="?route=referees" class="sidebar-item <?php if ($route == 'referees') echo 'active'; ?>" data-section="referees">
            <i class="fas fa-user-tie"></i>
            <span>Arbitres</span>
        </a>
        <a href="?route=ranking" class="sidebar-item <?php if ($route == 'ranking') echo 'active'; ?>" data-section="ranking">
            <i class="fas fa-list-ol"></i>
            <span>Classement</span>
        </a>
        <a href="?route=calendar" class="sidebar-item <?php if ($route == 'calendar') echo 'active'; ?>" data-section="calendar">
            <i class="far fa-calendar-alt"></i>
            <span>Calendrier</span>
        </a>
        <a href="?route=settings" class="sidebar-item <?php if ($route == 'settings') echo 'active'; ?>" data-section="settings">
            <i class="fas fa-cog"></i>
            <span>Paramètres du tournoi</span>
        </a>
    </div>
</div>