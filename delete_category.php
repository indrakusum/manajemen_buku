<?php
require 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryId = $_POST['id'] ?? '';

    if ($categoryId) {
        $stmt = $conn->prepare('DELETE FROM categories WHERE id = ?');
        $stmt->bind_param('i', $categoryId);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'ID tidak ditemukan']);
    }
}

$conn->close();
?>
