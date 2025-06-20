<?php
require_once __DIR__ . '/../config/api.php';

class ApiClient {
    private $baseUrl;

    public function __construct() {
        $this->baseUrl = API_BASE_URL;
    }

    /**
     * Effectue une requête GET à l'API
     * 
     * @param string $endpoint Le point de terminaison de l'API
     * @return array Les données renvoyées par l'API
     */
    public function get($endpoint) {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json'
        ]);
        
        // Options SSL pour résoudre les problèmes de certificat
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            throw new Exception('cURL Error: ' . $error);
        }
        
        if ($httpCode >= 200 && $httpCode < 300) {
            return json_decode($response, true) ?? [];
        } else if ($httpCode === 404) {
            // Gérer spécifiquement les 404 qui peuvent être des cas normaux (pas d'arbitres, etc.)
            $errorResponse = json_decode($response, true);
            if ($errorResponse && isset($errorResponse['message'])) {
                // Cas spéciaux où 404 signifie "aucun résultat" plutôt qu'une erreur
                $emptyResultMessages = [
                    'Aucun arbitre trouvé pour ce tournoi',
                    'Aucune équipe trouvée pour ce tournoi',
                    'Aucun match trouvé',
                    'Aucune catégorie trouvée',
                    'Aucun résultat trouvé'
                ];
                
                if (in_array($errorResponse['message'], $emptyResultMessages) || 
                    strpos($errorResponse['message'], 'Aucun') === 0) {
                    return []; // Retourner un tableau vide plutôt qu'une erreur
                }
            }
            
            // Pour les autres 404, lever une exception
            throw new Exception('API request failed with HTTP code: ' . $httpCode . ' - ' . ($errorResponse['message'] ?? $response));
        } else {
            throw new Exception('API request failed with HTTP code: ' . $httpCode . ' - Response: ' . $response);
        }
    }

    /**
     * Effectue une requête POST à l'API
     * 
     * @param string $endpoint Le point de terminaison de l'API
     * @param array $data Les données à envoyer
     * @return array Les données renvoyées par l'API
     */
    public function post($endpoint, $data) {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');

        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
                
        curl_close($ch);
        
        if ($error) {
            throw new Exception('cURL Error: ' . $error);
        }
        
        if ($httpCode >= 200 && $httpCode < 300) {
            $decoded = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON response: ' . $response);
            }
            return $decoded ?? [];
        } else {
            // Essayer de décoder la réponse d'erreur pour plus de détails
            $errorResponse = json_decode($response, true);
            $errorMessage = 'API request failed with HTTP code: ' . $httpCode;
            
            if ($errorResponse && isset($errorResponse['message'])) {
                $errorMessage .= ' - ' . $errorResponse['message'];
            } elseif ($errorResponse && isset($errorResponse['error'])) {
                $errorMessage .= ' - ' . $errorResponse['error'];
            } else {
                $errorMessage .= ' - Response: ' . $response;
            }
            
            throw new Exception($errorMessage);
        }
    }

    /**
     * Effectue une requête PUT à l'API
     * 
     * @param string $endpoint Le point de terminaison de l'API
     * @param array $data Les données à envoyer
     * @return array Les données renvoyées par l'API
     */
    public function put($endpoint, $data) {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            throw new Exception('cURL Error: ' . $error);
        }
        
        if ($httpCode >= 200 && $httpCode < 300) {
            return json_decode($response, true) ?? [];
        } else {
            throw new Exception('API request failed with HTTP code: ' . $httpCode . ' - Response: ' . $response);
        }
    }

    /**
     * Vérifie si l'API est disponible
     * 
     * @return bool True si l'API est disponible, false sinon
     */
    public function isAvailable() {
        try {
            $response = $this->get('/health');
            return isset($response['status']) && $response['status'] === 'live';
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Télécharge une image vers l'API
     * 
     * @param string $imageTmpPath Le chemin temporaire de l'image à télécharger
     * @return array Les données renvoyées par l'API
     */
    public function uploadImage($imageTmpPath) {
        $url = 'https://api.avironcastrais.fr/upload/image';
        
        // Fonction alternative pour détecter le type MIME sans fileinfo
        function getMimeTypeAlternative($filePath) {
            // Lire les premiers octets du fichier pour déterminer le type
            $handle = fopen($filePath, 'rb');
            if (!$handle) {
                return 'application/octet-stream';
            }
            
            $firstBytes = fread($handle, 8);
            fclose($handle);
            
            // Signatures des formats d'images
            $signatures = [
                "\xFF\xD8\xFF" => 'image/jpeg',  // JPEG
                "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A" => 'image/png',  // PNG
                "GIF87a" => 'image/gif',  // GIF87a
                "GIF89a" => 'image/gif',  // GIF89a
                "RIFF" => 'image/webp',   // WEBP (partiel)
                "\x00\x00\x01\x00" => 'image/x-icon',  // ICO
                "BM" => 'image/bmp'       // BMP
            ];
            
            foreach ($signatures as $signature => $mimeType) {
                if (strpos($firstBytes, $signature) === 0) {
                    return $mimeType;
                }
            }
            
            // Fallback sur l'extension du fichier
            $extension = strtolower(pathinfo($imageTmpPath, PATHINFO_EXTENSION));
            $extensionToMime = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
                'bmp' => 'image/bmp',
                'ico' => 'image/x-icon'
            ];
            
            return $extensionToMime[$extension] ?? 'application/octet-stream';
        }
        
        // Détecter le type MIME
        $mimeType = getMimeTypeAlternative($imageTmpPath);
        
        // Validation des types d'images acceptés
        $allowedMimeTypes = [
            'image/jpeg',
            'image/jpg', 
            'image/png',
            'image/gif',
            'image/webp',
            'image/bmp'
        ];
        
        if (!in_array($mimeType, $allowedMimeTypes)) {
            throw new Exception('Type de fichier non supporté: ' . $mimeType . '. Types acceptés: ' . implode(', ', $allowedMimeTypes));
        }
        
        // Vérifier que le fichier existe et est lisible
        if (!file_exists($imageTmpPath) || !is_readable($imageTmpPath)) {
            throw new Exception('Fichier non trouvé ou non lisible: ' . $imageTmpPath);
        }
        
        // Créer le CURLFile avec le bon type MIME
        $curlFile = new CURLFile($imageTmpPath, $mimeType, basename($imageTmpPath));
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'image' => $curlFile
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json'
            // Ne pas définir Content-Type pour multipart/form-data, cURL le fait automatiquement
        ]);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        // Debug info
        error_log("Upload debug - URL: $url");
        error_log("Upload debug - File: $imageTmpPath");
        error_log("Upload debug - MIME (detected): $mimeType");
        error_log("Upload debug - File size: " . filesize($imageTmpPath) . " bytes");
        error_log("Upload debug - HTTP Code: $httpCode");
        error_log("Upload debug - Response: $response");
        error_log("Upload debug - cURL Error: $error");
        
        curl_close($ch);
        
        if ($error) {
            throw new Exception('cURL Error: ' . $error);
        }
        
        if ($httpCode >= 200 && $httpCode < 300) {
            $decoded = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON response: ' . $response);
            }
            return $decoded;
        } else {
            throw new Exception('Upload failed with HTTP code: ' . $httpCode . ' - Response: ' . $response);
        }
    }

    /**
     * Effectue une requête DELETE à l'API
     * 
     * @param string $endpoint Le point de terminaison de l'API
     * @return array Les données renvoyées par l'API
     */
    public function delete($endpoint) {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json'
        ]);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            throw new Exception('cURL Error: ' . $error);
        }
        
        if ($httpCode >= 200 && $httpCode < 300) {
            return json_decode($response, true) ?? [];
        } else {
            throw new Exception('API request failed with HTTP code: ' . $httpCode . ' - Response: ' . $response);
        }
    }

    /**
     * Enrichit les données de tournoi avec les comptages (équipes, poules, matchs)
     * 
     * @param array $tournaments Liste des tournois à enrichir
     * @return array Les tournois enrichis avec les comptages
     */
    public function enrichTournamentsWithCounts($tournaments) {
        if (empty($tournaments)) {
            return $tournaments;
        }
        
        foreach ($tournaments as &$tournament) {
            $tournament_id = $tournament['Tournament_Id'];
            
            // Récupérer et compter les équipes
            try {
                $teams = $this->get('teams/tournaments/' . $tournament_id);
                $tournament['teams_count'] = is_array($teams) ? count($teams) : 0;
            } catch (Exception $e) {
                $tournament['teams_count'] = 0;
                error_log('Error fetching teams for tournament ' . $tournament_id . ': ' . $e->getMessage());
            }
            
            // Récupérer et compter les poules
            try {
                $pools = $this->get('pools/tournaments/' . $tournament_id);
                $tournament['pools_count'] = is_array($pools) ? count($pools) : 0;
            } catch (Exception $e) {
                $tournament['pools_count'] = 0;
                error_log('Error fetching pools for tournament ' . $tournament_id . ': ' . $e->getMessage());
            }
            
            // Récupérer tous les matchs et compter ceux qui appartiennent à ce tournoi
            try {
                $games = $this->get('games/');
                $tournament['games_count'] = 0;
                
                if (is_array($games)) {
                    foreach ($games as $game) {
                        if (isset($game['Tournament_Id']) && $game['Tournament_Id'] == $tournament_id) {
                            $tournament['games_count']++;
                        }
                    }
                }
            } catch (Exception $e) {
                $tournament['games_count'] = 0;
                error_log('Error fetching games for tournament ' . $tournament_id . ': ' . $e->getMessage());
            }
        }
        
        return $tournaments;
    }

    /**
     * Enrichit les données d'équipes avec un logo par défaut si aucun n'est présent
     * 
     * @param array $teams Liste des équipes à enrichir
     * @return array Les équipes enrichies
     */
    public function enrichTeamsWithDefaultLogo($teams) {
        if (empty($teams)) {
            return $teams;
        }
        
        foreach ($teams as &$team) {
            if (empty($team['logo'])) {
                $team['logo'] = 'img/placeholder-team.svg';
            }
        }
        
        return $teams;
    }
}
