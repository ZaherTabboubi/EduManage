<?php

include "../../includes/auth.php";
include "../../config/database.php";


// Get classes with student count

$query = $conn->prepare("

SELECT

classes.id,
classes.class_name,
classes.level,

COUNT(students.user_id) AS student_count


FROM classes


LEFT JOIN students

ON classes.id = students.class_id


GROUP BY classes.id


");


$query->execute();


$classes = $query->fetchAll(PDO::FETCH_ASSOC);


?>


<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Classes | EduManage</title>


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


<?php include "../../includes/header.php"; ?>



<div class="page-title">


<div>

<h1>
Classes
</h1>


<p>
Manage school classes
</p>


</div>



<a href="add_class.php" class="add-btn">

<i class="fa-solid fa-plus"></i>

Add Class

</a>



</div>





<div class="table-box">



<table>


<thead>


<tr>


<th>ID</th>

<th>Class Name</th>

<th>Level</th>

<th>Students</th>

<th>Actions</th>


</tr>


</thead>




<tbody>



<?php foreach($classes as $class){ ?>


<tr>


<td>

<?php echo $class['id']; ?>

</td>



<td>

<?php echo $class['class_name']; ?>

</td>



<td>

<?php echo $class['level']; ?>

</td>



<td>

<?php echo $class['student_count']; ?>

</td>



<td>



<a href="edit_class.php?id=<?php echo $class['id']; ?>"

class="edit-btn">


<i class="fa-solid fa-pen"></i>


</a>




<a href="delete_class.php?id=<?php echo $class['id']; ?>"

class="delete-btn"

onclick="return confirm('Delete this class?')">


<i class="fa-solid fa-trash"></i>


</a>



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