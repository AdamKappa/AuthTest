<?php
//initialize Session
session_start();

//check if the user is already logden in, 
if ( isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // if yes then redirect to welcome page
    header("Location: ./welcome/welcome.php");
    exit;
} else {
    // if not then redirect to login page
    header("Location: ./login/login.php");
    exit;
}
?>