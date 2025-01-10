<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./editpage.css">
</head>
<body>
    
    <?php
        // session_start() should be called after including necessary class definitions
        require_once("../User.class.php");
        session_start();
    ?>

    <p><?php echo "Welcome ". $_SESSION['loggedIn_user']->getUsername() .". You are an " . $_SESSION['loggedIn_user']->getAccessLevelString() ."."; ?></p>

    <p><a href="../editpage/editpage.php">Go to edit page</a></p>
</body>
</html>