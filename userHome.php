<?php
/**
 * Created by PhpStorm.
 * User: Biplob
 * Date: 12/21/2017
 * Time: 5:33 PM
 */
?>
<!DOCTYPE html>
<html>
<head>
    <?php
include_once "sessionStartCheck.php";
    if (!empty($_SESSION['email'])){
    $email = $_SESSION['email'];
    include "DbFile/dbconfig.php";
    $row = mysqli_fetch_array(mysqli_query($conn, "SELECT * from userinfo where email='$email'"), MYSQLI_ASSOC);
    echo "<title>" . $row['firstName'] . "s Profile</title>";

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
    include_once "userHeader.php";
    ?>

    <?php
    include_once "userSidebar.php";
    ?>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <h1 class="text-center text-danger">User Dashboard</h1>
        <iframe height="500px" width="100%" src="" name="iframe_all">
            <div id="iframe_id"></div>
        </iframe>
    </div>
    <?php
    }
    else {
        header("Location:logout.php");
    }
    ?>
    <script>
        $(document).ready(function () {
            function search() {
                var searchQuery = $("#searchAllUser").val();
                if (searchQuery !== "") {
                    //$("#result").html("<img alt="Searching" src='Resource/ajax-loader.gif'/>");
                    $.ajax({
                        type: "POST",
                        url: "searchBarCode.php",
                        data: "searchQuery=" + searchQuery,
                        success: function (data) {
                            $("#result").html(data);

                        },
                        window.setInterval(search, 3000);
                })
                    ;
                }
                //document.getElementById("result").innerHTML="Search has coming handy";
                console.log("Search have come in handy");
            }

            $("#searchIcon").click(function () {
                search();
            });
            $("#searchAllUser").keyup(function () {
                search();
            });
        });
    </script>

    <?php
    }
    else {
        header("Location:logout.php");
    }

    ?>
</body>
</html>

