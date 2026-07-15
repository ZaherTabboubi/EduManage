<?php

include "../../includes/auth.php";
include "../../config/database.php";


// Get classes

$classQuery = $conn->prepare("
SELECT id, class_name
FROM classes
ORDER BY class_name
");

$classQuery->execute();

$classes = $classQuery->fetchAll(PDO::FETCH_ASSOC);



$class_id = $_GET['class_id'] ?? null;


$schedule = [];



if($class_id){


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


ORDER BY schedule.start_time


");


$query->execute([$class_id]);


$rows = $query->fetchAll(PDO::FETCH_ASSOC);



foreach($rows as $row){


$time = substr($row['start_time'],0,5)
." - ".
substr($row['end_time'],0,5);



$schedule[$time][$row['day']] =

$row['subject_name']
."<br>
<small>"
.$row['teacher'].
"</small>";

}


}



$days = [

"Monday",
"Tuesday",
"Wednesday",
"Thursday",
"Friday"

];

?>



<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<title>View Schedule | EduManage</title>

<link rel="stylesheet" href="../../assets/css/dashboard.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>



<body>


<div class="layout">


<?php include "../../includes/sidebar.php"; ?>


<main class="content">


<?php include "../../includes/header.php"; ?>



<div class="page-title">

<div>

<h1>
View Schedule
</h1>

<p>
View timetable by class
</p>

</div>

</div>




<div class="table-box">



<form method="GET">


<label>
Select Class
</label>



<select name="class_id" onchange="this.form.submit()">



<option value="">
Choose Class
</option>



<?php foreach($classes as $class){ ?>


<option value="<?= $class['id']; ?>"
<?= ($class_id==$class['id'])?'selected':''; ?>
>

<?= $class['class_name']; ?>

</option>


<?php } ?>


</select>



</form>




<br>



<?php if($class_id){ ?>


<table>


<thead>


<tr>


<th>
Time
</th>



<?php foreach($days as $day){ ?>


<th>
<?= $day ?>
</th>


<?php } ?>


</tr>


</thead>




<tbody>



<?php foreach($schedule as $time=>$lessons){ ?>


<tr>


<td>
<?= $time ?>
</td>



<?php foreach($days as $day){ ?>


<td>

<?= $lessons[$day] ?? "-" ?>

</td>


<?php } ?>


</tr>


<?php } ?>



</tbody>


</table>



<?php } else { ?>


<p>
Select a class to display the timetable.
</p>


<?php } ?>




</div>



</main>


</div>


</body>


</html>