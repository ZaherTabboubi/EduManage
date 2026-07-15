<?php

include "../../config/database.php";


$class_id = $_GET['class_id'];



$query = $conn->prepare("

SELECT

users.id,

users.login_id,

users.full_name


FROM students


INNER JOIN users

ON students.user_id = users.id



WHERE students.class_id = ?



ORDER BY users.full_name


");


$query->execute([$class_id]);


echo json_encode($query->fetchAll(PDO::FETCH_ASSOC));
