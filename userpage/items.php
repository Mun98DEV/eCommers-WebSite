<?php
session_start();
$pagetitle='Items';
include 'init.php';

if(isset($_GET['item_id']) && is_numeric($_GET['item_id'])){
     $item=intval($_GET['item_id']);

     $stmt=$con->prepare("select items.*,categories.name as cat_name,id as catid,users.username,userid
                              from
                                   items
                              inner join
                                   categories
                              on
                                   items.cat_id=categories.id
                              inner join
                                   users
                              on
                                   items.member_id=users.userid
                              where
                                   item_id=?");
     $stmt->execute(array($item));

     $count=$stmt->rowCount();
     if($count > 0){
          $row=$stmt->fetch(); ?>
          <h1 class="text-center"><?php echo $row['name'] ?></h1>
          <div class="container">
               <div class="row">
                    <div class="col-md-3">
                         <img src="img1.png" alt="Error" class="img-responsive img-thumbnail center-block">
                    </div>

                         <ul class="list-unstyled">
                              <h4><span class="font-weight-bolder">Item Name:</span> <?php echo $row['name']; ?></h4>
                              <p><span class="font-weight-bolder"> Description:</span> <?php echo $row['description']; ?></p>
                    <div class="col-md-9">
                              <li><i class="fa fa-calendar"></i><span><span class="font-weight-bolder"> Date Of Show:</span> <?php echo $row['add_date'] ?></span></li>
                              <li><div><i class="fa fa-money"></i><span class="font-weight-bolder"> Price:</span> $<?php echo $row['price'] ?></div></li>
                              <li><div><i class="fa fa-building"></i><span class="font-weight-bolder"> Made in:</span> <?php echo $row['country_made'] ?> </div></li>
                              <li><div><i class="fa fa-tag"></i><span class="font-weight-bolder"> Category:</span> <?php echo '<a href=" categories.php?pagename='.$row["cat_name"].'&pageid='.$row["catid"].' "> '.$row["cat_name"].'</a>' ?> </div></li>
                              <li><div><i class="fa fa-user"></i><span class="font-weight-bolder"> The Publisher:</span> <?php echo '<a href="profile.php">'.$row["username"].'</a>' ?> </div></li>
                              <li><div><i class="fa fa-tags"></i><span class="font-weight-bolder"> Tags:</span> <?php
                              $alltags=explode(",",$row['tags']);
                              foreach($alltags as $tags){
                                   $tagcapital=str_replace(" ","",$tags);
                                   $tag=strtolower($tagcapital);
                                   echo '<a href="tags.php?name='.$tag.'"> '.$tag.' |</a>';
                              }
                              ?> </div></li>

                         </ul>
                    </div>
               </div>

               <hr style="border: 1px solid #808080;">
               <?php
               if(isset($_SESSION['user'])){ ?>
                    <div class="container">
                         <div class="row">
                              <div class="col-md-9 offset-md-3">
                                   <div class="add-comment">
                                        <h3 class="text-center">Add Your Comment</h3>
                                        <form action="<?php echo $_SERVER['PHP_SELF'] . '?item_id=' . $row['item_id'] ?>" method="POST">
                                             <textarea name="comment" required></textarea>
                                             <input type="submit" class="btn btn-primary" value="Add Comment">
                                        </form>
                                   </div>
                              </div>
                              <?php
                              if($_SERVER['REQUEST_METHOD']=="POST"){
                                   $comment =filter_var($_POST['comment'],FILTER_SANITIZE_STRING);
                                   $item_id =$row['item_id'];
                                   $user_id  =$_SESSION['user'];
                                   
                                   if(!empty($comment)){
                                   $stmt=$con->prepare("insert into comments(comment,comment_date,item_id,user_id) values(:zcomment,now(),:zitem_id,:zuser_id)");
                                   $stmt->execute(array(
                                        'zcomment' => $comment,
                                        'zitem_id' => $item_id,
                                        'zuser_id' => $user_id
                                   ));
                                   echo '<div class="alert alert-success">The comment is added</div>';
                                   }
                              }
                              ?>
                         </div>
                    </div>
               <?php
               }else{
                    echo '<div class="container"><div class="alert alert-info"><a href="login.php">Login</a> or <a href="login.php">regiester</a> to add comments</div></div>';
               }
               
               
               ?>
               <hr style="border: 1px solid #808080;">
               <?php 
               $stmt=$con->prepare("select comments.*,users.username from comments
               inner join users on comments.user_id=users.userid where status=1 and item_id=?");
               $stmt->execute(array($row['item_id']));
               $rows=$stmt->fetchAll();

               echo '<div class="container">';
               foreach($rows as $row){
                    echo '<div class="row">';
                         echo '<div class="col-md-3">'.$row['username'].'</div>';
                         echo '<div class="col-md-9">'.$row['comment'].'</div>';
                    echo '</div>';
               }
          echo '</div>';
          ?>
     <?php }

} 
include 'includes/templates/footer.php'; ?>