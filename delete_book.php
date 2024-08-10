<?php
require 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookId = $_POST['id'] ?? '';

    if ($bookId) {
        $stmt = $conn->prepare('DELETE FROM books WHERE id = ?');
        $stmt->bind_param('i', $bookId);
        
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
