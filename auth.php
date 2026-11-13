<?php session_start(); require_once 'back/config.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HôtelLux — Connexion</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="logo">HôtelLux</div>
        <nav>
            <button id="menu-toggle">☰</button>
            <ul id="nav-menu">
                <li><a href="index.php">Accueil</a></li>
                <li><a href="rooms.php">Chambres</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="booking.php">Réserver</a></li>
                    <?php if($_SESSION['role']==='admin'): ?>
                        <li><a href="dashboard.php">Admin</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="auth.php">Connexion</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <section class="auth-section">
            
            <div class="auth-tabs">
                <button class="tab-btn active" data-tab="login">Connexion</button>
                <button class="tab-btn" data-tab="register">Inscription</button>
            </div>

            
            <div class="tab-content active" id="login">
                <form id="login-form" action="back/login.php" method="POST">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label>Mot de passe</label>
                        <input type="password" name="password" required>
                    </div>
                    <button type="submit" class="btn-primary">Se connecter</button>
                </form>
            </div>

        
            <div class="tab-content" id="register">
                <form id="register-form" action="back/register.php" method="POST">
                    <div class="form-group">
                        <label>Nom complet</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="reg-email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label>Mot de passe</label>
                        <input type="password" name="password" required minlength="6">
                    </div>
                    <div class="form-group">
                        <label>Confirmer le mot de passe</label>
                        <input type="password" name="confirm_password" required minlength="6">
                    </div>
                    <button type="submit" class="btn-primary">S'inscrire</button>
                </form>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 HôtelLux. Tous droits réservés.</p>
    </footer>

    <script src="js/main.js"></script>
</body>
</html>