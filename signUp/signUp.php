<?php
session_start();
require_once("../configConnection.php");

// define and initialize variables
$username = "";
$password = "";
$repassword = "";
$user_type = "";
$message = "";

//if form submitted then proceed 
if($_SERVER['REQUEST_METHOD'] == "POST"){

    //get username, password and repassword (and encode to base64) from form
    $username = trim($_POST['username']);
    $password = base64_encode(trim($_POST['password']));
    $repassword = base64_encode(trim($_POST['confirm_password']));
    $user_type = $_POST['user_type'];
    
    // Check if passwords match
    if($password === $repassword){
        // Proceed with saving to the database

        //first check if username already exist on DB table
        //prepare sql query, bind the proper params, execute and get the results
        $sql_select = "SELECT username FROM Users WHERE username = ?";
        $stmt = $conn->prepare($sql_select);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $results = $stmt->get_result();

        // Check if any rows were returned
        if ($results->num_rows > 0) {
            //user already exist
            $message = "This username already exist!";
        } else {
            // user does not exist so proceed with insertion 
            // prepare sql query, bind the proper params, execute and get the results
            $sql_insert = "INSERT INTO Users (username, password, access_level) VALUES (?,?,?)";
            $stmt = $conn->prepare($sql_insert);
            $stmt->bind_param("sss", $username, $password, $user_type);
            $stmt->execute();
            
            //put this message on session to show in redirect page
            $_SESSION['SignUpMessage'] = "Sign up successful! Please log in."; // $message = "<div class='alert alert-success'>Sign up successful!";
            // redirect to login page
            header("Location: ../login/login.php");
            exit; 
        }
    } else {
        $message = "Passwords do not match!";
    }
}
// close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./signUp.css">
    <script>
        function checkPasswords() {
            const password = document.getElementById("password").value;
            const re_password = document.getElementById("confirm_password").value;
            const password_feedback = document.getElementById("password_feedback");
            const submitBtn = document.getElementById("submitBtn");
            
            if(password === "" || re_password === ""){
                password_feedback.textContent = "";
                password_feedback.className = "display-no";
                submitBtn.disabled = true;
            }
            else if(password === re_password) {
                password_feedback.textContent = "Passwords match!";
                password_feedback.className = "alert alert-success match";
                submitBtn.disabled = false;
                console.log("password same");
            } else { 
                password_feedback.textContent = "Passwords do not match!";
                password_feedback.className = "alert alert-warning no-match";
                submitBtn.disabled = true;
                console.log("password not same");
            }
        }
    </script>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Please enter a username" required> 
            </div>    
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Please enter a password" oninput="checkPasswords()" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Please re-enter the password" oninput="checkPasswords()" required>
                <div class="alert alert-warning display-no" role="alert" id="password_feedback"></div>
            </div>
            <div class="form-group">
                <div>
                    <label>User Type </label>
                </div>
                <label for="simple_user">Simple User</label>
                <input type="radio" name="user_type" id="simple_user" class="form-check-input" value="1" checked>
                <label for="admin">Admin</label>
                <input type="radio" name="user_type" id="admin" class="form-check-input" value="0">
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" id="submitBtn" value="Sign Up" disabled>
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p>Already have an account? <a href="../login/login.php">Login here</a>.</p>
        </form>
        <!-- Display sign up error message here -->
        <div class="alert alert-danger <?php echo (empty($message)) ? 'display-no' : ''; ?>"><?php echo $message; ?></div>
    </div>
</body>
</html>