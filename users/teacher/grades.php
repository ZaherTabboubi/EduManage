<?php

include "../../includes/auth.php";
include "../../config/database.php";


$teacher_id = $_SESSION['user']['id'];

$error="";
$success="";



// =======================
// GET TEACHER SUBJECTS
// =======================


$subjectsQuery = $conn->prepare("

SELECT 

subjects.id,
subjects.subject_name


FROM teacher_subjects


INNER JOIN subjects

ON teacher_subjects.subject_id = subjects.id


WHERE teacher_subjects.user_id = ?


");


$subjectsQuery->execute([$teacher_id]);


$subjects = $subjectsQuery->fetchAll(PDO::FETCH_ASSOC);






// =======================
// GET TEACHER CLASSES
// =======================


$classesQuery = $conn->prepare("

SELECT DISTINCT

classes.id,
classes.class_name


FROM schedule


INNER JOIN classes

ON schedule.class_id = classes.id


WHERE schedule.teacher_id = ?


");


$classesQuery->execute([$teacher_id]);


$classes = $classesQuery->fetchAll(PDO::FETCH_ASSOC);








// =======================
// SHOW STUDENTS
// =======================


$students=[];


if(isset($_POST['load'])){


$class_id=$_POST['class_id'];


$studentsQuery=$conn->prepare("


SELECT

users.id,
users.full_name


FROM students


INNER JOIN users

ON students.user_id = users.id


WHERE students.class_id = ?


");


$studentsQuery->execute([$class_id]);


$students=$studentsQuery->fetchAll(PDO::FETCH_ASSOC);



}






// =======================
// SAVE GRADES
// =======================



if(isset($_POST['save'])){

$class_id=$_POST['class_id'];

$subject_id=$_POST['subject_id'];

$exam=$_POST['exam_type'];

foreach($_POST['grades'] as $student=>$grade){

if($grade!=""){

$insert=$conn->prepare("

INSERT INTO grades

(
student_id,
subject_id,
teacher_id,
exam_type,
grade,
grade_date

)

VALUES

(?,?,?,?,?,CURDATE())

");

$insert->execute([

$student,

$subject_id,

$teacher_id,

$exam,

$grade

]);

}

}

$success="Grades saved successfully";

}?>


<!DOCTYPE html>

<html lang="en">

<head>


<meta charset="UTF-8">


<title>Grades | EduManage</title>


<link rel="stylesheet" href="../../assets/css/dashboard.css">


<link rel="stylesheet"

href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">


</head>



<body>


<div class="layout">


<?php include "../../includes/teacher_sidebar.php"; ?>



<main class="content">


<?php include "../../includes/teacher_header.php"; ?>




<div class="page-title">

<div>

<h1>
Grades
</h1>

<p>
Manage student grades
</p>

</div>


</div>





<?php if($success){ ?>

<div class="success-message">

<?= $success ?>

</div>

<?php } ?>








<div class="table-box">



<form method="POST">



<label>
Class
</label>


<select name="class_id" class="form-input" required>


<option>
Select Class
</option>


<?php foreach($classes as $c){ ?>


<option value="<?= $c['id'] ?>">


<?= $c['class_name'] ?>


</option>


<?php } ?>


</select>





<button class="add-btn" name="load">

Load Students

</button>



</form>



</div>







<?php if($students){ ?>


<div class="table-box">


<form method="POST">



<input type="hidden" name="class_id"
value="<?= $_POST['class_id'] ?>">



<label>
Subject
</label>


<select name="subject_id" class="form-input" required>


<?php foreach($subjects as $s){ ?>


<option value="<?= $s['id'] ?>">


<?= $s['subject_name'] ?>


</option>


<?php } ?>


</select>






<label>
Exam Type
</label>


<select name="exam_type" class="form-input">


<option>
Test
</option>


<option>
Exam
</option>


<option>
Homework
</option>


</select>







<table>


<tr>

<th>
Student
</th>


<th>
Grade
</th>


</tr>



<?php foreach($students as $s){ ?>


<tr>


<td>

<?= $s['full_name'] ?>

</td>


<td>


<input

type="number"

step="0.01"

max="20"

name="grades[<?= $s['id'] ?>]"

class="form-input">


</td>


</tr>



<?php } ?>



</table>





<button class="add-btn" name="save">

Save Grades

</button>



</form>


</div>


<?php } ?>





</main>


</div>


</body>

</html>