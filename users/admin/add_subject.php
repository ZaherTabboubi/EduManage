<?php

include "../../includes/auth.php";
include "../../config/database.php";


$message = "";
$error = "";



if(isset($_POST['submit'])){


    $subject_name = trim($_POST['subject_name']);



    if(empty($subject_name)){


        $error = "Subject name is required.";


    }else{



        // Check duplicate


        $check = $conn->prepare("

        SELECT id

        FROM subjects

        WHERE subject_name=?

        ");



        $check->execute([$subject_name]);





        if($check->rowCount() > 0){


            $error = "Subject already exists.";



        }else{



            $insert = $conn->prepare("

            INSERT INTO subjects

            (subject_name)

            VALUES(?)

            ");




            $insert->execute([

                $subject_name

            ]);




            $message = "Subject added successfully.";



        }



    }



}



?>



<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<title>Add Subject</title>



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




<div class="table-box">


<h1>
Add Subject
</h1>




<?php if($error){ ?>

<p style="color:red">

<?php echo $error; ?>

</p>

<?php } ?>





<?php if($message){ ?>

<p style="color:green">

<?php echo $message; ?>

</p>

<?php } ?>






<form method="POST">





<label>
Subject Name
</label>



<input

class="form-input"

type="text"

name="subject_name"

placeholder="Example: Mathematics"

required>





<button

class="add-btn"

name="submit">


Create Subject


</button>




</form>




</div>




</main>


</div>



</body>



</html>