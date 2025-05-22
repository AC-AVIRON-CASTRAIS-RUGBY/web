<?php
session_start();

require_once '../src/lib/ApiClient.php';
require_once '../src/controllers/AuthController.php';

$route = $_GET['route'];

if (!isset($_SESSION['user_id']) && $route !== 'login' && $route !== 'auth') {
    header('Location: index.php?route=login');
    exit;
}

switch ($route) {
    case 'login':
        require_once '../src/views/auth/login.php';
        break;

    case 'auth':
        $authController = new AuthController();
        $authController->login();
        break;

    case 'logout':
        session_destroy();
        header('Location: index.php?route=login');
        break;

    case 'home':
        $api = new ApiClient();
        $tournaments = $api->get('referees/' . $_SESSION['user_id'] . '/tournaments');

        require_once '../src/views/includes/header.php';
        require_once '../src/views/home.php';
        break;

    case 'bigeye':
        $tournament_id = $_GET['tournament_id'] ?? null;

        if(!$tournament_id) {
            header('Location: index.php?route=home');
            exit;
        }

        $api = new ApiClient();
        $teams = $api->get('teams/' . $tournament_id);
        $referees = $api->get('referees/tournaments/' . $tournament_id);
        $standing = $api->get('pools/tournaments/'.$tournament_id.'/standings/all');
        $schedule = $api->get('schedule/tournaments/'.$tournament_id);

        require_once '../src/views/includes/header.php';
        require_once '../src/views/dashboard.php';
        break;
    default:
        header('HTTP/1.0 404 Not Found');
        require_once '../src/views/404.php';
        break;
}