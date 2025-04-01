<?php
include("autoload.php");

$data = file_get_contents("php://input");
if($data != ""){
    $data = json_decode($data);
}
// print_r($date)
if(isset($data->action) && $data->action === "like_post"){
    include("Ajax/like.ajax.php");
}
if(isset($data->action) && $data->action === "Add_to_cart"){
    include("Ajax/Add_cart.ajax.php");
}
if(isset($data->action) && $data->action === "follow"){
    include("Ajax/Follow.ajax.php");
}
// echo $data;