<?php

include "../../includes/auth.php";
include "../../config/database.php";


$user_id = $_SESSION['user']['id'];



// Get admin data

$query = $conn->prepare("

SELECT 
id,
login_id,
full_name,
email,
role

FROM users

WHERE id=?

");


$query->execute([$user_id]);


$admin = $query->fetch(PDO::FETCH_ASSOC);



if(!$admin){

    die("Admin not found");

}





// Update profile

if(isset($_POST['update'])){


    $name = $_POST['full_name'];

    $email = $_POST['email'];

    $password = $_POST['password'];




    if(!empty($password)){


        $hashed = password_hash(
            $password,
            PASSWORD_DEFAULT
        );


        $update = $conn->prepare("

        UPDATE users

        SET
        full_name=?,
        email=?,
        password=?

        WHERE id=?

        ");



        $update->execute([

            $name,
            $email,
            $hashed,
            $user_id

        ]);



    }
    else{


        $update = $conn->prepare("

        UPDATE users

        SET
        full_name=?,
        email=?

        WHERE id=?

        ");



        $update->execute([

            $name,
            $email,
            $user_id

        ]);


    }



    $_SESSION['user']['name']=$name;


    header("Location: profile.php");

    exit();


}



?>



<!DOCTYPE html>

<html>

<head>

<title>Admin Profile</title>


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

$hideSearch=true;

include "../../includes/header.php";

?>





<div class="profile-card">


<h1>
Admin Profile
</h1>




<form method="POST">



<label>
Full Name
</label>


<input 
class="form-input"
type="text"
name="full_name"
value="<?= htmlspecialchars($admin['full_name']); ?>">





<label>
Email
</label>


<input 
class="form-input"
type="email"
name="email"
value="<?= htmlspecialchars($admin['email']); ?>">






<label>
Login ID
</label>


<input 
class="form-input"
type="text"
value="<?= htmlspecialchars($admin['login_id']); ?>"
disabled>





<label>
Role
</label>


<input 
class="form-input"
type="text"
value="Administrator"
disabled>






<label>
New Password
</label>


<input 
class="form-input"
type="password"
name="password"
placeholder="Leave empty to keep current password">





<br><br>


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