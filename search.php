<?php

include "config/database.php";


if(!isset($_GET['q'])){

    header("Location:index.html");
    exit();

}


$search = "%".$_GET['q']."%";



// Teachers

$teachers = $conn->prepare("

SELECT

users.login_id,
users.full_name,
users.email


FROM users


WHERE role='teacher'

AND

(full_name LIKE ? OR login_id LIKE ?)


");


$teachers->execute([

$search,
$search

]);





// Students

$students = $conn->prepare("

SELECT

users.login_id,
users.full_name,
users.email


FROM users


WHERE role='student'

AND

(full_name LIKE ? OR login_id LIKE ?)


");


$students->execute([

$search,
$search

]);





// Classes

$classes = $conn->prepare("

SELECT *

FROM classes

WHERE class_name LIKE ?

OR level LIKE ?

");


$classes->execute([

$search,
$search

]);





// Subjects

$subjects = $conn->prepare("

SELECT *

FROM subjects

WHERE subject_name LIKE ?

");


$subjects->execute([

$search

]);



?>


<!DOCTYPE html>

<html>

<head>

<title>Search Results | EduManage</title>

<link rel="stylesheet" href="assets/css/dashboard.css">

</head>


<body>


<div class="content">


<h1>

Search results for:
<?php echo $_GET['q']; ?>

</h1>



<h2>
Teachers
</h2>


<?php foreach($teachers as $teacher){ ?>


<div class="table-box">

<h3>
<?php echo $teacher['full_name']; ?>
</h3>

<p>
<?php echo $teacher['login_id']; ?>
</p>

<p>
<?php echo $teacher['email']; ?>
</p>


</div>


<?php } ?>




<h2>
Students
</h2>



<?php foreach($students as $student){ ?>


<div class="table-box">

<h3>
<?php echo $student['full_name']; ?>
</h3>

<p>
<?php echo $student['login_id']; ?>
</p>


</div>


<?php } ?>






<h2>
Classes
</h2>



<?php foreach($classes as $class){ ?>


<div class="table-box">


<h3>

<?php echo $class['class_name']; ?>

</h3>


<p>

<?php echo $class['level']; ?>

</p>


</div>



<?php } ?>






<h2>
Subjects
</h2>



<?php foreach($subjects as $subject){ ?>


<div class="table-box">

<h3>

<?php echo $subject['subject_name']; ?>

</h3>

</div>



<?php } ?>




</div>



</body>

</html>