<?php

include "../../includes/auth.php";
include "../../config/database.php";


$error = "";
$success = "";


// Get subjects

$subjectQuery = $conn->prepare(
    "SELECT * FROM subjects"
);

$subjectQuery->execute();

$subjects = $subjectQuery->fetchAll(PDO::FETCH_ASSOC);



if(isset($_POST['submit'])){


    $fullname = trim($_POST['fullname']);

    $email = trim($_POST['email']);

    $password = $_POST['password'];

    $phone = $_POST['phone'];

    $address = $_POST['address'];

    $hire_date = $_POST['hire_date'];

    $selectedSubjects = $_POST['subjects'];




    // Check email

    $check = $conn->prepare(
        "SELECT id FROM users WHERE email = ?"
    );

    $check->execute([$email]);



    if($check->rowCount() > 0){


        $error = "Email already exists.";


    }else{


        try{


            $conn->beginTransaction();



            // Generate teacher ID


            $last = $conn->query(
                "SELECT id FROM users ORDER BY id DESC LIMIT 1"
            )->fetch(PDO::FETCH_ASSOC);



            if($last){

                $number = $last['id'] + 1;

            }else{

                $number = 1;

            }



            $login_id = "T" . str_pad($number,3,"0",STR_PAD_LEFT);




            // Hash password

            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );




            // Insert into users


            $insertUser = $conn->prepare(

                "INSERT INTO users
                (login_id,full_name,email,password,role)
                VALUES
                (?,?,?,?, 'teacher')"

            );


            $insertUser->execute([

                $login_id,
                $fullname,
                $email,
                $hashedPassword

            ]);



            // Get created user id

            $user_id = $conn->lastInsertId();




            // Insert teacher profile


            $insertTeacher = $conn->prepare(

                "INSERT INTO teachers
                (user_id,phone,address,hire_date)
                VALUES
                (?,?,?,?)"

            );


            $insertTeacher->execute([

                $user_id,
                $phone,
                $address,
                $hire_date

            ]);





            // Assign subjects


            $insertSubject = $conn->prepare(

                "INSERT INTO teacher_subjects
                (user_id,subject_id)
                VALUES
                (?,?)"

            );



            foreach($selectedSubjects as $subject){


                $insertSubject->execute([

                    $user_id,
                    $subject

                ]);


            }




            $conn->commit();


            $success = "Teacher created successfully. Login ID: ".$login_id;



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

<title>Add Teacher | EduManage</title>


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

<h1>Add Teacher</h1>

<p>Create a new teacher account</p>

</div>


</div>





<?php if($error){ ?>

<p style="color:red;">
<?php echo $error; ?>
</p>

<?php } ?>



<?php if($success){ ?>

<p style="color:green;">
<?php echo $success; ?>
</p>

<?php } ?>






<div class="table-box">


<form method="POST">



<label>
Full Name
</label>

<input class="form-input"
type="text"
name="fullname"
required>



<label>
Email
</label>

<input class="form-input"
type="email"
name="email"
required>




<label>
Password
</label>

<input class="form-input"
type="password"
name="password"
required>




<label>
Phone
</label>

<input class="form-input"
type="text"
name="phone">




<label>
Address
</label>

<input class="form-input"
type="text"
name="address">




<label>
Hire Date
</label>

<input class="form-input"
type="date"
name="hire_date">




<label>
Subjects
</label>


<div>


<?php foreach($subjects as $subject){ ?>


<label>

<input

type="checkbox"

name="subjects[]"

value="<?php echo $subject['id']; ?>"

>

<?php echo $subject['subject_name']; ?>


</label>


<br>


<?php } ?>


</div>




<br>


<button class="add-btn"
type="submit"
name="submit">

Create Teacher

</button>



</form>


</div>



</main>


</div>



</body>

</html>