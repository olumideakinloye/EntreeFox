<?php
include("autoload.php");
if (isset($_SESSION['entreefox_userid']) && is_numeric($_SESSION['entreefox_userid'])) {
    if ($_SERVER['REQUEST_METHOD'] === "GET") {
        $username = $_GET['username'];
        $MSG = new Message();
        $user = new User();
        $time = new Time();
        $user_id = $user->get_user_by_name($username);
        $chats = $MSG->get_messages($_SESSION['entreefox_userid'], $user_id);
        $MSG->set_seen_msg($_SESSION['entreefox_userid'], $user_id);
    }
}
?>
<?php if ($chats) {
    $messages = "";
    foreach ($chats as $message) {
?>
        <div class="messages <?= $message['sender'] === $_SESSION['entreefox_userid'] ? "sender" : "" ?>">
            <div class="msg">
                <p><?= $message['message'] !== "" ? $message['message'] : "" ?></p>
                <?php
                if (file_exists($message['file'])) {
                ?>
                    <img src="<?= entreefox . $message['file'] ?>" alt="">
                <?php  } ?>
                <span class="msg_tail">

                </span>
            </div>
            <?php
            if ($message['sender'] === $_SESSION['entreefox_userid']) {
            ?>
                <p class="time"><?= $message['state'] ?> <?= $time->eval_message_time($message['date']) ?></p>
            <?php
            } else { ?>
                <p class="time"><?= $time->eval_message_time($message['date']) ?> <?= $message['state'] ?> </p>
            <?php
            } ?>

        </div>
<?php }
} 
?>
