<?php

include "../../includes/auth.php";
include "../../config/database.php";


if(!isset($_GET['id'])){

    header("Location: classes.php");
    exit();

}


$id = $_GET['id'];



// Get class data

$query = $conn->prepare("

SELECT *

FROM classes

WHERE id=?

");


$query->execute([$id]);


$class = $query->fetch(PDO::FETCH_ASSOC);



if(!$class){

    die("Class not found");

}




if(isset($_POST['update'])){


    $class_name = trim($_POST['class_name']);

    $level = trim($_POST['level']);



    $update = $conn->prepare("

    UPDATE classes

    SET class_name=?, level=?

    WHERE id=?

    ");



    $update->execute([

        $class_name,
        $level,
        $id

    ]);



    header("Location: classes.php");

    exit();



}



?>



<!DOCTYPE html>

<html>

<head>

<title>Edit Class</title>

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


<?php $hideSearch = true;include "../../includes/header.php"; ?>



<div class="table-box">


<h1>
Edit Class
</h1>




<form method="POST">


<label>
Class Name
</label>


<input

class="form-input"

type="text"

name="class_name"

value="<?php echo $class['class_name']; ?>"

required>




<label>
Level
</label>


<input

class="form-input"

type="text"

name="level"

value="<?php echo $class['level']; ?>"

required>





<button

class="add-btn"

name="update">

Save Changes

</button>



</form>



</div>



</main>


</div>



</body>


</html>