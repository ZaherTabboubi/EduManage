<?php

include "../../includes/auth.php";
include "../../config/database.php";



$teacher_id=$_SESSION['user']['id'];




// Subjects count

$subjectQuery=$conn->prepare("

SELECT COUNT(*)

FROM teacher_subjects

WHERE user_id=?

");


$subjectQuery->execute([$teacher_id]);


$subjectCount=$subjectQuery->fetchColumn();





// Classes count

$classQuery=$conn->prepare("

SELECT COUNT(DISTINCT class_id)

FROM schedule

WHERE teacher_id=?

");


$classQuery->execute([$teacher_id]);


$classCount=$classQuery->fetchColumn();







// Students count

$studentQuery=$conn->prepare("


SELECT COUNT(DISTINCT students.user_id)


FROM students



INNER JOIN schedule


ON students.class_id=schedule.class_id



WHERE schedule.teacher_id=?


");


$studentQuery->execute([$teacher_id]);


$studentCount=$studentQuery->fetchColumn();







// Today's classes

$today=date("l");



$todayQuery=$conn->prepare("


SELECT COUNT(*)


FROM schedule


WHERE teacher_id=?


AND day=?


");


$todayQuery->execute([

$teacher_id,

$today

]);


$todayClasses=$todayQuery->fetchColumn();







// Attendance statistics


$attendanceQuery=$conn->prepare("


SELECT

status,

COUNT(*) total


FROM attendance



WHERE teacher_id=?



GROUP BY status



");


$attendanceQuery->execute([$teacher_id]);


$attendance=$attendanceQuery->fetchAll(PDO::FETCH_ASSOC);



$present=0;

$absent=0;

$late=0;



foreach($attendance as $a){


if($a['status']=="Present")

$present=$a['total'];



if($a['status']=="Absent")

$absent=$a['total'];



if($a['status']=="Late")

$late=$a['total'];



}





?>





<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<title>Teacher Dashboard | EduManage</title>


<link rel="stylesheet" href="../../assets/css/teacher.css">


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





<div class="welcome">


<h1>

Welcome back,

<?= $_SESSION['user']['name']; ?>

👋

</h1>


<p>

Teacher dashboard overview.

</p>


</div>









<section class="cards">





<div class="stat-card">


<i class="fa-solid fa-book"></i>


<div>


<h3>

Subjects

</h3>


<h2>

<?= $subjectCount; ?>

</h2>


</div>


</div>








<div class="stat-card">


<i class="fa-solid fa-school"></i>


<div>


<h3>

Classes

</h3>


<h2>

<?= $classCount; ?>

</h2>


</div>


</div>








<div class="stat-card">


<i class="fa-solid fa-user-graduate"></i>


<div>


<h3>

Students

</h3>


<h2>

<?= $studentCount; ?>

</h2>


</div>


</div>








<div class="stat-card">


<i class="fa-solid fa-calendar-day"></i>


<div>


<h3>

Today Classes

</h3>


<h2>

<?= $todayClasses; ?>

</h2>


</div>


</div>





</section>








<section class="charts">


<div class="chart-box">


<h3>

Attendance Overview

</h3>


<canvas id="attendanceChart"></canvas>


</div>


</section>








</main>


</div>









<script>


new Chart(

document.getElementById('attendanceChart'),

{


type:'doughnut',


data:{


labels:[

'Present',

'Absent',

'Late'

],


datasets:[{


data:[

<?= $present ?>,

<?= $absent ?>,

<?= $late ?>

]


}]


}



});


</script>





</body>


</html>