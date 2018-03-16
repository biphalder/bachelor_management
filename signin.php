<?php
include_once "sessionStartCheck.php";
$emailBool = $passwordBool = "";
$emailError=$passwordError="";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign in</title>
    <noscript>
        <meta http-equiv="refresh" content="0;url=nojsSignin.php">
    </noscript>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="assets/css/signin.css">
    <link rel="stylesheet" type="text/css" href="Resource/form_buttonDesign.css">
</head>
<body>


<div class="container">
    <?php
    if (isset($_SESSION['registration_success'])) {
        echo $_SESSION['registration_success'];
    }
    ?>
    <form class="form-signin" method="post" name="singinForm">
        <h2 class="form-signin-heading">Please sign in</h2>
        <label for="email" class="control-label">Enter Email Address:</label>
        <input type="email" id="email" name="email" onblur="email_validation()" class="form-control"
               placeholder="Email address" autofocus>
        <p id="emailError"></p>
        <label for="password" class="control-label">Enter Your Password:</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Enter Password"
               onblur="password_validation()"
               class="form-control">
        <p id="passwordError"></p>
        <div class="siginUp">
            <label>
                Not a Member ?
                <a class="btn btn-link btn-lg" href="signup.php">Sign Up</a>
            </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit" name="submitLogin">Sign in</button>
    </form>
</div>


<?php
require('DbFile/dbconfig.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submitLogin'])) {
        $emailLogin = $_POST['email'];
        $passwordLogin = md5($_POST['password']);
        if (!empty($emailLogin)) {
            if (filter_var($emailLogin, FILTER_VALIDATE_EMAIL)) {
                $emailBool = true;
            } else {
                $emailError = " *Email Not properly validated";
            }
        } else {
            $emailError = "*Email can not be empty" . "<br>";
        }

        /*if (!empty($passwordLogin)) {
            if (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z._-]{7,15}$/', $passwordLogin)) {
                $passwordError = 'the password does not meet the requirements!<br>';
            } else {
                $passwordBool = true;
            }
        } else {
            $passwordError = "*Password can not be empty";
        }*/
        if ($emailBool == true /*&& $passwordBool == true*/) {
            $sql = "select *from userinfo WHERE email='$emailLogin' AND password='$passwordLogin'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $count = mysqli_num_rows($result);
            if ($count == 1) {
                $_SESSION['email'] = $row['email'];
                if ($row['userStatus'] == 1) {
                    $_SESSION['userStatus'] = 1;
                    $_SESSION['managerID'] = $row['id'];
                    $_SESSION['email'] = $emailLogin;
                    header("Location:managerHome.php");
                }
                else if ($row['userStatus'] == 0) {
                    $_SESSION['userStatus'] = 0;
                    $_SESSION['userID'] = $row['id'];
                    $_SESSION['email'] = $row['email'];
                    header("Location:userHome.php");
                }
            }
        } else {

            echo "<h3 class='jumbotron text-center'>Login Not successful<br>".$emailError."<br> ".$passwordError."</h3>";
        }
    }

}


?>


<script src="formValidation.js"></script>
</body>
</html>