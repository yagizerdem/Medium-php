<?php
    require("./db.php");
    $email = $password = "";
    $errors = [];
    $db = new Database(); // database object
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $errors = []; // clear errors 
        $email = htmlspecialchars(trim($_POST['email']));
        $password = htmlspecialchars(trim($_POST['password']));

        
        $user = $db->getUserByEmail($email, $password);
        if($user == null){
            $errors[] = "User not exist";
        }
        if($user){
            if (password_verify($password, $user['password'])) {
                // $expireTime = 15 * 24 * 60 * 60; // 15 days in seconds
                // session_set_cookie_params($expireTime);
                session_start();

                $_SESSION['isAuthenticated'] = true;
                $_SESSION['firstName'] = $user['first_name'];
                $_SESSION['lastName'] = $user['last_name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['created'] = time();
                $_SESSION['id'] = $user['Id'];
      
                header("location: index.php");
            } else {
                $errors[] = "Wrong credentials";
            }
        }

    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./styles/global.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
    
<?php include('./partialViews/header.php')?>

<?php
    if (!empty($errors)) {
        echo '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
    }
?>

<div class="container-md">
<form method="POST" action=""> 
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