<?php
include_once "sessionStartCheck.php";
?>
<!DOCTYPE html>
<html>
<head>
    <?php
if (!empty($_SESSION['email']))
{
    $email = $_SESSION['email'];
    include "DbFile/dbconfig.php";
    $row = mysqli_fetch_array(mysqli_query($conn, "SELECT * from userinfo where email='$email'"), MYSQLI_ASSOC);
    echo "<title>" . ucwords($row['firstName']) . "'s Profile</title>";

    ?>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="assets/css/dashboard.css">
    <link rel="stylesheet" type="text/css" href="Resource/pageLayout.css">
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</head>
<body>
<?php
if (isset($_SESSION['email'])){
$email = $_SESSION['email'];
?>
<div class="container">
    <?php
    include_once "managerHeader.php";
    ?>

    <?php
    include_once "managerSidebar.php";
    ?>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <h1 class="text-success text-center">Manager Dashboard</h1>
        <iframe height="500px" width="100%" src="" name="iframe_all">
            <div id="iframe_id"></div>
        </iframe>
    </div>
</div>
    <?php
    }
    else {
        header("Location:logout.php");
    }
    ?>

    <?php
    }
    else {
        header("Location:logout.php");
    }

    ?>
</body>
</html>
