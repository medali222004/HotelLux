<?php session_start(); require_once 'back/config.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HôtelLux — Accueil</title>
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
        <section class="hero">
            <div class="hero-content">
                <h1>Bienvenue à HôtelLux</h1>
                <p>Découvrez le luxe et le confort au cœur de la ville</p>
                <a href="rooms.php" class="btn-primary">Voir nos chambres</a>
            </div>
        </section>

        <section class="featured-rooms">
            <h2>Nos Chambres en Vedette</h2>
            <div class="rooms-grid" id="rooms-container">
                <?php
                $stmt = $pdo->query("SELECT * FROM rooms WHERE available=1 LIMIT 3");
                while($room = $stmt->fetch()):
                ?>
                <article class="room-card" data-type="<?= $room['type'] ?>">
                    <img src="<?= htmlspecialchars($room['image']) ?>" alt="<?= htmlspecialchars($room['name']) ?>">
                    <div class="card-body">
                        <span class="badge"><?= htmlspecialchars($room['type']) ?></span>
                        <h3><?= htmlspecialchars($room['name']) ?></h3>
                        <p><?= htmlspecialchars($room['description']) ?></p>
                        <div class="card-footer">
                            <strong><?= $room['price_per_night'] ?> TND/nuit</strong>
                            <a href="booking.php?room_id=<?= $room['id'] ?>" 
                               class="btn-primary">Réserver</a>
                        </div>
                    </div>
                </article>
                <?php endwhile; ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 HôtelLux. Tous droits réservés.</p>
    </footer>

    <script src="js/main.js"></script>
</body>
</html>