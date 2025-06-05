<?php
session_start();

require_once '../src/lib/ApiClient.php';
require_once '../src/controllers/AuthController.php';

$route = $_GET['route'] ?? 'home';

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
        $schedule = $api->get('schedule/tournaments/'.$tournament_id);
        $categories = $api->get('categories/tournaments/' . $tournament_id);

        // Récupérer le nombre de matchs pour chaque arbitre
        foreach ($referees as &$referee) {
            try {
                $refereeGames = $api->get('referees/' . $referee['Referee_Id'] . '/games');
                $referee['games_count'] = is_array($refereeGames) ? count($refereeGames) : 0;
            } catch (Exception $e) {
                // En cas d'erreur, définir le nombre de matchs à 0
                $referee['games_count'] = 0;
            }
        }

        require_once '../src/views/includes/header.php';
        require_once '../src/views/dashboard.php';
        break;

    case 'teams':
        $tournament_id = $_GET['tournament_id'] ?? null;

        if(!$tournament_id) {
            header('Location: index.php?route=home');
            exit;
        }

        $api = new ApiClient();
        $teams = $api->get('teams/' . $tournament_id);
        $categories = $api->get('categories/tournaments/' . $tournament_id);

        // Trier les équipes par ordre alphabétique
        if (!empty($teams)) {
            usort($teams, function($a, $b) {
                return strcasecmp($a['name'], $b['name']);
            });
        }

        require_once '../src/views/includes/header.php';
        require_once '../src/views/teams.php';
        break;

    case 'referees':
        $tournament_id = $_GET['tournament_id'] ?? null;

        if(!$tournament_id) {
            header('Location: index.php?route=home');
            exit;
        }

        $api = new ApiClient();
        $referees = $api->get('referees/tournaments/' . $tournament_id);

        // Récupérer le nombre de matchs pour chaque arbitre
        foreach ($referees as &$referee) {
            try {
                $refereeGames = $api->get('referees/' . $referee['Referee_Id'] . '/games');
                $referee['games_count'] = is_array($refereeGames) ? count($refereeGames) : 0;
            } catch (Exception $e) {
                $referee['games_count'] = 0;
            }
        }

        require_once '../src/views/includes/header.php';
        require_once '../src/views/referees.php';
        break;

    case 'ranking':
        $tournament_id = $_GET['tournament_id'] ?? null;

        if(!$tournament_id) {
            header('Location: index.php?route=home');
            exit;
        }

        $api = new ApiClient();
        $standing = $api->get('pools/tournaments/'.$tournament_id.'/standings/all');

        require_once '../src/views/includes/header.php';
        require_once '../src/views/ranking.php';
        break;

    case 'calendar':
        $tournament_id = $_GET['tournament_id'] ?? null;

        if(!$tournament_id) {
            header('Location: index.php?route=home');
            exit;
        }

        $api = new ApiClient();
        $schedule = $api->get('schedule/tournaments/'.$tournament_id);

        require_once '../src/views/includes/header.php';
        require_once '../src/views/calendar.php';
        break;

    case 'pools':
        $tournament_id = $_GET['tournament_id'] ?? null;

        if(!$tournament_id) {
            header('Location: index.php?route=home');
            exit;
        }

        $api = new ApiClient();
        $pools = $api->get('pools/tournaments/' . $tournament_id);
        $categories = $api->get('categories/tournaments/' . $tournament_id);
        $phases = $api->get('phases/tournaments/' . $tournament_id);

        require_once '../src/views/includes/header.php';
        require_once '../src/views/pools.php';
        break;

    case 'settings':
        $tournament_id = $_GET['tournament_id'] ?? null;

        if(!$tournament_id) {
            header('Location: index.php?route=home');
            exit;
        }

        $api = new ApiClient();
        $tournament = $api->get('tournaments/' . $tournament_id);

        require_once '../src/views/includes/header.php';
        require_once '../src/views/settings.php';
        break;

    case 'match-details':
        $tournament_id = $_GET['tournament_id'] ?? null;
        $match_id = $_GET['match_id'] ?? null;

        if(!$tournament_id || !$match_id) {
            header('Location: index.php?route=home');
            exit;
        }

        $api = new ApiClient();
        try {
            // Récupérer le match spécifique
            $allGames = $api->get('games');
            $match = null;
            
            foreach ($allGames as $game) {
                if ($game['Game_Id'] == $match_id && $game['Tournament_Id'] == $tournament_id) {
                    $match = $game;
                    break;
                }
            }
            
            // Enrichir les données du match avec les informations des équipes, arbitre, poule
            if (!empty($match)) {
                // Récupérer les équipes
                $teams = $api->get('teams/' . $tournament_id);
                $team1 = null;
                $team2 = null;
                
                foreach ($teams as $team) {
                    if ($team['Team_Id'] == $match['Team1_Id']) {
                        $team1 = $team;
                    }
                    if ($team['Team_Id'] == $match['Team2_Id']) {
                        $team2 = $team;
                    }
                }
                
                // Récupérer l'arbitre
                $referees = $api->get('referees/tournaments/' . $tournament_id);
                $referee = null;
                
                foreach ($referees as $ref) {
                    if ($ref['Referee_Id'] == $match['Referee_Id']) {
                        $referee = $ref;
                        break;
                    }
                }
                
                // Récupérer la poule
                $pools = $api->get('pools/tournaments/' . $tournament_id);
                $pool = null;
                
                foreach ($pools as $p) {
                    if ($p['Pool_Id'] == $match['Pool_Id']) {
                        $pool = $p;
                        break;
                    }
                }
                
                // Récupérer le terrain (si nécessaire)
                $field = null;
                if ($match['Field_Id']) {
                    try {
                        $field = $api->get('fields/' . $match['Field_Id']);
                    } catch (Exception $e) {
                        $field = null;
                    }
                }
                
                $match['team1'] = $team1;
                $match['team2'] = $team2;
                $match['referee'] = $referee;
                $match['pool'] = $pool;
                $match['field'] = $field;
            }
        } catch (Exception $e) {
            $match = null;
        }

        require_once '../src/views/includes/header.php';
        require_once '../src/views/match-details.php';
        break;

    case 'team-create':
        $tournament_id = $_GET['tournament_id'] ?? null;
        $error = null;

        if(!$tournament_id) {
            header('Location: index.php?route=home');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $api = new ApiClient();
            
            try {
                // Handle image upload if provided
                $logoUrl = null;
                if (isset($_FILES['team_logo']) && $_FILES['team_logo']['error'] === UPLOAD_ERR_OK) {
                    try {
                        $uploadResponse = $api->uploadImage($_FILES['team_logo']['tmp_name']);
                        $logoUrl = $uploadResponse['url'] ?? null;
                    } catch (Exception $e) {
                        error_log('Image upload error: ' . $e->getMessage());
                        // Continue without image if upload fails
                    }
                }

                // Create team data
                $teamData = [
                    'name' => $_POST['team_name'],
                    'tournament_id' => $tournament_id
                ];

                // Add optional fields if provided
                if (!empty($_POST['age_category'])) {
                    $teamData['age_category'] = $_POST['age_category'];
                }

                if ($logoUrl) {
                    $teamData['logo'] = $logoUrl;
                }

                // Create the team
                $result = $api->post('teams/' . $tournament_id . '/teams', $teamData);
                
                header('Location: index.php?route=teams&tournament_id=' . $tournament_id);
                exit;
                
            } catch (Exception $e) {
                error_log('Team creation error: ' . $e->getMessage());
                $error = 'Erreur lors de la création de l\'équipe: ' . $e->getMessage();
            }
        }

        require_once '../src/views/includes/header.php';
        require_once '../src/views/team-create.php';
        break;

    case 'team-update':
        $tournament_id = $_GET['tournament_id'] ?? null;
        $team_id = $_GET['team_id'] ?? null;
        $error = null;

        if(!$tournament_id || !$team_id) {
            header('Location: index.php?route=home');
            exit;
        }

        $api = new ApiClient();
        
        // Get current team data first
        $team = null;
        try {
            $teams = $api->get('teams/' . $tournament_id);
            foreach ($teams as $t) {
                if ($t['Team_Id'] == $team_id) {
                    $team = $t;
                    break;
                }
            }
        } catch (Exception $e) {
            header('Location: index.php?route=teams&tournament_id=' . $tournament_id);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Update team
            $teamData = [
                'name' => $_POST['team_name']
            ];
            
            try {
                $api->put('teams/' . $team_id, $teamData);
                header('Location: index.php?route=teams&tournament_id=' . $tournament_id);
                exit;
            } catch (Exception $e) {
                $error = 'Erreur lors de la mise à jour de l\'équipe';
            }
        }

        require_once '../src/views/includes/header.php';
        require_once '../src/views/team-update.php';
        break;

    case 'ajax-team-update':
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }

        $tournament_id = $_POST['tournament_id'] ?? null;
        $team_id = $_POST['team_id'] ?? null;
        $team_name = $_POST['team_name'] ?? '';
        $age_category = $_POST['age_category'] ?? '';

        if (!$tournament_id || !$team_id || !$team_name) {
            http_response_code(400);
            echo json_encode(['error' => 'Paramètres manquants']);
            exit;
        }

        try {
            $api = new ApiClient();
            
            // Handle image upload if provided
            $logoUrl = null;
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                try {
                    $uploadResponse = $api->uploadImage($_FILES['logo']['tmp_name']);
                    $logoUrl = $uploadResponse['url'] ?? null;
                } catch (Exception $e) {
                    http_response_code(500);
                    echo json_encode(['error' => 'Erreur lors de l\'upload de l\'image: ' . $e->getMessage()]);
                    exit;
                }
            }

            // Prepare team data
            $teamData = [
                'name' => $team_name,
                'age_category' => $age_category
            ];

            if ($logoUrl) {
                $teamData['logo'] = $logoUrl;
            }

            // Update team
            $result = $api->put("teams/{$tournament_id}/teams/{$team_id}", $teamData);
            
            echo json_encode([
                'success' => true,
                'message' => 'Équipe mise à jour avec succès',
                'data' => $result
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de la mise à jour: ' . $e->getMessage()]);
        }
        exit;

    case 'debug-upload':
        require_once '../src/views/debug-upload.php';
        break;

    default:
        header('HTTP/1.0 404 Not Found');
        echo '<h1>404 - Page Not Found</h1>';
        echo '<p>The requested page could not be found.</p>';
        echo '<a href="index.php?route=home">Return to Home</a>';
        break;
}