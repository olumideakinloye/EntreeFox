<?php
include("autoload.php");
session_start();
$Post = new Post();
$ERROR = "";
$ERROR2 = "";
$likesss = false;
$notification = $Post->get_recent_notification($_SESSION['entreefox_userid']);
$old_notification = $Post->get_old_notification($_SESSION['entreefox_userid']);
$seen = $Post->set_seen_notification($_SESSION['entreefox_userid']);
$Post->maintain_seen_notification($_SESSION['entreefox_userid']);
$ERROR = "No recent notificatios";
$ERROR2 = "No old notificatios";

$state = "Online";
$date = date("Y-m-d H:i:s");
$query2 = "update user_state set state = '$state', date = '$date' where userid = '$_SESSION[entreefox_userid]' limit 1";
$DB = new Database();
$DB->save($query2);
?>
<script>
    document.getElementById('notification_container').style.height = `${window.innerHeight - 40}px`

    function cancel(fuck, these) {
        fuck.preventDefault();
        var link = these.getAttribute("href");
        history.pushState(null, "", link);
        document.getElementById("cancel").style.display = "flex";
    }

    function ajax_send_V2(data, element) {
        var ajax = new XMLHttpRequest();

        ajax.addEventListener("readystatechange", function() {
            if (ajax.readyState == 4 && ajax.status == 200) {
                response_V2(ajax.responseText, element);
            }
        });
        data = JSON.stringify(data);

        ajax.open("post", "<?= ROOT ?>ajax.php", true);
        // alert(link);
        ajax.send(data, element);
    }

    function response_V2(result, element) {
        if (result != "") {
            // alert(result);
            var obj = JSON.parse(result);
            if (typeof obj.action != "undefined") {
                if (obj.action == "delete_order") {
                    document.getElementById("section" + obj.id + "confirmation").style.display = "none";
                    document.getElementById("cancel").style.display = "none";
                } else if (obj.action == "follow") {
                    if (obj.state == "inserted") {
                        const button = document.getElementById(`follow${obj.id}button`);
                        button.classList.add('following');
                        button.classList.remove('follow_input');
                        button.value = "Following";
                    } else if (obj.state == "removed") {
                        const button = document.getElementById(`follow${obj.id}button`);
                        button.classList.remove('following');
                        button.classList.add('follow_input');
                        button.value = "Follow back";
                    }
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
        ajax_send_V2(data, fuck);
    }

    function follow(userid) {
        var link = `<?= ROOT ?>Follow/${userid}/<?php echo $_SESSION['entreefox_userid'] ?>`;
        var data = {};
        data.link = link;
        data.action = "follow";
        fuck = "";
        ajax_send_V2(data, fuck);
    }

    function cancel_close() {
        var link = "<?= ROOT . "notification" ?>";
        history.pushState(null, "", link);
        document.getElementById("cancel").style.display = "none";
    }

    // const error = document.getElementById("error");
    // const error_shell = document.getElementById("error_shell");
    // if(error && error_shell){
    //     error.addEventListener("click", () => {
    //         error_shell.style.display = "none";
    //     });
    // }

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
</script>
<div class="notification_container" id="notification_container">
    <div class="cancel_container" id="cancel" style="display: none;">
        <div class="cancel_box">
            <p><b>Are you sure you want to cancel this order</b></p>
            <div class="buttons">
                <button onclick="remove_order()">Yes</button>
                <button onclick="cancel_close()">No</button>
            </div>
        </div>
    </div>
    <div class="recent_notifications">
        <form>
            <fieldset>
                <legend>Recent Notifications</legend>
            </fieldset>
        </form>
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
            echo "<h1><b>$ERROR</b></h1>";
        }
        ?>
    </div>
    <div class="old_notifiations">
        <form>
            <fieldset>
                <legend>Old Notifications</legend>
            </fieldset>
        </form>
        <?php
        if ($old_notification) {
            foreach ($old_notification as $notification_row) {
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
            echo "<h1><b>$ERROR2</b></h1>";
        }
        ?>
    </div>
</div>
<style>
    .following {
        background-color: rgb(240, 240, 240) !important;
        color: black !important;
        box-shadow: inset 0 0 0 2px black !important;
        border: none !important;
    }

    .follow_input {
        background-color: rgb(255, 172, 124) !important;
        color: white !important;
        /* height: fit-content; */
        padding: 10px 7px !important;
        width: min-content !important;
        border-radius: 30px !important;
        z-index: 10 !important;
        transition: all 0.2s !important;
        border: none !important;
    }
</style>