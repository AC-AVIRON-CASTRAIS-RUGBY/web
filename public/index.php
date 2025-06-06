<?php
session_start();

//SHOW ERRORS
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

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
        
        // Initialize with empty arrays to handle API failures gracefully
        $teams = [];
        $referees = [];
        $schedule = [];
        $categories = [];
        
        try {
            $teams = $api->get('teams/tournaments/' . $tournament_id) ?: [];
        } catch (Exception $e) {
            error_log('Error fetching teams: ' . $e->getMessage());
            $teams = [];
        }
        
        try {
            $referees = $api->get('referees/tournaments/' . $tournament_id) ?: [];
        } catch (Exception $e) {
            error_log('Error fetching referees: ' . $e->getMessage());
            $referees = [];
        }
        
        try {
            $schedule = $api->get('schedule/tournaments/'.$tournament_id) ?: [];
        } catch (Exception $e) {
            error_log('Error fetching schedule: ' . $e->getMessage());
            $schedule = [];
        }
        
        try {
            $categories = $api->get('categories/tournaments/' . $tournament_id) ?: [];
        } catch (Exception $e) {
            error_log('Error fetching categories: ' . $e->getMessage());
            $categories = [];
        }

        // Récupérer le nombre de matchs pour chaque arbitre
        foreach ($referees as &$referee) {
            try {
                $refereeGames = $api->get('referees/' . $referee['Referee_Id'] . '/games');
                $referee['games_count'] = is_array($refereeGames) ? count($refereeGames) : 0;
            } catch (Exception $e) {
                // En cas d'erreur, définir le nombre de matchs à 0
                $referee['games_count'] = 0;
                error_log('Error fetching games for referee ' . $referee['Referee_Id'] . ': ' . $e->getMessage());
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
        $teams = $api->get('teams/tournaments/' . $tournament_id);
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
        
        try {
            $referees = $api->get('referees/tournaments/' . $tournament_id) ?: [];
        } catch (Exception $e) {
            error_log('Error fetching referees: ' . $e->getMessage());
            $referees = [];
        }

        // Récupérer le nombre de matchs pour chaque arbitre
        foreach ($referees as &$referee) {
            try {
                $refereeGames = $api->get('referees/' . $referee['Referee_Id'] . '/games');
                $referee['games_count'] = is_array($refereeGames) ? count($refereeGames) : 0;
            } catch (Exception $e) {
                $referee['games_count'] = 0;
                error_log('Error fetching games for referee ' . $referee['Referee_Id'] . ': ' . $e->getMessage());
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

    case 'create-team':
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }

        $tournament_id = $_GET['tournament_id'] ?? null;
        
        if (!$tournament_id) {
            http_response_code(400);
            echo json_encode(['error' => 'Tournament ID is required']);
            exit;
        }

        try {
            $api = new ApiClient();
            
            // Debug des données reçues
            error_log('Create team - POST data: ' . print_r($_POST, true));
            error_log('Create team - FILES data: ' . print_r($_FILES, true));
            
            // Handle image upload if provided
            $logoUrl = null;
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                try {
                    $uploadResponse = $api->uploadImage($_FILES['logo']['tmp_name']);
                    $logoUrl = $uploadResponse['url'] ?? null;
                    error_log('Create team - Image uploaded: ' . $logoUrl);
                } catch (Exception $e) {
                    error_log('Create team - Image upload error: ' . $e->getMessage());
                    http_response_code(500);
                    echo json_encode(['error' => 'Erreur lors de l\'upload de l\'image: ' . $e->getMessage()]);
                    exit;
                }
            }

            // Get form data
            $teamName = $_POST['team_name'] ?? $_POST['name'] ?? '';
            $categoryId = $_POST['category_id'] ?? $_POST['age_category'] ?? '';
            
            if (!$teamName) {
                http_response_code(400);
                echo json_encode(['error' => 'Le nom de l\'équipe est requis']);
                exit;
            }

            // Prepare team data - format simple pour l'API
            $teamData = [
                'name' => $teamName
            ];

            if ($logoUrl) {
                $teamData['logo'] = $logoUrl;
            }

            // Ajouter l'ID de catégorie si fourni et non vide
            if (!empty($categoryId)) {
                $teamData['Category_Id'] = (int)$categoryId;
            }

            error_log('Create team - Sending to API: ' . json_encode($teamData));
            error_log('Create team - API endpoint: teams/tournaments/' . $tournament_id);

            // Create team via API
            $result = $api->post("teams/tournaments/{$tournament_id}", $teamData);
            
            error_log('Create team - API response: ' . json_encode($result));
            
            echo json_encode([
                'success' => true,
                'message' => 'Équipe créée avec succès',
                'data' => $result
            ]);

        } catch (Exception $e) {
            error_log('Team creation error: ' . $e->getMessage());
            error_log('Team creation error trace: ' . $e->getTraceAsString());
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de la création de l\'équipe: ' . $e->getMessage()]);
        }
        exit;

    case 'update-team':
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }

        $tournament_id = $_GET['tournament_id'] ?? $_POST['tournament_id'] ?? null;
        $team_id = $_GET['team_id'] ?? $_POST['team_id'] ?? null;
        
        if (!$tournament_id || !$team_id) {
            http_response_code(400);
            echo json_encode(['error' => 'Tournament ID and Team ID are required']);
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

            // Get data (from JSON for PUT, from POST for multipart)
            if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                $input = file_get_contents('php://input');
                $teamData = json_decode($input, true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Invalid JSON data']);
                    exit;
                }
            } else {
                // POST with multipart (for file uploads)
                $teamData = [
                    'name' => $_POST['team_name'] ?? $_POST['name'] ?? '',
                ];
                
                $categoryId = $_POST['category_id'] ?? $_POST['age_category'] ?? '';
                if (!empty($categoryId)) {
                    $teamData['Category_Id'] = (int)$categoryId;
                }
            }

            if (empty($teamData['name'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Le nom de l\'équipe est requis']);
                exit;
            }

            if ($logoUrl) {
                $teamData['logo'] = $logoUrl;
            }

            // Update team via API
            $result = $api->put("teams/tournaments/{$tournament_id}/{$team_id}", $teamData);
            
            echo json_encode([
                'success' => true,
                'message' => 'Équipe mise à jour avec succès',
                'data' => $result
            ]);

        } catch (Exception $e) {
            error_log('Team update error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de la mise à jour de l\'équipe: ' . $e->getMessage()]);
        }
        exit;

    case 'create-tournament':
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }

        try {
            $api = new ApiClient();
            
            // Récupérer les données JSON
            $input = file_get_contents('php://input');
            $tournamentData = json_decode($input, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid JSON data']);
                exit;
            }

            // Validation des données requises
            if (empty($tournamentData['name']) || empty($tournamentData['location']) || empty($tournamentData['start_date'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Nom, lieu et date de début sont requis']);
                exit;
            }

            // Convertir la date au format ISO
            try {
                $date = new DateTime($tournamentData['start_date']);
                $tournamentData['start_date'] = $date->format('c'); // Format ISO 8601
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['error' => 'Format de date invalide']);
                exit;
            }

            // Valeurs par défaut pour les champs optionnels
            $tournamentData['description'] = $tournamentData['description'] ?? '';
            $tournamentData['break_time'] = (int)($tournamentData['break_time'] ?? 5);
            $tournamentData['points_win'] = (int)($tournamentData['points_win'] ?? 3);
            $tournamentData['points_draw'] = (int)($tournamentData['points_draw'] ?? 1);
            $tournamentData['points_loss'] = (int)($tournamentData['points_loss'] ?? 0);

            error_log($tournamentData['image']);

            // Créer le tournoi via l'API
            $result = $api->post('tournaments', $tournamentData);
            
            echo json_encode([
                'success' => true,
                'message' => 'Tournoi créé avec succès',
                'data' => $result
            ]);

        } catch (Exception $e) {
            error_log('Tournament creation error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de la création du tournoi: ' . $e->getMessage()]);
        }
        exit;

    case 'debug-upload':
        require_once '../src/views/debug-upload.php';
        break;

    case 'upload-image':
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }

        if (!isset($_FILES['image'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Aucun fichier image fourni']);
            exit;
        }

        $imageFile = $_FILES['image'];

        if ($imageFile['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(['error' => 'Erreur lors de l\'upload du fichier']);
            exit;
        }

        try {
            $api = new ApiClient();
            $result = $api->uploadImage($imageFile['tmp_name']);

            error_log('Image upload result: ' . print_r($result, true));

            echo json_encode([
                'success' => true,
                'message' => 'Image uploadée avec succès',
                'url' => $result['url'],
                'filename' => $result['filename'] ?? null
            ]);

        } catch (Exception $e) {
            error_log('Image upload error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de l\'upload: ' . $e->getMessage()]);
        }
        exit;

    case 'create-category':
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }

        $tournament_id = $_GET['tournament_id'] ?? null;
        
        if (!$tournament_id) {
            http_response_code(400);
            echo json_encode(['error' => 'Tournament ID is required']);
            exit;
        }

        try {
            $api = new ApiClient();
            
            // Get JSON data
            $input = file_get_contents('php://input');
            $categoryData = json_decode($input, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid JSON data']);
                exit;
            }

            // Validate required fields
            if (empty($categoryData['name'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Category name is required']);
                exit;
            }

            // Set defaults for optional fields
            $categoryData['age_min'] = (int)($categoryData['age_min'] ?? 0);
            $categoryData['age_max'] = (int)($categoryData['age_max'] ?? 0);
            $categoryData['description'] = $categoryData['description'] ?? '';
            $categoryData['game_duration'] = (int)($categoryData['game_duration'] ?? 10);

            // Create category via API
            $result = $api->post('categories/tournaments/' . $tournament_id, $categoryData);
            
            echo json_encode([
                'success' => true,
                'message' => 'Catégorie créée avec succès',
                'data' => $result
            ]);

        } catch (Exception $e) {
            error_log('Category creation error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de la création de la catégorie: ' . $e->getMessage()]);
        }
        exit;

    case 'update-category':
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }

        $tournament_id = $_GET['tournament_id'] ?? null;
        $category_id = $_GET['category_id'] ?? null;
        
        if (!$tournament_id || !$category_id) {
            http_response_code(400);
            echo json_encode(['error' => 'Tournament ID and Category ID are required']);
            exit;
        }

        try {
            $api = new ApiClient();
            
            // Get JSON data
            $input = file_get_contents('php://input');
            $categoryData = json_decode($input, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid JSON data']);
                exit;
            }

            // Validate required fields
            if (empty($categoryData['name'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Category name is required']);
                exit;
            }

            // Set defaults for optional fields
            $categoryData['age_min'] = (int)($categoryData['age_min'] ?? 0);
            $categoryData['age_max'] = (int)($categoryData['age_max'] ?? 0);
            $categoryData['description'] = $categoryData['description'] ?? '';
            $categoryData['game_duration'] = (int)($categoryData['game_duration'] ?? 10);

            // Update category via API
            $result = $api->put('categories/tournaments/' . $tournament_id . '/' . $category_id, $categoryData);
            
            echo json_encode([
                'success' => true,
                'message' => 'Catégorie mise à jour avec succès',
                'data' => $result
            ]);

        } catch (Exception $e) {
            error_log('Category update error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de la mise à jour de la catégorie: ' . $e->getMessage()]);
        }
        exit;

    case 'create-referee':
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }

        $tournament_id = $_GET['tournament_id'] ?? null;
        
        if (!$tournament_id) {
            http_response_code(400);
            echo json_encode(['error' => 'Tournament ID is required']);
            exit;
        }

        try {
            $api = new ApiClient();
            
            // Get JSON data
            $input = file_get_contents('php://input');
            error_log('Create referee - Raw input: ' . $input);
            
            $refereeData = json_decode($input, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid JSON data: ' . json_last_error_msg()]);
                exit;
            }

            // Log received data
            error_log('Create referee - Parsed data: ' . print_r($refereeData, true));

            // Validate required fields with original names
            if (empty($refereeData['first_name']) || empty($refereeData['last_name']) || 
                empty($refereeData['loginUUID']) || empty($refereeData['password'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Prénom, nom, identifiant et mot de passe sont requis']);
                exit;
            }

            // Format data with exact API variable names
            $apiData = [
                'firstName' => trim($refereeData['first_name']),
                'lastName' => trim($refereeData['last_name']),
                'loginUUID' => trim($refereeData['loginUUID']),
                'password' => trim($refereeData['password']),
                'tournamentId' => (int)$tournament_id
            ];

            // Vérifier à nouveau après trim
            if (empty($apiData['firstName']) || empty($apiData['lastName']) || 
                empty($apiData['loginUUID']) || empty($apiData['password'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Tous les champs doivent être remplis (pas seulement des espaces)']);
                exit;
            }

            // Validation supplémentaire de la longueur des champs
            if (strlen($apiData['firstName']) < 2 || strlen($apiData['lastName']) < 2) {
                http_response_code(400);
                echo json_encode(['error' => 'Le prénom et le nom doivent contenir au moins 2 caractères']);
                exit;
            }

            if (strlen($apiData['loginUUID']) < 4 || strlen($apiData['password']) < 4) {
                http_response_code(400);
                echo json_encode(['error' => 'L\'identifiant et le mot de passe doivent contenir au moins 4 caractères']);
                exit;
            }
            
            // Log pour debug
            error_log('Create referee - Final API data: ' . json_encode($apiData));
            error_log('Create referee - Tournament ID: ' . $tournament_id);
            error_log('Create referee - API endpoint: referees');

            // Create referee via API
            $result = $api->post('referees', $apiData);
            
            error_log('Create referee - API success response: ' . json_encode($result));
            
            echo json_encode([
                'success' => true,
                'message' => 'Arbitre créé avec succès',
                'data' => $result
            ]);

        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
            error_log('=== REFEREE CREATION ERROR ===');
            error_log('Error message: ' . $errorMessage);
            error_log('Error file: ' . $e->getFile());
            error_log('Error line: ' . $e->getLine());
            error_log('Error trace: ' . $e->getTraceAsString());
            error_log('Tournament ID: ' . $tournament_id);
            error_log('Input data: ' . ($input ?? 'null'));
            error_log('API data: ' . (isset($apiData) ? json_encode($apiData) : 'not set'));
            error_log('==============================');
            
            http_response_code(500);
            
            // Extraire plus d'informations de l'erreur API
            $detailedError = 'Erreur lors de la création de l\'arbitre';
            
            if (strpos($errorMessage, 'HTTP code: 500') !== false) {
                $detailedError = 'Erreur interne du serveur API. Vérifiez que tous les champs sont corrects.';
            } elseif (strpos($errorMessage, 'HTTP code: 400') !== false) {
                $detailedError = 'Données invalides envoyées à l\'API. ' . $errorMessage;
            } elseif (strpos($errorMessage, 'HTTP code: 404') !== false) {
                $detailedError = 'Endpoint API non trouvé. Vérifiez la configuration.';
            } elseif (strpos($errorMessage, 'cURL Error') !== false) {
                $detailedError = 'Erreur de connexion à l\'API. ' . $errorMessage;
            }
            
            echo json_encode([
                'error' => $detailedError,
                'debug' => [
                    'original_error' => $errorMessage,
                    'tournament_id' => $tournament_id,
                    'api_data' => $apiData ?? null,
                    'timestamp' => date('Y-m-d H:i:s')
                ]
            ]);
        }
        exit;

    case 'update-referee':
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }

        $tournament_id = $_GET['tournament_id'] ?? null;
        $referee_id = $_GET['referee_id'] ?? null;
        
        if (!$tournament_id || !$referee_id) {
            http_response_code(400);
            echo json_encode(['error' => 'Tournament ID and Referee ID are required']);
            exit;
        }

        try {
            $api = new ApiClient();
            
            // Get JSON data
            $input = file_get_contents('php://input');
            $refereeData = json_decode($input, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid JSON data']);
                exit;
            }

            // Validate required fields with original names
            if (empty($refereeData['first_name']) || empty($refereeData['last_name']) || 
                empty($refereeData['loginUUID']) || empty($refereeData['password'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Prénom, nom, identifiant et mot de passe sont requis']);
                exit;
            }

            // Format data with exact API variable names
            $apiData = [
                'firstName' => trim($refereeData['first_name']),
                'lastName' => trim($refereeData['last_name']),
                'loginUUID' => trim($refereeData['loginUUID']),
                'password' => trim($refereeData['password']),
                'tournamentId' => (int)$tournament_id,
                'Referee_Id' => (int)$referee_id
            ];

            // Log pour debug
            error_log('Update referee - Data being sent: ' . json_encode($apiData));

            // Update referee via API
            $result = $api->put('referees/' . $referee_id, $apiData);
            
            echo json_encode([
                'success' => true,
                'message' => 'Arbitre mis à jour avec succès',
                'data' => $result
            ]);

        } catch (Exception $e) {
            error_log('Referee update error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de la mise à jour de l\'arbitre: ' . $e->getMessage()]);
        }
        exit;

    case 'delete-referee':
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }

        $referee_id = $_GET['referee_id'] ?? null;
        
        if (!$referee_id) {
            http_response_code(400);
            echo json_encode(['error' => 'Referee ID is required']);
            exit;
        }

        try {
            $api = new ApiClient();
            
            // Delete referee via API
            $result = $api->delete('referees/' . $referee_id);
            
            echo json_encode([
                'success' => true,
                'message' => 'Arbitre supprimé avec succès',
                'data' => $result
            ]);

        } catch (Exception $e) {
            error_log('Referee deletion error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de la suppression de l\'arbitre: ' . $e->getMessage()]);
        }
        exit;

    default:
        header('HTTP/1.0 404 Not Found');
        echo '<h1>404 - Page Not Found</h1>';
        echo '<p>The requested page could not be found.</p>';
        echo '<a href="index.php?route=home">Return to Home</a>';
        break;
}