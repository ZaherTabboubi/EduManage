<?php

include "../../includes/auth.php";
include "../../config/database.php";


if(isset($_GET['id'])){


    $id = $_GET['id'];



    // Check if class has students

    $check = $conn->prepare("

    SELECT COUNT(*)

    FROM students

    WHERE class_id = ?

    ");



    $check->execute([$id]);


    $students = $check->fetchColumn();




    if($students > 0){


        echo "

        <script>

        alert('Cannot delete this class because students are assigned to it.');

        window.location='classes.php';

        </script>

        ";


        exit();


    }





    // Delete class

    $delete = $conn->prepare("

    DELETE FROM classes

    WHERE id=?

    ");



    $delete->execute([$id]);



}



header("Location: classes.php");

exit();


?>