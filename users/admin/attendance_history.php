<?php

include "../../includes/auth.php";
include "../../config/database.php";



// Get filters

$class_id = $_GET['class_id'] ?? "";

$subject_id = $_GET['subject_id'] ?? "";

$date = $_GET['date'] ?? "";




// Classes

$classQuery = $conn->query("

SELECT *

FROM classes

ORDER BY class_name

");


$classes = $classQuery->fetchAll(PDO::FETCH_ASSOC);





// Subjects

$subjectQuery = $conn->query("

SELECT *

FROM subjects

ORDER BY subject_name

");


$subjects = $subjectQuery->fetchAll(PDO::FETCH_ASSOC);






// Attendance query


$sql = "

SELECT


attendance.id,


users.full_name AS student_name,

users.login_id,


classes.class_name,


subjects.subject_name,


teacher.full_name AS teacher_name,


attendance.attendance_date,


attendance.status



FROM attendance




INNER JOIN users

ON attendance.student_id = users.id




INNER JOIN classes

ON attendance.class_id = classes.id





INNER JOIN subjects

ON attendance.subject_id = subjects.id





INNER JOIN users AS teacher

ON attendance.teacher_id = teacher.id





WHERE 1=1

";



$params = [];





if(!empty($class_id)){


    $sql .= " AND attendance.class_id = ?";

    $params[] = $class_id;


}





if(!empty($subject_id)){


    $sql .= " AND attendance.subject_id = ?";

    $params[] = $subject_id;


}





if(!empty($date)){


    $sql .= " AND attendance.attendance_date = ?";

    $params[] = $date;


}




$sql .= "

ORDER BY attendance.attendance_date DESC

";




$query = $conn->prepare($sql);


$query->execute($params);



$records = $query->fetchAll(PDO::FETCH_ASSOC);



?>




<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<title>Attendance History</title>


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



<?php $hideSearch = true; include "../../includes/header.php"; ?>





<div class="page-title">


<div>

<h1>
Attendance History
</h1>


<p>
View and filter attendance records
</p>


</div>


</div>






<div class="table-box">


<form method="GET">



<select class="form-input" name="class_id">


<option value="">
All Classes
</option>



<?php foreach($classes as $class){ ?>


<option

value="<?php echo $class['id']; ?>"

<?php echo $class_id==$class['id'] ? "selected" : ""; ?>


>

<?php echo $class['class_name']; ?>


</option>



<?php } ?>



</select>






<select class="form-input" name="subject_id">


<option value="">
All Subjects
</option>



<?php foreach($subjects as $subject){ ?>


<option

value="<?php echo $subject['id']; ?>"

<?php echo $subject_id==$subject['id'] ? "selected" : ""; ?>


>


<?php echo $subject['subject_name']; ?>


</option>



<?php } ?>



</select>





<input

class="form-input"

type="date"

name="date"

value="<?php echo $date; ?>">





<button class="add-btn">

Search

</button>



</form>



</div>







<div class="table-box">


<table>


<thead>


<tr>


<th>
Student
</th>


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
Date
</th>


<th>
Status
</th>


</tr>


</thead>



<tbody>



<?php foreach($records as $row){ ?>


<tr>


<td>

<?php echo $row['login_id']." - ".$row['student_name']; ?>

</td>



<td>

<?php echo $row['class_name']; ?>

</td>



<td>

<?php echo $row['subject_name']; ?>

</td>



<td>

<?php echo $row['teacher_name']; ?>

</td>



<td>

<?php echo $row['attendance_date']; ?>

</td>



<td>

<?php echo $row['status']; ?>

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