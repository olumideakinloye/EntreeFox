<?php

$URL = explode("/", $data->link);
$request_type = $URL[5];
$product_id = $URL[6];
$shop_id = $URL[7];
$response = "";
$sum = 0;
if (isset($_SESSION['entreefox_userid']) && is_numeric($_SESSION['entreefox_userid'])) {
    if (isset($request_type) && isset($shop_id) && isset($product_id)) {
        $shop = new Shopping();
        $action = "add";
        if ($request_type === "product_decrement") {
            $action = "decrement";
        } elseif ($request_type === "product_increment") {
            $action = "increment";
        }
        $response = $shop->Add_to_cart($_SESSION['entreefox_userid'], $product_id, $shop_id, $action);
        $result = $shop->check_cart($product_id, $_SESSION['entreefox_userid']);
        if($result && is_array($result)){
            foreach($result as $key){
                $sum++;
            }
        }
    }
}
// $action = "";
// if(isset($post_id) && isset($type) && is_numeric($post_id)){
//     if($type = "post"){
//     $post = new Post();
//     $action = $post->like_post($post_id, $type, $_SESSION['entreefox_userid']);
//     }
// }
$obj = (object)[];
$obj->action = $response;
$obj->id = $product_id;
$obj->sum = $sum;
echo json_encode($obj);
// // print_r($URL);
// print_r($response);
