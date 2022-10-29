<?php
     session_start();
     $pagetitle='Home Page';
     include "init.php";
     $stmt=$con->prepare("select * from items where approve=1 order by item_id desc");
     $stmt->execute();
     $rows=$stmt->fetchAll();
     echo '<div class="container">';
          echo '<div class="row">';
     foreach($rows as $row){ ?>
               
               <div class="col-md-3 col-sm-6">
                    <div class="thumbnail" style="margin-top: 70px">
                         <span class="span1 bg-primary" style="border-radius: 8px;"><?php echo $row['price'] ?>$</span>
                         <?php
                         if(empty($row['image'])){
                              echo '<p style="margin-top:110px;text-align:left"><strong>There is no image</strong></p>';
                         }else{
                              echo '<img style="width:100px;height:100px;margin-top:35px;text-align:center" src="../uploads/images/'.$row['image'].'" alt="ERROR">';
                         }
                         ?>
                         <div class="caption">
                              <h3><span class="font-weight-bolder">Name: </span><a href="items.php?item_id=<?php echo $row['item_id']?>"><?php echo $row['name'] ?></a></h3>
                              <p style="overflow: scroll;"> <span class="font-weight-bolder">Description: </span><?php echo $row['description'] ?></p>
                              <p><span class="font-weight-bolder">Madi in: </span><?php echo $row['country_made'] ?></p>
                              <p><span class="font-weight-bolder">Date of add: </span><?php echo $row['add_date'] ?></p>
                         </div>
                    </div>
               </div>
          <?php }
          echo  '</div>';
     echo '</div>';
     include "includes/templates/footer.php" ?>