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
}
