<?php

include "../../includes/auth.php";
include "../../config/database.php";



// ================= COUNTS =================


// Students

$studentCount = $conn->query("

SELECT COUNT(*)

FROM users

WHERE role='student'

")->fetchColumn();




// Teachers

$teacherCount = $conn->query("

SELECT COUNT(*)

FROM users

WHERE role='teacher'

")->fetchColumn();




// Classes

$classCount = $conn->query("

SELECT COUNT(*)

FROM classes

")->fetchColumn();




// ================= ATTENDANCE =================


// Attendance percentage

$attendanceQuery = $conn->query("

SELECT

ROUND(

(SUM(status='Present') / COUNT(*)) * 100

,2)

FROM attendance

");


$attendanceRate = $attendanceQuery->fetchColumn();


if($attendanceRate === null){

    $attendanceRate = 0;

}




// Today attendance

$todayAttendance = $conn->query("

SELECT COUNT(*)

FROM attendance

WHERE attendance_date = CURDATE()

")->fetchColumn();




// Attendance chart

$presentCount = $conn->query("

SELECT COUNT(*)

FROM attendance

WHERE status='Present'

AND attendance_date = CURDATE()

")->fetchColumn();



$absentCount = $conn->query("

SELECT COUNT(*)

FROM attendance

WHERE status='Absent'

AND attendance_date = CURDATE()

")->fetchColumn();


$lateCount = $conn->query("

SELECT COUNT(*)

FROM attendance

WHERE status='Late'

AND attendance_date = CURDATE()

")->fetchColumn();




// ================= STUDENT GROWTH =================


$growthQuery = $conn->query("

SELECT

MONTH(created_at) month,

COUNT(*) total

FROM users

WHERE role='student'

GROUP BY MONTH(created_at)

ORDER BY MONTH(created_at)

");


$growth = $growthQuery->fetchAll(PDO::FETCH_ASSOC);



$months=[];

$studentTotals=[];



foreach($growth as $row){

    $months[] = date(
        "M",
        mktime(0,0,0,$row['month'],1)
    );

    $studentTotals[]=$row['total'];

}




// ================= STUDENTS PER CLASS =================


$classStudents = $conn->query("

SELECT

classes.class_name,

COUNT(students.user_id) total


FROM classes


LEFT JOIN students

ON classes.id = students.class_id


GROUP BY classes.id


ORDER BY classes.class_name


")->fetchAll(PDO::FETCH_ASSOC);



$classNames=[];

$classTotals=[];



foreach($classStudents as $row){

    $classNames[]=$row['class_name'];

    $classTotals[]=$row['total'];

}




// ================= LATEST USERS =================


$latestStudents = $conn->query("

SELECT

login_id,

full_name,

email


FROM users


WHERE role='student'


ORDER BY created_at DESC


LIMIT 5


")->fetchAll(PDO::FETCH_ASSOC);





$latestTeachers = $conn->query("

SELECT

login_id,

full_name,

email


FROM users


WHERE role='teacher'


ORDER BY created_at DESC


LIMIT 5


")->fetchAll(PDO::FETCH_ASSOC);



?>



<!DOCTYPE html>

<html lang="en">

<head>


<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Admin Dashboard | EduManage</title>



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



<section class="welcome">


<h1>

Welcome back,
<?php echo $_SESSION['user']['name']; ?> 👋

</h1>


<p>

School overview and daily activities.

</p>


</section>






<!-- CARDS -->


<section class="cards">



<div class="stat-card">

<i class="fa-solid fa-user-graduate"></i>

<div>

<h3>Students</h3>

<h2><?php echo $studentCount; ?></h2>

</div>

</div>





<div class="stat-card">

<i class="fa-solid fa-chalkboard-user"></i>

<div>

<h3>Teachers</h3>

<h2><?php echo $teacherCount; ?></h2>

</div>

</div>






<div class="stat-card">

<i class="fa-solid fa-school"></i>

<div>

<h3>Classes</h3>

<h2><?php echo $classCount; ?></h2>

</div>

</div>






<div class="stat-card">

<i class="fa-solid fa-calendar-check"></i>

<div>

<h3>Attendance</h3>

<h2><?php echo $attendanceRate; ?>%</h2>

</div>

</div>



</section>






<!-- QUICK INFO -->


<section class="cards">


<div class="stat-card">

<i class="fa-solid fa-calendar-day"></i>

<div>

<h3>Today Attendance</h3>

<h2><?php echo $todayAttendance; ?></h2>

</div>


</div>


<div class="stat-card">

<i class="fa-solid fa-user-check"></i>

<div>

<h3>Present</h3>

<h2><?php echo $presentCount; ?></h2>

</div>

</div>


<div class="stat-card">

<i class="fa-solid fa-user-xmark"></i>

<div>

<h3>Absent</h3>

<h2><?php echo $absentCount; ?></h2>

</div>

</div>


<div class="stat-card">

<i class="fa-solid fa-clock"></i>

<div>

<h3>Late</h3>

<h2><?php echo $lateCount; ?></h2>

</div>

</div>


</section>







<!-- CHARTS -->


<section class="charts">


<div class="chart-box">

<h3>Student Growth</h3>

<canvas id="studentsChart"></canvas>


</div>




<div class="chart-box">

<h3>Attendance Overview</h3>

<canvas id="attendanceChart"></canvas>


</div>



<div class="chart-box">

<h3>Students Per Class</h3>

<canvas id="classChart"></canvas>


</div>



</section>









<!-- TABLES -->


<div class="table-box">


<h2>Latest Students</h2>


<table>


<tr>

<th>ID</th>

<th>Name</th>

<th>Email</th>

</tr>


<?php foreach($latestStudents as $s){ ?>

<tr>

<td><?php echo $s['login_id']; ?></td>

<td><?php echo $s['full_name']; ?></td>

<td><?php echo $s['email']; ?></td>

</tr>


<?php } ?>


</table>


</div>






<div class="table-box">


<h2>Latest Teachers</h2>


<table>


<tr>

<th>ID</th>

<th>Name</th>

<th>Email</th>

</tr>


<?php foreach($latestTeachers as $t){ ?>

<tr>

<td><?php echo $t['login_id']; ?></td>

<td><?php echo $t['full_name']; ?></td>

<td><?php echo $t['email']; ?></td>

</tr>


<?php } ?>


</table>


</div>





</main>


</div>






<script>


new Chart(

document.getElementById('studentsChart'),

{

type:'line',

data:{

labels:

<?php echo json_encode($months); ?>,


datasets:[{

label:'Students',

data:

<?php echo json_encode($studentTotals); ?>


}]

}


});






new Chart(

document.getElementById('attendanceChart'),

{

type:'doughnut',

data:{

labels:[

'Present',

'Absent',

'Late'

],


datasets:[{

data:[

<?php echo $presentCount; ?>,

<?php echo $absentCount; ?>,

<?php echo $lateCount; ?>

]


}]

}


});






new Chart(

document.getElementById('classChart'),

{

type:'bar',

data:{

labels:

<?php echo json_encode($classNames); ?>,


datasets:[{

label:'Students',

data:

<?php echo json_encode($classTotals); ?>


}]


}


});


</script>




<?php include "../../includes/footer.php"; ?>



</body>
</html>
</body>

</html>