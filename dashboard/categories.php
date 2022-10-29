<?php
session_start();
$pagetitle='Categories';
if(isset($_SESSION['username'])){
     include 'init.php';
     $do='';
     if(isset($_GET['do'])){
          $do=$_GET['do'];
     }else{
          echo 'Sorry there is no page like this';
     }
     if($do =='manage'){
          $sort='';
          if(isset($_GET['sort']) && $_GET['sort']=='asc'){
               $sort='ASC';
          }elseif(isset($_GET['sort']) && $_GET['sort']=='desc'){
               $sort='DESC';
          }else{
               $sort='ASC';
          }
          $stmt=$con->prepare("select * from categories where parent=0 order by ordering $sort");
          $stmt->execute();
          $rows=$stmt->fetchAll(); ?>
          <div class="container categories">
               <h1 class="text-center font-weight-bolder">Manage Categories Page</h1>
               <dv class="panel panel-default">
                    <div class="card-header fw-bolder">Manage Categories
                         <div class="ordering pull-right">
                              <span><i class="fa fa-sort"></i> Ordering: </span>
                              [ 
                              <a href="categories.php?do=manage&sort=asc">ASC |</a>
                              <a href="categories.php?do=manage&sort=desc">DESC </a> ]
                              <span><i class="fa fa-eye"></i> View:</span>
                              [ 
                              <a class="classic" href="#">Classic |</a>
                              <a class="full" href="#">Full</a> 
                              ]
                         </div>
                    </div>
                    <div class="card-body">
                         <?php foreach($rows as $row){
                              echo "<div class='cats'>";
                                   echo "<div class='hidden-buttons'>";
                                        echo "<a href='categories.php?do=edit&catid=".$row['id']."' class='btn btn-primary btn-sm'><sapn><i class='fa fa-edit'></i></sapn> Edit</a>";
                                        echo "<a href='categories.php?do=delete&catid=".$row['id']."' class='btn btn-danger btn-sm'><sapn><i class='fa fa-close'></i></sapn> Delete</a>";
                                   echo "</div>";
                                   echo "<h3>" .$row['name']."</h3>";
                                   echo '<div class="full-view">';
                                        echo "<p>Description: "; if($row['description']==''){echo '<span style="color:red;font-weight:bolder">This section is empty</span>';}else{echo $row['description'];} echo"</p>";
                                        if($row['visibility']==1){echo "<span class='visible'>Hidden</span>";}
                                        if($row['allow_comment']==1){echo "<span class='comments'>Hidden Comments</span>";}
                                        if($row['allow_ads']==1){echo "<span class='ads'>No ADS</span>";}
                                   echo '</div>';
                              echo "</div>";

                                   $stmt=$con->prepare("select * from categories where parent=? order by ordering asc");
                                   $stmt->execute(array($row['id']));
                                   $cats=$stmt->fetchAll();
                                   if(! empty($cats)){
                                        echo '<h5 class="font-weight-bolder" style="color:red;">Child of the category</h5>';
                                        foreach($cats as $cat){
                                             echo '<ul class="list-unstyled ml-4">';
                                                  echo "<li><h3 style='color:green;'>- " .$cat['name']."</h3></li>";
                                                  echo '<li><div class="full-view">';
                                                       echo "<p>Description: "; if($cat['description']==''){echo '<span style="color:red;font-weight:bolder">This section is empty</span>';}else{echo $cat['description'];} echo"</p>";
                                                       if($cat['visibility']==1){echo "<span class='visible'>Hidden</span>";}
                                                       if($cat['allow_comment']==1){echo "<span class='comments'>Hidden Comments</span>";}
                                                       if($cat['allow_ads']==1){echo "<span class='ads'>No ADS</span>";}
                                                  echo '</div></li>';
                                             echo '</ul>';
                                        }
                                   }
                              }
                         ?>
                    </div>
                    <a href="categories.php?do=add" class="btn btn-primary mt-4 mb-5">Add New Category</a>
               </div>
          </div>
<?php 
     }elseif($do=='add'){ ?>
          <h1 class="text-center">Add New Categories</h1>
          <div class="container">
               <form class="form-horizontal ms-5" action="?do=insert" method="POST">
                    <!-- start name field -->
                    <div class="form-group ms-5">
                         <label class="col-sm-2 control-label">Username</label>
                         <div class="col-sm-10">
                              <input type="text" name="name" class="col-sm-10 form-control" autocomplete="off" required placeholder="Name Of The Category">
                         </div>
                    </div>
                    <!-- end name field -->

                    <!-- start description field -->
                    <div class="form-group ms-5">
                         <label class="col-sm-2 control-label">Discription</label>
                         <div class="input-group mb-3">
                              <input type="text" name="description" class="form-control" placeholder="Write Description About The Categories">                       
                         </div>
                    </div>
                    <!-- end description field -->

                    <!-- start ordering field -->
                    <div class="form-group ms-5">
                    <label class="col-sm-2 control-label">Ordering</label>
                    <div class="col-sm-10">
                         <input type="text" name="ordering"  class="form-control" placeholder="Write The Ordering Of Category">
                    </div>
                    </div>
                    <!-- end ordering field -->

                    <!-- start parent field -->
                    <div class="form-group ms-5">
                         <label class="col-sm-2 control-label">Parent</label>
                         <div class="col-sm-10">
                              <select name="parent">
                                   <option value="0">None</option>
                                   <?php
                                   $stmt=$con->prepare("select * from categories where parent=0 order by ordering asc");
                                   $stmt->execute();
                                   $cats=$stmt->fetchAll();
                                   foreach($cats as $cat){
                                        echo '<option value="'.$cat["id"].'">'.$cat['name'].'</option>';
                                   }
                                   ?>
                              </select>
                         </div>
                    </div>
                    <!-- end parent field -->

                    <!-- start visibility field -->
                    <div class="form-group form-group-lg">
                         <label class="col-sm-2 control-label">Visible</label>
                         <div class="col-sm-10 ">
                              <div>
                                   <input id="vis-yes" type="radio" value="0" name="visibility" checked>
                                   <label for="vis-yes">Yes</label>
                              </div>
                              <div>
                                   <input id="vis-no" type="radio" value="1" name="visibility">
                                   <label for="vis-no">No</label>
                              </div>
                         </div>
                    </div>
                    <!-- end visibility field -->

                    <!-- start commennting field -->
                    <div class="form-group form-group-lg">
                         <label class="col-sm-2 control-label">Allow Comments</label>
                         <div class="col-sm-10 ">
                              <div>
                                   <input id="comm-yes" type="radio" value="0" name="allow_comment" checked>
                                   <label for="comm-yes">Yes</label>
                              </div>
                              <div>
                                   <input id="comm-no" type="radio" value="1" name="allow_comment">
                                   <label for="comm-no">No</label>
                              </div>
                         </div>
                    </div>
                    <!-- end commenting field -->

                    <!-- start ads field -->
                    <div class="form-group form-group-lg">
                         <label class="col-sm-2 control-label">Allow Adds</label>
                         <div class="col-sm-10 ">
                              <div>
                                   <input id="ads-yes" type="radio" value="0" name="allow_ads" checked>
                                   <label for="ads-yes">Yes</label>
                              </div>
                              <div>
                                   <input id="ads-no" type="radio" value="1" name="allow_ads">
                                   <label for="ads-no">No</label>
                              </div>
                         </div>
                    </div>
                    <!-- end ads field -->

                    <!-- start button field -->
                    <div class="form-group ms-5">
                         <div class="col-sm-offset-2 col-sm-10">
                              <input type="submit" value="Add Category" class="btn btn-info w-25 mt-3">
                         </div>
                    </div>
                    <!-- end button field -->
               </form>
          </div>
<?php 
     }elseif($do=='insert'){
          if($_SERVER['REQUEST_METHOD']=='POST'){
               echo '<h1 class="text-center">Insert Categories Page</h1>';
               echo '<div class="container">';

               $name=$_POST['name'];
               $description=$_POST['description'];
               $parent=$_POST['parent'];
               $order=$_POST['ordering'];
               $visible=$_POST['visibility'];
               $comment=$_POST['allow_comment'];
               $ads=$_POST['allow_ads'];

               if(!empty($name)){
                    $stmt=$con->prepare("select name from categories where name=? limit 1");
                    $stmt->execute(array($name));
                    $check=$stmt->rowCount();
                    if($check != 1){
                         $stmt=$con->prepare("insert into categories(name,description,parent,ordering,visibility,allow_comment,allow_ads)
                         values(:zname,:zdesc,:zparent,:zorder,:zvisible,:zcomment,:zads)");
                         $stmt->execute(array(
                              'zname'   => $name,
                              'zdesc'   => $description,
                              'zparent' => $parent,
                              'zorder'  => $order,
                              'zvisible'=> $visible,
                              'zcomment'=> $comment,
                              'zads'    => $ads
                         ));
                         $row=$stmt->rowCount();
                         if($row != 0){
                              $themsg='<div class="alert alert-success">'.$row.' Record Insearted</div>';
                              rdh($themsg);
                         }else{
                              echo '<div class="alert alert-danger">Sorry there is something roung!</div>';
                         }
                    }else{
                         $themsg="<div class='alert alert-warning'>Sorry this name is a duplicate!</div>";
                         rdh($themsg,'back');
                    }
               }else{
                    $themsg='<div class="alert alert-danger">Sorry the name faild must not be empty!</div>';
                    rdh($themsg,'back');
               }
          }
     }elseif($do == 'edit'){
          $catid='';
          if(isset($_GET['catid']) && is_numeric($_GET['catid'])){
               $catid=intval($_GET['catid']);
               $stmt=$con->prepare("select * from categories where id=?");
               $stmt->execute(array($catid));
               $count=$stmt->rowCount();
               $row=$stmt->fetch();
               if($count > 0){ ?>
                    <h1 class="text-center">Edit Category</h1>
                    <div class="container">
                         <form class="form-horizontal ms-5" action="categories.php?do=update" method="POST">
                              <!-- start name field -->
                              <div class="form-group ms-5">
                                   <label class="col-sm-2 control-label">Username</label>
                                   <div class="col-sm-10">
                                        <input type="text" name="name" class="col-sm-10 form-control" required placeholder="Name Of The Category" value="<?php echo $row['name']; ?>">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                   </div>
                              </div>
                              <!-- end name field -->

                              <!-- start description field -->
                              <div class="form-group ms-5">
                                   <label class="col-sm-2 control-label">Discription</label>
                                   <div class="input-group mb-3">
                                        <input type="text" name="description" class="form-control" placeholder="Write Description About The Categories" value="<?php echo $row['description']; ?>">                       
                                   </div>
                              </div>
                              <!-- end description field -->

                              <!-- start parent field -->
                              <div class="form-group ms-5">
                                   <label class="col-sm-2 control-label">Parent</label>
                                   <div class="col-sm-10">
                                        <select name="parent">
                                             <option value="0">None</option>
                                             <?php
                                             $stmt=$con->prepare("select * from categories where parent=0 order by ordering asc");
                                             $stmt->execute();
                                             $cats=$stmt->fetchAll();
                                             foreach($cats as $c){
                                                  echo '<option value="'.$c["id"].'"';
                                                       if($row['parent'] == $c['id']){
                                                            echo 'selected';
                                                       }
                                                  echo '>'.$c["name"].'</option>';
                                             }
                                             ?>
                                        </select>
                                   </div>
                              </div>
                              <!-- end parent field -->

                              <!-- start ordering field -->
                              <div class="form-group ms-5">
                              <label class="col-sm-2 control-label">Ordering</label>
                              <div class="col-sm-10">
                                   <input type="text" name="ordering"  class="form-control" placeholder="Write The Ordering Of Category" value="<?php echo $row['ordering']; ?>">
                              </div>
                              </div>
                              <!-- end ordering field -->

                              <!-- start visibility field -->
                              <div class="form-group form-group-lg">
                                   <label class="col-sm-2 control-label">Visible</label>
                                   <div class="col-sm-10 ">
                                        <div>
                                             <input id="vis-yes" type="radio" value="0" name="visibility" <?php if($row['visibility']==0){echo 'checked';} ?>>
                                             <label for="vis-yes">Yes</label>
                                        </div>
                                        <div>
                                             <input id="vis-no" type="radio" value="1" name="visibility" <?php if($row['visibility']==1){ echo 'checked';} ?>>
                                             <label for="vis-no">No</label>
                                        </div>
                                   </div>
                              </div>
                              <!-- end visibility field -->

                              <!-- start commennting field -->
                              <div class="form-group form-group-lg">
                                   <label class="col-sm-2 control-label">Allow Comments</label>
                                   <div class="col-sm-10 ">
                                        <div>
                                             <input id="comm-yes" type="radio" value="0" name="allow_comment" <?php if($row['allow_comment']==0){echo 'checked';} ?>>
                                             <label for="comm-yes">Yes</label>
                                        </div>
                                        <div>
                                             <input id="comm-no" type="radio" value="1" name="allow_comment" <?php if($row['allow_comment']==1){echo 'checked';} ?>>
                                             <label for="comm-no">No</label>
                                        </div>
                                   </div>
                              </div>
                              <!-- end commenting field -->

                              <!-- start ads field -->
                              <div class="form-group form-group-lg">
                                   <label class="col-sm-2 control-label">Allow Adds</label>
                                   <div class="col-sm-10 ">
                                        <div>
                                             <input id="ads-yes" type="radio" value="0" name="allow_ads" <?php if($row['allow_ads']==0){echo 'checked';} ?>>
                                             <label for="ads-yes">Yes</label>
                                        </div>
                                        <div>
                                             <input id="ads-no" type="radio" value="1" name="allow_ads" <?php if($row['allow_ads']==1){echo 'checked';}?>>
                                             <label for="ads-no">No</label>
                                        </div>
                                   </div>
                              </div>
                              <!-- end ads field -->

                              <!-- start add button -->
                              <input type="submit" class="btn btn-primary" value="Edit Categories">
                              <!-- end add button -->
                         </form>
                    </div>
<?php          }else{
                    $themsg='<div class="alert alert-danger">Sorry there is no such ID!</div>';
                    rdh($themsg,'back');
               }
          }
     }elseif($do=='update'){
          if($_SERVER['REQUEST_METHOD']=='POST'){
               echo '<h1 class="text-center">Update Categories</h1>';
               
               echo '<div class="container">';
               $id=$_POST['id'];
               $name=$_POST['name'];
               $description=$_POST['description'];
               $parent=$_POST['parent'];
               $ordering=$_POST['ordering'];
               $visibility=$_POST['visibility'];
               $allow_comment=$_POST['allow_comment'];
               $allow_ads=$_POST['allow_ads'];

               $stmt=$con->prepare("update categories 
               set 
                    name=? , 
                    description=? ,
                    parent=?,
                    ordering=? ,
                    visibility=? ,
                    allow_comment=? ,
                    allow_ads=? 
               where 
                    id=? limit 1");
               $stmt->execute(array($name,$description,$parent,$ordering,$visibility,$allow_comment,$allow_ads,$id));

               $themsg='<div class="alert alert-success">The Information is updated.</div>';
               rdh($themsg,'back');
               echo '</div>';
          }else{
               $themsg='<div class="alert alert-danger">Sorry you can not enter to this page directly!</div>';
               rdh($themsg);
          }
     }elseif($do='delete'){
          if(isset($_GET['catid']) && is_numeric($_GET['catid'])){
               echo '<h1 class="text-center">Delete Categories Page</h1>';
               echo '<div class="container">';
               $catid=intval($_GET['catid']);
               $stmt=$con->prepare("select * from categories where id=? limit 1");
               $stmt->execute(array($catid));
               $count=$stmt->rowCount();
               $row=$stmt->fetch();
               if($count > 0){
                    $stmt=$con->prepare("delete from categories where id=? limit 1");
                    $stmt->execute(array($catid));
                    $themsg='<div class="alert alert-success">The category is deleted</div>';
                    rdh($themsg,'back');
               }
          }
          echo '</div>';
     }

     include 'includes/templates/footer.php';
}