<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
<?php
/**
 * Created by PhpStorm.
 * User: Biplob
 * Date: 12/24/2017
 * Time: 2:31 PM
 */
include_once "sessionStartCheck.php";
include_once "DbFile/dbconfig.php";
/*if (isset($_GET['groupName'])){*/
    $groupName="";
    $groupId="";
    $query="select * from groupDetails WHERE manager_id='".$_SESSION['managerID']."'";
    $result=mysqli_query($conn,$query);
    while ($row=mysqli_fetch_assoc($result)){
        $groupName=$row['group_name'];
        $groupId=$row['group_id'];
    }
    ?>
    <form class="form-horizontal">
        <input type="text" class="form-control" name="changedName" value='<?=$groupName?>'">
        <input type="submit" class="btn btn-primary" name="changeGroupName">
    </form>
<?php
    /*}
    else{
        header("Location:groupDetails.php");
    }*/
    if(isset($_GET['changeGroupName'])){
        $name=$_GET['changedName'];
        $updateQ="Update groupDetails SET group_name='$name' WHERE group_id='$groupId'";
        if (mysqli_query($conn,$updateQ)){
            echo "Group Name Updated Successfully";
        }
    }
?>