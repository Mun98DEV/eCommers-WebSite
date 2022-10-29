<?php

$do='';

if(isset($_GET['do'])){
     $do=$_GET['do'];
}else{
     $do= 'manage';
}


if($do=='manage'){
     echo 'hello you are in manage page';
}elseif($do=='add'){
     echo'hello you are in add page';
}elseif($do=='contact'){
     echo 'hello you are in contact page';
}else{
     echo $do;
}