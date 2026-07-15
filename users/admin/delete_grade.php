<?php

include "../../includes/auth.php";
include "../../config/database.php";



if(!isset($_GET['id'])){

    header("Location: grades.php");
    exit();

}



$id = $_GET['id'];





$delete = $conn->prepare("

DELETE FROM grades

WHERE id = ?

");



$delete->execute([$id]);





header("Location: grades.php");

exit();



?>