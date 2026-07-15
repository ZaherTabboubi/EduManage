<?php

include "../../includes/auth.php";
include "../../config/database.php";



if(isset($_GET['id'])){


    $id = $_GET['id'];



    try{


        $conn->beginTransaction();




        // Delete student profile first


        $deleteStudent = $conn->prepare("

        DELETE FROM students

        WHERE user_id=?

        ");



        $deleteStudent->execute([$id]);







        // Delete user account


        $deleteUser = $conn->prepare("

        DELETE FROM users

        WHERE id=?

        AND role='student'

        ");




        $deleteUser->execute([$id]);






        $conn->commit();




    }catch(Exception $e){



        $conn->rollBack();


        echo $e->getMessage();

        exit();


    }





}



header("Location: students.php");

exit();


?>