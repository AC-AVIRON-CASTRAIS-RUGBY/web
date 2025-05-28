<?php

class SimplePdfExporter {
    
    public function exportRanking($standing, $tournamentName, $tournamentDate) {
        // Générer le HTML pour le PDF
        $html = $this->generateRankingHtml($standing, $tournamentName, $tournamentDate);
        
        // Configurer les headers pour le téléchargement
        $filename = 'classement_' . date('Y-m-d_H-i-s') . '.html';
        
        header('Content-Type: text/html; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
        
        echo $html;
        exit;
    }
    
    private function generateRankingHtml($standing, $tournamentName, $tournamentDate) {
        $totalTeams = count($standing);
        $totalGames = 0;
        
        foreach ($standing as $team) {
            $totalGames += $team['wins'] + $team['losses'] + $team['draws'];
        }
        $totalGames = intval($totalGames / 2); // Chaque match compte pour 2 équipes
        
        usort($standing, function($a, $b) {
            return $a['rank'] - $b['rank'];
        });
        
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Classement - ' . htmlspecialchars($tournamentName) . '</title>
            <style>
                @page {
                    size: A4 landscape;
                    margin: 1cm;
                }
                
                @media print {
                    .no-print { display: none !important; }
                    
                    @page {
                        @top-left {
                            content: "' . htmlspecialchars($tournamentName) . '";
                            font-size: 12px;
                            color: #666;
                        }
                        
                        @top-right {
                            content: "' . date('d/m/Y', strtotime($tournamentDate)) . '";
                            font-size: 12px;
                            color: #666;
                        }
                        
                        @bottom-center {
                            content: "Page " counter(page) " / " counter(pages);
                            font-size: 10px;
                            color: #666;
                        }
                    }
                }
                
                body {
                    font-family: Arial, sans-serif;
                    font-size: 12px;
                    line-height: 1.4;
                    margin: 0;
                    padding: 0;
                    background: white;
                }
                
                .page-header {
                    text-align: center;
                    margin-bottom: 30px;
                    border-bottom: 3px solid #232c5a;
                    padding-bottom: 20px;
                    page-break-inside: avoid;
                }
                
                .page-header h1 {
                    color: #232c5a;
                    font-size: 28px;
                    margin: 0 0 10px 0;
                    font-weight: bold;
                }
                
                .page-header .tournament-info {
                    color: #666;
                    font-size: 16px;
                    margin: 5px 0;
                }
                
                .page-header .generation-info {
                    color: #999;
                    font-size: 12px;
                    margin-top: 15px;
                }
                
                .ranking-table {
                    width: 100%;
                    border-collapse: collapse;
                    background-color: white;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
                    border-radius: 10px;
                    overflow: hidden;
                    page-break-inside: auto;
                    margin-top: 20px;
                }
                
                .ranking-table th {
                    background: linear-gradient(135deg, #232c5a 0%, #1a2147 100%);
                    color: white;
                    padding: 15px 12px;
                    text-align: center;
                    font-weight: bold;
                    font-size: 13px;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    border: none;
                }
                
                .ranking-table th:first-child,
                .ranking-table th:nth-child(2) {
                    text-align: left;
                }
                
                .ranking-table td {
                    padding: 12px;
                    border-bottom: 1px solid #eee;
                    text-align: center;
                    vertical-align: middle;
                }
                
                .ranking-table tbody tr:nth-child(even) {
                    background-color: #f8f9fa;
                }
                
                .ranking-table tbody tr:hover {
                    background-color: #e3f2fd;
                }
                
                .team-cell {
                    text-align: left !important;
                    font-weight: 500;
                    font-size: 14px;
                }
                
                .position {
                    font-weight: bold;
                    color: #232c5a;
                    text-align: center !important;
                    font-size: 16px;
                }
                
                .position-1::before { content: "🥇 "; }
                .position-2::before { content: "🥈 "; }
                .position-3::before { content: "🥉 "; }
                
                .wins { 
                    color: #28a745; 
                    font-weight: bold; 
                    background-color: rgba(40, 167, 69, 0.1);
                }
                
                .losses { 
                    color: #dc3545; 
                    font-weight: bold;
                    background-color: rgba(220, 53, 69, 0.1);
                }
                
                .draws { 
                    color: #ffc107; 
                    font-weight: bold;
                    background-color: rgba(255, 193, 7, 0.1);
                }
                
                .games { 
                    color: #232c5a; 
                    font-weight: bold;
                    background-color: rgba(35, 44, 90, 0.1);
                }
                
                .page-footer {
                    margin-top: 40px;
                    text-align: center;
                    font-size: 11px;
                    color: #666;
                    border-top: 1px solid #eee;
                    padding-top: 20px;
                    page-break-inside: avoid;
                }
                
                .print-controls {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: white;
                    padding: 15px;
                    border-radius: 10px;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
                    z-index: 1000;
                }
                
                .print-btn {
                    background: #232c5a;
                    color: white;
                    border: none;
                    padding: 10px 20px;
                    border-radius: 5px;
                    cursor: pointer;
                    font-size: 14px;
                    margin-right: 10px;
                }
                
                .print-btn:hover {
                    background: #1a2147;
                }
                
                .close-btn {
                    background: #6c757d;
                    color: white;
                    border: none;
                    padding: 10px 20px;
                    border-radius: 5px;
                    cursor: pointer;
                    font-size: 14px;
                }
                
                .close-btn:hover {
                    background: #5a6268;
                }
                
                /* Responsive pour l\'impression */
                @media print and (max-width: 1200px) {
                    .ranking-table {
                        font-size: 10px;
                    }
                    
                    .ranking-table th,
                    .ranking-table td {
                        padding: 8px;
                    }
                }
            </style>
        </head>
        <body>
            <div class="print-controls no-print">
                <button class="print-btn" onclick="window.print()">
                    🖨️ Imprimer
                </button>
                <button class="close-btn" onclick="window.close()">
                    ✖️ Fermer
                </button>
            </div>
            
            <div class="page-header">
                <h1>' . htmlspecialchars($tournamentName) . '</h1>
                <div class="tournament-info">
                    <strong>Classement Général</strong><br>
                    Tournoi du ' . date('d/m/Y', strtotime($tournamentDate)) . '
                </div>
                <div class="generation-info">
                    Document généré le ' . date('d/m/Y à H:i') . ' - Aviron Castrais
                </div>
            </div>
            
            <table class="ranking-table">
                <thead>
                    <tr>
                        <th>Position</th>
                        <th>Équipe</th>
                        <th>MJ</th>
                        <th>V</th>
                        <th>D</th>
                        <th>N</th>
                    </tr>
                </thead>
                <tbody>';
        
        foreach ($standing as $index => $team) {
            $positionClass = 'position-' . $team['rank'];
            $matchesPlayed = $team['wins'] + $team['losses'] + $team['draws'];
            
            $html .= '
                <tr>
                    <td class="position ' . $positionClass . '">' . $team['rank'] . '</td>
                    <td class="team-cell">' . htmlspecialchars($team['name']) . '</td>
                    <td class="games">' . $matchesPlayed . '</td>
                    <td class="wins">' . $team['wins'] . '</td>
                    <td class="losses">' . $team['losses'] . '</td>
                    <td class="draws">' . $team['draws'] . '</td>
                </tr>';
        }
        
        $html .= '
                </tbody>
            </table>
            
            <div class="page-footer">
                <p><strong>Aviron Castrais</strong> - Système de gestion de tournois</p>
                <p>Ce document a été généré automatiquement le ' . date('d/m/Y à H:i:s') . '</p>
            </div>
            
            <script>
                // Auto-print si demandé
                if (window.location.search.includes("auto-print=1")) {
                    window.onload = function() {
                        setTimeout(function() {
                            window.print();
                        }, 1000);
                    };
                }
            </script>
        </body>
        </html>';
        
        return $html;
    }
}
