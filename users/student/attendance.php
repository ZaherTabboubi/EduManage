<?php

include "../../includes/auth.php";
include "../../config/database.php";


$user_id = $_SESSION['user']['id'];



// =========================
// ATTENDANCE COUNTS
// =========================


$totalQuery = $conn->prepare("

SELECT COUNT(*)

FROM attendance

WHERE student_id=?

");


$totalQuery->execute([$user_id]);

$total = $totalQuery->fetchColumn();





$presentQuery = $conn->prepare("

SELECT COUNT(*)

FROM attendance

WHERE student_id=?

AND status='Present'

");


$presentQuery->execute([$user_id]);

$present = $presentQuery->fetchColumn();






$absentQuery = $conn->prepare("

SELECT COUNT(*)

FROM attendance

WHERE student_id=?

AND status='Absent'

");


$absentQuery->execute([$user_id]);

$absent = $absentQuery->fetchColumn();






$lateQuery = $conn->prepare("

SELECT COUNT(*)

FROM attendance

WHERE student_id=?

AND status='Late'

");


$lateQuery->execute([$user_id]);

$late = $lateQuery->fetchColumn();





if($total > 0){

    $percentage = round(($present / $total) * 100);

}else{

    $percentage = 0;

}






// =========================
// HISTORY
// =========================


$historyQuery = $conn->prepare("


SELECT


subjects.subject_name,

attendance.attendance_date,

attendance.status



FROM attendance



LEFT JOIN subjects

ON attendance.subject_id = subjects.id




WHERE attendance.student_id=?




ORDER BY attendance.attendance_date DESC



");



$historyQuery->execute([$user_id]);


$history = $historyQuery->fetchAll(PDO::FETCH_ASSOC);



?>




<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<title>Attendance | EduManage</title>


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






<div class="page-title">


<h1>

My Attendance

</h1>


<p>

Track your attendance history

</p>


</div>







<section class="cards">





<div class="student-card">


<i class="fa-solid fa-calendar-check"></i>


<h3>

Attendance Rate

</h3>


<h2>

<?= $percentage ?>%

</h2>


</div>







<div class="student-card">


<i class="fa-solid fa-user-check"></i>


<h3>

Present

</h3>


<h2>

<?= $present ?>

</h2>


</div>







<div class="student-card">


<i class="fa-solid fa-user-xmark"></i>


<h3>

Absent

</h3>


<h2>

<?= $absent ?>

</h2>


</div>







<div class="student-card">


<i class="fa-solid fa-clock"></i>


<h3>

Late

</h3>


<h2>

<?= $late ?>

</h2>


</div>



</section>









<div class="box">



<h2>

Attendance History

</h2>





<table>


<thead>


<tr>


<th>

Subject

</th>


<th>

Date

</th>


<th>

Status

</th>


</tr>


</thead>






<tbody>



<?php foreach($history as $row){ ?>



<tr>


<td>

<?= $row['subject_name'] ?? "Unknown"; ?>

</td>




<td>

<?= $row['attendance_date']; ?>

</td>




<td>

<?= $row['status']; ?>

</td>



</tr>



<?php } ?>





<?php if(count($history)==0){ ?>


<tr>

<td colspan="3">

No attendance records.

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