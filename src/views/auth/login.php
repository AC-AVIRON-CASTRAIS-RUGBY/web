<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Aviron Castrais</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
<div class="login-container">
    <div class="login-left">
        <div class="logo-section">
            <img src="img/logo.png" alt="Logo Aviron Castrais" class="main-logo">
        </div>

        <div class="welcome-text">
            <h2>Aviron Castrais</h2>
            <p>Gérez facilement vos équipes, arbitres et compétitions</p>
        </div>
    </div>

    <!-- Section droite avec formulaire de connexion -->
    <div class="login-right">
        <div class="login-form-container">
            <div class="form-header">
                <h2>Connexion</h2>
                <p>Connectez-vous à votre compte</p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form class="login-form" action="index.php?route=auth" method="post" id="loginForm">
                <div class="form-group">
                    <label for="username">Identifiant</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>



                <button type="submit" class="login-btn" id="loginBtn">
                    <span class="btn-text">Se connecter</span>
                    <div class="spinner hidden">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="login-footer">
            <p>&copy; <?= date("Y") ?> Aviron Castrais. Tous droits réservés.</p>
            <div class="footer-links">
                <a href="#">Conditions d'utilisation</a>
                <a href="#">Politique de confidentialité</a>
                <a href="#">Support</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
