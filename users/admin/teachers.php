<?php

include "../../includes/auth.php";
include "../../config/database.php";



// Get teachers with their information

$query = $conn->prepare("

SELECT

users.id,
users.login_id,
users.full_name,
users.email,

teachers.phone,


GROUP_CONCAT(subjects.subject_name SEPARATOR ', ') AS subjects


FROM users


INNER JOIN teachers

ON users.id = teachers.user_id



LEFT JOIN teacher_subjects

ON users.id = teacher_subjects.user_id



LEFT JOIN subjects

ON teacher_subjects.subject_id = subjects.id



WHERE users.role = 'teacher'


GROUP BY users.id


");



$query->execute();


$teachers = $query->fetchAll(PDO::FETCH_ASSOC);



?>



<!DOCTYPE html>

<html lang="en">

<head>


<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Teachers | EduManage</title>



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
Teachers
</h1>


<p>
Manage teachers and their subjects
</p>


</div>



<a href="add_teacher.php" class="add-btn">

<i class="fa-solid fa-plus"></i>

Add Teacher

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
Phone
</th>


<th>
Subjects
</th>


<th>
Actions
</th>


</tr>



</thead>






<tbody>


<?php foreach($teachers as $teacher){ ?>



<tr>


<td>

<?php echo $teacher['login_id']; ?>

</td>




<td>

<a href="view_teacher.php?id=<?php echo $teacher['id']; ?>">

<?php echo $teacher['full_name']; ?>

</a>
</td>





<td>

<?php echo $teacher['email']; ?>

</td>





<td>

<?php echo $teacher['phone'] ?? "No phone"; ?>

</td>






<td>

<?php echo $teacher['subjects'] ?? "No subjects"; ?>

</td>







<td>


<a

href="edit_teacher.php?id=<?php echo $teacher['id']; ?>"

class="edit-btn">

<i class="fa-solid fa-pen"></i>

</a>
<a href="assign_subjects.php?id=<?php echo $teacher['id']; ?>"
class="edit-btn">

<i class="fa-solid fa-book"></i>

</a>




<a

href="delete_teacher.php?id=<?php echo $teacher['id']; ?>"

class="delete-btn"

onclick="return confirm('Delete this teacher?')">

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