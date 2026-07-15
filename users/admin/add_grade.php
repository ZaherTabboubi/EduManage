<?php

include "../../includes/auth.php";
include "../../config/database.php";



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






// Save grade

if(isset($_POST['submit'])){


    $student_id = $_POST['student_id'];

    $subject_id = $_POST['subject_id'];

    $teacher_id = $_POST['teacher_id'];

    $exam_type = $_POST['exam_type'];

    $grade = $_POST['grade'];

    $date = $_POST['grade_date'];





    // Check teacher really teaches this subject

    $check = $conn->prepare("

    SELECT *

    FROM teacher_subjects

    WHERE user_id = ?

    AND subject_id = ?

    ");


    $check->execute([

        $teacher_id,

        $subject_id

    ]);



    if($check->rowCount() == 0){

        die("Error: This teacher is not assigned to this subject.");

    }






    // Check student belongs to selected class

    // Verify student belongs to selected class

$checkStudent = $conn->prepare("

SELECT *

FROM students

WHERE user_id = ?

AND class_id = ?

");


$checkStudent->execute([

    $student_id,

    $_POST['class_id']

]);



if($checkStudent->rowCount() == 0){

    die("Error: Student is not in this class.");

}
    // Insert grade

    $insert = $conn->prepare("

    INSERT INTO grades

    (

    student_id,

    subject_id,

    teacher_id,

    exam_type,

    grade,

    grade_date

    )

    VALUES (?,?,?,?,?,?)

    ");





    $insert->execute([

        $student_id,

        $subject_id,

        $teacher_id,

        $exam_type,

        $grade,

        $date

    ]);




    header("Location: grades.php");

    exit();



}


?>



<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<title>Add Grade | EduManage</title>


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


<h1>Add Grade</h1>



<form method="POST">






<label>
Teacher
</label>


<select

class="form-input"

id="teacher"

name="teacher_id"

required>


<option value="">
Select Teacher
</option>


<?php foreach($teachers as $teacher){ ?>


<option value="<?php echo $teacher['id']; ?>">


<?php echo $teacher['full_name']; ?>


</option>


<?php } ?>


</select>







<label>
Subject
</label>


<select

class="form-input"

id="subject"

name="subject_id"

required>


<option>
Select teacher first
</option>


</select>








<label>
Class
</label>


<select

class="form-input"

id="class"

name="class_id"

required>


<option value="">
Select Class
</option>



<?php foreach($classes as $class){ ?>


<option value="<?php echo $class['id']; ?>">


<?php echo $class['class_name']." - ".$class['level']; ?>


</option>


<?php } ?>


</select>







<label>
Student
</label>


<select

class="form-input"

id="student"

name="student_id"

required>


<option>
Select class first
</option>


</select>







<label>
Exam Type
</label>


<select

class="form-input"

name="exam_type">


<option value="Test">
Test
</option>


<option value="Exam">
Exam
</option>


<option value="Homework">
Homework
</option>


</select>






<label>
Grade /20
</label>


<input

class="form-input"

type="number"

step="0.01"

min="0"

max="20"

name="grade"

required>







<label>
Date
</label>


<input

class="form-input"

type="date"

name="grade_date"

value="<?php echo date('Y-m-d'); ?>"

required>







<button

class="add-btn"

name="submit">


<i class="fa-solid fa-save"></i>

Save Grade


</button>



</form>



</div>



</main>


</div>






<script>


// Load subjects when teacher changes

document.getElementById("teacher").onchange=function(){


fetch("get_subjects.php?teacher_id="+this.value)


.then(response=>response.json())


.then(data=>{


let subject=document.getElementById("subject");


subject.innerHTML="";



data.forEach(item=>{


subject.innerHTML +=

`<option value="${item.id}">

${item.subject_name}

</option>`;


});


});


};





// Load students when class changes


document.getElementById("class").onchange=function(){


fetch("get_students.php?class_id="+this.value)


.then(response=>response.json())


.then(data=>{


let student=document.getElementById("student");


student.innerHTML="";



data.forEach(item=>{


student.innerHTML +=


`<option value="${item.id}">

${item.login_id} - ${item.full_name}

</option>`;



});


});


};



</script>



</body>


</html>