<?php
require("./db.php");
// Initialize variables
$firstName = $lastName = $email = $password = "";
$errors = [];
$flag = false;
$db = new Database(); // database object
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];
    // Sanitize and validate input data
    $firstName = htmlspecialchars(trim($_POST['firstName']));
    $lastName = htmlspecialchars(trim($_POST['lastName']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Simple validation (you can expand this as needed)
    if (empty($firstName)) {
        $errors[] = "First name is required.";
    }
    if (empty($lastName)) {
        $errors[] = "Last name is required.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (empty($password)) {
        $errors[] = "Password is required.";
    }

        // Validate first name length
        if (strlen($firstName) < 3 || strlen($firstName) > 30) {
            $errors[] = "First name must be between 3 and 30 characters.";
        }
    
        // Validate last name length
        if (strlen($lastName) < 3 || strlen($lastName) > 30) {
            $errors[] = "Last name must be between 3 and 30 characters.";
        }
    
        // Validate email format
        if (!preg_match("/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/", $email)) {
            $errors[] = "Invalid email format.";
        }
    
        // Validate password strength
        if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/", $password)) {
            $errors[] = "Password must be at least 8 characters long, contain at least one letter and one number.";
        }

        if (empty($errors)) {
            $flag = true;
            // successful verificaiton , than log to db
            $result = $db->registerUser($firstName, $lastName, $email, $password);
            if($result['success']){                
                $url = "./login.php";
                header("location: login.php");
                exit();
            }
            else{
                $flag = false;
                $errors[] =  $result['message'];
            }
        }
        else{
            $flag = false;

            // show error messagte
        }

    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width="device-width", initial-scale=1.0">
    <title>register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./styles/global.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
<?php include('./partialViews/header.php')?>    

<?php 
        // if ($flag) {
        //     // Here you would typically save to a database or perform another action
        //     // For demonstration, we'll just display a success message
        //     echo "<div class='alert alert-success'>Form submitted successfully!</div>";
        //     echo "<p>First Name: $firstName</p>";
        //     echo "<p>Last Name: $lastName</p>";
        //     echo "<p>Email: $email</p>";
        //     // Do not display the password for security reasons
        // }

            // Display errors if any
    if (!$flag && !empty($errors)) {
        echo '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
    }
?>


<div class="container-md">
<form method="POST" action="">
  <div class="form-group">
    <label for="firstName">First Name</label>
    <input type="text" class="form-control" id="firstName"  name="firstName" placeholder="Enter first name" required>
  </div>
  
  <div class="form-group">
    <label for="lastName">Last Name</label>
    <input type="text" class="form-control" id="lastName"  name="lastName" placeholder="Enter last name" required>
  </div>
  
  <div class="form-group">
    <label for="exampleInputEmail1">Email address</label>
    <input type="email" class="form-control" id="exampleInputEmail1" name="email" aria-describedby="emailHelp" placeholder="Enter email" required>
    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
  </div>
  
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Password" required>
  </div>
</br>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>

</div>



</body>
</html>