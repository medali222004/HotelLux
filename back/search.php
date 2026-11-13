<?php
require_once 'config.php';
header('Content-Type: application/json');

$q = $_GET['q'] ?? '';

if (empty($q)) {
    $stmt = $pdo->query("SELECT * FROM rooms");
} else {
    $search = '%' . $q . '%';
    $stmt = $pdo->prepare("SELECT * FROM rooms WHERE name LIKE ? OR type LIKE ?");
    $stmt->execute([$search, $search]);
}

echo json_encode($stmt->fetchAll());
?>
