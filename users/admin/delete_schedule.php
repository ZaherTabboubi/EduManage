<?php

include "../../includes/auth.php";
include "../../config/database.php";


if(!isset($_GET['id'])){

    header("Location:schedule.php");
    exit;

}


$id=$_GET['id'];



$delete=$conn->prepare("

DELETE FROM schedule

WHERE id=?

");



$delete->execute([$id]);



header("Location:schedule.php");

exit;


?>