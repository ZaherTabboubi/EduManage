<?php

include "../../config/database.php";

header("Content-Type: application/json");

if (!isset($_GET['class_id']) || empty($_GET['class_id'])) {
    echo json_encode([]);
    exit();
}

$class_id = (int)$_GET['class_id'];

$query = $conn->prepare("
    SELECT
        users.id,
        users.login_id,
        users.full_name
    FROM students
    INNER JOIN users
        ON students.user_id = users.id
    WHERE students.class_id = ?
      AND users.role = 'student'
      AND users.status = 'active'
    ORDER BY users.full_name
");

$query->execute([$class_id]);

echo json_encode($query->fetchAll(PDO::FETCH_ASSOC));