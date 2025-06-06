<?php
require_once '../src/lib/SimplePdfExporter.php';
require_once '../src/lib/ApiClient.php';

class ExportController {
    private $apiClient;
    private $pdfExporter;

    public function __construct() {
        $this->apiClient = new ApiClient();
        $this->pdfExporter = new SimplePdfExporter();
    }

    public function exportRanking() {
        $tournament_id = $_GET['tournament_id'] ?? null;

        if (!$tournament_id) {
            header('Location: index.php?route=home');
            exit;
        }

        try {
            // Récupérer les données du tournoi
            $tournament = $this->apiClient->get('tournaments/' . $tournament_id);
            $standing = $this->apiClient->get('pools/tournaments/' . $tournament_id . '/standings/all');

            if (empty($standing) || !is_array($standing)) {
                $_SESSION['error'] = "Aucun classement disponible pour ce tournoi";
                header('Location: index.php?route=ranking&tournament_id=' . $tournament_id);
                exit;
            }

            // Générer et télécharger le document
            $this->pdfExporter->exportRanking(
                $standing,
                $tournament['name'] ?? 'Tournoi',
                $tournament['start_date'] ?? date('Y-m-d')
            );

        } catch (Exception $e) {
            error_log('Export error: ' . $e->getMessage());
            $_SESSION['error'] = "Erreur lors de l'export: " . $e->getMessage();
            header('Location: index.php?route=ranking&tournament_id=' . $tournament_id);
            exit;
        }
    }
}
