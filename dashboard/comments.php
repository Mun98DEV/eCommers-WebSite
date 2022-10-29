<?php
session_start();
$pagetitle='Comments';
if(isset($_SESSION['username'])){
     include 'init.php';
     $do='';
     if(isset($_GET['do'])){
          $do=$_GET['do'];
     }

     if($do=='manage'){
          echo '<h1 class="text-center">Manage Comments</h1>';
          echo '<div class="container">';
          $stmt=$con->prepare("select comments.*,items.name as item_name,users.username from comments 
               inner join 
                    items 
               on 
                    items.item_id=comments.item_id
               inner join
                    users
               on
                    users.userid=comments.user_id");
          $stmt->execute();
          $rows=$stmt->fetchAll(); ?>
          <div class="table-responsive">
               <table class="table main-table table-border text-center">
                    <tr>
                         <td>Comment</td>
                         <td>Item</td>
                         <td>Username</td>
                         <td>Comment Date</td>
                         <td>Control</td>
                    </tr>

                    <?php
                         foreach($rows as $row){
                              echo '<tr>';
                                   echo '<td>'.$row["comment"].'</td>';
                                   echo '<td>'.$row["item_name"].'</td>';
                                   echo '<td>'.$row["username"].'</td>';
                                   echo '<td>'.$row["comment_date"].'</td>';
                                   echo '<td>
                                             <a class="btn btn-primary btn-sm" href="comments.php?do=edit&comment_id='.$row['c_id'].'"><i class="fa fa-edit"></i> Edit</a>
                                             <a class="btn btn-danger btn-sm" href="comments.php?do=delete&comment_id='. $row['c_id'] .'"><i class="fa fa-trash"></i> Delete</a>';
                                             if($row['status']==0){
                                                  echo '<a class="btn btn-info btn-sm ml-1" href="comments.php?do=approve&comment_id='.$row['c_id'].'"><i class=" fa fa-check"></i> Approve</a>';
                                             }
                                   echo '</td>';
                              echo '</tr>';
                         } ?>
               </table>
          </div>

<?php }elseif($do=='edit'){
          echo '<h1 class="text-center">Edit Page</h1>';
          echo '<div class="container">';
               if(isset($_GET['comment_id']) && is_numeric($_GET['comment_id'])){
                    $comment_id=intval($_GET['comment_id']);
                    $stmt=$con->prepare("select * from comments where c_id=? limit 1");
                    $stmt->execute(array($comment_id));
                    $row=$stmt->fetch(); ?>
                    <form class="form-horizontal ms-5" action="comments.php?do=update" method="POST">
                         <!-- start comment field -->
                         <div class="form-group ms-5">
                              <label class="col-sm-2 control-label">Comment</label>
                              <div class="col-sm-10">
                                   <input type="hidden" name="id" value="<?php echo $row['c_id']; ?>">
                                   <input type="text" name="comment" class="col-sm-10 form-control" placeholder="put your comment here" value="<?php echo $row['comment']; ?>">
                              </div>
                         </div>
                         <!-- end comment field -->

                         <!-- start add button -->
                         <input type="submit" class="btn btn-primary mt-3 mb-5 ml-2" value="Update Comment">
                         <!-- end add button -->
                    </form>
<?php          }
          echo '</div>';
     }elseif($do=='update'){
          if($_SERVER['REQUEST_METHOD']=='POST'){
               echo '<h1 class="text-center">Update Comment Page</h1>';
               echo '<div class="container">';
               $comment_id=$_POST['id'];
               $comment=$_POST['comment'];

               $stmt=$con->prepare("UPDATE comments set comment=? where c_id=?");
               $stmt->execute(array($comment,$comment_id));

               $themsg='<div class="alert alert-success">The comment is updated.</div>';
               rdh($themsg,'back');

          }               
          echo '</di>';
     }elseif($do=='delete'){
          if(isset($_GET['comment_id']) && is_numeric($_GET['comment_id'])){
               echo '<h1 class="text-center">Delete comment Page</h1>';
               echo '<div class="container">';
               $comment_id=$_GET['comment_id'];
               $stmt=$con->prepare("delete from comments where c_id=?");
               $stmt->execute(array($comment_id));

               $themsg='<div class="alert alert-success">The comment is deleted</div>';
               rdh($themsg,'back');
               echo '</div>';
          }
     }elseif($do=='approve'){
          if(isset($_GET['comment_id']) && is_numeric($_GET['comment_id'])){
               echo '<h1 class="text-center">Approved comment Page</h1>';
               echo '<div class="container">';
               $comment_id=$_GET['comment_id'];
               $stmt=$con->prepare("update comments set status=1 where c_id=?");
               $stmt->execute(array($comment_id));

               $themsg='<div class="alert alert-success">The comment is approved</div>';
               rdh($themsg,'back');
               echo '</div>';
          }
     }

     include 'includes/templates/footer.php';
}else{
     header('location: index.php');
     exit();
}