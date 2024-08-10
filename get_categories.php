<?php
include 'db_config.php';

header('Content-Type: application/json');

$sql = "SELECT id, name FROM categories";
$result = $conn->query($sql);

$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

echo json_encode($categories);

$conn->close();
?>
