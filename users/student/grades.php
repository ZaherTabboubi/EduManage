<?php

include "../../includes/auth.php";
include "../../config/database.php";


$user_id = $_SESSION['user']['id'];




// Get grades

$query = $conn->prepare("

SELECT

subjects.subject_name,

grades.exam_type,

grades.grade,

grades.grade_date


FROM grades


INNER JOIN subjects

ON grades.subject_id = subjects.id



WHERE grades.student_id = ?



ORDER BY grades.grade_date DESC


");



$query->execute([$user_id]);


$grades = $query->fetchAll(PDO::FETCH_ASSOC);




// Average

$avgQuery = $conn->prepare("

SELECT ROUND(AVG(grade),2)

FROM grades

WHERE student_id=?


");


$avgQuery->execute([$user_id]);


$average = $avgQuery->fetchColumn();



if($average==null){

    $average=0;

}



?>



<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<title>Grades | EduManage</title>



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

My Grades

</h1>


<p>

Academic performance overview

</p>


</div>






<div class="cards">



<div class="student-card">


<i class="fa-solid fa-chart-line"></i>


<h3>

Average Grade

</h3>


<h2>

<?= $average ?>/20

</h2>


</div>



</div>







<div class="box">



<h2>

Grade History

</h2>




<table>


<thead>


<tr>


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



<?php foreach($grades as $grade){ ?>


<tr>


<td>

<?= $grade['subject_name']; ?>

</td>




<td>

<?= $grade['exam_type']; ?>

</td>





<td>

<?= $grade['grade']; ?>/20

</td>





<td>

<?= $grade['grade_date']; ?>

</td>



</tr>



<?php } ?>




<?php if(count($grades)==0){ ?>


<tr>

<td colspan="4">

No grades available yet.

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