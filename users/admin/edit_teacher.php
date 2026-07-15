<?php

include "../../includes/auth.php";
include "../../config/database.php";


if(!isset($_GET['id'])){

    header("Location: teachers.php");
    exit();

}


$id = $_GET['id'];



/* Get teacher information */

$query = $conn->prepare("

SELECT

users.id,
users.full_name,
users.email,

teachers.phone,
teachers.address,
teachers.hire_date


FROM users


INNER JOIN teachers

ON users.id = teachers.user_id


WHERE users.id = ?

");


$query->execute([$id]);


$teacher = $query->fetch(PDO::FETCH_ASSOC);



if(!$teacher){

    die("Teacher not found");

}




if(isset($_POST['update'])){


    $name = $_POST['fullname'];

    $email = $_POST['email'];

    $password = $_POST['password'];

    $phone = $_POST['phone'];

    $address = $_POST['address'];

    $hire = $_POST['hire_date'];



    // Update user information

    if(!empty($password)){


        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );


        $updateUser = $conn->prepare("

        UPDATE users

        SET 
        full_name=?,
        email=?,
        password=?

        WHERE id=?

        ");


        $updateUser->execute([

            $name,
            $email,
            $hashedPassword,
            $id

        ]);


    }
    else{


        $updateUser = $conn->prepare("

        UPDATE users

        SET 
        full_name=?,
        email=?

        WHERE id=?

        ");


        $updateUser->execute([

            $name,
            $email,
            $id

        ]);


    }





    // Update teacher information

    $updateTeacher = $conn->prepare("

    UPDATE teachers

    SET 
    phone=?,
    address=?,
    hire_date=?

    WHERE user_id=?

    ");



    $updateTeacher->execute([

        $phone,
        $address,
        $hire,
        $id

    ]);




    header("Location: teachers.php");

    exit();


}



?>



<!DOCTYPE html>

<html>

<head>

<title>Edit Teacher</title>

<link rel="stylesheet" href="../../assets/css/dashboard.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

<script src="../../assets/js/dashboard.js"></script>


</head>


<body>



<div class="layout">


<?php include "../../includes/sidebar.php"; ?>


<main class="content">


<?php 
$hideSearch = true; 
include "../../includes/header.php"; 
?>



<div class="table-box">


<h1>Edit Teacher</h1>



<form method="POST">



<label>
Full Name
</label>

<input 
class="form-input"
type="text"
name="fullname"
value="<?= htmlspecialchars($teacher['full_name']); ?>">





<label>
Email
</label>

<input 
class="form-input"
type="email"
name="email"
value="<?= htmlspecialchars($teacher['email']); ?>">





<label>
New Password (leave empty to keep current)
</label>

<input 
class="form-input"
type="password"
name="password">





<label>
Phone
</label>

<input 
class="form-input"
type="text"
name="phone"
value="<?= htmlspecialchars($teacher['phone']); ?>">





<label>
Address
</label>

<input 
class="form-input"
type="text"
name="address"
value="<?= htmlspecialchars($teacher['address']); ?>">





<label>
Hire Date
</label>

<input 
class="form-input"
type="date"
name="hire_date"
value="<?= $teacher['hire_date']; ?>">





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