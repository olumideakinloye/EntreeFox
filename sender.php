<?php
$image = "";
if (isset($message['file'])) {
    if (file_exists($message['file'])) {
        $image = $message['file'];
    }
}
$time = new Time();
?>

<?php
$currentDate = new DateTime();
$pastDate = new DateTime($message['date']);
// Calculate the difference in seconds between the past time and now
$diffInSeconds = $currentDate->getTimestamp() - $pastDate->getTimestamp();
?>
<section class="mess_section messager_section" data-state="<?= $message['state'] ?>" data-time="<?= $message['date'] ?>" data-idess="<?= $message['id'] ?>" data-id="<?= $message['msgid'] ?>" data-receiver="<?= $message['deleted_receiver'] ?>" data-sender="<?= $message['deleted_sender'] ?>" data-image="<?= $image ?>">
    <div class="date top_date">
        <p id="elapsed-<?= $message['msgid'] ?>-date" data-time="<?= $time->get_time3($message['date']) ?>">
            <?php
            $currentDate = new DateTime();
            $pastDate = new DateTime($message['date']);
            // Calculate the difference in seconds between the past time and now
            $diffInSeconds = $currentDate->getTimestamp() - $pastDate->getTimestamp();
            ?>
        </p>
    </div>
    <div class="messager_section_box" id="messager_<?= $message['msgid'] ?>section_box">

        <div class="message" id="long_<?= $message['msgid'] ?>_press"  data-state="<?= $message['state'] ?>" data-time="<?= $message['date'] ?>" data-idess="<?= $message['id'] ?>" data-id="<?= $message['msgid'] ?>" data-receiver="<?= $message['deleted_receiver'] ?>" data-sender="<?= $message['deleted_sender'] ?>" data-image="<?= $image ?>">
            <?php
            if (file_exists($message['file'])) {
                echo "<img src='" . ROOT . "$message[file]' >";
            }
            ?>
            <?php
            if (!empty($message['message'])) {
                $padding = 0;
                if (file_exists($message['file'])) {
                    $padding = "10px";
                }
                echo "<p style='padding: $padding 0 0 0;' id='message_$message[msgid]_text'>" . nl2br(htmlspecialchars($message['message'])) . "</p>";
            }
            ?>
        </div>
    </div>
    <div class="date">
        <p id="elapsed-<?= $message['msgid'] ?>-time">
            <?php
            $currentDate = new DateTime();
            $pastDate = new DateTime($message['date']);
            // Calculate the difference in seconds between the past time and now
            $diffInSeconds = $currentDate->getTimestamp() - $pastDate->getTimestamp();
            ?>
        </p>
    </div>
</section>
<script>
    // PHP calculated difference (time elapsed in seconds)
    let elapsedTime = <?php echo $diffInSeconds; ?>;

    // Function to format time as "Years : Months : Days : Hours : Minutes : Seconds"
    function formatElapsedTime(seconds) {
        const years = Math.floor(seconds / (365 * 24 * 60 * 60));
        seconds %= 365 * 24 * 60 * 60;
        const days = Math.floor(seconds / (24 * 60 * 60));
        seconds %= 24 * 60 * 60;
        const hours = Math.floor(seconds / (60 * 60));
        seconds %= 60 * 60;
        const minutes = Math.floor(seconds / 60);
        seconds = seconds % 60;

        return `${years}y : ${days}d : ${hours}h : ${minutes}m : ${seconds}s`;
    }

    // Update the elapsed time every second
    function updateElapsedTime() {
        document.getElementById("elapsed-<?= $message['msgid'] ?>-time").innerHTML = formatElapsedTime(elapsedTime);
        elapsedTime++; // Increment elapsed time by 1 second
    }

    // Start the real-time elapsed time interval
    setInterval(updateElapsedTime, 1000);

    // Initial display
    updateElapsedTime();
</script>