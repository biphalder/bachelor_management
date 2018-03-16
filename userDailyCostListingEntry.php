<?php



session_start();

require "DbFile/dbconfig.php";
echo $_SESSION['email'];
//echo count($_POST['field_name']);

if (!empty($_POST['sendForApproval'])) {
    echo "No of Item:" . count($_POST['items']) . "<br>";
    //echo count($_POST['prices'])."<br>";
    $itemCount = count($_POST['items']);
    $itemNameList = array();
    $itemPriceList = array();
    $dateForUserDailyCost = $_POST['dateforuserdailycost'];
    echo $dateForUserDailyCost;

    $gettingUserIdQuery = "SELECT id,group_id from userinfo WHERE email='" . $_SESSION['email'] . "'";
    $resultGettingUserId = mysqli_fetch_array(mysqli_query($conn, $gettingUserIdQuery), MYSQLI_ASSOC);
    $gettingMangerGroupQuery = "SELECT manager_id,group_id from groupdetails WHERE group_id='" . $resultGettingUserId['group_id'] . "'";
    $resultManagerGroupId = mysqli_fetch_array(mysqli_query($conn, $gettingMangerGroupQuery), MYSQLI_ASSOC);
    echo "User Id : " . $resultGettingUserId['id'] . " Group Id : " . $resultGettingUserId['group_id'] . "<br>";
    echo "Manager Id : " . $resultManagerGroupId['manager_id'] . "Group Id : " . $resultGettingUserId['group_id'] . "<br>";
    $userID = $resultGettingUserId['id'];
    $managerID = $resultManagerGroupId['manager_id'];
    $group_id = $resultGettingUserId['group_id'];


    $query = "INSERT INTO userdailycost (user_id,group_id,manager_id,entry_time_date,item_name,item_price) VALUES ";
    $queryValue = "";
    $itemValues = 0;
    for ($i = 0; $i < $itemCount; $i++) {
        if (!empty($_POST["items"][$i])) {
            $itemValues++;
            if ($queryValue != "") {
                $queryValue .= ",";
            }
            $queryValue .= "('$userID','$managerID','$group_id','$dateForUserDailyCost','" . $_POST["items"][$i] . "','" . $_POST["prices"][$i] . "')";
        }
    }

    $sql = $query . $queryValue;
    if ($itemValues != 0) {
        $result = mysqli_query($conn, $sql);
        if (!empty($result)) $message = "Added Successfully.";
        else {
            echo "Unsucessful" . mysqli_error();
        }
    }

}



?>