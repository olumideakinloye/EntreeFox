<?php

$URL = explode("/", $data->link);
$post_id = $URL[6];
$type = $URL[5];
$action = "";
if(isset($post_id) && isset($type) && is_numeric($post_id)){
    if($type = "post"){
    $post = new Post();
    $action = $post->like_post($post_id, $type, $_SESSION['entreefox_userid']);
    }
}
$obj = (object)[];
$obj->action = $action;
$obj->id = $post_id;
echo json_encode($obj);