<?php

include "../../includes/auth.php";
include "../../config/database.php";


$teacher_id=$_SESSION['user']['id'];



// Get teacher schedule

$query=$conn->prepare("


SELECT


schedule.day,

schedule.start_time,

schedule.end_time,


classes.class_name,


subjects.subject_name



FROM schedule



INNER JOIN classes


ON schedule.class_id = classes.id




INNER JOIN subjects


ON schedule.subject_id = subjects.id




WHERE schedule.teacher_id=?



ORDER BY

FIELD(

schedule.day,

'Monday',

'Tuesday',

'Wednesday',

'Thursday',

'Friday',

'Saturday'

),

schedule.start_time



");



$query->execute([$teacher_id]);


$schedule=$query->fetchAll(PDO::FETCH_ASSOC);



?>





<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<title>My Schedule | EduManage</title>


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

My Schedule

</h1>


<p>

Your weekly teaching timetable

</p>


</div>


</div>








<div class="table-box">


<table>


<thead>


<tr>


<th>

Day

</th>


<th>

Time

</th>


<th>

Class

</th>


<th>

Subject

</th>


</tr>


</thead>





<tbody>


<?php foreach($schedule as $s){ ?>



<tr>


<td>

<?= $s['day']; ?>

</td>




<td>

<?= date("H:i",strtotime($s['start_time'])); ?>

-

<?= date("H:i",strtotime($s['end_time'])); ?>

</td>




<td>

<?= $s['class_name']; ?>

</td>




<td>

<?= $s['subject_name']; ?>

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