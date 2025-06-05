<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Politique de confidentialité - Aviron Castrais</title>
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

        h3 {
            color: #232c5a;
            font-size: 18px;
            margin: 20px 0 10px 0;
        }

        p {
            margin-bottom: 15px;
            text-align: justify;
        }

        ul {
            margin: 15px 0;
            padding-left: 20px;
        }

        li {
            margin-bottom: 8px;
        }

        .contact-info {
            background: #e8ecff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
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
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 14px;
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
            <p>Aviron Castrais - Système de gestion de tournois</p>
        </div>

        <div class="content">
            <p><strong>Dernière mise à jour :</strong> <?= date('d/m/Y') ?></p>

            <h2>1. Responsable du traitement</h2>
            <div class="contact-info">
                <p><strong>Aviron Castrais</strong><br>
                [Adresse du club]<br>
                [Code postal] [Ville]<br>
                Email : [email@avironcastrais.fr]<br>
                Téléphone : [numéro de téléphone]</p>
            </div>

            <h2>2. Données collectées</h2>
            <p>Dans le cadre de l'utilisation de notre système de gestion de tournois, nous collectons les données suivantes :</p>
            
            <h3>Données des arbitres</h3>
            <ul>
                <li>Nom et prénom</li>
                <li>Identifiant de connexion</li>
                <li>Mot de passe (chiffré)</li>
                <li>Adresse email (si fournie)</li>
            </ul>

            <h3>Données des équipes</h3>
            <ul>
                <li>Nom de l'équipe</li>
                <li>Catégorie d'âge</li>
                <li>Logo de l'équipe (image)</li>
            </ul>

            <h3>Données des tournois</h3>
            <ul>
                <li>Informations sur les matchs</li>
                <li>Résultats sportifs</li>
                <li>Planning des compétitions</li>
                <li>Classements</li>
            </ul>

            <h2>3. Finalité du traitement</h2>
            <p>Les données sont collectées et traitées pour les finalités suivantes :</p>
            <ul>
                <li>Gestion des tournois d'aviron</li>
                <li>Organisation des compétitions</li>
                <li>Suivi des résultats sportifs</li>
                <li>Communication avec les arbitres et équipes</li>
                <li>Amélioration du service</li>
            </ul>

            <h2>4. Base légale</h2>
            <p>Le traitement de vos données personnelles est basé sur :</p>
            <ul>
                <li>L'exécution d'un contrat ou de mesures précontractuelles</li>
                <li>L'intérêt légitime pour l'organisation de compétitions sportives</li>
                <li>Votre consentement pour certaines finalités spécifiques</li>
            </ul>

            <h2>5. Durée de conservation</h2>
            <p>Vos données sont conservées pour la durée nécessaire aux finalités pour lesquelles elles ont été collectées :</p>
            <ul>
                <li>Données de connexion : durée de votre participation aux tournois</li>
                <li>Résultats sportifs : 5 ans à des fins d'archivage sportif</li>
                <li>Données administratives : selon les obligations légales applicables</li>
            </ul>

            <h2>6. Destinataires des données</h2>
            <p>Vos données peuvent être partagées avec :</p>
            <ul>
                <li>Les organisateurs du tournoi</li>
                <li>Les autres arbitres et équipes participants (dans la limite nécessaire à l'organisation)</li>
                <li>Les prestataires techniques assurant l'hébergement et la maintenance du système</li>
            </ul>

            <h2>7. Vos droits</h2>
            <p>Conformément au RGPD, vous disposez des droits suivants :</p>
            <ul>
                <li><strong>Droit d'accès :</strong> obtenir la confirmation que vos données sont traitées et y accéder</li>
                <li><strong>Droit de rectification :</strong> corriger des données inexactes ou incomplètes</li>
                <li><strong>Droit à l'effacement :</strong> supprimer vos données dans certaines conditions</li>
                <li><strong>Droit à la limitation :</strong> restreindre le traitement de vos données</li>
                <li><strong>Droit à la portabilité :</strong> récupérer vos données dans un format structuré</li>
                <li><strong>Droit d'opposition :</strong> vous opposer au traitement pour des motifs légitimes</li>
            </ul>

            <p>Pour exercer ces droits, contactez-nous à l'adresse : <strong>[email@avironcastrais.fr]</strong></p>

            <h2>8. Sécurité des données</h2>
            <p>Nous mettons en œuvre des mesures techniques et organisationnelles appropriées pour protéger vos données :</p>
            <ul>
                <li>Chiffrement des mots de passe</li>
                <li>Accès sécurisé par authentification</li>
                <li>Sauvegarde régulière des données</li>
                <li>Limitation des accès aux personnes autorisées</li>
            </ul>

            <h2>9. Cookies et technologies similaires</h2>
            <p>Notre site utilise des cookies de session nécessaires au fonctionnement de l'application. Ces cookies ne collectent pas de données personnelles à des fins publicitaires.</p>

            <h2>10. Transferts de données</h2>
            <p>Vos données sont hébergées en France et ne font l'objet d'aucun transfert vers des pays tiers, sauf dans le cadre de prestations techniques sécurisées avec des garanties appropriées.</p>

            <h2>11. Réclamations</h2>
            <p>Si vous estimez que le traitement de vos données ne respecte pas la réglementation, vous pouvez introduire une réclamation auprès de la CNIL :</p>
            <div class="contact-info">
                <p><strong>Commission Nationale de l'Informatique et des Libertés (CNIL)</strong><br>
                3 Place de Fontenoy - TSA 80715<br>
                75334 PARIS CEDEX 07<br>
                Téléphone : 01 53 73 22 22<br>
                Site web : <a href="https://www.cnil.fr" target="_blank">www.cnil.fr</a></p>
            </div>

            <h2>12. Modifications</h2>
            <p>Cette politique de confidentialité peut être mise à jour. La date de dernière modification est indiquée en haut de cette page. Nous vous encourageons à consulter régulièrement cette page.</p>

            <h2>13. Contact</h2>
            <p>Pour toute question concernant cette politique de confidentialité ou le traitement de vos données personnelles, vous pouvez nous contacter :</p>
            <div class="contact-info">
                <p><strong>Email :</strong> [email@avironcastrais.fr]<br>
                <strong>Téléphone :</strong> [numéro de téléphone]<br>
                <strong>Adresse :</strong> [Adresse complète du club]</p>
            </div>
        </div>

        <div class="footer">
            <p>&copy; <?= date('Y') ?> Aviron Castrais. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
