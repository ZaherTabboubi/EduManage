<?php

include "../../includes/auth.php";
include "../../config/database.php";


$user_id = $_SESSION['user']['id'];



// Get student id

$studentQuery = $conn->prepare("

SELECT user_id, class_id

FROM students

WHERE user_id=?

");


$studentQuery->execute([$user_id]);


$student = $studentQuery->fetch(PDO::FETCH_ASSOC);


$student_id = $student['user_id'];

$class_id = $student['class_id'];



// =====================
// SUBJECTS COUNT
// =====================


$subjectCount = $conn->prepare("

SELECT COUNT(DISTINCT subject_id)

FROM grades

WHERE student_id=?

");


$subjectCount->execute([$student_id]);


$subjects = $subjectCount->fetchColumn();




// =====================
// AVERAGE GRADE
// =====================


$averageQuery = $conn->prepare("

SELECT ROUND(AVG(grade),2)

FROM grades

WHERE student_id=?

");


$averageQuery->execute([$student_id]);


$average = $averageQuery->fetchColumn();


if($average==null){

    $average=0;

}




// =====================
// ATTENDANCE
// =====================


$attendanceQuery = $conn->prepare("

SELECT

ROUND(

SUM(status='Present') / COUNT(*) * 100

,2)

FROM attendance

WHERE student_id=?

");


$attendanceQuery->execute([$student_id]);


$attendance = $attendanceQuery->fetchColumn();


if($attendance==null){

    $attendance=0;

}




// =====================
// LATEST GRADES
// =====================


$gradesQuery=$conn->prepare("

SELECT

subjects.subject_name,

grades.exam_type,

grades.grade


FROM grades


JOIN subjects

ON grades.subject_id=subjects.id


WHERE grades.student_id=?


ORDER BY grade_date DESC


LIMIT 5


");


$gradesQuery->execute([$student_id]);


$grades=$gradesQuery->fetchAll(PDO::FETCH_ASSOC);




// =====================
// ATTENDANCE CHART
// =====================


$present=$conn->prepare("

SELECT COUNT(*)

FROM attendance

WHERE student_id=?

AND status='Present'

");


$present->execute([$student_id]);

$present=$present->fetchColumn();



$absent=$conn->prepare("

SELECT COUNT(*)

FROM attendance

WHERE student_id=?

AND status='Absent'

");


$absent->execute([$student_id]);

$absent=$absent->fetchColumn();




// Late attendance

$late=$conn->prepare("

SELECT COUNT(*)

FROM attendance

WHERE student_id=?

AND status='Late'

");


$late->execute([$student_id]);

$late=$late->fetchColumn();


?>



<!DOCTYPE html>

<html>


<head>

<title>Student Dashboard | EduManage</title>


<link rel="stylesheet" href="../../assets/css/student.css">


<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<script src="../../assets/js/dashboard.js"></script>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


</head>



<body>



<div class="layout">



<?php include "../../includes/student_sidebar.php"; ?>



<main class="content">



<?php include "../../includes/student_header.php"; ?>




<section class="welcome">


<h1>

Welcome back,
<?= $_SESSION['user']['name']; ?> 👋

</h1>


<p>

Here is your academic overview

</p>


</section>






<section class="cards">



<div class="student-card">

<i class="fa-solid fa-book"></i>

<h3>Subjects</h3>

<h2><?= $subjects ?></h2>

</div>





<div class="student-card">

<i class="fa-solid fa-chart-line"></i>

<h3>Average</h3>

<h2><?= $average ?>/20</h2>

</div>





<div class="student-card">

<i class="fa-solid fa-calendar-check"></i>

<h3>Attendance</h3>

<h2><?= $attendance ?>%</h2>

</div>




</section>







<div class="dashboard-grid">



<div class="box">


<h2>
Latest Grades
</h2>


<table>


<tr>

<th>Subject</th>

<th>Exam</th>

<th>Grade</th>


</tr>



<?php foreach($grades as $g){ ?>


<tr>

<td><?= $g['subject_name']; ?></td>

<td><?= $g['exam_type']; ?></td>

<td><?= $g['grade']; ?>/20</td>


</tr>


<?php } ?>


</table>



</div>






<div class="box">


<h2>
Attendance
</h2>


<canvas id="attendanceChart"></canvas>


</div>




</div>





</main>



</div>




<script>


new Chart(

document.getElementById('attendanceChart'),

{

type:'doughnut',

data:{


labels:[

"Present",

"Absent",

"Late"

],


datasets:[{

data:[

<?= $present ?>,

<?= $absent ?>,

<?= $late ?>

]


}]


}


}


);




</script>




</body>

</html>