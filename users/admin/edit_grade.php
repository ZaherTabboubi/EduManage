<?php

include "../../includes/auth.php";
include "../../config/database.php";



if(!isset($_GET['id'])){

    header("Location: grades.php");
    exit();

}


$id = $_GET['id'];




// Get current grade

$query = $conn->prepare("

SELECT *

FROM grades

WHERE id = ?

");


$query->execute([$id]);


$gradeData = $query->fetch(PDO::FETCH_ASSOC);



if(!$gradeData){

    die("Grade not found");

}






// Teachers

$teacherQuery = $conn->prepare("

SELECT id, full_name

FROM users

WHERE role='teacher'

ORDER BY full_name

");


$teacherQuery->execute();

$teachers = $teacherQuery->fetchAll(PDO::FETCH_ASSOC);







// Classes

$classQuery = $conn->prepare("

SELECT id, class_name, level

FROM classes

ORDER BY class_name

");


$classQuery->execute();

$classes = $classQuery->fetchAll(PDO::FETCH_ASSOC);






// Find student's class

$classStudent = $conn->prepare("

SELECT class_id

FROM students

WHERE user_id = ?

");


$classStudent->execute([$gradeData['student_id']]);


$currentClass = $classStudent->fetchColumn();





// Subjects for current teacher

$subjectQuery = $conn->prepare("

SELECT

subjects.id,

subjects.subject_name


FROM subjects


INNER JOIN teacher_subjects

ON subjects.id = teacher_subjects.subject_id


WHERE teacher_subjects.user_id = ?


");


$subjectQuery->execute([$gradeData['teacher_id']]);


$subjects = $subjectQuery->fetchAll(PDO::FETCH_ASSOC);







// Students for current class

$studentQuery = $conn->prepare("

SELECT

users.id,

users.login_id,

users.full_name


FROM students


INNER JOIN users

ON students.user_id = users.id


WHERE students.class_id = ?


ORDER BY users.full_name


");


$studentQuery->execute([$currentClass]);


$students = $studentQuery->fetchAll(PDO::FETCH_ASSOC);







if(isset($_POST['submit'])){


    $student_id = $_POST['student_id'];

    $subject_id = $_POST['subject_id'];

    $teacher_id = $_POST['teacher_id'];

    $exam_type = $_POST['exam_type'];

    $grade = $_POST['grade'];

    $date = $_POST['grade_date'];





    // Verify teacher-subject

    $check = $conn->prepare("

    SELECT *

    FROM teacher_subjects

    WHERE user_id=?

    AND subject_id=?

    ");


    $check->execute([

        $teacher_id,

        $subject_id

    ]);



    if($check->rowCount()==0){

        die("This teacher is not assigned to this subject");

    }






    $update = $conn->prepare("

    UPDATE grades

    SET

    student_id=?,

    subject_id=?,

    teacher_id=?,

    exam_type=?,

    grade=?,

    grade_date=?


    WHERE id=?

    ");



    $update->execute([

        $student_id,

        $subject_id,

        $teacher_id,

        $exam_type,

        $grade,

        $date,

        $id

    ]);





    header("Location: grades.php");

    exit();



}



?>



<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<title>Edit Grade</title>


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


<h1>Edit Grade</h1>



<form method="POST">



<label>Teacher</label>

<select class="form-input" name="teacher_id">


<?php foreach($teachers as $teacher){ ?>


<option

value="<?php echo $teacher['id']; ?>"

<?php echo $teacher['id']==$gradeData['teacher_id'] ? "selected":""; ?>

>


<?php echo $teacher['full_name']; ?>


</option>


<?php } ?>


</select>





<label>Subject</label>

<select class="form-input" name="subject_id">


<?php foreach($subjects as $subject){ ?>


<option

value="<?php echo $subject['id']; ?>"

<?php echo $subject['id']==$gradeData['subject_id'] ? "selected":""; ?>

>


<?php echo $subject['subject_name']; ?>


</option>


<?php } ?>


</select>






<label>Class</label>

<select class="form-input">


<option>

<?php echo $currentClass; ?>

</option>

</select>







<label>Student</label>

<select class="form-input" name="student_id">


<?php foreach($students as $student){ ?>


<option

value="<?php echo $student['id']; ?>"

<?php echo $student['id']==$gradeData['student_id'] ? "selected":""; ?>

>


<?php echo $student['login_id']." - ".$student['full_name']; ?>


</option>


<?php } ?>


</select>






<label>Exam Type</label>

<select class="form-input" name="exam_type">


<option <?php echo $gradeData['exam_type']=="Test"?"selected":""; ?>>
Test
</option>


<option <?php echo $gradeData['exam_type']=="Exam"?"selected":""; ?>>
Exam
</option>


<option <?php echo $gradeData['exam_type']=="Homework"?"selected":""; ?>>
Homework
</option>


</select>







<label>Grade</label>

<input

class="form-input"

type="number"

step="0.01"

min="0"

max="20"

name="grade"

value="<?php echo $gradeData['grade']; ?>">






<label>Date</label>

<input

class="form-input"

type="date"

name="grade_date"

value="<?php echo $gradeData['grade_date']; ?>">






<button class="add-btn" name="submit">

<i class="fa-solid fa-save"></i>

Update Grade

</button>



</form>


</div>



</main>


</div>



</body>

</html>