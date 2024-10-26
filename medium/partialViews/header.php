<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Medium</a>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="./register.php">Register</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./login.php">Log In</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./logout.php">Log Out</a>
        </li>
      </ul>


     
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <span class="navbar-text d-flex flex-row">
            <a class="nav-link" href="./profile.php" style="margin-right:30px;">Profile</a>    
                  <?php 
               if(isset($_SESSION['isAuthenticated']) && $_SESSION['isAuthenticated']){
                echo('<b><a class="nav-link" href="#"> welcome ' .$_SESSION['firstName']. ' '. $_SESSION['lastName'].'</a></b>');
            }
      ?>
        </span>
    </div>
</nav>
      




    </div>

  </div>
</nav>