<?php

include "../../includes/auth.php";
include "../../config/database.php";


$teacher_id=$_SESSION['user']['id'];



$class_id=$_GET['class_id'];



// Verify teacher owns this class

$check=$conn->prepare("

SELECT id

FROM schedule

WHERE teacher_id=?

AND class_id=?

LIMIT 1

");


$check->execute([

$teacher_id,

$class_id

]);



if($check->rowCount()==0){

    die("Access denied");

}





// Get class students

$query=$conn->prepare("


SELECT


users.id,

users.login_id,

users.full_name,

users.email,


students.parent_name,

students.parent_phone



FROM students



INNER JOIN users


ON students.user_id=users.id



WHERE students.class_id=?



AND users.role='student'


ORDER BY users.full_name



");



$query->execute([$class_id]);


$students=$query->fetchAll(PDO::FETCH_ASSOC);




?>




<!DOCTYPE html>

<html lang="en">

<head>


<meta charset="UTF-8">


<title>Students | EduManage</title>


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

Students

</h1>


<p>

Students in this class

</p>


</div>



</div>








<div class="table-box">


<table>


<thead>


<tr>


<th>

ID

</th>


<th>

Name

</th>


<th>

Email

</th>


<th>

Parent

</th>


<th>

Phone

</th>


</tr>


</thead>




<tbody>



<?php foreach($students as $s){ ?>



<tr>


<td>

<?= $s['login_id']; ?>

</td>




<td>

<?= $s['full_name']; ?>

</td>




<td>

<?= $s['email']; ?>

</td>




<td>

<?= $s['parent_name'] ?? "No parent"; ?>

</td>




<td>

<?= $s['parent_phone'] ?? "No phone"; ?>

</td>




</tr>



<?php } ?>



</tbody>


</table>



</div>






</main>


</div>





</body>

</html>