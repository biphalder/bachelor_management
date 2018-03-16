<?php

session_start();
if (isset($_SESSION['email']) && isset($_SESSION['managerID'])) {
    session_destroy();
    unset($_SESSION['email']);
    unset($_SESSION['managerID']);

    header("location:signin.php");

} else {

    header("Location:signin.php");
    session_destroy();
    unset($_SESSION['email']);
    unset($_SESSION['managerID']);


}
?>