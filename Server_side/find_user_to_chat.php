<?php

include("../autoload.php");
$chats = false;
if (isset($_SESSION['entreefox_userid']) && is_numeric($_SESSION['entreefox_userid'])) {
    if ($_SERVER['REQUEST_METHOD'] === "GET") {
        $username = $_GET['username'];
        $MSG = new Message();
        $user = new User();
        $time = new Time();
        $user_ids = $user->get_users_by_name($username, $_SESSION['entreefox_userid']);
        if ($user_ids !== false) {
            $chats = $MSG->find_chats($_SESSION['entreefox_userid'], array_column($user_ids, "userid"));
        } else {
            $chats = false;
            echo "<h1>Can't find user</h1>";
        }
    }
}
?>
<?php
// print_r(array_column($user_ids, "userid"));
if ($chats) {
    foreach ($chats as $message) {
        if (!empty($message[0]['msgid'])) {

            // print_r($message[0]['sender']);
            // echo "<pre>";
            // print_r($chats);
            // echo "</pre>";
            if ($message[0]['sender'] === $_SESSION['entreefox_userid']) {
                $chat_id = $message[0]['receiver'];
            } elseif ($message[0]['receiver'] === $_SESSION['entreefox_userid']) {
                $chat_id = $message[0]['sender'];
            }
            $chater_info = $user->get_data($chat_id);
            $unseen = $MSG->unseen($_SESSION['entreefox_userid'], $chat_id);
            $profile = "Images/profile.png";
            if (file_exists("../" . $chater_info["profile_image"]) && $chater_info["profile_image"] !== "") {
                $profile = $chater_info["profile_image"];
            }
?>
            <div class="messagee" onclick=" get_chat(this)" data-name=<?= $chater_info['user_name'] ?>>
                <div class="profile" style="background-image: url(<?= entreefox . $profile ?>);"></div>
                <div class="text">
                    <h2><?= $chater_info['user_name'] ?></h2>
                    <p class="message">
                        <?php
                        if ($message[0]['message'] != "") {
                            echo $message[0]['message'];
                        } elseif (file_exists($message[0]['file'])) {
                            echo $chater_info["user_name"] . " sent you a picture";
                        } else {
                            echo "";
                        } ?>
                    </p>
                </div>
                <div class="unseen">
                    <p class="time">
                        <?= $time->eval_message_time($message[0]['date']); ?>
                    </p>
                    <?php if ($unseen !== 0) {
                    ?>
                        <p class="unseen_num">
                            <?= $unseen ?>
                        </p>
                    <?php } ?>

                </div>
            </div>
        <?php
        } elseif (isset($message['userid'])) {
            $profile = "Images/profile.png";
            if (file_exists("../" . $message["profile_image"]) && $message["profile_image"] !== "") {
                $profile = $message["profile_image"];
            }
        ?>
            <div class="messagee" onclick=" get_chat(this)" data-name=<?= $message['user_name'] ?>>
                <div class="profile" style="background-image: url(<?= entreefox . $profile ?>);"></div>
                <div class="text">
                    <h2><?= $message['user_name'] ?></h2>
                    <p class="message"><?=$message['About']?></p>
                </div>
            </div>
<?php   }
    }
}
?>