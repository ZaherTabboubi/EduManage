<?php

include "config/database.php";


$error = "";
$success = "";


if(isset($_POST['submit'])){


    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $admin_code = $_POST['admin_code'];



    // Check admin code

    if($admin_code !== "0000"){

        $error = "Invalid admin code.";

    }


    // Check password match

    elseif($password !== $confirm_password){

        $error = "Passwords do not match.";

    }


    else{


        // Check existing email

        $check = $conn->prepare(
            "SELECT id FROM users WHERE email = ?"
        );

        $check->execute([$email]);



        if($check->rowCount() > 0){

            $error = "Email already exists.";

        }


        else{


            // Generate admin login ID

            $lastUser = $conn->query(
                "SELECT id FROM users ORDER BY id DESC LIMIT 1"
            )->fetch(PDO::FETCH_ASSOC);



            if($lastUser){

                $nextID = $lastUser['id'] + 1;

            }

            else{

                $nextID = 1;

            }



            $login_id = "ADM" . str_pad($nextID,3,"0",STR_PAD_LEFT);



            // Encrypt password

            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );



            // Insert admin

            $insert = $conn->prepare(
                "INSERT INTO users
                (login_id, full_name, email, password, role)
                VALUES
                (?, ?, ?, ?, 'admin')"
            );



            $insert->execute([

                $login_id,
                $fullname,
                $email,
                $hashedPassword

            ]);



            $success = "Account created successfully. Your Login ID is: ".$login_id;


        }


    }


}

?>



<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>EduManage | Sign Up</title>


<link rel="stylesheet" href="assets/css/signup.css">


<link rel="preconnect" href="https://fonts.googleapis.com">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">


<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">


</head>


<body>


<div class="signup-container">


<div class="signup-box">



<div class="logo">

<a href="index.html"><img src="assets/images/logo.jpg" class="logo-img"></a>

<h1>EduManage</h1>

</div>



<h2>Create Admin Account</h2>

<p>Setup your school management system</p>



<?php if($error != ""){ ?>

<p style="color:red;">
<?php echo $error; ?>
</p>

<?php } ?>


<?php if($success != ""){ ?>

<p style="color:green;">
<?php echo $success; ?>
</p>

<?php } ?>





<form method="POST" action="">



<div class="input-box">

<i class="fa-solid fa-user"></i>

<input 
type="text"
name="fullname"
placeholder="Full Name"
required>

</div>




<div class="input-box">

<i class="fa-solid fa-envelope"></i>

<input 
type="email"
name="email"
placeholder="Email"
required>

</div>





<div class="input-box">

<i class="fa-solid fa-lock"></i>

<input 
type="password"
name="password"
placeholder="Password"
required>

</div>




<div class="input-box">

<i class="fa-solid fa-lock"></i>

<input 
type="password"
name="confirm_password"
placeholder="Confirm Password"
required>

</div>





<div class="input-box">

<i class="fa-solid fa-key"></i>

<input 
type="password"
name="admin_code"
placeholder="Admin Code"
required>

</div>





<button type="submit" name="submit">

Create Account

</button>




</form>




<a href="login.php" class="back-login">

Already have an account? Login

</a>



</div>


</div>



</body>

</html>