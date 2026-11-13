<?php
session_start();
require_once 'config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false]);
    exit;
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false]);
    exit;
}

$id = $_POST['id'] ?? 0;

if (empty($id)) {
    echo json_encode(['success' => false]);
    exit;
}

$stmt = $pdo->prepare("DELETE FROM bookings WHERE id = ?");
$result = $stmt->execute([$id]);

echo json_encode(['success' => $result]);
?>
