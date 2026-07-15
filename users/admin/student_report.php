<?php

include "../../includes/auth.php";
include "../../config/database.php";


if(!isset($_GET['id'])){

    header("Location: reports.php");
    exit();

}


$student_id = $_GET['id'];



// Student information

$studentQuery = $conn->prepare("

SELECT

users.full_name,

users.login_id,

classes.class_name,

classes.level


FROM users


INNER JOIN students

ON users.id = students.user_id


LEFT JOIN classes

ON students.class_id = classes.id


WHERE users.id = ?


");


$studentQuery->execute([$student_id]);


$student = $studentQuery->fetch(PDO::FETCH_ASSOC);



if(!$student){

    die("Student not found");

}




// Grades

$gradeQuery = $conn->prepare("

SELECT


subjects.subject_name,


grades.exam_type,


grades.grade,


grades.grade_date,


teacher.full_name AS teacher_name



FROM grades



INNER JOIN subjects

ON grades.subject_id = subjects.id



INNER JOIN users AS teacher

ON grades.teacher_id = teacher.id



WHERE grades.student_id = ?



ORDER BY subjects.subject_name


");


$gradeQuery->execute([$student_id]);


$grades = $gradeQuery->fetchAll(PDO::FETCH_ASSOC);





// Average grade

$avgQuery = $conn->prepare("

SELECT AVG(grade)

FROM grades

WHERE student_id = ?

");


$avgQuery->execute([$student_id]);


$average = $avgQuery->fetchColumn();


$average = $average ? round($average,2) : 0;





// Attendance

$attendanceQuery = $conn->prepare("

SELECT

COUNT(*) AS total,

SUM(status='Present') AS present


FROM attendance


WHERE student_id = ?


");


$attendanceQuery->execute([$student_id]);


$attendance = $attendanceQuery->fetch(PDO::FETCH_ASSOC);



if($attendance['total'] > 0){

    $attendanceRate = round(

        ($attendance['present'] / $attendance['total']) * 100

    );

}else{

    $attendanceRate = 0;

}



?>



<!DOCTYPE html>

<html>


<head>


<title>Student Report</title>


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


<?php $hideSearch=true; include "../../includes/header.php"; ?>





<div class="table-box">


<h1>
Student Report Card
</h1>



<h2>

<?= $student['full_name']; ?>

</h2>


<p>

Login ID:
<?= $student['login_id']; ?>

</p>


<p>

Class:
<?= $student['class_name'] ?? "No class"; ?>

</p>


<p>

Level:
<?= $student['level'] ?? ""; ?>

</p>





<hr>





<h2>
Academic Summary
</h2>


<h3>

Average Grade:
<?= $average; ?>/20

</h3>


<h3>

Attendance:
<?= $attendanceRate; ?>%

</h3>




</div>







<div class="table-box">


<h2>
Grades
</h2>



<table>


<thead>

<tr>

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


</tr>


</thead>



<tbody>


<?php foreach($grades as $grade){ ?>


<tr>


<td>

<?= $grade['subject_name']; ?>

</td>



<td>

<?= $grade['teacher_name']; ?>

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



</tbody>


</table>


</div>



</main>


</div>


</body>


</html>