<?php

include "../../includes/auth.php";
include "../../config/database.php";



// Check teacher id

if(!isset($_GET['id']) || empty($_GET['id'])){

    header("Location: teachers.php");
    exit();

}


$teacher_id = $_GET['id'];




// Get teacher information

$teacherQuery = $conn->prepare("

SELECT 

id,

full_name

FROM users

WHERE id=? AND role='teacher'

");


$teacherQuery->execute([$teacher_id]);


$teacher = $teacherQuery->fetch(PDO::FETCH_ASSOC);



if(!$teacher){

    die("Teacher not found");

}




// Get subjects

$subjectQuery = $conn->prepare("

SELECT

id,

subject_name

FROM subjects

ORDER BY subject_name

");


$subjectQuery->execute();


$subjects = $subjectQuery->fetchAll(PDO::FETCH_ASSOC);





// Get current assigned subjects

$assignedQuery = $conn->prepare("

SELECT subject_id

FROM teacher_subjects

WHERE user_id=?

");


$assignedQuery->execute([$teacher_id]);


$assignedSubjects = $assignedQuery->fetchAll(PDO::FETCH_COLUMN);







// Save

if(isset($_POST['save'])){


    $selectedSubjects = $_POST['subjects'] ?? [];



    try{


        $conn->beginTransaction();



        // Remove old assignments

        $delete = $conn->prepare("

        DELETE FROM teacher_subjects

        WHERE user_id=?

        ");


        $delete->execute([$teacher_id]);





        // Insert new ones

        $insert = $conn->prepare("

        INSERT INTO teacher_subjects

        (user_id, subject_id)

        VALUES (?,?)

        ");




        foreach($selectedSubjects as $subject_id){


            $insert->execute([

                $teacher_id,

                $subject_id

            ]);


        }





        $conn->commit();



        header("Location: teachers.php");

        exit();



    }catch(Exception $e){


        $conn->rollBack();

        die($e->getMessage());


    }



}



?>



<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<title>Assign Subjects | EduManage</title>



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
Assign Subjects
</h1>


<p>
Assign teaching subjects to <?php echo $teacher['full_name']; ?>
</p>


</div>


<a href="teachers.php" class="add-btn">

<i class="fa-solid fa-arrow-left"></i>

Back

</a>


</div>







<div class="table-box">



<div class="teacher-info">


<i class="fa-solid fa-chalkboard-user"></i>


<div>


<h2>

<?php echo $teacher['full_name']; ?>

</h2>


<p>
Select the subjects this teacher can teach
</p>


</div>


</div>








<form method="POST">





<div class="subjects-grid">



<?php foreach($subjects as $subject){ ?>



<label class="subject-card">



<input

type="checkbox"

name="subjects[]"

value="<?php echo $subject['id']; ?>"

<?php

if(in_array($subject['id'],$assignedSubjects)){

echo "checked";

}

?>

>


<div>


<i class="fa-solid fa-book"></i>


<span>

<?php echo $subject['subject_name']; ?>

</span>


</div>



</label>




<?php } ?>



</div>







<br>




<button

type="submit"

name="save"

class="add-btn">


<i class="fa-solid fa-floppy-disk"></i>


Save Subjects


</button>






</form>





</div>





</main>


</div>



</body>


</html>