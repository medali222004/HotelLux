<?php
session_start();
require_once 'config.php';
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(!isset($_SESSION['user_id'])) {
        header('Location: ../auth.php');
        exit;
    }
    $user_id = $_SESSION['user_id'];
    $room_id = intval($_POST['room_id'] ?? 0);
    $check_in = trim($_POST['check_in'] ?? '');
    $check_out = trim($_POST['check_out'] ?? '');
    $dateIn = strtotime($check_in);
    $dateOut = strtotime($check_out);
    if (!$check_in || !$check_out || !$dateIn || !$dateOut || $dateOut <= $dateIn) {
        $_SESSION['booking_error'] = 'Les dates doivent être valides et la date de départ doit être après la date d’arrivée.';
        header('Location: ../booking.php?room_id=' . $room_id);
        exit;
    }
    $nights = ($dateOut - $dateIn) / 86400;
    $stmt = $pdo->prepare("SELECT price_per_night FROM rooms WHERE id=?");
    $stmt->execute([$room_id]);
    $room = $stmt->fetch();
    if (!$room) {
        $_SESSION['booking_error'] = 'Chambre introuvable.';
        header('Location: ../rooms.php');
        exit;
    }
    $total = $nights * $room['price_per_night'];
    $stmt = $pdo->prepare(
        "INSERT INTO bookings (user_id, room_id, check_in, check_out, total_price, status) 
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->execute([$user_id, $room_id, $check_in, $check_out, $total, 'pending']);
    $_SESSION['success'] = 'Réservation confirmée !';
    header('Location: ../index.php');
    exit;
}
?>