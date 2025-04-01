<?php
// include("Post.php");
// include("Classes/signup.php");
include("Classes/connect.php");
include("Classes/login_&_signup_class.php");
include("Classes/user.php");
include("Classes/Post.php");
include("Classes/image.php");
include("Classes/time.php");
include("Classes/shoping_functions.php");
include("Classes/messages_function.php");
// include("log_in.php");
if (!defined("ROOT")) {
    $root = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . $_SERVER['PHP_SELF'];
    define("ROOT", str_replace("Router.php", "", $root));
}
$Home_folder = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . "/" . "EntreeFox/";
define("entreefox", $Home_folder);

session_start();
