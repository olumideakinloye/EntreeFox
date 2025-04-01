<?php
// $MSG = new Message;
$unseen = $MSG->unseen($_SESSION['entreefox_userid'], $chat_id)
?>
<div class="messagee" onclick="get_chat(this)" data-name = <?=$chater_info['user_name']?>>
    <div class="profile" style="background-image: url(<?= ROOT . $image = file_exists($chater_info["profile_image"]) ? $chater_info["profile_image"] : "Images/profile.png" ?>);"></div>
    <div class="text">
        <h2><?=$chater_info['user_name']?></h2>
        <p class="message">
            <?php
            if ($chat[0]['message'] != "") {
                echo $chat[0]['message'];
            } elseif (file_exists($chat[0]['file'])) {
                echo $chater_info["user_name"] . " sent you a picture";
            } else {
                echo "";
            } ?>
        </p>
    </div>
    <div class="unseen">
        <p class="time">
            <?=$time->eval_message_time($chat[0]['date']);?>
        </p>
        <?php if ($unseen !== 0) {
        ?>
            <p class="unseen_num">
                <?= $unseen ?>
            </p>
        <?php } ?>

    </div>
</div>