<?php

include "../../includes/auth.php";
include "../../config/database.php";


$teacher_id = $_SESSION['user']['id'];



// Get teacher grades

$query = $conn->prepare("

SELECT

grades.id,

users.full_name AS student_name,

subjects.subject_name,

grades.exam_type,

grades.grade,

grades.grade_date


FROM grades



INNER JOIN users

ON grades.student_id = users.id



INNER JOIN subjects

ON grades.subject_id = subjects.id



WHERE grades.teacher_id = ?



ORDER BY grades.grade_date DESC


");


$query->execute([$teacher_id]);


$grades = $query->fetchAll(PDO::FETCH_ASSOC);



?>


<!DOCTYPE html>

<html lang="en">

<head>


<meta charset="UTF-8">


<title>Grades | EduManage</title>


<link rel="stylesheet" href="../../assets/css/dashboard.css">


<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<script src="../../assets/js/dashboard.js"></script>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>


<body>


<div class="layout">


<?php include "../../includes/teacher_sidebar.php"; ?>


<main class="content">


<?php include "../../includes/teacher_header.php"; ?>



<div class="page-title">


<div>

<h1>
Grades
</h1>

<p>
View student grades
</p>

</div>


<a href="grades.php" class="add-btn">

<i class="fa-solid fa-plus"></i>

Add Grades

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
Exam
</th>


<th>
Grade
</th>


<th>
Date
</th>


</tr>


</thead>



<tbody>



<?php foreach($grades as $g){ ?>


<tr>


<td>

<?= $g['student_name']; ?>

</td>



<td>

<?= $g['subject_name']; ?>

</td>



<td>

<?= $g['exam_type']; ?>

</td>



<td>


<strong>

<?= $g['grade']; ?>/20

</strong>


</td>



<td>

<?= $g['grade_date']; ?>

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