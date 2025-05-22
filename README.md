# 🖥️ Application Web - Gestion de Tournois de Rugby (PHP)

Cette interface web permet d'administrer les données du projet de gestion de tournois de rugby. Elle est développée en PHP pur, sans framework, pour une structure simple et rapide à mettre en place. Elle utilise une API pour récupérer les données.

## 📁 Structure
```
web/
├── public/
│   ├── css/
│   ├── js/
│   ├── img/
│   └── index.php → Point d'entrée de l'application
├── src/
│   ├── controllers/
│   ├── models/
│   ├── lib/
│       ├── ApiClient.php
│   ├── views/
│   │   └── header.php
│   └── config/
│       └── api.php
└── README.md
```

## ⚙️ Installation
> Ce projet nécessite une API, voir [🔧 Configuration de l'API](#-configuration-de-lapi).
```bash
git clone https://github.com/ton-utilisateur/projet-web-php.git
cd projet-web-php
```

## 🌐 Lancer l'application
Placez le dossier dans le répertoire htdocs (ou équivalent), puis accèdez à l'application via :
```
http://localhost/web/public
```

## 🔧 Configuration de l'API
Créez un fichier `src/config/api.php` contenant :
```php
<?php
define('API_BASE_URL', 'http://localhost:3000/api');
```

Assurez-vous que l'API est en cours d'exécution sur l'URL spécifiée.
