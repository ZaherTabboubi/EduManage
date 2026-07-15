<?php

include "../../includes/auth.php";
include "../../config/database.php";



// Get subjects with teacher count

$query = $conn->prepare("

SELECT


subjects.id,

subjects.subject_name,


COUNT(teacher_subjects.user_id) AS teacher_count



FROM subjects



LEFT JOIN teacher_subjects

ON subjects.id = teacher_subjects.subject_id



GROUP BY subjects.id



");


$query->execute();


$subjects = $query->fetchAll(PDO::FETCH_ASSOC);



?>



<!DOCTYPE html>

<html lang="en">

<head>


<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Subjects | EduManage</title>



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
Subjects
</h1>


<p>
Manage school subjects
</p>


</div>




<a href="add_subject.php" class="add-btn">


<i class="fa-solid fa-plus"></i>


Add Subject


</a>




</div>






<div class="table-box">


<table>


<thead>


<tr>


<th>
ID
</th>


<th>
Subject Name
</th>


<th>
Teachers
</th>


<th>
Actions
</th>


</tr>



</thead>





<tbody>




<?php foreach($subjects as $subject){ ?>



<tr>



<td>

<?php echo $subject['id']; ?>

</td>





<td>

<?php echo $subject['subject_name']; ?>

</td>





<td>

<?php echo $subject['teacher_count']; ?>

</td>





<td>



<a href="edit_subject.php?id=<?php echo $subject['id']; ?>"

class="edit-btn">


<i class="fa-solid fa-pen"></i>


</a>





<a href="delete_subject.php?id=<?php echo $subject['id']; ?>"

class="delete-btn"


onclick="return confirm('Delete this subject?')">


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