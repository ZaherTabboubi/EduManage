<?php

include "../../includes/auth.php";
include "../../config/database.php";


if(!isset($_GET['id'])){

    header("Location: teachers.php");
    exit();

}


$id = $_GET['id'];



// Get teacher information

$query = $conn->prepare("

SELECT

users.id,
users.login_id,
users.full_name,
users.email,

teachers.phone,
teachers.address,
teachers.hire_date


FROM users


INNER JOIN teachers

ON users.id = teachers.user_id


WHERE users.id = ?

");


$query->execute([$id]);


$teacher = $query->fetch(PDO::FETCH_ASSOC);



if(!$teacher){

    die("Teacher not found");

}





// Get subjects

$subjectQuery = $conn->prepare("

SELECT subjects.subject_name


FROM teacher_subjects


INNER JOIN subjects


ON teacher_subjects.subject_id = subjects.id


WHERE teacher_subjects.user_id = ?

");


$subjectQuery->execute([$id]);


$subjects = $subjectQuery->fetchAll(PDO::FETCH_ASSOC);



?>



<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<title>Teacher Profile</title>


<link rel="stylesheet" href="../../assets/css/dashboard.css">
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<script src="../../assets/js/dashboard.js"></script>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


</head>


<body>


<div class="layout">


<?php include "../../includes/sidebar.php"; ?>


<main class="content">


<?php 
$hideSearch = true;
include "../../includes/header.php"; ?>



<div class="table-box">


<h1>

<?php echo $teacher['full_name']; ?>

</h1>



<br>


<p>
<strong>Login ID:</strong>

<?php echo $teacher['login_id']; ?>

</p>


<p>
<strong>Email:</strong>

<?php echo $teacher['email']; ?>

</p>



<p>
<strong>Phone:</strong>

<?php echo $teacher['phone']; ?>

</p>



<p>
<strong>Address:</strong>

<?php echo $teacher['address']; ?>

</p>



<p>
<strong>Hire Date:</strong>

<?php echo $teacher['hire_date']; ?>

</p>




<br>



<h2>
Subjects
</h2>



<ul>


<?php foreach($subjects as $subject){ ?>


<li>

<?php echo $subject['subject_name']; ?>

</li>


<?php } ?>


</ul>




<br>


<a href="edit_teacher.php?id=<?php echo $id; ?>" 
class="add-btn">

Edit Teacher

</a>



</div>



</main>


</div>


</body>

</html>