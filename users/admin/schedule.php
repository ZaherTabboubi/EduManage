<?php

include "../../includes/auth.php";
include "../../config/database.php";



// Get schedules

$query = $conn->prepare("

SELECT


schedule.id,


classes.class_name,


subjects.subject_name,


users.full_name AS teacher,


schedule.day,


schedule.start_time,


schedule.end_time



FROM schedule



INNER JOIN classes

ON schedule.class_id = classes.id



INNER JOIN subjects

ON schedule.subject_id = subjects.id



INNER JOIN users

ON schedule.teacher_id = users.id



ORDER BY 

FIELD(schedule.day,

'Monday',

'Tuesday',

'Wednesday',

'Thursday',

'Friday',

'Saturday'

),

schedule.start_time



");



$query->execute();


$schedules = $query->fetchAll(PDO::FETCH_ASSOC);



?>



<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<title>Schedule | EduManage</title>


<link rel="stylesheet" href="../../assets/css/dashboard.css">


<link rel="stylesheet"

href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">


<script src="../../assets/js/dashboard.js"></script>


</head>




<body>



<div class="layout">



<?php include "../../includes/sidebar.php"; ?>



<main class="content">



<?php include "../../includes/header.php"; ?>






<div class="page-title">


<div>


<h1>

Schedule

</h1>


<p>

Manage school timetable

</p>


</div>





<a href="add_schedule.php" class="add-btn">


<i class="fa-solid fa-plus"></i>


Add Schedule


</a>



</div>







<div class="table-box">



<table>


<thead>


<tr>


<th>
Class
</th>


<th>
Subject
</th>


<th>
Teacher
</th>


<th>
Day
</th>


<th>
Time
</th>


<th>
Actions
</th>


</tr>


</thead>






<tbody>



<?php foreach($schedules as $row){ ?>



<tr>



<td>

<?= $row['class_name']; ?>

</td>



<td>

<?= $row['subject_name']; ?>

</td>




<td>

<?= $row['teacher']; ?>

</td>





<td>

<?= $row['day']; ?>

</td>




<td>

<?= substr($row['start_time'],0,5); ?>

-

<?= substr($row['end_time'],0,5); ?>

</td>






<td>


<a href="edit_schedule.php?id=<?= $row['id']; ?>" 

class="edit-btn">


<i class="fa-solid fa-pen"></i>


</a>





<a href="delete_schedule.php?id=<?= $row['id']; ?>" 

class="delete-btn"

onclick="return confirm('Delete this schedule?')">


<i class="fa-solid fa-trash"></i>


</a>



</td>




</tr>



<?php } ?>






<?php if(count($schedules)==0){ ?>


<tr>


<td colspan="6">

No schedule created yet.

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