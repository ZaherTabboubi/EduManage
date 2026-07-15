<?php

include "../../includes/auth.php";
include "../../config/database.php";



$studentsQuery = $conn->prepare("

SELECT

users.id,

users.full_name,

users.login_id,

classes.class_name


FROM users


INNER JOIN students

ON users.id = students.user_id


LEFT JOIN classes

ON students.class_id = classes.id


WHERE users.role='student'


ORDER BY users.full_name


");


$studentsQuery->execute();


$students = $studentsQuery->fetchAll(PDO::FETCH_ASSOC);


?>


<!DOCTYPE html>

<html>

<head>

<title>Reports | EduManage</title>

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
Reports
</h1>

<p>
Generate student reports
</p>

</div>

</div>





<div class="table-box">


<table>


<thead>

<tr>

<th>
Login ID
</th>

<th>
Student
</th>

<th>
Class
</th>

<th>
Report
</th>


</tr>

</thead>



<tbody>


<?php foreach($students as $student){ ?>


<tr>


<td>

<?= $student['login_id']; ?>

</td>


<td>

<?= $student['full_name']; ?>

</td>


<td>

<?= $student['class_name'] ?? "No class"; ?>

</td>



<td>


<a

class="add-btn"

href="student_report.php?id=<?= $student['id']; ?>">


<i class="fa-solid fa-file"></i>

View Report


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