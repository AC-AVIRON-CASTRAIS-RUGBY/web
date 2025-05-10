<?php
require_once '../src/lib/Database.php';

try {
    $db = new Database();
    $connection = $db->getConnection();
    echo 'Connexion réussie à la base de données.';
} catch (Exception $e) {
    echo 'Erreur : ' . $e->getMessage();
}

