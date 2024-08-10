<?php
require 'db_config.php';

$text = htmlspecialchars($_POST['text'] ?? '', ENT_QUOTES, 'UTF-8');
$category = (int)($_POST['category'] ?? '');
$date_start = htmlspecialchars($_POST['date_start'] ?? '', ENT_QUOTES, 'UTF-8');
$date_end = htmlspecialchars($_POST['date_end'] ?? '', ENT_QUOTES, 'UTF-8');
$pages = $_POST['pages'] ?? '';

$params = [];
$sql = "SELECT books.*, categories.name AS category 
        FROM books 
        LEFT JOIN categories ON books.category_id = categories.id 
        WHERE 1=1";

if (!empty($text)) {
    $text = "%{$text}%";
    $sql .= " AND (books.title LIKE ? OR books.author LIKE ? OR books.publisher LIKE ?)";
    $params[] = $text;
    $params[] = $text;
    $params[] = $text;
}

if (!empty($category)) {
    $sql .= " AND books.category_id = ?";
    $params[] = $category;
}

if (!empty($date_start)) {
    $sql .= " AND books.publication_date >= ?";
    $params[] = $date_start;
}

if (!empty($date_end)) {
    $sql .= " AND books.publication_date <= ?";
    $params[] = $date_end;
}

if (!empty($pages)) {
    $sql .= " AND books.pages = ?";
    $params[] = $pages;
}

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    // Menggabungkan tipe data yang sesuai
    $param_types = str_repeat('s', count($params) - 1) . 'i';  // Sesuaikan tipe param
    $stmt->bind_param($param_types, ...$params);
}

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $books = [];

    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }

    echo json_encode($books);
} else {
    echo json_encode(["error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
