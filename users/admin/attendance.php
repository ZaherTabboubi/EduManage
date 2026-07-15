<?php

include "../../includes/auth.php";
include "../../config/database.php";


// Get teachers

$teacherQuery = $conn->prepare("
    SELECT 
        id,
        full_name
    FROM users
    WHERE role='teacher'
    ORDER BY full_name
");

$teacherQuery->execute();

$teachers = $teacherQuery->fetchAll(PDO::FETCH_ASSOC);



// Get classes

$classQuery = $conn->prepare("
    SELECT
        id,
        class_name,
        level
    FROM classes
    ORDER BY class_name
");

$classQuery->execute();

$classes = $classQuery->fetchAll(PDO::FETCH_ASSOC);


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


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>


<body>


<div class="layout">


<?php include "../../includes/sidebar.php"; ?>


<main class="content">


<?php $hideSearch=true; include "../../includes/header.php"; ?>



<div class="page-title">

<div>

<h1>
Attendance
</h1>

<p>
Record daily student attendance
</p>

</div>

</div>




<form method="POST" action="save_attendance.php">



<div class="table-box">


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

<option value="<?= $teacher['id']; ?>">

<?= $teacher['full_name']; ?>

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


<option value="">

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


<option value="<?= $class['id']; ?>">

<?= $class['class_name']." - ".$class['level']; ?>

</option>


<?php } ?>


</select>






<label>
Date
</label>


<input

class="form-input"

type="date"

name="attendance_date"

value="<?= date('Y-m-d'); ?>"

required>


</div>







<div class="table-box">


<h2>
Students
</h2>



<table>


<thead>

<tr>

<th>
Login ID
</th>

<th>
Name
</th>

<th>
Status
</th>


</tr>


</thead>



<tbody id="studentsTable">


<tr>

<td colspan="3">

Select a class first

</td>


</tr>


</tbody>


</table>





<br>



<button

class="add-btn"

type="submit">


<i class="fa-solid fa-floppy-disk"></i>

Save Attendance


</button>



</div>



</form>



</main>


</div>








<script>


// Teacher -> Subjects

document.getElementById("teacher").addEventListener("change", function(){


let teacher_id = this.value;


let subject = document.getElementById("subject");


subject.innerHTML = 
"<option>Loading...</option>";



fetch("get_attendance_subjects.php?teacher_id="+teacher_id)


.then(response => response.json())


.then(data => {


subject.innerHTML="";



if(data.length === 0){


subject.innerHTML =
"<option>No subjects assigned</option>";

return;

}



data.forEach(item=>{


subject.innerHTML += `

<option value="${item.id}">

${item.subject_name}

</option>

`;


});


});


});








// Class -> Students


document.getElementById("class").addEventListener("change", function(){


let class_id = this.value;


let table = document.getElementById("studentsTable");



table.innerHTML = `

<tr>

<td colspan="3">

Loading students...

</td>

</tr>

`;




fetch("get_attendance_students.php?class_id="+class_id)


.then(response=>response.json())


.then(data=>{


table.innerHTML="";



if(data.length === 0){


table.innerHTML = `

<tr>

<td colspan="3">

No students found

</td>

</tr>

`;

return;


}




data.forEach(student=>{


table.innerHTML += `


<tr>


<td>

${student.login_id}

</td>



<td>

${student.full_name}

</td>



<td>


<select

class="form-input"

name="attendance[${student.id}]"

required>


<option value="Present">

Present

</option>


<option value="Absent">

Absent

</option>


<option value="Late">

Late

</option>


</select>


</td>



</tr>


`;


});



});


});



</script>



</body>

</html>