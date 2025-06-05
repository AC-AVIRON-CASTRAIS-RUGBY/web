<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Politique de confidentialité - AC Aviron Castrais Rugby</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #232c5a 0%, #1a2147 100%);
            color: white;
            text-align: center;
            padding: 40px 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 16px;
            opacity: 0.9;
            text-align: center;
        }

        .content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        h2 {
            color: #232c5a;
            font-size: 22px;
            margin-bottom: 15px;
            border-bottom: 2px solid #e8ecff;
            padding-bottom: 10px;
        }

        p {
            margin-bottom: 15px;
            text-align: justify;
        }

        .highlight-box {
            background: #e8f5e8;
            border-left: 4px solid #38a169;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .highlight-box h3 {
            color: #2e7d32;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .contact-info {
            background: #e8ecff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }

        .contact-info a {
            color: #232c5a;
            text-decoration: none;
            font-weight: 600;
        }

        .contact-info a:hover {
            text-decoration: underline;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #232c5a;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 20px;
            padding: 10px 15px;
            background: white;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .back-link:hover {
            background: #232c5a;
            color: white;
            transform: translateY(-1px);
        }

        .footer {
            padding: 20px;
            color: #666;
            font-size: 14px;
        }

        .footer p {
            text-align: center;;
        }

        .tabs {
            display: flex;
            margin-bottom: 0;
            background: white;
            border-radius: 10px 10px 0 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .tab-button {
            flex: 1;
            padding: 15px 20px;
            background: #f8f9fa;
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            color: #666;
            border-radius: 10px 10px 0 0;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
        }

        .tab-button.active {
            background: white;
            color: #232c5a;
            border-bottom: 3px solid #232c5a;
        }

        .tab-button:hover:not(.active) {
            background: #e9ecef;
            color: #495057;
        }

        .tab-content {
            display: none;
            background: white;
            padding: 30px;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .tab-content.active {
            display: block;
        }

        .mobile-highlight {
            background: #e8f5e8;
            border-left: 4px solid #38a169;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }

        .mobile-highlight h3 {
            color: #2e7d32;
            margin-bottom: 10px;
            font-size: 20px;
        }

        .mobile-highlight p {
            font-size: 16px;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .header {
                padding: 30px 15px;
            }

            .header h1 {
                font-size: 24px;
            }

            .content {
                padding: 20px;
            }

            h2 {
                font-size: 20px;
            }

            .tab-button {
                padding: 12px 15px;
                font-size: 14px;
            }

            .tab-content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="/" class="back-link">
            <i class="fas fa-arrow-left"></i> Retour à l'accueil
        </a>

        <div class="header">
            <h1><i class="fas fa-shield-alt"></i> Politique de confidentialité</h1>
            <p>AC Aviron Castrais Rugby - Système de gestion de tournois</p>
        </div>

        <div class="tabs">
            <button class="tab-button" onclick="showTab('web')">
                <i class="fas fa-globe"></i> Données générales
            </button>
            <button class="tab-button active" onclick="showTab('mobile')">
                <i class="fas fa-mobile-alt"></i> Application Mobile
            </button>
        </div>

        <div id="web-content" class="tab-content">
            <p><strong>Dernière mise à jour :</strong> <?= date('d/m/Y') ?></p>

            <h2>1. Responsable de l'application</h2>
            <div class="contact-info">
                <p><strong>AC AVIRON CASTRAIS RUGBY</strong><br>
                Association loi 1901<br>
                RNA : W812001296</p>
                
                <p><strong>Siège social :</strong><br>
                32 Place de l'Albinque<br>
                81100 CASTRES</p>
                
                <p><strong>Adresse de gestion :</strong><br>
                BP30607<br>
                81116 CASTRES, FRANCE</p>
                
                <p><strong>Contact :</strong><br>
                <i class="fas fa-phone"></i> 07 86 39 26 67<br>
                <i class="fas fa-envelope"></i> aviron.castrais@orange.fr</p>
            </div>

            <h2>2. Données traitées</h2>
            <p>Cette application traite uniquement <strong>les données que les joueurs ont soumises aux clubs participants aux tournois</strong>. Ces données comprennent :</p>
            <ul style="margin-left: 20px; margin-bottom: 15px;">
                <li>Noms des équipes</li>
                <li>Catégories d'âge des équipes</li>
                <li>Logos des équipes (si fournis)</li>
                <li>Résultats sportifs des matchs</li>
                <li>Planning des compétitions</li>
                <li>Informations des arbitres participant aux tournois</li>
            </ul>

            <p><strong>Important :</strong> Aucune donnée personnelle n'est collectée directement par l'application. Toutes les informations proviennent des clubs participants et ont été préalablement communiquées par les joueurs à leur club respectif.</p>

            <h2>3. Finalité du traitement</h2>
            <p>Les données sont utilisées exclusivement pour :</p>
            <ul style="margin-left: 20px; margin-bottom: 15px;">
                <li>L'organisation et la gestion des tournois</li>
                <li>L'affichage des plannings de compétition</li>
                <li>Le suivi et l'affichage des résultats sportifs</li>
                <li>L'établissement des classements</li>
                <li>La coordination entre arbitres et équipes</li>
            </ul>

            <h2>4. Base légale</h2>
            <p>Le traitement des données repose sur :</p>
            <ul style="margin-left: 20px; margin-bottom: 15px;">
                <li>L'intérêt légitime pour l'organisation de compétitions sportives</li>
                <li>L'exécution d'une mission d'intérêt public dans le domaine sportif</li>
                <li>Le consentement préalable donné par les joueurs à leur club</li>
            </ul>

            <h2>5. Localisation des données</h2>
            <div class="highlight-box">
                <h3><i class="fas fa-map-marker-alt"></i> Hébergement en Union Européenne</h3>
                <p><strong>Toutes les données sont stockées dans l'Union Européenne</strong> et ne font l'objet d'aucun transfert / vente vers des pays tiers en dehors de l'Union européenne.</p>
            </div>

            <h2>6. Durée de conservation</h2>
            <p>Les données sont conservées :</p>
            <ul style="margin-left: 20px; margin-bottom: 15px;">
                <li>Pendant la durée du tournoi et jusqu'à sa suppression par l'Aviron Castrais</li>
                <li>Les résultats sportifs peuvent être archivés à des fins statistiques</li>
            </ul>

            <h2>7. Vos droits</h2>
            <p>Conformément au RGPD, vous disposez des droits suivants :</p>
            <ul style="margin-left: 20px; margin-bottom: 15px;">
                <li><strong>Droit d'accès :</strong> connaître les données vous concernant</li>
                <li><strong>Droit de rectification :</strong> corriger des informations inexactes</li>
                <li><strong>Droit à l'effacement :</strong> demander la suppression de vos données</li>
                <li><strong>Droit à la portabilité :</strong> récupérer vos données dans un format structuré</li>
                <li><strong>Droit d'opposition :</strong> vous opposer au traitement pour des motifs légitimes</li>
            </ul>

            <div class="highlight-box">
                <h3><i class="fas fa-download"></i> Demande de téléchargement des données</h3>
                <p>Si vous souhaitez obtenir un téléchargement de vos données personnelles, envoyez un email à <a href="mailto:app@avironcastrais.fr&subject=Demande de téléchargement de données">app@avironcastrais.fr</a> en précisant :</p>
                <ul style="margin-left: 20px; margin-bottom: 15px;">
                    <li>Votre nom complet</li>
                    <li>Votre adresse email</li>
                    <li>Le club auquel vous êtes affilié</li>
                    <li>La nature des données demandées (par exemple, résultats sportifs, informations d'équipe ou toutes les données vous concernant)</li>
                </ul>
                <p>Nous vous répondrons dans les délais légaux.</p>
            </div>

            <h2>8. Sécurité des données</h2>
            <p>Nous mettons en œuvre des mesures de sécurité appropriées :</p>
            <ul style="margin-left: 20px; margin-bottom: 15px;">
                <li>Hébergement sécurisé</li>
                <li>Accès restreint aux données par authentification</li>
                <li>Chiffrement des communications</li>
                <li>Sauvegarde régulière des données</li>
                <li>Surveillance de la sécurité du système</li>
            </ul>

            <h2>9. Cookies et technologies similaires</h2>
            <p>L'application n'utilise pas de cookies.</p>

            <h2>10. Modifications de la politique</h2>
            <p>Cette politique peut être mise à jour pour refléter les évolutions légales ou techniques. La date de dernière modification est indiquée en haut de cette page.</p>

            <h2>11. Contact et réclamations</h2>
            <p>Pour toute question concernant cette politique de confidentialité ou pour exercer vos droits :</p>
            <div class="contact-info">
                <p><strong>AC AVIRON CASTRAIS RUGBY</strong></p>
                
                <p><i class="fas fa-envelope"></i> <strong>Email :</strong><br>
                <a href="mailto:app@avironcastrais.fr">app@avironcastrais.fr</a><br>
            </div>

            <p><strong>Droit de réclamation :</strong> En cas de désaccord, vous pouvez également introduire une réclamation auprès de la CNIL (Commission Nationale de l'Informatique et des Libertés) sur <a href="https://www.cnil.fr" target="_blank">www.cnil.fr</a></p>
        </div>

        <div id="mobile-content" class="tab-content active">
            <p><strong>Dernière mise à jour :</strong> <?= date('d/m/Y') ?></p>

            <h2>1. Responsable de l'application</h2>
            <div class="contact-info">
                <p><strong>AC AVIRON CASTRAIS RUGBY</strong><br>
                Association loi 1901<br>
                RNA : W812001296</p>
                
                <p><strong>Siège social :</strong><br>
                32 Place de l'Albinque<br>
                81100 CASTRES</p>
                
                <p><strong>Adresse de gestion :</strong><br>
                BP30607<br>
                81116 CASTRES, FRANCE</p>
                
                <p><strong>Contact :</strong><br>
                <i class="fas fa-phone"></i> 07 86 39 26 67<br>
                <i class="fas fa-envelope"></i> aviron.castrais@orange.fr</p>
            </div>

            <div class="mobile-highlight">
                <h3><i class="fas fa-shield-check"></i> Aucune collecte de données</h3>
                <p>L'application mobile n'effectue aucune collecte de données personnelles,<br>
                ni à des fins commerciales, ni à des fins statistiques.</p>
            </div>

            <h2>2. Fonctionnement de l'application mobile</h2>
            <p>L'application mobile fonctionne selon deux modes :</p>
            
            <h3>Mode consultation (accès libre) :</h3>
            <ul style="margin-left: 20px; margin-bottom: 15px;">
                <li>Affichage des informations publiques des tournois</li>
                <li>Consultation des plannings de compétition</li>
                <li>Visualisation des résultats sportifs</li>
            </ul>
            
            <h3>Mode arbitre (accès avec identifiants) :</h3>
            <ul style="margin-left: 20px; margin-bottom: 15px;">
                <li>Modification des scores des matchs</li>
                <li>Clôture des matchs</li>
                <li>Accès via identifiants fournis par l'organisateur</li>
            </ul>
            
            <p><strong>Dans tous les cas :</strong></p>
            <ul style="margin-left: 20px; margin-bottom: 15px;">
                <li>Aucune donnée personnelle n'est collectée</li>
                <li>Aucun cookie ou technologie de traçage utilisé</li>
                <li>Les identifiants arbitres sont fournis par l'organisateur du tournoi</li>
            </ul>

            <h2>3. Données consultées et modifiées</h2>
            <p>L'application permet de :</p>
            
            <h3>Consulter :</h3>
            <ul style="margin-left: 20px; margin-bottom: 15px;">
                <li>Les informations publiques des tournois</li>
                <li>Les noms des équipes participantes</li>
                <li>Les plannings des matchs</li>
                <li>Les résultats sportifs</li>
            </ul>
            
            <h3>Modifier (pour les arbitres authentifiés) :</h3>
            <ul style="margin-left: 20px; margin-bottom: 15px;">
                <li>Les scores des matchs</li>
                <li>Le statut des matchs (en cours/terminé)</li>
            </ul>
            
            <p><strong>Important :</strong> Ces informations sont déjà publiques et disponibles lors des compétitions. L'application ne fait que les rendre accessibles et modifiables de manière pratique pour les arbitres.</p>

            <h2>4. Authentification des arbitres</h2>
            <p>Pour les fonctionnalités d'arbitrage :</p>
            <ul style="margin-left: 20px; margin-bottom: 15px;">
                <li>Les identifiants sont fournis par l'organisateur du tournoi</li>
                <li>Seuls les arbitres désignés peuvent modifier les résultats des matchs qui leurs sont attribués</li>
                <li>L'authentification se fait via un système de codes temporaires, valables uniquement pour la durée des tournois</li>
                <li>Aucune donnée personnelle de l'arbitre n'est stockée</li>
            </ul>

            <h2>5. Absence de traitement de données personnelles</h2>
            <p>L'application mobile :</p>
            <ul style="margin-left: 20px; margin-bottom: 15px;">
                <li>Ne demande aucune inscription ou création de compte</li>
                <li>N'accède à aucune donnée de votre appareil</li>
                <li>Ne collecte aucune information personnelle</li>
                <li>Ne suit pas votre utilisation de l'application</li>
                <li>Ne stocke aucune donnée sur nos serveurs</li>
            </ul>

            <h2>6. Sécurité et confidentialité</h2>
            <p>Même sans collecte de données, nous garantissons :</p>
            <ul style="margin-left: 20px; margin-bottom: 15px;">
                <li>Des connexions sécurisées (HTTPS)</li>
                <li>Aucun stockage de données sur l'appareil</li>
                <li>Aucune transmission de données personnelles</li>
                <li>Respect total de votre vie privée</li>
            </ul>

            <h2>6. Contact</h2>
            <p>Pour toute question concernant l'application mobile :</p>
            <div class="contact-info">
                <p><strong>Contactez-nous:</strong></p>
                
                <p><i class="fas fa-envelope"></i> <strong>Email :</strong><br>
                <a href="mailto:app@avironcastrais.fr">app@avironcastrais.fr</a></p>
            </div>
        </div>

        <div class="footer">
            <p>&copy; <?= date('Y') ?> AC AVIRON CASTRAIS RUGBY. Tous droits réservés.</p>
            <p>Association loi 1901 - RNA W812001296</p>
            <p>Données hébergées en France - Conforme RGPD</p>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tab contents
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => {
                content.classList.remove('active');
            });

            // Remove active class from all tab buttons
            const tabButtons = document.querySelectorAll('.tab-button');
            tabButtons.forEach(button => {
                button.classList.remove('active');
            });

            // Show selected tab content
            document.getElementById(tabName + '-content').classList.add('active');

            // Add active class to clicked button
            event.target.classList.add('active');
        }
    </script>
</body>
</html>
