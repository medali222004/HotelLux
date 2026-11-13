<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header('Location: auth.php');
    exit;
}
require_once 'back/config.php';
$room_id = intval($_GET['room_id'] ?? 1);
$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id=?");
$stmt->execute([$room_id]);
$room = $stmt->fetch();

$bookingsStmt = $pdo->prepare(
    "SELECT b.*, r.name as room_name, r.image as room_image, r.type as room_type 
     FROM bookings b 
     JOIN rooms r ON b.room_id = r.id 
     WHERE b.user_id = ? 
     ORDER BY b.created_at DESC"
);
$bookingsStmt->execute([$_SESSION['user_id']]);
$bookings = $bookingsStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HôtelLux — Réservation</title>
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
        <section class="booking-section">
            <div class="booking-summary">
                <h3><?= htmlspecialchars($room['name']) ?></h3>
                <img src="<?= htmlspecialchars($room['image']) ?>" alt="<?= htmlspecialchars($room['name']) ?>">
                <p>Type: <?= htmlspecialchars($room['type']) ?></p>
                <p>Prix: <span id="price-per-night"><?= $room['price_per_night'] ?></span> TND/nuit</p>
                <p>Total estimé: <strong id="total-price">0</strong> TND</p>
                <?php if(!empty($_SESSION['booking_error'])): ?>
                    <p class="error-msg"><?= htmlspecialchars($_SESSION['booking_error']) ?></p>
                    <?php unset($_SESSION['booking_error']); ?>
                <?php endif; ?>
            </div>

            <form id="booking-form" action="back/book.php" method="POST">
                <input type="hidden" name="room_id" value="<?= $room['id'] ?>">
                <div class="form-group">
                    <label>Date d'arrivée</label>
                    <input type="date" name="check_in" id="check-in" required>
                </div>
                <div class="form-group">
                    <label>Date de départ</label>
                    <input type="date" name="check_out" id="check-out" required>
                </div>
                <button type="submit" class="btn-primary">Confirmer la réservation</button>
            </form>
        </section>

        <section class="user-bookings">
            <h2>Mes Réservations</h2>
            <?php if(count($bookings) > 0): ?>
                <div class="bookings-grid">
                    <?php foreach($bookings as $booking): ?>
                        <article class="booking-card">
                            <img src="<?= 'https://picsum.photos/seed/room'.($booking['room_id'] + 6).'/600/400' ?>" alt="<?= htmlspecialchars($booking['room_name']) ?>">
                            <div class="booking-card-body">
                                <h3><?= htmlspecialchars($booking['room_name']) ?></h3>
                                <p>Type: <?= htmlspecialchars($booking['room_type']) ?></p>
                                <p>Arrivée: <?= htmlspecialchars($booking['check_in']) ?></p>
                                <p>Départ: <?= htmlspecialchars($booking['check_out']) ?></p>
                                <p>Total: <?= htmlspecialchars($booking['total_price']) ?> TND</p>
                                <p>Status: <span class="status-badge <?= htmlspecialchars($booking['status']) ?>"><?= htmlspecialchars($booking['status']) ?></span></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Vous n'avez pas encore de réservation.</p>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 HôtelLux. Tous droits réservés.</p>
    </footer>

    <script src="js/main.js"></script>
</body>
</html>