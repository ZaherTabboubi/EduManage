<?php

include "../../includes/auth.php";
include "../../config/database.php";



$message = "";


// Get settings

$query = $conn->prepare(
    "SELECT * FROM school_settings LIMIT 1"
);

$query->execute();

$settings = $query->fetch(PDO::FETCH_ASSOC);





if(isset($_POST['save'])){


    $name = $_POST['school_name'];

    $email = $_POST['school_email'];

    $phone = $_POST['school_phone'];

    $address = $_POST['school_address'];



    $update = $conn->prepare("

    UPDATE school_settings

    SET 

    school_name=?,

    school_email=?,

    school_phone=?,

    school_address=?

    WHERE id=?

    ");



    $update->execute([

        $name,
        $email,
        $phone,
        $address,
        $settings['id']

    ]);



    $message = "Settings updated successfully";



    $settings = $conn->query(
        "SELECT * FROM school_settings LIMIT 1"
    )->fetch(PDO::FETCH_ASSOC);



}


?>



<!DOCTYPE html>

<html>

<head>

<title>School Settings</title>

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


<?php
$hideSearch = true;
 include "../../includes/header.php"; ?>



<div class="table-box">


<h1>
School Settings
</h1>


<?php if($message){ ?>

<p style="color:green">

<?php echo $message; ?>

</p>


<?php } ?>




<form method="POST">



<label>
School Name
</label>

<input 
class="form-input"
type="text"
name="school_name"
value="<?php echo $settings['school_name']; ?>"
>



<label>
School Email
</label>


<input 
class="form-input"
type="email"
name="school_email"
value="<?php echo $settings['school_email']; ?>"
>




<label>
Phone
</label>


<input 
class="form-input"
type="text"
name="school_phone"
value="<?php echo $settings['school_phone']; ?>"
>




<label>
Address
</label>


<input 
class="form-input"
type="text"
name="school_address"
value="<?php echo $settings['school_address']; ?>"
>




<button 
class="add-btn"
name="save">

Save Settings

</button>



</form>



</div>



</main>


</div>


</body>

</html>