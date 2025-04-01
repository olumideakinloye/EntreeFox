<?php
function split_url(){
    $url = isset($_GET['url']) ? $_GET['url'] : "Home";
    $url =explode("/", filter_var(trim($url, "/"), FILTER_SANITIZE_URL));
    return $url;
}
$root = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . $_SERVER['PHP_SELF'];
define("ROOT", str_replace("Router.php" , "", $root));
$URL = split_url();
if(file_exists($URL[0] . ".php")){

    // echo $URL[0];
    require($URL[0] . ".php");
}else{
    require("404.php");

}