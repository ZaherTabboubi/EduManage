<?php

include "../../includes/auth.php";
include "../../config/database.php";



if(!isset($_GET['id'])){

    header("Location: students.php");
    exit();

}



$id = $_GET['id'];




// Get student information


$query = $conn->prepare("


SELECT


users.id,
users.login_id,
users.full_name,
users.email,


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



$query->execute([$id]);



$student = $query->fetch(PDO::FETCH_ASSOC);




if(!$student){


    die("Student not found");


}



?>



<!DOCTYPE html>

<html lang="en">

<head>


<meta charset="UTF-8">


<title>Student Profile</title>


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



<?php $hideSearch = true; include "../../includes/header.php"; ?>





<div class="table-box">



<h1>

<?php echo $student['full_name']; ?>

</h1>




<br>



<p>

<strong>Login ID:</strong>

<?php echo $student['login_id']; ?>

</p>




<p>

<strong>Email:</strong>

<?php echo $student['email']; ?>

</p>





<p>

<strong>Class:</strong>

<?php echo $student['class_name'] ?? "No class"; ?>

</p>




<p>

<strong>Level:</strong>

<?php echo $student['level'] ?? "No level"; ?>

</p>





<br>



<h2>
Parent Information
</h2>




<p>

<strong>Name:</strong>

<?php echo $student['parent_name'] ?? "No parent"; ?>

</p>




<p>

<strong>Phone:</strong>

<?php echo $student['parent_phone'] ?? "No phone"; ?>

</p>





<br>




<a href="edit_student.php?id=<?php echo $student['id']; ?>"

class="add-btn">


Edit Student


</a>





</div>



</main>



</div>



</body>



</html>