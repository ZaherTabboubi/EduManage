<?php

include "../../includes/auth.php";
include "../../config/database.php";


$teacher_id=$_SESSION['user']['id'];

$error="";
$success="";




// Get teacher classes

$classQuery=$conn->prepare("

SELECT DISTINCT

classes.id,
classes.class_name


FROM schedule


INNER JOIN classes

ON schedule.class_id=classes.id


WHERE schedule.teacher_id=?


ORDER BY classes.class_name


");


$classQuery->execute([$teacher_id]);


$classes=$classQuery->fetchAll(PDO::FETCH_ASSOC);





// Get teacher subjects

$subjectQuery=$conn->prepare("

SELECT DISTINCT

subjects.id,
subjects.subject_name


FROM teacher_subjects


INNER JOIN subjects

ON teacher_subjects.subject_id=subjects.id



WHERE teacher_subjects.user_id=?



");


$subjectQuery->execute([$teacher_id]);


$subjects=$subjectQuery->fetchAll(PDO::FETCH_ASSOC);







// Save attendance


if(isset($_POST['save'])){


$class_id = $_POST['class_id'];
$subject_id = $_POST['subject_id'];
$date = $_POST['date'];



foreach($_POST['status'] as $student_id=>$status){



// check if attendance already exists

$check=$conn->prepare("

SELECT id

FROM attendance

WHERE student_id=?

AND subject_id=?

AND attendance_date=?

");



$check->execute([

$student_id,

$subject_id,

$date

]);





if($check->rowCount()>0){



$update=$conn->prepare("

UPDATE attendance

SET status=?,
teacher_id=?,
class_id=?

WHERE student_id=?

AND subject_id=?

AND attendance_date=?

");



$update->execute([

$status,

$teacher_id,

$class_id,

$student_id,

$subject_id,

$date

]);



}else{



$insert=$conn->prepare("


INSERT INTO attendance

(
student_id,
class_id,
subject_id,
teacher_id,
attendance_date,
status

)

VALUES

(?,?,?,?,?,?)

");


$insert->execute([

$student_id,

$class_id,

$subject_id,

$teacher_id,

$date,

$status

]);


}



}


$success="Attendance saved successfully";


}






// Load students

$students=[];



if(isset($_POST['load'])){


$class_id=$_POST['class_id'];



$studentQuery=$conn->prepare("


SELECT


students.user_id,

users.full_name



FROM students



INNER JOIN users


ON students.user_id=users.id



WHERE students.class_id=?



ORDER BY users.full_name



");


$studentQuery->execute([$class_id]);


$students=$studentQuery->fetchAll(PDO::FETCH_ASSOC);



}



?>



<!DOCTYPE html>

<html lang="en">

<head>


<meta charset="UTF-8">


<title>Attendance | EduManage</title>


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

Attendance

</h1>


<p>

Mark student attendance

</p>


</div>

</div>






<div class="table-box">



<?php if($success){ ?>

<p class="success">

<?= $success ?>

</p>

<?php } ?>






<form method="POST">





<label>

Class

</label>


<select name="class_id" class="form-input" required>



<option value="">

Select Class

</option>



<?php foreach($classes as $c){ ?>


<option value="<?= $c['id']; ?>">

<?= $c['class_name']; ?>

</option>



<?php } ?>


</select>






<label>

Subject

</label>


<select name="subject_id" class="form-input" required>



<option value="">

Select Subject

</option>



<?php foreach($subjects as $s){ ?>


<option value="<?= $s['id']; ?>">

<?= $s['subject_name']; ?>

</option>



<?php } ?>


</select>







<label>

Date

</label>



<input

type="date"

name="date"

class="form-input"

value="<?= date('Y-m-d'); ?>"

required>





<button

class="add-btn"

name="load">

Load Students

</button>





</form>





</div>









<?php if(count($students)>0){ ?>





<div class="table-box">



<form method="POST">



<input type="hidden" name="class_id" value="<?= $_POST['class_id']; ?>">


<input type="hidden" name="subject_id" value="<?= $_POST['subject_id']; ?>">


<input type="hidden" name="date" value="<?= $_POST['date']; ?>">






<table>


<thead>

<tr>

<th>

Student

</th>


<th>

Present

</th>


<th>

Absent

</th>


<th>

Late

</th>


</tr>

</thead>




<tbody>



<?php foreach($students as $s){ ?>

<tr>


<td>

<?= $s['full_name']; ?>

</td>



<td>

<input 

type="radio"

name="status[<?= $s['user_id']; ?>]"

value="Present"

checked>

</td>




<td>

<input 

type="radio"

name="status[<?= $s['user_id']; ?>]"

value="Absent">

</td>





<td>

<input 

type="radio"

name="status[<?= $s['user_id']; ?>]"

value="Late">

</td>



</tr>


<?php } ?>



</tbody>


</table>





<button class="add-btn" name="save">

Save Attendance

</button>




</form>



</div>



<?php } ?>





</main>


</div>



</body>

</html>