<?php

include "../../includes/auth.php";
include "../../config/database.php";


$user_id = $_SESSION['user']['id'];


// Get student class

$classQuery = $conn->prepare("

SELECT class_id

FROM students

WHERE user_id=?

");


$classQuery->execute([$user_id]);


$class_id = $classQuery->fetchColumn();





// Get schedule

$query = $conn->prepare("


SELECT


schedule.day,

schedule.start_time,

schedule.end_time,


subjects.subject_name,


users.full_name AS teacher



FROM schedule



INNER JOIN subjects

ON schedule.subject_id = subjects.id



INNER JOIN users

ON schedule.teacher_id = users.id




WHERE schedule.class_id = ?



ORDER BY

FIELD(day,

'Monday',

'Tuesday',

'Wednesday',

'Thursday',

'Friday',

'Saturday'

),

start_time



");



$query->execute([$class_id]);


$schedule = $query->fetchAll(PDO::FETCH_ASSOC);



?>



<!DOCTYPE html>

<html>


<head>


<title>Schedule | EduManage</title>


<link rel="stylesheet" href="../../assets/css/dashboard.css">


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

My Schedule

</h1>


<p>

Weekly timetable

</p>


</div>






<div class="box">


<table>


<tr>


<th>
Day
</th>


<th>
Time
</th>


<th>
Subject
</th>


<th>
Teacher
</th>


</tr>





<?php foreach($schedule as $row){ ?>

<tr>


<td>

<?= $row['day']; ?>

</td>



<td>

<?= substr($row['start_time'],0,5); ?>

-

<?= substr($row['end_time'],0,5); ?>

</td>




<td>

<?= $row['subject_name']; ?>

</td>




<td>

<?= $row['teacher']; ?>

</td>


</tr>


<?php } ?>




<?php if(count($schedule)==0){ ?>


<tr>

<td colspan="4">

No schedule available.

</td>

</tr>


<?php } ?>



</table>



</div>



</main>


</div>



</body>


</html>