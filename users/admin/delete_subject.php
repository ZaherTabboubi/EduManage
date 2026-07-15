<?php

include "../../includes/auth.php";
include "../../config/database.php";



if(isset($_GET['id'])){


    $id = $_GET['id'];




    // Check if teachers are assigned


    $check = $conn->prepare("

    SELECT COUNT(*)

    FROM teacher_subjects

    WHERE subject_id=?

    ");



    $check->execute([$id]);



    $teachers = $check->fetchColumn();






    if($teachers > 0){



        echo "

        <script>

        alert('Cannot delete this subject because teachers are assigned to it.');

        window.location='subjects.php';

        </script>

        ";



        exit();



    }







    // Delete subject


    $delete = $conn->prepare("

    DELETE FROM subjects

    WHERE id=?

    ");



    $delete->execute([$id]);





}



header("Location: subjects.php");

exit();



?>