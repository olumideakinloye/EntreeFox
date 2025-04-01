<?php
include("autoload.php");

$login = new Login();
$user_data = $login->check_login($_SESSION['entreefox_userid']);

$User_data = $user_data;

$Post = new Post();
$ERROR = "";
$likesss = false;
$notification = $Post->get_notification($_SESSION['entreefox_userid']);
$ERROR = "No recent notification";


$state = "Online";
$query2 = "update user_state set state = '$state' where userid = '$_SESSION[entreefox_userid]' limit 1";
$DB = new Database();
$DB->save($query2);
$date = date("Y-m-d H:i:s");
$query3 = "update user_state set date = '$date' where userid = '$_SESSION[entreefox_userid]' limit 1";
$DB = new Database();
$DB->save($query3);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo ROOT ?>notification_stylesheet.css">
    <link rel="shortcut icon" href="<?php echo ROOT ?>JHHW9351.PNG" type="image/x-icon">
    <title>Notification | Entreefox</title>
    <script>
        function cancel(fuck, these) {
            fuck.preventDefault();
            var link = these.getAttribute("href");
            history.pushState(null, "", link);
            document.getElementById("cancel").style.display = "flex";
        }

        function ajax_send(data, element) {
            var ajax = new XMLHttpRequest();

            ajax.addEventListener("readystatechange", function() {
                if (ajax.readyState == 4 && ajax.status == 200) {
                    response(ajax.responseText, element);
                }
            });
            data = JSON.stringify(data);

            ajax.open("post", "<?= ROOT ?>ajax.php", true);
            // alert(link);
            ajax.send(data, element);
        }

        function response(result, element) {
            if (result != "") {
                // alert(result);
                var obj = JSON.parse(result);
                if (typeof obj.action != "undefined") {
                    if (obj.action == "delete_order") {
                        document.getElementById("section" + obj.id + "confirmation").style.display = "none";
                        document.getElementById("cancel").style.display = "none";
                    }
                }
            }
        }

        function remove_order() {
            var link = window.document.location.href;
            var data = {};
            data.link = link;
            data.action = "cancel_order";
            fuck = "";
            ajax_send(data, fuck);
        }

        function cancel_close() {
            document.getElementById("cancel").style.display = "none";
        }

        const error = document.getElementById("error");
        const error_shell = document.getElementById("error_shell");
        error.addEventListener("click", () => {
            error_shell.style.display = "none";
        });

        function postmenu() {
            const elements = document.querySelectorAll(".postmenu");

            elements.forEach((element) => {
                element.addEventListener("click", () => {
                    element.src = "close2.png";

                    if (element.src == "close2.png") {
                        element.src = "dots.png";
                    }
                });
            });
        }

        function notification() {
            document.getElementById("notification").style.width = "100vw";
            document.getElementById("notification").style.transition = "ease-in-out 0.2s";
            document.getElementById("notification").style.opacity = 1;
            document.getElementById("content").style.display = "none";
        }

        function back() {
            document.getElementById("notification").style.width = 0;
            document.getElementById("notification").style.opacity = 0;
            document.getElementById("content").style.display = "flex";
        }

        function menu() {
            document.getElementById("menu").style.display = "none";
            document.getElementById("close").style.display = "block";
            const menu_container = document.querySelector(".menu_container");
            menu_container.classList.toggle("close");

            const menu_section = document.querySelector(".menu_section");
            menu_section.classList.toggle("close");

            const menu = document.querySelector(".menu");
            menu.classList.toggle("close");
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
        }


        var lastScrollTop = 200;
        const header = document.getElementById("header");
        // const about = document.getElementById("About");
        window.addEventListener("scroll", function() {
            var scrollTop = window.pageYoffset || document.documentElement.scrollTop;
            if (scrollTop > lastScrollTop) {
                document.getElementById("header").style.top = "-80px"
            } else {
                document.getElementById("header").style.top = "0"
            }
            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
        });
    </script>
</head>

<body>
    <div class="cancel_container" id="cancel" style="display: none;">
        <div class="cancel_box">
            <p><b>Are you sure you want to cancel this order</b></p>
            <div class="buttons">
                <button onclick="remove_order()">Yes</button>
                <button onclick="cancel_close()">No</button>
            </div>
        </div>
    </div>
    <!-- <?php include("Head.php") ?> -->
    <?php
    if ($notification) {
        foreach ($notification as $notification_row) {
            $user = new user();
            $notification_user = $user->get_user($notification_row['notifier_id']);
            if ($notification_row['activity'] == "like") {
                include("like_notofication.php");
            } elseif ($notification_row['activity'] == "comment") {
                include("comment_notification.php");
            } elseif ($notification_row['activity'] == "reply") {
                include("reply_notification.php");
            } elseif ($notification_row['activity'] == "follow") {
                include("follow_notification.php");
            } elseif ($notification_row['activity'] == "message") {
                include("message_notification.php");
            } elseif ($notification_row['activity'] == "buyers_request") {
                include("buyer_notification.php");
            } elseif ($notification_row['activity'] == "delivery_confirmation") {
                include("buyer_confirmation.php");
            }
        }
    } else {
        echo "<h1 style='text-align: center; padding-top: 30vh;'><b>$ERROR</b></h1>";
    }
    ?>

</body>

</html>