<?php
if(isset($data->name)){
    $user = new User();
    $action = $user->follow($data->name, $_SESSION['entreefox_userid']);
}else{
    $action = "error";
}
// print_r($action);

$obj = (object)[];
$obj->action = $action;
echo json_encode($obj);