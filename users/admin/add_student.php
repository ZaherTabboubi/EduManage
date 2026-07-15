<?php

include "../../includes/auth.php";
include "../../config/database.php";


$error = "";
$success = "";



// Get classes

$classQuery = $conn->prepare("

SELECT *

FROM classes

");


$classQuery->execute();


$classes = $classQuery->fetchAll(PDO::FETCH_ASSOC);





if(isset($_POST['submit'])){


    $fullname = trim($_POST['fullname']);

    $email = trim($_POST['email']);

    $password = $_POST['password'];

    $class_id = $_POST['class_id'];

    $parent_name = $_POST['parent_name'];

    $parent_phone = $_POST['parent_phone'];





    // Check email

    $check = $conn->prepare("

    SELECT id

    FROM users

    WHERE email=?

    ");


    $check->execute([$email]);



    if($check->rowCount() > 0){


        $error = "Email already exists.";


    }else{



        try{


            $conn->beginTransaction();





            // Generate student ID


            $last = $conn->query("

            SELECT id

            FROM users

            ORDER BY id DESC

            LIMIT 1

            ")->fetch(PDO::FETCH_ASSOC);





            if($last){

                $number = $last['id'] + 1;

            }else{

                $number = 1;

            }





            $login_id = "S" . str_pad($number,3,"0",STR_PAD_LEFT);






            // Hash password


            $hashedPassword = password_hash(

                $password,

                PASSWORD_DEFAULT

            );





            // Insert user


            $insertUser = $conn->prepare("

            INSERT INTO users

            (login_id, full_name, email, password, role)

            VALUES

            (?,?,?,?, 'student')

            ");




            $insertUser->execute([


                $login_id,

                $fullname,

                $email,

                $hashedPassword


            ]);





            $user_id = $conn->lastInsertId();






            // Insert student profile


            $insertStudent = $conn->prepare("

            INSERT INTO students

            (user_id, class_id, parent_name, parent_phone)

            VALUES

            (?,?,?,?)

            ");




            $insertStudent->execute([


                $user_id,

                $class_id,

                $parent_name,

                $parent_phone


            ]);







            $conn->commit();



            $success = "Student created successfully. Login ID: ".$login_id;




        }catch(Exception $e){



            $conn->rollBack();


            $error = $e->getMessage();



        }





    }





}



?>




<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<title>Add Student</title>


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
Add Student
</h1>



<?php if($error){ ?>

<p style="color:red">

<?php echo $error; ?>

</p>

<?php } ?>



<?php if($success){ ?>

<p style="color:green">

<?php echo $success; ?>

</p>

<?php } ?>





<form method="POST">





<label>
Full Name
</label>


<input

class="form-input"

type="text"

name="fullname"

required>






<label>
Email
</label>


<input

class="form-input"

type="email"

name="email"

required>






<label>
Password
</label>


<input

class="form-input"

type="password"

name="password"

required>






<label>
Class
</label>


<select

class="form-input"

name="class_id"

required>



<option value="">
Select Class
</option>



<?php foreach($classes as $class){ ?>


<option value="<?php echo $class['id']; ?>">


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

name="parent_name">






<label>
Parent Phone
</label>


<input

class="form-input"

type="text"

name="parent_phone">





<button

class="add-btn"

name="submit">

Create Student

</button>



</form>



</div>



</main>


</div>



</body>


</html>