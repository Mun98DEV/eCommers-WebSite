<div style="margin-left:25px;font-weight:bolder;height:30px;">
<?php
if(isset($_SESSION['user'])){
  echo 'Hello '. $_SESSION['user'];
  echo '<a href="profile.php"> My Profile </a>';
  echo '<a href="new_ad.php"> New AD </a>';
}else{
echo '<a href="login.php">Login</a>';
}
?>


</div>

<nav class="navbar navbar-expand-lg navbar-light bg-info">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarScroll">
    <ul class="navbar-nav mr-auto my-2 my-lg-0 navbar-nav-scroll pull-right" style="max-height: 100px;">
      <li class="nav-item active">
        <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
          Categories
        </a>
        <ul class="dropdown-menu">
          <?php
          $stmt=$con->prepare("select * from categories");
          $stmt->execute();
          $cats=$stmt->fetchAll();
          foreach($cats as $cat){
            echo '<li>';
                echo '<a href="categories.php?pagename='.$cat['name'].'&pageid='. $cat['id'] .'">'. $cat['name'] .'</a>';
            echo '</li>';
          }
          
          ?>
        </ul>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="logout.php">Logout</a>
      </li>
    </ul>
    <!-- <form class="d-flex">
      <input class="form-control mr-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-danger" type="submit">Search</button>
    </form> -->
  </div>
</nav>