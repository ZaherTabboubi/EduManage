<?php

include "../../includes/auth.php";
include "../../config/database.php";


$teacher_id=$_SESSION['user']['id'];



// Get teacher classes

$query=$conn->prepare("

SELECT

classes.id,
classes.class_name,
classes.level,

COUNT(students.user_id) AS students


FROM schedule


INNER JOIN classes

ON schedule.class_id=classes.id



LEFT JOIN students

ON classes.id=students.class_id



WHERE schedule.teacher_id=?


GROUP BY classes.id


ORDER BY classes.class_name


");


$query->execute([$teacher_id]);


$classes=$query->fetchAll(PDO::FETCH_ASSOC);



?>



<!DOCTYPE html>

<html lang="en">

<head>


<meta charset="UTF-8">

<title>My Classes | EduManage</title>


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

My Classes

</h1>


<p>

Classes assigned to you

</p>


</div>


</div>








<div class="table-box">


<table>



<thead>


<tr>


<th>

Class

</th>


<th>

Level

</th>


<th>

Students

</th>


<th>

Action

</th>


</tr>


</thead>




<tbody>


<?php foreach($classes as $class){ ?>



<tr>


<td>

<?= $class['class_name']; ?>

</td>




<td>

<?= $class['level']; ?>

</td>




<td>

<?= $class['students']; ?>

</td>





<td>


<a class="edit-btn"

href="students.php?class_id=<?= $class['id']; ?>">


<i class="fa-solid fa-users"></i>


</a>


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