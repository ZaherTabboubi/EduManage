<?php

include "../../config/database.php";


$subject=$_GET['subject_id'];


$query=$conn->prepare("

SELECT

users.id,
users.full_name


FROM users


INNER JOIN teacher_subjects

ON users.id=teacher_subjects.user_id



WHERE teacher_subjects.subject_id=?


");


$query->execute([$subject]);


echo json_encode(
$query->fetchAll(PDO::FETCH_ASSOC)
);
