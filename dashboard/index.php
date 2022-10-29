<?php

     session_start();
     $pagetitle='login';
     $nonavbar='';
     $pagetitle='Login';
     if(isset($_SESSION['username'])){
          header('location: dashboard.php');
          exit();
     }
     include "init.php";

     // check if user coming from POST
     if($_SERVER['REQUEST_METHOD']=='POST'){
          $username=$_POST['user'];
          $password=$_POST['pass'];
          $hashedpass=sha1($password);

          $stmt=$con->prepare('select userid, username,password from users where username=? and password=? and groupid=1 limit 1');
          $stmt->execute(array($username,$hashedpass));
          $row=$stmt->fetch();
          // check if user can enter to dashboard or not
          if($stmt->rowCount() > 0){
               $_SESSION['username']=$username; //regiester session name
               $_SESSION['id']=$row['userid'];

               header('location: dashboard.php');
               exit();
          }

     
     }

?>

<?php include "includes/templates/header.php";?>

<form class="login" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
     <h4 class="text-center">Login Admin</h4>
     <input name="user" class="form-control shadow" type="text" placeholder="Username" autocomplete="off">
     <input name="pass" class="form-control shadow" type="password" placeholder="Password" autocomplete="new-password">
     <input class="btn shadow btn-primary btn-lg w-100" type="submit" value="Login">
</form>

<?php include "includes/templates/footer.php" ?>