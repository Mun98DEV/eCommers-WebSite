<?php
include 'init.php'; ?>
<div class="container">

     <h1 class="text-center"><?php echo str_replace('%20',' ',$_GET['pagename']) ?></h1>
     <div class="row">
          <?php
          $catid=$_GET['pageid'];
          $stmt=$con->prepare("select * from items where cat_id=?");
          $stmt->execute(array($catid));
          $cats=$stmt->fetchAll();
               foreach($cats as $cat){
                    echo '<div class="col-md-3 col-sm-6">';
                         echo '<div class="thumbnail">';
                              echo '<span class="bg-primary span1">'.$cat['price'].'</span>';
                              echo '<img class="img-responsive" src="img1.png" alt="erro">';
                                   echo '<div class="caption">';
                                        echo '<h3>'.$cat['name'].'</h3>';
                                        echo '<p>'.$cat['description'].'</p>';
                                        echo '<p class="pull-right font-weight-bolder">'.$cat['add_date'].'</p>';
                                   echo '</div>';
                         echo '</div>';
                    echo '</div>';
               }
          ?>
     </div>
</div>
<?php include 'includes/templates/footer.php'; ?>