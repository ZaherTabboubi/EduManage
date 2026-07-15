<?php

include "../../includes/auth.php";
include "../../config/database.php";


$message = "";


if(isset($_POST['submit'])){


    $class_name = trim($_POST['class_name']);

    $level = trim($_POST['level']);



    if(!empty($class_name) && !empty($level)){


        $insert = $conn->prepare("

        INSERT INTO classes
        (class_name, level)

        VALUES (?,?)

        ");



        $insert->execute([

            $class_name,
            $level

        ]);



        $message = "Class added successfully";

    }



}



?>


<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<title>Add Class</title>


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



<div class="table-box">



<h1>
Add Class
</h1>



<?php if($message){ ?>

<p style="color:green">

<?php echo $message; ?>

</p>

<?php } ?>




<form method="POST">



<label>
Class Name
</label>


<input

class="form-input"

type="text"

name="class_name"

placeholder="Example: Computer Science A"

required>




<label>
Level
</label>


<input

class="form-input"

type="text"

name="level"

placeholder="Example: 3rd Year"

required>





<button

class="add-btn"

name="submit">

Create Class

</button>



</form>



</div>



</main>



</div>



</body>

</html>