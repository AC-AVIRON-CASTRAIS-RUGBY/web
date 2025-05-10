# 🖥️ Application Web - Gestion de Tournois de Rugby (PHP)

Cette interface web permet d’administrer les données du projet de gestion de tournois de rugby. Elle est développée en PHP pur, sans framework, pour une structure simple et rapide à mettre en place.

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
│       └── Database.php
│   ├── views/
│   │   └── header.php
│   └── config/
│       └── db.php
└── README.md
```

## ⚙️ Installation
> Ce projet nécessite une base de données, voir [🔧 Configuration de la base de données](#-configuration-de-la-base-de-données).
```bash
git clone https://github.com/ton-utilisateur/projet-web-php.git
cd projet-web-php
```

## 🌐 Lancer l'application
Placez le dossier dans le répertoire htdocs (ou équivalent), puis accèdez à l'application via :
```
http://localhost/web/public
```

## 🔧 Configuration de la base de données
Créez un fichier `src/config/db.php` contenant :
```php
<?php
define('DB_HOST', 'ADDRESSE_DE_LA_BASE_DE_DONNEES');
define('DB_NAME', 'NOM_DE_LA_BASE_DE_DONNEES');
define('DB_USER', 'NOM_UTILISATEUR');
define('DB_PASS', 'MOT_DE_PASSE');
```