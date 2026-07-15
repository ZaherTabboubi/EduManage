<?php

include "../../includes/auth.php";
include "../../config/database.php";


$teacher_id=$_SESSION['user']['id'];



// Get teacher information

$query=$conn->prepare("


SELECT


users.login_id,
users.full_name,
users.email,
users.status,


teachers.phone,



GROUP_CONCAT(subjects.subject_name SEPARATOR ', ') AS subjects



FROM users



INNER JOIN teachers

ON users.id=teachers.user_id




LEFT JOIN teacher_subjects

ON users.id=teacher_subjects.user_id




LEFT JOIN subjects

ON teacher_subjects.subject_id=subjects.id





WHERE users.id=?

GROUP BY users.id



");



$query->execute([$teacher_id]);



$teacher=$query->fetch(PDO::FETCH_ASSOC);



?>





<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<title>My Profile | EduManage</title>


<link rel="stylesheet" href="../../assets/css/dashboard.css">


<link rel="stylesheet"

href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">


<script src="../../assets/js/dashboard.js"></script>


</head>





<body>



<div class="layout">



<?php include "../../includes/teacher_sidebar.php"; ?>



<main class="content">



<?php include "../../includes/teacher_header.php"; ?>





<div class="page-title">


<div>


<h1>

My Profile

</h1>


<p>

Account information

</p>


</div>


</div>









<div class="profile-card">



<div class="profile-icon">


<i class="fa-solid fa-user-tie"></i>


</div>





<h2>

<?= $teacher['full_name']; ?>

</h2>



<p>

Teacher

</p>





<div class="profile-info">



<div>

<i class="fa-solid fa-id-card"></i>

<strong>Login ID:</strong>

<?= $teacher['login_id']; ?>

</div>





<div>

<i class="fa-solid fa-envelope"></i>

<strong>Email:</strong>

<?= $teacher['email']; ?>

</div>






<div>

<i class="fa-solid fa-phone"></i>

<strong>Phone:</strong>

<?= $teacher['phone'] ?? "No phone"; ?>

</div>







<div>

<i class="fa-solid fa-book"></i>

<strong>Subjects:</strong>

<?= $teacher['subjects'] ?? "No subjects"; ?>

</div>







<div>

<i class="fa-solid fa-circle-check"></i>

<strong>Status:</strong>

<?= $teacher['status']; ?>

</div>





</div>





</div>





</main>


</div>


</body>


</html>