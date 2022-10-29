<?php

session_start();
if(isset($_SESSION['username'])){
     $pagetitle='dashboard';     
     include 'init.php'; ?>
     <div class="container text-center home-stats">
          <h1>Dashboard Page</h1>
          <div class="row">
               <div class="col-md-3">
                    <div class="stat st-members">
                         <i class="fa fa-user"></i>
                         <div class="info">
                              Total Members
                              <span>
                              <a href="members.php?do=manage">
                                   <?php
                                   $stmt=$con->prepare("select count(userid) from users");
                                   $stmt->execute();
                                   echo $stmt->fetchColumn();?> </a>
                              </span>
                         </div>
                    </div>
               </div>
               <div class="col-md-3">
                    <div class="stat st-pending">
                         <i class="fa fa-user-plus" style="left: 10px;font-size:85px;"></i>
                         <div class="info">
                         Pending Members
                         <span>
                              <a href="members.php?do=manage&page=pending" style="text-decoration: none;color:#FFF;">
                         <?php
                         $stmt=$con->prepare("select count(userid) from users where regstatus=0");
                         $stmt->execute();
                         $col=$stmt->fetchColumn();
                         echo $col; ?></a> </span>
                         </div>
                    </div>
               </div>
               <div class="col-md-3">
                    <div class="stat st-items">
                         <i class="fa fa-tag"></i>
                         <div class="info">
                         Total Items
                         <span>
                              <a href="items.php?do=manage" style="text-decoration: none;color:#FFF;">
                              <?php
                              $stmt=$con->prepare("select count(name) from items");
                              $stmt->execute();
                              $col=$stmt->fetchColumn();
                              echo $col;
                              ?>
                              </a>
                         </span>
                         </div>
                    </div>
               </div>
               <div class="col-md-3">
                    <div class="stat st-comments">
                         <i class="fa fa-comments"></i>
                         <div class="info">
                         Total Comments
                         <span>
                              <a href="comments.php?do=manage" style="text-decoration: none;color:white;">
                                   <?php  
                                   $stmt=$con->prepare("select count(c_id) from comments where status=1");
                                   $stmt->execute();
                                   $count=$stmt->fetchColumn();
                                   echo $count;
                                   ?>
                              </a>
                         </span>
                         </div>
                    </div>
               </div>
          </div>
     </div>

     <div class="container lasts text-center">
          <div class="row">
               <div class="col-sm-6">
                    <div class="panel panel-default last">
                         <div class="panel-heading">
                              <span><i class="fa fa-users"></i>
                              Lasts <?php $numlast=4;
                              echo $numlast; ?> Registerd Users</span>
                              <span  class="toggle-info pull-right"><i class="fa fa-minus fa-larg  " style="cursor: pointer;"></i></span>
                         </div>                              
                         <div class="panel-body">
                              <div style="margin-top: 5px;">
                                   <ul class="list-unstyled last-users">
                                        <?php
                                        $stmt=$con->prepare("select * from users where groupid=0 order by userid desc limit $numlast");
                                        $stmt->execute();
                                        $row=$stmt->fetchAll();
                                        foreach($row as $rows){
                                             echo "<li>";
                                                  echo '<span class="pull-left" style="margin-left:30px">'. $rows['username'] .'</span>';
                                                  echo '<span class="btn btn-success btn-sm pull-right" style="margin-right:10px">';
                                                  echo '<i class="fa fa-edit"></i><a href="members.php?do=edit&userid=' . $rows['userid'] .'"> Edit</a>';
                                                  echo '</span>';
                                                  if($rows['regstatus']==0){
                                                       echo '<span class="btn btn-info btn-sm pull-right" style="margin-right:5px;">';
                                                       echo '<i class="fa fa-thin fa-pause"></i><a href="members.php?do=activate&userid=' . $rows['userid'] .'"> Activate</a>';
                                                       echo '</span>';
                                                  }
                                             echo "</li>";
                                        }?>
                                   </ul>
                              </div>
                         </div>
                    </div>
               </div>
               <div class="col-sm-6">
                    <div class="panel panel-default last">
                         <div class="panel-heading">
                              <span><i class="fa fa-tag"></i></span>
                              Lasts Items
                              <span  class="toggle-info pull-right"><i class="fa fa-minus fa-larg  " style="cursor: pointer;"></i></span>
                         </div>
                         <div class="panel-body">
                              <div style="margin-top: 5px;">
                                   <ul class="list-unstyled">
                                        <?php 
                                        $stmt=$con->prepare("select * from items where approve=0 order by item_id desc");
                                        $stmt->execute();
                                        $items=$stmt->fetchAll();
                                        foreach($items as $item){
                                             echo '<li style="height:39px;margin-bottom:8px;">';
                                                  echo '<div class="pull-left text-center" style="">'.$item['name'].'</div>';
                                                  echo '<div class="pull-right mr-5">';
                                                       echo '<a href="items.php?do=edit&item_id='.$item['item_id'].'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>';
                                                       if($item['approve']==0){
                                                            echo '<a href="items.php?do=approve&item_id='.$item['item_id'].'" class="btn btn-info btn-sm ml-2"><i class="fa fa-check"></i> Approve</a>';
                                                       }
                                                  echo '</div>';
                                             echo '</li>';
                                        }
                                        ?>
                                   </ul>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
          <div class="container lasts text-center">
               <div class="row">
                    <div class="col-sm-6">
                         <div class="panel panel-default last">
                              <div class="panel-heading">
                                   <span><i class="fa fa-comments"></i></span>Lastes Comment
                              </div>                              
                              <div class="panel-body text-left">
                                   <ul class="list-unstyled last-users w-5">
                                        <?php
                                        $stmt=$con->prepare("select comments.*,users.username from comments
                                        inner join users
                                        on users.userid=comments.user_id");
                                        $stmt->execute();
                                        $rows=$stmt->fetchAll();
                                        foreach($rows as $row){
                                             echo '<li>';
                                             echo '<div>'.$row['username'].'</div>';
                                             echo '<div>'.$row['comment'].'</div>';
                                             echo '</li>';
                                             
                                        }
                                        ?>
                                   </ul>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>

     <?php include 'includes/templates/footer.php';

}else{
     header('location: index.php');
     exit();
}