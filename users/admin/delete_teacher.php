<?php

include "../../includes/auth.php";
include "../../config/database.php";


if(isset($_GET['id'])){


    $id = $_GET['id'];



    $delete = $conn->prepare("

    DELETE FROM users

    WHERE id=?

    ");



    $delete->execute([$id]);



}



header("Location: teachers.php");

exit();

?>