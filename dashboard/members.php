<?php
session_start();
$pagetitle='Members';

if(isset($_SESSION['username'])){
     include 'init.php';

     $do='';
     if(isset($_GET['do'])){
          $do=$_GET['do'];
     }else{
          echo 'sorry you are in fall website.';
     }
     //edit page start
     if($do=='edit'){
          if(isset($_GET['userid']) && is_numeric($_GET['userid'])){
               echo '<h1 class="text-center">Edit Members</h1>';
               $userid=intval($_GET['userid']);
               $stmt=$con->prepare('select * from users where userid=? limit 1');
               $stmt->execute(array($userid));
               $count=$stmt->rowCount();
               $row=$stmt->fetch();
               if($count>0){ ?>
                    <div class="container">
                         <form class="form-horizontal ms-5" action="?do=update" method="POST">
                              <!-- start username field -->
                                   <div class="form-group ms-5">
                                   <label class="col-sm-2 control-label">Username</label>
                                   <div class="col-sm-10">
                                        <input type="hidden" name="id" value="<?php echo $row['userid'] ?>">
                                        <input type="text" name="username" value="<?php echo $row['username'] ?>" class="form-control" autocomplete="off" required>
                                   </div>
                                   </div>
                              <!-- end username field -->

                              <!-- start password field -->
                                   <div class="form-group ms-5">
                                   <label class="col-sm-2 control-label">Password</label>
                                   <div class="col-sm-10">
                                        <input type="hidden" name="oldpassword" value="<?php echo $row['password'] ?>">
                                        <input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave the blank empty to not change the password.">
                                   </div>
                                   </div>
                              <!-- end password field -->

                              <!-- start email field -->
                                   <div class="form-group ms-5">
                                   <label class="col-sm-2 control-label">Email</label>
                                   <div class="col-sm-10">
                                        <input type="email" name="email" value="<?php echo $row['email'] ?>" class="form-control" required>
                                   </div>
                                   </div>
                              <!-- end email field -->

                              <!-- start full name field -->
                                   <div class="form-group ms-5">
                                   <label class="col-sm-2 control-label">Full Name</label>
                                   <div class="col-sm-10">
                                        <input type="text" name="fullname" value="<?php echo $row['fullname'] ?>" class="form-control" required>
                                   </div>
                                   </div>
                              <!-- end full name field -->

                              <!-- start button field -->
                                   <div class="form-group ms-5">
                                        <div class="col-sm-offset-2 col-sm-10">
                                             <input type="submit" value="Save" class="btn btn-info w-25 mt-3">
                                        </div>
                                   </div>
                              <!-- end button field -->
                         </form>
                    </div>
               <?php }else{
                    $themsg='<div class="alert alert-danger">sorry there is no such ID</div>';
                    rdh($themsg,5);
               }
          }else{
               $themsg='<div class="alert alert-danger">Sorry You Cannot Access To This Page Directly!</div>';
               rdh($themsg);
          }
          //edit page end
          // update page start.
     }elseif($do=='update'){
          if($_SERVER['REQUEST_METHOD']=='POST'){
               echo '<h1 class="text-center">Update Members</h1>';
               echo '<div class="container">';
               $id=$_POST['id'];
               $username=$_POST['username'];
               $email=$_POST['email'];
               $fullname=$_POST['fullname'];
               $password='';

               $formerrors=array();
               if(empty($username)){
                    $formerrors[]='<div class="alert alert-danger">username cannot be empty!</div>';
               }
               if(strlen($username)<4 && !empty($user)){
                    $formerrors[]='<div class="alert alert-danger">username must be more than 4 charcter!</div>';
               }
               if(empty($email)){
                    $formerrors[]='<div class="alert alert-danger">email cannot be empty!</div>';
               }
               if(empty($fullname)){
                    $formerrors[]='<div class="alert alert-danger">fullname cannot be empty!</div>';
               }
               foreach($formerrors as $errors){
                    echo $errors;
               }
     
               if(empty($_POST['newpassword'])){
                    $password=$_POST['oldpassword'];
               }else{
                    $password=sha1($_POST['newpassword']);
               }
               echo '</div>';
     
               if(empty($formerrors)){
               $stmt=$con->prepare("update users set username=?,email=?,fullname=?,password=? where userid=?");
               $stmt->execute(array($username,$email,$fullname,$password,$id));
               $themsg="<div class='alert alert-info'>" . $stmt->rowCount() . "record update </div>";
               rdh($themsg,'back');
               }else{
                    $themsg="<div class='alert alert-danger'>Sorry There Is An Error In The Information!</div>";
                    rdh($themsg,'back');
               }
               //update page end
               //add page start
          }
     }elseif($do=='add'){ ?>
          <h1 class="text-center">Add Members</h1>
          <div class="container">
               <form class="form-horizontal ms-5" action="?do=insert" method="POST" enctype="multipart/form-data">
                         <!-- start username field -->
                              <div class="form-group ms-5">
                              <label class="col-sm-2 control-label">Username</label>
                              <div class="col-sm-10">
                                   <input type="text" name="username" class="form-control" autocomplete="off" required placeholder="username must be more than 4 charcter">
                              </div>
                              </div>
                         <!-- end username field -->

                         <!-- start password field -->
                              <div class="form-group ms-5">
                                   <label class="col-sm-2 control-label">Password</label>
                                   <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                             <div class="input-group-text">
                                                  <span class="show-pass"> <i class="fa fa-eye fa-2x"></i> </span>
                                             </div>
                                        </div>
                                        <input type="password" name="password" class="form-control password" autocomplete="new-password" placeholder="password must be complex" required>                       
                                   </div>
                              </div>

                         <!-- end password field -->

                         <!-- start email field -->
                              <div class="form-group ms-5">
                              <label class="col-sm-2 control-label">Email</label>
                              <div class="col-sm-10">
                                   <input type="email" name="email"  class="form-control" required placeholder="enter youe email">
                              </div>
                              </div>
                         <!-- end email field -->

                         <!-- start full name field -->
                              <div class="form-group ms-5">
                              <label class="col-sm-2 control-label">Full Name</label>
                              <div class="col-sm-10">
                                   <input type="text" name="fullname" class="form-control" required placeholder="enter your fullname">
                              </div>
                              </div>
                         <!-- end full name field -->

                              <!-- start full name field -->
                              <div class="form-group ms-5">
                              <label class="col-sm-2 control-label">Person image</label>
                              <div class="col-sm-10">
                                   <input type="file" name="avatar" class="form-control">
                              </div>
                              </div>
                         <!-- end full name field -->

                         <!-- start button field -->
                              <div class="form-group ms-5">
                                   <div class="col-sm-offset-2 col-sm-10">
                                        <input type="submit" value="Add" class="btn btn-info w-25 mt-3">
                                   </div>
                              </div>
                         <!-- end button field -->
               </form>
          </div>
          <!-- add page end -->
          <!-- insert page start  -->
     <?php }elseif($do=='insert'){
               if($_SERVER['REQUEST_METHOD']=='POST'){
                    echo '<div class="container">';
                    echo '<h1 class="text-center">Insert Members</h1>';

                    $name=$_FILES['avatar']['name'];
                    $size=$_FILES['avatar']['size'];
                    $tmp=$_FILES['avatar']['tmp_name'];
                    $type=$_FILES['avatar']['type'];

                    $avatarextension=array('png','jpg','jpeg','gif');
                    $avatarex=strtolower(end(explode('.',$name)));


                    $username=$_POST['username'];
                    $password=$_POST['password'];
                    $email=$_POST['email'];
                    $fullname=$_POST['fullname'];
                    $hashpassword=sha1($password);

                    $formerrors=array();
                    if(empty($username)){
                         $formerrors[]='<div class="alert alert-danger">username cannot be empty!</div>';
                    }
                    if(strlen($username)<4 && !empty($username)){
                         $formerrors[]='<div class="alert alert-danger">username must be more than 4 charcter!</div>';
                    }
                    if(empty($hashpassword)){
                         $formerrors[]='<div class="alert alert-danger">password must be valid!</div>';
                    }
                    if(empty($email)){
                         $formerrors[]='<div class="alert alert-danger">email cannot be empty!</div>';
                    }
                    if(empty($fullname)){
                         $formerrors[]='<div class="alert alert-danger">fullname cannot be empty!</div>';
                    }
                    if(!in_array($avatarex,$avatarextension) && !empty($name)){
                         $formerrors[]='<div class="alert alert-danger">Sorry your extension is not accept!</div>';
                    }
                    if(empty($name)){
                         $formerrors[]='<div class="alert alert-danger">Sorry you must chose an image!</div>';
                    }
                    if($size > 5242880){
                         $formerrors[]='<div class="alert alert-danger">your file is very big!</div>';
                    }
                    foreach($formerrors as $errors){
                         echo $errors;
                    }
                    
                    if(empty($formerrors)){

                         $avatar=rand(0,1000000).'_'.$name;
                         move_uploaded_file($tmp,'..\uploads\images\\'.$avatar);

                         $stmt=$con->prepare("insert into users(username,password,email,fullname, regstatus,date,image) 
                         values(:cusername , :cpassword , :cemail , :cfullname , 1 , now() , :zimage)");
                         $stmt->execute(array(
                              'cusername' => $username,
                              'cpassword' => $hashpassword,
                              'cemail'    => $email,
                              'cfullname' => $fullname,
                              'zimage'    => $avatar
                         ));
                         echo '<div class="container alert alert-info">' . $stmt->rowCount() . ' Record Inserted </div>';
                         echo '</div>';
                         }else{
                              $themsg="<div class='alert alert-danger'>Sorry There Is An Error In The Information!</div>";
                              rdh($themsg,'back');
                    }
               }
               else{
                    $themsg='<div class="alert alert-danger">you can not enter to this page directly</div>';
                    rdh($themsg,3);
               }
     }elseif($do=='manage'){
          $query='';
          if(isset($_GET['page']) && $_GET['page']=='pending'){
               $query='and regstatus=0';
          }
          $stmt=$con->prepare('select * from users where groupid != 1 '.$query);
          $stmt->execute();
          $rows=$stmt->fetchAll();
          ?>
          <div class="container">
               <h1 class="text-center">Manage Members</h1>
               <div class="table-responsive">
                    <table class="table main-table text-center table-bordered">
                         <tr>
                              <td>#ID</td>
                              <td>Image</td>
                              <td>Username</td>
                              <td>Email</td>
                              <td>Full Name</td>
                              <td>Register Date</td>
                              <td>Control</td>
                         </tr>
                         <?php foreach($rows as $row){
                              echo '<tr>';
                              echo '<td>' . $row['userid'] . '</td>';

                              echo '<td>';
                              if(empty($row['image'])){
                                   echo '<strong>there is no image here.</strong>';
                              }else{
                                   echo '<img style="width:50px;height:50px" src="../uploads/images/' . $row['image'] . '" alt="error">';
                              }
                              echo '</td>';

                              echo '<td>' . $row['username'] . '</td>';

                              echo '<td>' . $row['email'] . '</td>';

                              echo '<td>'.$row['fullname'].'</td>';

                              echo '<td>' . $row['date'] .'</td>';

                              echo '<td>
                              <a href="members.php?do=edit&userid='.$row["userid"].'"class="btn btn-success">Edit</a>
                              <a href="members.php?do=delete&userid='.$row["userid"].'" class="btn btn-danger confirm">Delete</a>';
                              if($row['regstatus'] == 0){
                                   echo '<a href="members.php?do=activate&userid='.$row["userid"].'" class="btn btn-info activate">Activate</a>';
                              }
                              echo '</td>';
                              echo '</tr>';
                         } ?>
                    </table>
               </div>
               <a href="members.php?do=add" class="btn btn-primary"><i class="fa fa-plus"></i> Add A New Members</a>
          </div>
     <?php }elseif($do=='delete'){
          echo '<h1 class="text-center">Delete Menmber</h1>';
          echo '<div class="container">';
          if(isset($_GET['userid']) && is_numeric($_GET['userid'])){
               $userid=$_GET['userid'];
               $stmt=$con->prepare('select * from users where userid=? limit 1');
               $stmt->execute(array($userid));
               $count=$stmt->rowCount();
               if($count > 0){
                    $stmt=$con->prepare('delete from users where userid=?');
                    $stmt->execute(array($userid));
                    echo "<div class='alert alert-success'>".$count."Record Deleted</div>";
               }else{
                    $themsg='<div class="alert alert-danger text-center">Sorry This ID Is Not Exsist!</div>';
                    rdh($themsg);
               }
          }else{
               $themsg='<div class="alert alert-danger">you can not enter to this page directly</div>';
               rdh($themsg,3);
          }
          echo '</div>';
     }elseif($do='activate'){
          echo "<h1 class='text-center'>activation</h1>";
          echo "<div class='container'>";
          if(isset($_GET['userid']) && is_numeric($_GET['userid'])){
               $userid=intval($_GET['userid']);
               $stmt=$con->prepare("update users set regstatus=1 where userid=?");
               $stmt->execute(array($userid));
               echo "<div class='alert alert-success'> ".$stmt->rowCount()." Record Activated</div>";
               echo "</div>";
          }
     }

     include 'includes/templates/footer.php';
}else{
     header('location:dashboard.php');
     exit();
}