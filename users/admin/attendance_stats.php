<?php

include "../../includes/auth.php";
include "../../config/database.php";



// Total records

$totalQuery = $conn->query("

SELECT COUNT(*)

FROM attendance

");


$total = $totalQuery->fetchColumn();




// Present

$presentQuery = $conn->query("

SELECT COUNT(*)

FROM attendance

WHERE status='Present'

");


$present = $presentQuery->fetchColumn();




// Absent

$absentQuery = $conn->query("

SELECT COUNT(*)

FROM attendance

WHERE status='Absent'

");


$absent = $absentQuery->fetchColumn();




// Late

$lateQuery = $conn->query("

SELECT COUNT(*)

FROM attendance

WHERE status='Late'

");


$late = $lateQuery->fetchColumn();




// Percentage


if($total > 0){

    $percentage = round(($present / $total) * 100);

}else{

    $percentage = 0;

}



?>



<!DOCTYPE html>

<html lang="en">


<head>

<meta charset="UTF-8">

<title>Attendance Statistics</title>


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


<?php $hideSearch = true; include "../../includes/header.php"; ?>



<div class="page-title">


<div>

<h1>
Attendance Statistics
</h1>


<p>
School attendance overview
</p>


</div>


</div>





<div class="cards">



<div class="card">


<i class="fa-solid fa-calendar-check"></i>


<h3>
Total Records
</h3>


<h2>

<?php echo $total; ?>

</h2>


</div>





<div class="card">


<i class="fa-solid fa-user-check"></i>


<h3>
Present
</h3>


<h2>

<?php echo $present; ?>

</h2>


</div>






<div class="card">


<i class="fa-solid fa-user-xmark"></i>


<h3>
Absent
</h3>


<h2>

<?php echo $absent; ?>

</h2>


</div>







<div class="card">


<i class="fa-solid fa-clock"></i>


<h3>
Late
</h3>


<h2>

<?php echo $late; ?>

</h2>


</div>




</div>






<div class="table-box">


<h2>

Attendance Rate

</h2>



<h1>

<?php echo $percentage; ?>%

</h1>



</div>




</main>


</div>



</body>


</html>