<?php 
//initialize Session
session_start();

//check if the user is already logden in, if yes then redirect to landpage
if( isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true){
    header("Location: landpage.php");
    exit;
}

require_once("../configConnection.php");

// define and initialize variables
$username = "";
$password = "";
$login_message = "";

//if form submitted then proceed 
if($_SERVER['REQUEST_METHOD'] == "POST"){
    
    //get username from form
    $username = trim($_POST['username']);
    // get and password from form and encode it using Base64 encode 
    $password = base64_encode(trim($_POST['password']));
    
    $sql_select = "SELECT * FROM Users WHERE username = ? AND password = ?";
    
    //prepare sql query, bind the pproper params, execute and get the results
    $stmt = $conn->prepare($sql_select);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $results = $stmt->get_result();

    // Check the result
    if ($results->num_rows > 0) {
        //suuccess logged in.. redirect to landpage... and check the user-type to display accordingly
        $login_message = "User logged in";
    } else {
        $login_message = "User not logged in";
    }

    // Close statement
    $stmt->close();
}


// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./login.css">
</head>
<body>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <?php 
        if(!empty($login_message)){
            echo '<div class="alert alert-danger">' . $login_message . '</div>';
        }
        
        if(isset($_SESSION['SignUpMessage'])){
            echo '<div class="alert alert-success">' . $_SESSION['SignUpMessage'] . '</div>';
            unset($_SESSION['SignUpMessage']);
        }       
        ?>

        <form action="" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Please enter Username" required>
            </div>    
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Please enter password" required>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="../signUp/signUp.php">Sign up now</a>.</p>
        </form>
    </div>
</body>
</html>