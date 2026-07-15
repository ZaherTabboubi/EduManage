<?php

include "../../includes/auth.php";
include "../../config/database.php";



// Get grades


$query = $conn->prepare("


SELECT


grades.id,


student.full_name AS student_name,

student.login_id,


subjects.subject_name,


teacher.full_name AS teacher_name,


grades.exam_type,


grades.grade,


grades.grade_date




FROM grades





INNER JOIN users AS student

ON grades.student_id = student.id





INNER JOIN subjects

ON grades.subject_id = subjects.id





INNER JOIN users AS teacher

ON grades.teacher_id = teacher.id





ORDER BY grades.grade_date DESC




");



$query->execute();



$grades = $query->fetchAll(PDO::FETCH_ASSOC);



?>



<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Grades | EduManage</title>



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


<?php $hideSearch = true;  include "../../includes/header.php"; ?>





<div class="page-title">



<div>


<h1>
Grades
</h1>


<p>
Manage student grades
</p>


</div>




<a href="add_grade.php" class="add-btn">


<i class="fa-solid fa-plus"></i>


Add Grade


</a>



</div>







<div class="table-box">



<table>



<thead>


<tr>


<th>
Student
</th>


<th>
Subject
</th>


<th>
Teacher
</th>


<th>
Type
</th>


<th>
Grade
</th>


<th>
Date
</th>


<th>
Actions
</th>


</tr>


</thead>





<tbody>



<?php foreach($grades as $grade){ ?>



<tr>



<td>

<?php echo $grade['login_id']." - ".$grade['student_name']; ?>

</td>




<td>

<?php echo $grade['subject_name']; ?>

</td>





<td>

<?php echo $grade['teacher_name']; ?>

</td>





<td>

<?php echo $grade['exam_type']; ?>

</td>





<td>

<?php echo $grade['grade']; ?>

</td>





<td>

<?php echo $grade['grade_date']; ?>

</td>





<td>


<a href="edit_grade.php?id=<?php echo $grade['id']; ?>"

class="edit-btn">


<i class="fa-solid fa-pen"></i>


</a>





<a href="delete_grade.php?id=<?php echo $grade['id']; ?>"

class="delete-btn"

onclick="return confirm('Delete this grade?')">


<i class="fa-solid fa-trash"></i>


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