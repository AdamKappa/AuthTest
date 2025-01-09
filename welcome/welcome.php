<?php
// session_start() should be called after including necessary class definitions
require_once("../User.class.php");
session_start();

echo "Welcome ". $_SESSION['loggedIn_user']->getUsername() .". You are an " . $_SESSION['loggedIn_user']->getAccessLevelString();
?>