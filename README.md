# Aviron Castrais - Système de Gestion de Tournois

## Description
Application web pour la gestion des tournois d'aviron du club Aviron Castrais. Permet la gestion des équipes, arbitres, calendrier des matchs et classements.

## Structure du projet
```
web/
├── public/                 # Point d'entrée web
│   ├── css/               # Feuilles de style
│   ├── img/               # Images et assets
│   ├── legals/            # Pages légales
│   ├── .htaccess          # Configuration Apache
│   └── index.php          # Routeur principal
├── src/                   # Code source PHP
│   ├── controllers/       # Contrôleurs
│   ├── lib/              # Bibliothèques
│   ├── views/            # Vues PHP
│   └── config/           # Configuration
└── .htaccess             # Redirection racine
```

## Installation

### Prérequis
- PHP 7.4 ou supérieur
- Serveur web Apache avec mod_rewrite
- API Backend sur localhost:3000

### Configuration
1. Cloner le projet dans le répertoire web
2. Configurer l'URL de l'API dans `src/config/api.php`
3. S'assurer que mod_rewrite est activé
4. Configurer le virtual host pour pointer vers le dossier `public/`

### Structure d'URL
- `/` → Page d'accueil (public/index.php)
- `/confidentialite` → Politique de confidentialité
- `/api/*` → Proxy vers l'API backend

## Fonctionnalités

### Authentification
- Connexion des arbitres
- Session sécurisée
- Gestion des droits d'accès

### Gestion des équipes
- Création d'équipes avec logo
- Catégorisation par âge
- Modification en temps réel
- Upload d'images

### Calendrier des matchs
- Planification automatique
- Filtres par statut, arbitre, poule
- Vue détaillée des matchs
- Suivi des résultats

### Classements
- Classement général et par poule
- Export PDF/HTML
- Statistiques détaillées

### Interface responsive
- Design adaptatif mobile/desktop
- Menu burger sur mobile
- Interface intuitive

## API Integration

### Endpoints utilisés
- `GET /tournaments/{id}` - Détails d'un tournoi
- `GET /teams/{tournamentId}` - Équipes d'un tournoi
- `POST /teams/{tournamentId}/teams` - Création d'équipe
- `PUT /teams/{tournamentId}/teams/{id}` - Modification d'équipe
- `POST /upload/image` - Upload d'images
- `GET /schedule/tournaments/{id}` - Planning des matchs
- `GET /standings/tournaments/{id}` - Classements

### Configuration API
```php
// src/config/api.php
define('API_BASE_URL', 'http://localhost:3000/api');
```

## Sécurité

### Mesures implémentées
- Protection CSRF via sessions
- Validation des données utilisateur
- Échappement HTML systématique
- Upload sécurisé d'images
- Headers de sécurité

### Politique de confidentialité
- Conforme RGPD
- Page dédiée `/confidentialite`
- Gestion des cookies de session

## Performance

### Optimisations
- Cache navigateur pour les assets
- Compression des images
- Minification CSS (production)
- Requêtes API optimisées

### Monitoring
- Logs d'erreurs PHP
- Suivi des performances API
- Alertes de disponibilité

## Développement

### Debug
- Page de test upload : `/debug-upload`
- Logs détaillés dans les contrôleurs
- Mode développement configurable

### Standards de code
- PSR-4 pour l'autoloading
- Séparation MVC
- Documentation inline
- Tests unitaires recommandés

## Déploiement

### Environnement de production
1. Vérifier la configuration Apache
2. Activer les modules requis (mod_rewrite, mod_headers)
3. Configurer HTTPS
4. Sauvegarder les données régulièrement

### Variables d'environnement
```bash
# Configuration API
API_BASE_URL=https://api.avironcastrais.fr
DEBUG_MODE=false
```

## Support

### Contact technique
- Email : [email technique]
- Documentation API : [URL de la doc API]
- Issues : [URL du repository]

### Maintenance
- Mises à jour de sécurité régulières
- Backup automatique des données
- Monitoring 24/7

## Licence
© 2024 Aviron Castrais. Tous droits réservés.
