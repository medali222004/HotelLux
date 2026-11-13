<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}
require_once 'back/config.php';
$stmt = $pdo->query("
    SELECT b.*, u.name as user_name, r.name as room_name 
    FROM bookings b 
    JOIN users u ON b.user_id = u.id 
    JOIN rooms r ON b.room_id = r.id
    ORDER BY b.created_at DESC
");
$bookings = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HôtelLux — Admin</title>
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
        <section class="admin-section">
            <h2>Gestion des Réservations</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th><th>Client</th><th>Chambre</th>
                        <th>Arrivée</th><th>Départ</th>
                        <th>Total</th><th>Statut</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($bookings as $b): ?>
                    <tr id="row-<?= $b['id'] ?>">
                        <td><?= $b['id'] ?></td>
                        <td><?= htmlspecialchars($b['user_name']) ?></td>
                        <td><?= htmlspecialchars($b['room_name']) ?></td>
                        <td><?= $b['check_in'] ?></td>
                        <td><?= $b['check_out'] ?></td>
                        <td><?= $b['total_price'] ?> TND</td>
                        <td>
                            <span class="status-badge <?= $b['status'] ?>" 
                                  id="status-<?= $b['id'] ?>">
                                <?= $b['status'] ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn-confirm" data-id="<?= $b['id'] ?>">Confirmer</button>
                            <button class="btn-delete" data-id="<?= $b['id'] ?>">Supprimer</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 HôtelLux. Tous droits réservés.</p>
    </footer>

    <script src="js/main.js"></script>
</body>
</html>