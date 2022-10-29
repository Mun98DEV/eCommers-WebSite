<?php
session_start();
$pagetitle='Items';
if(isset($_SESSION['username'])){
     include 'init.php';

     $do='';
     if(isset($_GET['do'])){
          $do=$_GET['do'];
     }else{
          $themsg='<div class="alert alert-danger">Sorry there is roung in https!</div>';
          rdh($themsg);
     }

     if($do=='add'){ ?>
          <h1 class="text-center">Add items</h1>
          <div class="container">
               <form class="form-horizontal ms-5" action="items.php?do=insert" method="POST">
                    <!-- start name field -->
                    <div class="form-group ms-5">
                         <label class="col-sm-2 control-label">Name</label>
                         <div class="col-sm-10">
                              <input type="text" name="name" class="col-sm-10 form-control" placeholder="Name Of The Item">
                         </div>
                    </div>
                    <!-- end name field -->

                    <!-- start description field -->
                    <div class="form-group ms-5">
                    <label class="col-sm-2 control-label">Discription</label>
                    <div class="col-sm-10">
                         <input type="text" name="description" class="form-control" placeholder="Write Description About The Items">                       
                    </div>
                    </div>
                    <!-- end description field -->

                    <!-- start price field -->
                    <div class="form-group ms-5">
                    <label class="col-sm-3 control-label">Price of item</label>
                    <div class="col-sm-9">
                         <input type="text" name="price"  class="form-control" placeholder="Write The Price Of The Item" required>
                    </div>
                    </div>
                    <!-- end price field -->

                    <!-- start country of madefield -->
                    <div class="form-group ms-5">
                    <label class="col-sm-3 control-label">Country of made</label>
                    <div class="col-sm-9">
                         <input type="text" name="country"  class="form-control" placeholder="Write The country of made the item" required>
                    </div>
                    </div>
                    <!-- end country of made field -->

                    <!-- start users field -->
                    <div class="form-group ms-5">
                    <label class="col-sm-3 control-label">Users</label>
                    <div class="col-sm-9">
                         <select class="form-control" name="user">
                              <?php
                              $stmt=$con->prepare("select * from users where groupid=0");
                              $stmt->execute();
                              $users=$stmt->fetchAll();
                              foreach($users as $user){
                                   echo "<option value='".$user["userid"]."'>". $user['username'] ."</option>";
                              }
                              
                              ?>
                         </select>
                    </div>
                    </div>
                    <!-- end users field -->

                    <!-- start categories field -->
                    <div class="form-group ms-5">
                    <label class="col-sm-3 control-label">The categories</label>
                    <div class="col-sm-9">
                         <select class="form-control" name="categories">
                              <?php
                              $stmt=$con->prepare("select * from categories");
                              $stmt->execute();
                              $cats=$stmt->fetchAll();
                              foreach($cats as $cat){
                              echo "<option value='".$cat["id"]."'>".$cat["name"]."</option>";
                              }
                              ?>
                         </select>
                    </div>
                    </div>
                    <!-- end categories field -->

                    <!-- start status field -->
                    <div class="form-group ms-5">
                    <label class="col-sm-3 control-label" for="select">Status of the item</label>
                    <div class="col-sm-9">
                         <select class="form-control form-select" aria-label="Default select example" name="status" id="selects">
                              <option value="0">...</option>
                              <option value="1">New</option>
                              <option value="2">Like new</option>
                              <option value="3">Used</option>
                              <option value="4">Very old</option>
                         </select>
                    </div>
                    </div>
                    <!-- end status field -->

                    <!-- start tags field -->
                    <div class="form-group ms-5">
                    <label class="col-sm-3 control-label">Tags</label>
                    <div class="col-sm-9">
                         <input type="text" name="tags"  class="form-control" placeholder="Separate between tags with comma (,)">
                    </div>
                    </div>
                    <!-- end tags field -->

                    <!-- start add button -->
                    <input type="submit" class="btn btn-primary mt-3 mb-5 ml-2" value="Add Item">
                    <!-- end add button -->
               </form>
          </div>
<?php }elseif($do=='insert'){
          if($_SERVER['REQUEST_METHOD']=='POST'){
               echo '<h1 class="text-center">Insert Page</h1>';
               echo '<div class="container">';
               $formerrors=array();
               
               $name=$_POST['name'];
               $description=$_POST['description'];
               $price=$_POST['price'];
               $country=$_POST['country'];
               $user=$_POST['user'];
               $categories=$_POST['categories'];
               $status=$_POST['status'];
               $tags=$_POST['tags'];
               $tag=str_replace(' ','',$tags);
               $tag=strtolower($tags);

               if(empty($price)){
                    $formerrors[]='Sorry but the price field must not be empty!';
               }
               if(empty($country)){
                    $formerrors[]='Sorry but the country field must not be empty!';
               }
               if($status == 0){
                    $formerrors[]='Sorry but the status field must not be empty!';
               }
               if($user == 0){
                    $formerrors[]='Sorry but the user field must not be empty!';
               }
               if($categories == 0){
                    $formerrors[]='Sorry but the category field must not be empty!';
               }

               foreach($formerrors as $error){
                    echo "<div class='alert alert-danger'>".$error."</div>";
               }

               if(empty($formerrors)){
                    $stmt=$con->prepare("insert into 
                    items(name,description,price,country_made,status,add_date,cat_id,member_id,tags)
                    values(:zname,:zdescription,:zprice,:zcountry_made,:zstatus,now(),:zcat_id,:zuser,:ztags)");
                    $stmt->execute(array(
                         'zname'        => $name,
                         'zdescription' => $description,
                         'zprice'       => $price,
                         'zcountry_made'=> $country,
                         'zstatus'      => $status,
                         'zcat_id'      => $categories,
                         'zuser'        => $user,
                         'ztags'        => $tag
                    ));

                    $themsg="<div class='alert alert-success'>The item is added.</div>";
                    rdh($themsg,'back');
               }
          }
     }elseif($do=='manage'){
          echo '<h1 class="text-center">Mange Items</h1>';
          echo '<div class="m-5">';
          $stmt=$con->prepare("SELECT items.*,categories.name as category_name,users.username
                              FROM 
                                   items
                              INNER JOIN
                                   categories
                              ON
                                   categories.id=items.cat_id
                              INNER JOIN
                                   users
                              ON
                                   users.userid=items.member_id");
          $stmt->execute();
          $items=$stmt->fetchAll(); ?>
          <div class="table-responsive">
               <table class="table main-table text-center table-border">
                    <tr>
                         <td>ID</td>
                         <td>Name</td>
                         <td>Description</td>
                         <td>Price</td>
                         <td>Adding_Date</td>
                         <td>Category</td>
                         <td>Username</td>
                         <td>Control</td>
                    </tr>

                    <?php
                    foreach($items as $item){
                         echo '<tr>';
                         echo '<td>'. $item['item_id'] .'</td>';
                         echo '<td>'. $item['name'] .'</td>';
                         echo '<td style="overflow: auto;">'. $item['description'] .'</td>';
                         echo '<td>'. $item['price'] .'</td>';
                         echo '<td>'. $item['add_date'] .'</td>';
                         echo '<td>'. $item['category_name'] .'</td>';
                         echo '<td>'. $item['username'] .'</td>';
                         echo '<td>
                                   <a class="btn btn-info btn-sm" href="items.php?do=edit&item_id='.$item['item_id'].'"><i class="fa fa-edit"></i> Edit</a>
                                   <a class="btn btn-danger btn-sm" href="items.php?do=delete&item_id='.$item['item_id'].'"><i class=" fa fa-trash"></i> Delete</a>';
                                   if($item['approve']==0){
                                        echo '<a class="btn btn-success btn-sm ml-5 mt-2" href="items.php?do=approve&item_id='.$item['item_id'].'"><i class="fa fa-check"></i> Approve</a>';
                                   }
                         echo  '</td>';
                         echo '</tr>';
                    }?>
               </table>
          </div>

<?php }elseif($do=='edit'){
          echo '<h1 class="text-center">Edit Items Page</h1>';
          echo '<div class="container">';
          if(isset($_GET['item_id']) && is_numeric($_GET['item_id'])){
               $item_id=intval($_GET['item_id']);
               $stmt=$con->prepare("select * from items where item_id=? limit 1");
               $stmt->execute(array($item_id));
               $row=$stmt->fetch();
               $count=$stmt->rowCount();
               if($count > 0){ ?>
                    <div class="container">
                         <form class="form-horizontal ms-5" action="items.php?do=update" method="POST">
                              <!-- start name field -->
                              <div class="form-group ms-5">
                                   <label class="col-sm-2 control-label">Name</label>
                                   <div class="col-sm-10">
                                        <input type="hidden" name="id" value="<?php echo $row['item_id']; ?>">
                                        <input type="text" name="name" class="col-sm-10 form-control" placeholder="Name Of The Item" value="<?php echo $row['name']; ?>">
                                   </div>
                              </div>
                              <!-- end name field -->

                              <!-- start description field -->
                              <div class="form-group ms-5">
                              <label class="col-sm-2 control-label">Discription</label>
                              <div class="col-sm-10">
                                   <input type="text" name="description" class="form-control" placeholder="Write Description About The Items" value="<?php echo $row['description']; ?>">                       
                              </div>
                              </div>
                              <!-- end description field -->

                              <!-- start price field -->
                              <div class="form-group ms-5">
                              <label class="col-sm-3 control-label">Price of item</label>
                              <div class="col-sm-9">
                                   <input type="text" name="price"  class="form-control" placeholder="Write The Price Of The Item" required value="<?php echo $row['price']; ?>">
                              </div>
                              </div>
                              <!-- end price field -->

                              <!-- start country of madefield -->
                              <div class="form-group ms-5">
                              <label class="col-sm-3 control-label">Country of made</label>
                              <div class="col-sm-9">
                                   <input type="text" name="country"  class="form-control" placeholder="Write The country of made the item" required value="<?php echo $row['country_made']; ?>">
                              </div>
                              </div>
                              <!-- end country of made field -->

                              <!-- start users field -->
                              <div class="form-group ms-5">
                              <label class="col-sm-3 control-label">Users</label>
                              <div class="col-sm-9">
                                   <select class="form-control" name="user">
                                        <?php
                                        $stmt=$con->prepare("select * from users where groupid=0");
                                        $stmt->execute();
                                        $users=$stmt->fetchAll();
                                        foreach($users as $user){
                                             echo "<option value='". $user['userid'] ."'";
                                             if($row['member_id']==$user['userid']){echo 'selected';}
                                             echo ">";
                                             echo $user['username'];
                                             echo "</option>";
                                        }
                                        ?>
                                   </select>
                              </div>
                              </div>
                              <!-- end users field -->

                              <!-- start categories field -->
                              <div class="form-group ms-5">
                              <label class="col-sm-3 control-label">The categories</label>
                              <div class="col-sm-9">
                                   <select class="form-control" name="categories">
                                        <?php
                                        $stmt=$con->prepare("select * from categories");
                                        $stmt->execute();
                                        $cats=$stmt->fetchAll();
                                        foreach($cats as $cat){
                                        echo "<option value='".$cat["id"]."'";
                                        if($cat['id']==$row['cat_id']){ echo 'selected';}
                                        echo '>';
                                        echo $cat['name'] . "</option>";
                                        }
                                        ?>
                                   </select>
                              </div>
                              </div>
                              <!-- end categories field -->

                              <!-- start status field -->
                              <div class="form-group ms-5">
                              <label class="col-sm-3 control-label" for="select">Status of the item</label>
                              <div class="col-sm-9">
                                   <select class="form-control" name="status">
                                        <option value="1" <?php if($row['status']==1){ echo 'selected';} ?>>New</option>
                                        <option value="2" <?php if($row['status']==2){ echo 'selected';} ?>>Like new</option>
                                        <option value="3" <?php if($row['status']==3){ echo 'selected';} ?>>Used</option>
                                        <option value="4" <?php if($row['status']==4){ echo 'selected';} ?>>Very old</option>
                                   </select>
                              </div>
                              </div>
                              <!-- end status field -->

                              <!-- start country of madefield -->
                              <div class="form-group ms-5">
                              <label class="col-sm-3 control-label">Country of made</label>
                              <div class="col-sm-9">
                                   <input type="text" name="tags"  class="form-control" placeholder="Separate between tags with comma (,)" value="<?php echo $row['tags']; ?>">
                              </div>
                              </div>
                              <!-- end country of made field -->

                              <!-- start add button -->
                              <input type="submit" class="btn btn-primary mt-3 mb-5 ml-2" value="Update Item">
                              <!-- end add button -->
                         </form>
                         <?php
                         $stmt=$con->prepare("select comments.*,users.username from comments 
                              inner join
                                   users
                              on
                                   users.userid=comments.user_id where item_id=?");
                         $stmt->execute(array($item_id));
                         $rows=$stmt->fetchAll(); 
                         if(!empty($rows)){ 
                              echo '<h1 class="text-center">Manage [ '. $row['name'] . ' ] Comments</h1>'; ?>
                              <div class="table-responsive">
                                   <table class="table main-table table-border text-center">
                                        <tr>
                                             <td>Comment</td>
                                             <td>Comment Date</td>
                                             <td>Control</td>
                                        </tr>

                                        <?php
                                             foreach($rows as $row){
                                                  echo '<tr>';
                                                       echo '<td>'.$row["comment"].'</td>';
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
          </div>
          <?php                    }
          }
          }
     }elseif($do=='update'){
          if($_SERVER['REQUEST_METHOD']=='POST'){
               echo '<h1 class="text-center">Update Page</h1>';
               echo '<div class="container">';

               $id=$_POST['id'];
               $name=$_POST['name'];
               $description=$_POST['description'];
               $price=$_POST['price'];
               $country=$_POST['country'];
               $user=$_POST['user'];
               $categories=$_POST['categories'];;
               $status=$_POST['status'];
               $tags=$_POST['tags'];
               $tag=str_replace('',' ',$tags);
               $tag=strtolower($tags);

               $formerrors=array();
               if(empty($name)){
                    $formerrors[]='Sorry the name field must not be empty!';
               }
               if($name<1){
                    $formerrors[]='Sorry the name field must be more than one!';
               }
               if(empty($price)){
                    $formerrors[]='Sorry the price field must not be empty!';
               }
               if(empty($country)){
                    $formerrors[]='Sorry the country field must not be empty!';
               }
               foreach($formerrors as $error){
                    echo "<div class='alert alert-danger'>".$error."</div>";
               }

               if(empty($formerrors)){
                    $stmt=$con->prepare("update items set name=?,description=?,price=?,country_made=?,status=?,cat_id=?,member_id=?,tags=? where item_id=?");
                    $stmt->execute(array($name,$description,$price,$country,$status,$categories,$user,$tag,$id));

                    $themsg='<div class="alert alert-success">The item is updated.</div>';
                    rdh($themsg,'back');
               }
               echo '</div>';
          }
     }elseif($do=='delete'){
          echo '<h1 class="text-center">Delete Page</h1>';
          echo '<div class="container">';
          if(isset($_GET['item_id'])&&is_numeric($_GET['item_id'])){
               $item_id=intval($_GET['item_id']);
               $stmt=$con->prepare("delete from items where item_id=?");
               $stmt->execute(array($item_id));

               $themsg='<div class="alert alert-success">The item is deleted.</div>';
               rdh($themsg,'back');
          }else{
               $themsg='<div class="alert alert-danger">Sorry some thing is roung!</div>';
               rdh($themsg,'back');
          }
          echo "</div>";
     }elseif($do=='approve'){
          if(isset($_GET['item_id']) && is_numeric($_GET['item_id'])){
               $item_id=intval($_GET['item_id']);
               $stmt=$con->prepare("update items set approve=1 where item_id=? limit 1");
               $stmt->execute(array($item_id));

               $themsg='<div class="alert alert-success">The item is approved.</div>';
               rdh($themsg,'back');
          }
     }


     include 'includes/templates/footer.php';
}