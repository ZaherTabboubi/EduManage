<?php

include "../../includes/auth.php";
include "../../config/database.php";


$error="";
$success="";



// Get classes

$classes = $conn->query("

SELECT *

FROM classes

ORDER BY class_name

")->fetchAll(PDO::FETCH_ASSOC);




// Get subjects

$subjects = $conn->query("

SELECT *

FROM subjects

ORDER BY subject_name

")->fetchAll(PDO::FETCH_ASSOC);






if(isset($_POST['submit'])){


$class_id = $_POST['class_id'];

$subject_id = $_POST['subject_id'];

$teacher_id = $_POST['teacher_id'];

$day = $_POST['day'];

$start_time = $_POST['start_time'];

$end_time = $_POST['end_time'];





// Verify teacher teaches subject

$checkTeacher = $conn->prepare("

SELECT *

FROM teacher_subjects

WHERE user_id=?

AND subject_id=?

");


$checkTeacher->execute([

$teacher_id,

$subject_id

]);





if($checkTeacher->rowCount()==0){


$error="This teacher does not teach this subject.";


}else{



try{


$insert = $conn->prepare("


INSERT INTO schedule

(

class_id,

subject_id,

teacher_id,

day,

start_time,

end_time

)


VALUES

(?,?,?,?,?,?)


");




$insert->execute([


$class_id,

$subject_id,

$teacher_id,

$day,

$start_time,

$end_time


]);



$success="Schedule created successfully";



}catch(Exception $e){


$error=$e->getMessage();


}



}




}



?>



<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<title>Add Schedule | EduManage</title>


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

Add Schedule

</h1>





<?php if($error){ ?>

<p class="error">

<?= $error; ?>

</p>

<?php } ?>





<?php if($success){ ?>

<p class="success">

<?= $success; ?>

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



<select 

name="subject_id" 

class="form-input"

id="subject"

required>


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

Teacher

</label>



<select 

name="teacher_id"

class="form-input"

id="teacher"

required>



<option value="">

Select Subject First

</option>



</select>










<label>

Day

</label>



<select name="day" class="form-input" required>


<option>Monday</option>

<option>Tuesday</option>

<option>Wednesday</option>

<option>Thursday</option>

<option>Friday</option>

<option>Saturday</option>


</select>








<label>

Start Time

</label>


<input

type="time"

name="start_time"

class="form-input"

required>








<label>

End Time

</label>


<input

type="time"

name="end_time"

class="form-input"

required>








<button

class="add-btn"

name="submit">


<i class="fa-solid fa-plus"></i>

Create Schedule


</button>





</form>



</div>






</main>


</div>








<script>


document

.getElementById("subject")

.addEventListener("change",function(){



let subject_id=this.value;



let teacher=document.getElementById("teacher");



teacher.innerHTML=

`<option>

Loading...

</option>`;





fetch("get_teachers.php?subject_id="+subject_id)



.then(response=>response.json())



.then(data=>{



teacher.innerHTML="";





if(data.length===0){



teacher.innerHTML=

`

<option>

No teacher assigned

</option>

`;

return;


}





data.forEach(t=>{



teacher.innerHTML +=

`

<option value="${t.id}">

${t.full_name}

</option>

`;



});



});





});



</script>



</body>


</html>