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
$server =  $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'];
define("Server", $server);

session_start();
$category =
    [
        "Home Appliances",
        "Home & Kitchen",
        "Home",
        "Office Products",
        "Mobile Phones",
        "Tablets",
        "Mobile Phone Accessories",
        "Men\'s Fashion",
        "Women\'s Fashion",
        "Kid\'s Fashion",
        "Watches",
        "Luggage & Travel Gear",
        "Makeup",
        "Fragrances",
        "Hair Care",
        "Personal Care",
        "Oral Care",
        "Health Care",
        "Television & Video",
        "Home Audio",
        "Camera & Photo",
        "Generators & Portable Power",
        "Computers",
        "Data Storage",
        "Antivirus & Security",
        "Printers",
        "Computer Accessories",
        "Beer, Wine & Sprits",
        "Food Cupboard",
        "Household Cleaning",
        "Car Care",
        "Car Electronics & Accessories",
        "Light & Lightning Accessories",
        "Exterior Accessories",
        "Oil & Fluids",
        "Interior Accessories",
        "Tyre & Rim",
        "Cardio Training",
        "Strength Training",
        "Sporting Accessories",
        "Team Sports",
        "Outdoor & Adventure",
        "Playstation",
        "Xbox",
        "Nintendo",
        "Apperel & Accessories",
        "Diapering",
        "Feeding",
        "Baby & Toddler Toys",
        "Baby Gear",
        "Baby Bathing & Skin Care",
        "Potty Training",
        "Baby Safety Products"
    ];
