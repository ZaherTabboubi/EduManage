<?php

include "../../includes/auth.php";
include "../../config/database.php";


if(!isset($_GET['id'])){

    header("Location: students.php");
    exit();

}


$id = $_GET['id'];




// Get student data


$query = $conn->prepare("

SELECT


users.id,
users.full_name,
users.email,


students.class_id,
students.parent_name,
students.parent_phone


FROM users



INNER JOIN students

ON users.id = students.user_id



WHERE users.id = ?



");



$query->execute([$id]);


$student = $query->fetch(PDO::FETCH_ASSOC);



if(!$student){

    die("Student not found");

}




// Get classes


$classQuery = $conn->prepare("

SELECT *

FROM classes

");


$classQuery->execute();


$classes = $classQuery->fetchAll(PDO::FETCH_ASSOC);







if(isset($_POST['update'])){


    $fullname = trim($_POST['fullname']);

    $email = trim($_POST['email']);

    $class_id = $_POST['class_id'];

    $parent_name = $_POST['parent_name'];

    $parent_phone = $_POST['parent_phone'];

    $password = $_POST['password'];





    try{


        $conn->beginTransaction();





        // Update user information


        if(!empty($password)){


            $hashed = password_hash(

                $password,

                PASSWORD_DEFAULT

            );


            $updateUser = $conn->prepare("

            UPDATE users

            SET full_name=?, email=?, password=?

            WHERE id=?

            ");

            

            $updateUser->execute([

                $fullname,

                $email,

                $hashed,

                $id

            ]);



        }else{


            $updateUser = $conn->prepare("

            UPDATE users

            SET full_name=?, email=?

            WHERE id=?

            ");



            $updateUser->execute([

                $fullname,

                $email,

                $id

            ]);



        }







        // Update student table


        $updateStudent = $conn->prepare("

        UPDATE students

        SET class_id=?,

        parent_name=?,

        parent_phone=?

        WHERE user_id=?

        ");



        $updateStudent->execute([


            $class_id,

            $parent_name,

            $parent_phone,

            $id


        ]);





        $conn->commit();



        header("Location: students.php");


        exit();





    }catch(Exception $e){


        $conn->rollBack();


        echo $e->getMessage();



    }





}



?>




<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<title>Edit Student</title>


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
Edit Student
</h1>




<form method="POST">





<label>
Full Name
</label>


<input

class="form-input"

type="text"

name="fullname"

value="<?php echo $student['full_name']; ?>"

required>





<label>
Email
</label>


<input

class="form-input"

type="email"

name="email"

value="<?php echo $student['email']; ?>"

required>






<label>
New Password (optional)
</label>


<input

class="form-input"

type="password"

name="password"

placeholder="Leave empty to keep old password">




<label>
Class
</label>


<select

class="form-input"

name="class_id">



<?php foreach($classes as $class){ ?>


<option

value="<?php echo $class['id']; ?>"

<?php echo $student['class_id']==$class['id'] ? "selected" : ""; ?>


>


<?php echo $class['class_name']; ?>


</option>



<?php } ?>



</select>






<label>
Parent Name
</label>


<input

class="form-input"

type="text"

name="parent_name"

value="<?php echo $student['parent_name']; ?>">






<label>
Parent Phone
</label>


<input

class="form-input"

type="text"

name="parent_phone"

value="<?php echo $student['parent_phone']; ?>">






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