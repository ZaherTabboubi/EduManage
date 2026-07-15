<?php

include "../../includes/auth.php";
include "../../config/database.php";


$error="";
$success="";


if(!isset($_GET['id'])){

    header("Location:schedule.php");
    exit;

}


$id=$_GET['id'];




// Get existing schedule

$get = $conn->prepare("

SELECT *

FROM schedule

WHERE id=?

");


$get->execute([$id]);


$schedule=$get->fetch(PDO::FETCH_ASSOC);



if(!$schedule){

    die("Schedule not found");

}





// Get classes

$classes=$conn->query("

SELECT *

FROM classes

ORDER BY class_name

")->fetchAll(PDO::FETCH_ASSOC);





// Get subjects

$subjects=$conn->query("

SELECT *

FROM subjects

ORDER BY subject_name

")->fetchAll(PDO::FETCH_ASSOC);







if(isset($_POST['submit'])){


$class_id=$_POST['class_id'];

$subject_id=$_POST['subject_id'];

$teacher_id=$_POST['teacher_id'];

$day=$_POST['day'];

$start_time=$_POST['start_time'];

$end_time=$_POST['end_time'];






// Check teacher-subject relation

$check=$conn->prepare("

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


$error="This teacher does not teach this subject.";


}else{



$update=$conn->prepare("

UPDATE schedule SET


class_id=?,

subject_id=?,

teacher_id=?,

day=?,

start_time=?,

end_time=?


WHERE id=?


");



$update->execute([


$class_id,

$subject_id,

$teacher_id,

$day,

$start_time,

$end_time,

$id


]);



$success="Schedule updated successfully";


// Refresh data

$get->execute([$id]);

$schedule=$get->fetch(PDO::FETCH_ASSOC);



}




}




?>



<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<title>Edit Schedule | EduManage</title>


<link rel="stylesheet" href="../../assets/css/dashboard.css">


<link rel="stylesheet"

href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">


</head>




<body>



<div class="layout">



<?php include "../../includes/sidebar.php"; ?>



<main class="content">



<?php $hideSearch=true; include "../../includes/header.php"; ?>





<div class="table-box">


<h1>

Edit Schedule

</h1>





<?php if($error){ ?>

<p class="error">

<?= $error ?>

</p>

<?php } ?>





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


<?php foreach($classes as $c){ ?>


<option

value="<?= $c['id']; ?>"

<?= $c['id']==$schedule['class_id']?'selected':'' ?>

>


<?= $c['class_name']; ?>


</option>


<?php } ?>


</select>








<label>

Subject

</label>


<select 

name="subject_id"

id="subject"

class="form-input"

required>



<?php foreach($subjects as $s){ ?>


<option

value="<?= $s['id']; ?>"

<?= $s['id']==$schedule['subject_id']?'selected':'' ?>

>


<?= $s['subject_name']; ?>


</option>


<?php } ?>



</select>








<label>

Teacher

</label>


<select

name="teacher_id"

id="teacher"

class="form-input"

required>


<option value="<?= $schedule['teacher_id']; ?>">

Loading...

</option>


</select>








<label>

Day

</label>



<select name="day" class="form-input">


<?php

$days=[

"Monday",

"Tuesday",

"Wednesday",

"Thursday",

"Friday",

"Saturday"

];


foreach($days as $d){

?>


<option

<?= $d==$schedule['day']?'selected':'' ?>

>


<?= $d ?>


</option>


<?php } ?>



</select>








<label>

Start Time

</label>


<input

type="time"

name="start_time"

class="form-input"

value="<?= $schedule['start_time']; ?>"

required>








<label>

End Time

</label>


<input

type="time"

name="end_time"

class="form-input"

value="<?= $schedule['end_time']; ?>"

required>








<button

class="add-btn"

name="submit">


<i class="fa-solid fa-save"></i>


Update Schedule


</button>





</form>



</div>



</main>


</div>









<script>


function loadTeachers(){



let subject=document.getElementById("subject").value;


let current="<?= $schedule['teacher_id']; ?>";



fetch("get_teachers.php?subject_id="+subject)



.then(res=>res.json())

.then(data=>{



let select=document.getElementById("teacher");

select.innerHTML="";



data.forEach(t=>{



select.innerHTML +=

`

<option value="${t.id}"

${t.id==current?'selected':''}

>

${t.full_name}

</option>

`;



});



});



}





document

.getElementById("subject")

.addEventListener("change",loadTeachers);



loadTeachers();



</script>





</body>

</html>