<?php

include "../../config/database.php";

header("Content-Type: application/json");

if (!isset($_GET['teacher_id']) || empty($_GET['teacher_id'])) {
    echo json_encode([]);
    exit();
}

$teacher_id = (int)$_GET['teacher_id'];

$query = $conn->prepare("
    SELECT DISTINCT
        subjects.id,
        subjects.subject_name
    FROM teacher_subjects
    INNER JOIN subjects
        ON teacher_subjects.subject_id = subjects.id
    WHERE teacher_subjects.user_id = ?
    ORDER BY subjects.subject_name
");

$query->execute([$teacher_id]);

echo json_encode($query->fetchAll(PDO::FETCH_ASSOC));