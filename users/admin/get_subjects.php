<?php

include "../../config/database.php";


$teacher_id = $_GET['teacher_id'];


$query = $conn->prepare("

SELECT

subjects.id,

subjects.subject_name


FROM subjects


INNER JOIN teacher_subjects

ON subjects.id = teacher_subjects.subject_id


WHERE teacher_subjects.user_id = ?


ORDER BY subjects.subject_name


");


$query->execute([$teacher_id]);


echo json_encode($query->fetchAll(PDO::FETCH_ASSOC));
