<?php
/**
 * Created by PhpStorm.
 * User: Biplob
 * Date: 12/24/2017
 * Time: 3:12 PM
 */
include_once "sessionStartCheck.php";
include_once "DbFile/dbconfig.php";
if ($_SERVER['REQUEST_METHOD']=="POST") {
    $id=$_POST['id'];
    $sql = "UPDATE userinfo SET group_id=0,userGroupStatus=0 WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " .mysqli_error($conn);
    }
}

?>