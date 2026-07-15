<?php

include "../../includes/auth.php";
include "../../config/database.php";


// Get students with class information

$query = $conn->prepare("

SELECT


users.id,
users.login_id,
users.full_name,
users.email,


students.parent_name,
students.parent_phone,


classes.class_name


FROM users



INNER JOIN students

ON users.id = students.user_id




LEFT JOIN classes

ON students.class_id = classes.id





WHERE users.role = 'student'



");


$query->execute();


$students = $query->fetchAll(PDO::FETCH_ASSOC);



?>



<!DOCTYPE html>

<html lang="en">

<head>


<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Students | EduManage</title>


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
Students
</h1>


<p>
Manage students and their classes
</p>


</div>




<a href="add_student.php" class="add-btn">


<i class="fa-solid fa-plus"></i>


Add Student


</a>



</div>






<div class="table-box">


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
Email
</th>


<th>
Class
</th>


<th>
Parent
</th>


<th>
Actions
</th>


</tr>


</thead>





<tbody>



<?php foreach($students as $student){ ?>



<tr>


<td>

<?php echo $student['login_id']; ?>

</td>




<td>


<a href="view_student.php?id=<?php echo $student['id']; ?>">


<?php echo $student['full_name']; ?>


</a>


</td>




<td>

<?php echo $student['email']; ?>

</td>




<td>

<?php echo $student['class_name'] ?? "No class"; ?>

</td>





<td>

<?php echo $student['parent_name'] ?? "No parent"; ?>

</td>






<td>



<a href="edit_student.php?id=<?php echo $student['id']; ?>"

class="edit-btn">


<i class="fa-solid fa-pen"></i>


</a>





<a href="delete_student.php?id=<?php echo $student['id']; ?>"

class="delete-btn"


onclick="return confirm('Delete this student?')">


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