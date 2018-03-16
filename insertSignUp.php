<?php

include_once "sessionStartCheck.php";
$firstNameError=$lastNameError=$emailError=$passwordError=$confirmPasswordError=$phoneNumberError=$passwordRule1=$passwordRule2=$passwordRule3="";
$firstNameBool=$lastNameBool=$emailBool=$passwordBool=$confirmPasswordBool=$phoneNumberBool=false;
$firstname=$lastname=$email=$confirmpassword=$password=$phonenumber="";
if($_SERVER['REQUEST_METHOD']=="POST") {

    $firstname=$_POST['firstName'];
    $lastname=$_POST['lastName'];
    $email=$_POST['email'];
    $confirmpassword=md5($_POST['confirmPassword']);
    $password=md5($_POST['password']);
    $phonenumber=$_POST['phoneNumber'];

    if(!empty($firstname) && !empty($lastname)){
        if(ctype_alpha($firstname[0]) && ctype_alpha($lastname[0])){
            $firstNameBool=true;
            $lastNameBool=true;
        }
        else{
            echo "* Name Must start with a character<br>";
        }
    }
    else{

        echo "* Name can not be empty!<br>";
    }
    if (!empty($email)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailBool=true;
        } else {
            echo " *Email Not properly validated<br>";
        }
    } else {
        echo "*Email can not be empty" . "<br>";
    }
    if(!empty($password)) {
        if (!md5(preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z._-]{7,15}$/', $password))) {
            $passwordError='the password does not meet the requirements!<br>';
            $passwordRule1=" *Must contain 7-15 letters<br>";
            $passwordRule2=" Must use combination of letter and numbers<br>";
            $passwordRule3=" May Contain Dot(.) Dash(-) Underscore(_)<br>";
        } else {
            $passwordBool=true;
        }
    }
    else{
        echo "*Password can not be empty<br>";
    }
    if(!empty($confirmpassword)){
        if($confirmpassword == $password){
            $confirmPasswordBool=true;
        }
        else{
            echo " *Password does not match<br>";
        }
    }
    else{
        $confirmPasswordError=" *Enter the above same password<br>";
    }
    if(!empty($phonenumber)) {
        if (strlen($phonenumber) == 10 && is_numeric($phonenumber)) {
            $phoneNumberBool=true;
        }
        else {
            echo "*Does not met the criteria";
            echo $phonenumber;
        }
    }
    else{
        echo "*Can not be empty";
    }

    if($firstNameBool===true && $lastNameBool==true && $emailBool==true && $passwordBool==true &&
        $confirmPasswordBool==true && $phoneNumberBool==true) {

        include_once "DbFile/dbconfig.php";
        $sql = "INSERT INTO userinfo (firstName, lastName, email,password,phoneNumber,userGroupStatus,userStatus, group_id)
VALUES ('$firstname', '$lastname', '$email','$confirmpassword','$phonenumber',0,0,0)";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
            echo "Please <a href='signin.php'>Sigin </a> To activate Your account!";
            $_SESSION['registration_success']="You Are now a Registered Member. Please Login!";
        }
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }


        mysqli_close($conn);


    }
}

?>

