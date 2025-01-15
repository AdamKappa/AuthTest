<?php
require_once("../LoggedInUser.class.php");
session_start();
require_once("../configConnection.php");

//check if the user is already logden in, 
if ( !isset($_SESSION['loggedin']) && $_SESSION['loggedin'] !== true) {
    // if not then redirect to login page
    header("Location: ../login/login.php");
    exit;
}

$id="";
$username = "";
$password = "";
$message = "";

//if simple user form submitted
if(($_SERVER['REQUEST_METHOD'] === "POST") && ($_SESSION['loggedIn_user']->getAccessLevel()==="1")){

    //get username, password (and encode to base64) from form
    $username = trim($_POST['username']);
    $password = base64_encode(trim($_POST['password']));
    $id = $_SESSION['loggedIn_user']->getID();

    //prepare sql query, bind the proper params and execute 
    $sql_insert = "UPDATE Users SET username = ?, password = ? WHERE ID = ?";
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("sss", $username, $password, $id);
    
    if ($stmt->execute()) {
        // Check if any rows were updated
        if ($stmt->affected_rows > 0) {
            $message = "Successful update of your details!";
        } else {
            $message = "No changes were made to your details.";
        }
    } else {
        echo "Error updating your details: " . $stmt->error;
        $message = "Error updating your details: " . $stmt->error;
    }

    $stmt->close();
}

//if admin user form submitted
if(($_SERVER['REQUEST_METHOD'] === "POST") && ($_SESSION['loggedIn_user']->getAccessLevel()==="0")){

    // Iterate through POST data to process each user
    foreach ($_POST as $key => $value) {
        // Check if the key corresponds to a hidden user ID field
        if (strpos($key, 'userID_') === 0) {
            // Extract user ID
            $userId = $value;
           
            // Check if the corresponding checkbox is checked
            if (isset($_POST["editCheckbox_$userId"])) {

                // Get the username and password for this user
                $username = trim($_POST["username_$userId"]);
                $password = base64_encode(trim($_POST["password_$userId"]));
                
                // Update the user in the database
                $stmt = $conn->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
                $stmt->bind_param("sss", $username, $password, $userId);

                if ($stmt->execute()) {
                    // Check if any rows were updated
                    if ($stmt->affected_rows > 0) {
                        $message = "User with ID $userId updated successfully.";
                    } else {
                        $message = "No changes were made to your details.";
                    }
                } else {
                    $message = "Error updating user with ID $userId: " . $stmt->error;
                }

                $stmt->close();
            }
            else{
                $message = "Select any row(s) to change the corresponding details.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./editpage.css">
</head>
<body>
    <div class="wrapper">
        <h2>Edit page</h2>
        <p><?php echo "hi ". $_SESSION['loggedIn_user']->getUsername(); ?></p>
    <?php
    if(isset($_SESSION['loggedIn_user']) && $_SESSION['loggedIn_user']->getAccessLevel()==="1"){ ?>
        <div class="simple-container">    
            <form action="" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Please enter Username" value="<?php echo $_SESSION['loggedIn_user']->getUsername(); ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Please enter new password" required>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" name="save" value="Save">
                    <input type="button" class="btn btn-secondary" onclick="location.href='../welcome/welcome.php';"  value="Cancel" >
                </div>
            </form>
        </div>
    <?php
    } else if(isset($_SESSION['loggedIn_user']) && $_SESSION['loggedIn_user']->getAccessLevel()==="0"){ 
        
        //get all users fron db and print on a form 
        $sql_select = "SELECT * FROM Users ";
    
        //prepare sql query, bind the pproper params, execute and get the results
        $results = $conn->query($sql_select);
        if (!$results) {
            die("Error executing query: " . $conn->error);
        }
    ?>
    
        <?php // Check the results
        if ($results->num_rows > 0) { 
        ?>
            <form action="" method="post">
            <?php
            //get data from results
            while($row = $results->fetch_assoc()){ ?>
            <div class="admin-container">
                    <div class="form-group admin-group">
                        <label class="form-check-label" for="<?php echo "editCheckbox_".$row['id'] ?>">Select</label>
                        <input class="form-check-input" type="checkbox" name="<?php echo "editCheckbox_".$row['id'] ?>" id="<?php echo "editCheckbox_".$row['id'] ?>" value="<?php echo $row['id'] ?>">                        
                    </div>
                    <div class="form-group admin-group">
                        <label for="<?php echo "userID_".$row['id'] ?>">ID: <?php echo $row['id'] ?></label>
                        <input type="hidden" id="<?php echo "userID_".$row['id'] ?>" name="<?php echo "userID_".$row['id'] ?>" value="<?php echo $row['id'] ?>"> 
                    </div>
                    <div class="form-group admin-group">
                        <label for="<?php echo "username_".$row['id'] ?>">Username:</label>
                        <input type="text" id="<?php echo "username_".$row['id'] ?>" name="<?php echo "username_".$row['id'] ?>" placeholder="Enter new username" value="<?php echo $row['username'] ?>">
                    </div>
                    <div class="form-group admin-group">
                        <label for="<?php echo "password_".$row['id'] ?>">Password:</label>
                        <input type="password" id="<?php echo "password_".$row['id'] ?>" name="<?php echo "password_".$row['id'] ?>" placeholder="Enter new password" >
                    </div>
            </div>
            <?php
            }
            ?>            
                <input type="submit" class="btn btn-primary" name="save" value="Save">
                <input type="button" class="btn btn-secondary"  onclick="location.href='../welcome/welcome.php';" value="Cancel" >
            </form>
        <?php
        }
        else {
            echo "<p>No data to show</p>";
        }
        ?>

    <?php 
    }
    ?>
    </div>
    <div class="alert alert-info message <?php echo (empty($message)) ? 'display-no' : ''; ?>"><?php echo $message; ?></div>
</body>