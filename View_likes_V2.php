<?php
include("autoload.php");

$login = new Login();
$User_data = $login->check_login($_SESSION['entreefox_userid']);
$user_data = $User_data;
$Post = new Post();
$ERROR = "";
$likesss = false;
if (isset($URL[2]) && isset($URL[1])) {

    $row = $Post->get_one_posts($URL[2]);
    if (!$row) {
        $ERROR = "No such post was found";
    } else {
        if ($row['userid'] != $_SESSION['entreefox_userid']) {
            $ERROR = "Access denied";
        }
    }
    $likesss = $Post->get_likes($URL[2], $URL[1]);
    if (empty($likesss)) {
        $ERROR = "No likes";
    }
} else {

    $ERROR = "No likes";
}

$state = "Online";
$date = date("Y-m-d H:i:s");
$query2 = "update user_state set state = '$state', date = '$date' where userid = '$_SESSION[entreefox_userid]' limit 1";
$DB = new Database();
$DB->save($query2);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Likes || Entreefox</title>
    <link rel="shortcut icon" href="<?php echo ROOT ?>JHHW9351.PNG" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo ROOT ?>view_comments_V2_stylesheet.css">
    <link rel="stylesheet" href="<?php echo ROOT ?>comments_V2_stylesheet.css">
    <link rel="stylesheet" href="<?php echo ROOT ?>View_likes.css">
    <script>
        function menu() {
            document.getElementById("menu").style.display = "none";
            document.getElementById("close").style.display = "block";
            const menu_container = document.querySelector(".menu_container");
            menu_container.classList.toggle("close");

            const menu_section = document.querySelector(".menu_section");
            menu_section.classList.toggle("close");

            const menu = document.querySelector(".menu");
            menu.classList.toggle("close");
            document.body.classList.add('no-scroll');
        }

        function close_menu() {
            document.getElementById("close").style.display = "none";
            document.getElementById("menu").style.display = "block";
            const menu_container = document.querySelector(".menu_container");
            menu_container.classList.toggle("close");

            const menu_section = document.querySelector(".menu_section");
            menu_section.classList.toggle("close");

            const menu = document.querySelector(".menu");
            menu.classList.toggle("close");
            document.body.classList.remove('no-scroll');
        }
    </script>
</head>

<body>
    <header id="header">
        <?php
        if (isset($_SERVER['HTTP_REFERER'])) {
            $return_to = $_SERVER['HTTP_REFERER'];
        } else {
            $return_to = "Home";
        }
        ?>
        <div class="left">
            <a href="<?php echo $return_to ?>"><img src="<?php echo ROOT ?>arrow.png"></a>
            <h1>Comments</h1>
        </div>
        <div class="top">
            <img src="<?php echo ROOT ?>close2.png" id="close" style="display: none;" onclick="close_menu()" />
            <img src="<?php echo ROOT ?>menu.png" id="menu" onclick="menu()" />
        </div>
    </header>
    <?php include("Head.php") ?>
    <div class="view_likes_container" style="padding-bottom: 50px;">

        <?php
        $user = new user();

        if ($ERROR == "") {
            if (is_array($likesss)) {
                foreach ($likesss as $row) {
                    $like_row = $user->get_user($row['userid']);
                    include('likes.php');
                }
            }
        } else {
            echo "<h1 style='text-align: center; padding: 10px 0;'><b>$ERROR</b></h1>";
        }

        ?>
    </div>
    <style>
        .menu_container {
            z-index: 200;
            bottom: 0;
            /* background-color: bisque;... */
        }

        .menu {
            bottom: 0;
            position: fixed;
            padding-bottom: 1rem;
            /* background-color: aqua; */
            padding-bottom: 0;
        }

        header {
            z-index: 110;
        }
    </style>
    <script>
        window.addEventListener('load', function() {
            const menu_Y_px = (window.innerHeight - 80);
            const menu_Y_vh = (menu_Y_px / window.innerHeight) * 100;
            document.getElementById('comment_menu').style.height = `${menu_Y_vh}dvh`;
            document.getElementById('menu_container').style.height = `${menu_Y_vh}dvh`;
        });
    </script>
</body>

</html>