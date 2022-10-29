<?php

function gettitle() {
     global $pagetitle;
     if (isset($pagetitle)) {
          echo $pagetitle;
     } else {
          echo 'default';
     }
}

function rdh($themsg, $url=null,$seconds=3){
     if($url === null){
          $url='index.php';
     }else{
          if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != ''){
               $url=$_SERVER['HTTP_REFERER'];
          }else{
               $url='index.php';
          }
     }

     echo '<div class="container">';
     echo $themsg;
     echo '<div class="alert alert-success">'. 'You Will Be Redirected To Home Page After ' . $seconds . ' Seconds' .'</div>';
     echo '</div>';
     header("refresh:$seconds;url=$url");
     exit();
}

