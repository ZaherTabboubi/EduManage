<?php

include "../../includes/auth.php";
include "../../config/database.php";



if(!isset($_GET['id'])){


    header("Location: subjects.php");

    exit();


}



$id = $_GET['id'];





// Get subject


$query = $conn->prepare("

SELECT *

FROM subjects

WHERE id=?

");


$query->execute([$id]);



$subject = $query->fetch(PDO::FETCH_ASSOC);





if(!$subject){


    die("Subject not found");


}





if(isset($_POST['update'])){


    $subject_name = trim($_POST['subject_name']);



    if(!empty($subject_name)){



        $update = $conn->prepare("

        UPDATE subjects

        SET subject_name=?

        WHERE id=?

        ");




        $update->execute([

            $subject_name,

            $id

        ]);




        header("Location: subjects.php");

        exit();



    }



}



?>



<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<title>Edit Subject</title>


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
Edit Subject
</h1>




<form method="POST">





<label>
Subject Name
</label>



<input

class="form-input"

type="text"

name="subject_name"

value="<?php echo $subject['subject_name']; ?>"

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