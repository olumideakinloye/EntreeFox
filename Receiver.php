<?php
$image = "";
if (isset($message['file'])) {
    if (file_exists($message['file'])) {
        $image = $message['file'];
    }
}
$time = new Time();
?>

<section class="mess_section messagee_section" data-state="<?= $message['state'] ?>" data-time="<?= $message['date'] ?>" data-idess="<?= $message['id'] ?>" data-id="<?= $message['msgid'] ?>" data-receiver="<?= $message['deleted_receiver'] ?>" data-sender="<?= $message['deleted_sender'] ?>" data-image="<?= $image ?>">
    <div class="date top_date">
        <!-- <?= $message['msgid'] ?> -->
        <p id="elapsed-<?= $message['msgid'] ?>-date" data-time="<?= $time->get_time3($message['date']) ?>">
            <?php
            $image = "";
            if (isset($message['file'])) {
                if (file_exists($message['file'])) {
                    $image = $message['file'];
                }
            }
            if (isset($message['state'])) {
                if ($message['state'] != "") {
                    echo $message['state'] . " ";
                }
            }
            $time = new Time();
            $Time = $time->get_time2($message['date']);
            // echo $Time
            ?>
        </p>
    </div>
    <div class="messagee_section_box">

        <div class="message" id="Long<?= $message['msgid'] ?>_Press_for_receiver" data-state="<?= $message['state'] ?>" data-time="<?= $message['date'] ?>" data-idess="<?= $message['id'] ?>" data-id="<?= $message['msgid'] ?>" data-receiver="<?= $message['deleted_receiver'] ?>" data-sender="<?= $message['deleted_sender'] ?>" data-image="<?= $image ?>">
            <?php
            if (file_exists($message['file'])) {
                echo "<img src='" . ROOT . "$message[file]' >";
            }
            ?>
            <?php
            if (!empty($message['message'])) {
                $more = "end";
                if (strlen($message['message']) > 10) {
                    $more = "start";
                } else {
                }
                $padding = 0;
                if (file_exists($message['file'])) {
                    $padding = "10px";
                }
                echo "<p style='padding: $padding 0 0 0; text-align: " . $more . "'>" . nl2br(htmlspecialchars($message['message'])) . "</p>";
            }
            ?>
        </div>
    </div>
    <div class="date">
        <p id="elapsed-<?= $message['msgid'] ?>-time">
            <?php
            $image = "";
            if (isset($message['file'])) {
                if (file_exists($message['file'])) {
                    $image = $message['file'];
                }
            }
            if (isset($message['state'])) {
                if ($message['state'] != "") {
                    echo $message['state'] . " ";
                }
            }
            $time = new Time();
            $Time = $time->get_time2($message['date']);
            // echo $Time
            ?>
        </p>
    </div>

</section>