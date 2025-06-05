<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Gestion des requêtes OPTIONS pour CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Configuration de l'API
$apiBaseUrl = 'https://api.avironcastrais.fr/';

// Récupérer le chemin de l'API depuis l'URL
$requestUri = $_SERVER['REQUEST_URI'];
$scriptName = $_SERVER['SCRIPT_NAME'];
$pathInfo = str_replace(dirname($scriptName), '', $requestUri);
$pathInfo = ltrim($pathInfo, '/');

// Supprimer 'api-proxy.php' du chemin
$apiPath = preg_replace('#^api-proxy\.php/?#', '', $pathInfo);

// Construire l'URL complète de l'API
$apiUrl = $apiBaseUrl . '/' . $apiPath;

// Récupérer la méthode HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Récupérer les données POST/PUT
$inputData = null;
if (in_array($method, ['POST', 'PUT', 'PATCH'])) {
    $inputData = file_get_contents('php://input');
}

// Initialiser cURL
$ch = curl_init();

// Configuration de base
curl_setopt_array($ch, [
    CURLOPT_URL => $apiUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_CUSTOMREQUEST => $method,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Accept: application/json'
    ]
]);

// Ajouter les données pour POST/PUT
if ($inputData) {
    curl_setopt($ch, CURLOPT_POSTFIELDS, $inputData);
}

// Exécuter la requête
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

curl_close($ch);

// Gestion des erreurs cURL
if ($error) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Erreur de connexion à l\'API',
        'details' => $error
    ]);
    exit;
}

// Retourner la réponse de l'API
http_response_code($httpCode);
echo $response;
?>
