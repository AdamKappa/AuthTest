<?php
    // session_start() should be called after including necessary class definitions
    require_once("../LoggedInUser.class.php");
    session_start();
    //check if the user is already logden in, 
    if ( !isset($_SESSION['loggedin']) && $_SESSION['loggedin'] !== true) {
        // if not then redirect to login page
        header("Location: ../login/login.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./welcome.css">
</head>
<body>
    <div class="exit-container">
        <form method="post" action="../logout/logout.php">
            <button type="submit" class="btn btn-secondary">Logout</button>
        </form>
    </div>
    <div class="welcome-container">
        <p><?php echo "Welcome ". $_SESSION['loggedIn_user']->getUsername() .". You are an " . $_SESSION['loggedIn_user']->getAccessLevelString() ."."; ?></p>
        <p><a href="../editpage/editpage.php">Go to edit page</a></p>
    </div>
    
</body>
</html>