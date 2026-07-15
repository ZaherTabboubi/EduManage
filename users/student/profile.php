<?php

include "../../includes/auth.php";
include "../../config/database.php";


$user_id = $_SESSION['user']['id'];



// Get student information

$query = $conn->prepare("

SELECT

users.login_id,
users.full_name,
users.email,
users.status,

students.parent_name,
students.parent_phone,

classes.class_name,
classes.level


FROM users


INNER JOIN students

ON users.id = students.user_id



LEFT JOIN classes

ON students.class_id = classes.id



WHERE users.id = ?


");


$query->execute([$user_id]);


$student = $query->fetch(PDO::FETCH_ASSOC);



?>



<!DOCTYPE html>

<html lang="en">


<head>

<meta charset="UTF-8">

<title>My Profile | Manage</title>


<link rel="stylesheet" href="../../assets/css/student.css">


<link rel="stylesheet"

href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">


<script src="../../assets/js/dashboard.js"></script>


</head>



<body>



<div class="layout">



<?php include "../../includes/student_sidebar.php"; ?>



<main class="content">



<?php include "../../includes/student_header.php"; ?>




<div class="page-title">

<h1>

My Profile

</h1>


<p>

Personal information

</p>


</div>







<div class="profile-card">



<div class="profile-avatar">

<i class="fa-solid fa-user"></i>

</div>




<h2>

<?= $student['full_name']; ?>

</h2>


<p>

Student

</p>





<div class="profile-info">



<div>

<i class="fa-solid fa-id-card"></i>

<strong>Student ID</strong>

<span>

<?= $student['login_id']; ?>

</span>

</div>





<div>

<i class="fa-solid fa-envelope"></i>

<strong>Email</strong>

<span>

<?= $student['email']; ?>

</span>

</div>






<div>

<i class="fa-solid fa-school"></i>

<strong>Class</strong>

<span>

<?= $student['class_name'] ?? "No class"; ?>

</span>

</div>





<div>

<i class="fa-solid fa-layer-group"></i>

<strong>Level</strong>

<span>

<?= $student['level'] ?? "-"; ?>

</span>

</div>







<div>

<i class="fa-solid fa-user-group"></i>

<strong>Parent</strong>

<span>

<?= $student['parent_name'] ?? "-"; ?>

</span>

</div>






<div>

<i class="fa-solid fa-phone"></i>

<strong>Parent Phone</strong>

<span>

<?= $student['parent_phone'] ?? "-"; ?>

</span>

</div>





<div>

<i class="fa-solid fa-circle-check"></i>

<strong>Status</strong>

<span>

<?= $student['status']; ?>

</span>

</div>



</div>



</div>





</main>



</div>



</body>


</html>