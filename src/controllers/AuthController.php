<?php
class AuthController {
    private $apiClient;

    public function __construct() {
        $this->apiClient = new ApiClient();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                $_SESSION['error'] = "Veuillez remplir tous les champs";
                header('Location: index.php?route=login');
                exit;
            }

            try {
                $response = $this->apiClient->post('/auth/login', [
                    'username' => $username,
                    'password' => $password
                ]);

                if (isset($response['user']) && isset($response['token'])) {
                    $_SESSION['user_id'] = $response['user']['id'];
                    $_SESSION['username'] = $response['user']['username'];
                    $_SESSION['token'] = $response['token'];

                    header('Location: index.php?route=home');
                    exit;
                } else {
                    $_SESSION['error'] = "Identifiants incorrects";
                    header('Location: index.php?route=login');
                    exit;
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Erreur de connexion: " . $e->getMessage();
                header('Location: index.php?route=login');
                exit;
            }
        }
    }
}