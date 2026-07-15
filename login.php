<?php

session_start();

include "config/database.php";


$error = "";


if(isset($_POST['submit'])){


    $login_id = trim($_POST['login_id']);
    $password = $_POST['password'];
    $role = $_POST['role'];



    // Search user

    $query = $conn->prepare(
        "SELECT * FROM users WHERE login_id = ? AND role = ?"
    );


    $query->execute([
        $login_id,
        $role
    ]);



    $user = $query->fetch(PDO::FETCH_ASSOC);



    if($user){


        // Verify password

        if(password_verify($password, $user['password'])){


            $_SESSION['user'] = [

                "id" => $user['id'],

                "login_id" => $user['login_id'],

                "name" => $user['full_name'],

                "role" => $user['role']

            ];



            // Redirect by role


            if($user['role'] == "admin"){


                header("Location: users/admin/dashboard.php");

            }


            elseif($user['role'] == "teacher"){


                header("Location: users/teacher/dashboard.php");

            }


            elseif($user['role'] == "student"){


                header("Location: users/student/dashboard.php");

            }


            exit();


        }

        else{


            $error = "Incorrect password.";

        }


    }

    else{


        $error = "Account not found.";

    }


}

?>



<!DOCTYPE html>
<html lang="en">

<head>


<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>EduManage | Login</title>


<link rel="stylesheet" href="assets/css/login.css">


<link rel="preconnect" href="https://fonts.googleapis.com">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">


<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">


</head>



<body>



<div class="login-container">


<div class="login-box">



<div class="logo">

<a href="index.html"><img src="assets/images/logo.jpg" class="logo-img"></a>

<h1>EduManage</h1>

</div>




<h2>
Welcome Back
</h2>


<p>
Login to access your dashboard
</p>




<?php if($error != ""){ ?>

<p style="color:red;">
<?php echo $error; ?>
</p>

<?php } ?>





<form method="POST" action="">



<div class="input-box">

<i class="fa-solid fa-id-card"></i>


<input 
type="text"
name="login_id"
placeholder="Login ID"
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


<i class="fa-solid fa-user-tag"></i>


<select name="role" required>


<option value="">
Select Role
</option>


<option value="admin">
Admin
</option>


<option value="teacher">
Teacher
</option>


<option value="student">
Student
</option>


</select>


</div>





<button type="submit" name="submit">

Login

</button>



</form>





<a href="signup.php" class="create-account">

Create Admin Account

</a>



<a href="index.html" class="back-home">

← Back to Home

</a>



</div>


</div>



</body>

</html>