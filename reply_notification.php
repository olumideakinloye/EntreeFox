<section>

    <?php
    $sql = "select userid from posts where postid = '$notification_row[contentid2]' limit 1";
    $DB = new Database();
    $owner = $DB->read($sql);
    $Owner = $owner[0]['userid'];
    // echo $Owner . "<br>";
    // echo $_SESSION['entreefox_userid'];
    if ($Owner != $_SESSION['entreefox_userid']) {
        $cont = $notification_row['contentid2'];
        $href = "comments_V2/" . $cont . "/" . $Owner;
    } else {
        $cont = $notification_row['contentid2'];
        $href = "view_comments_V2/" . $cont;
    }
    ?>
    <a href="<?php echo ROOT . $href ?>" style="color: black; text-decoration: none; background-color: aqua;">
        <div class="section_box">
            <div class="time">
                <p>
                    <?php
                    $shopping = new Shopping();

                    $time = new Time();

                    $Time = $time->get_time2($notification_row['date']);
                    echo $Time
                    ?>
                </p>
            </div>
            <div class="section_box_content">
                <?php
                $image = "profile.png";
                if (file_exists($notification_user['profile_image'])) {
                    $image = $notification_user['profile_image'];
                }
                $sql = "select * from posts where postid = '$notification_row[contentid2]' limit 1 ";
                $DB = new Database();
                $imagee = $DB->read($sql);
                if (!empty($imagee[0]['image'])) {
                    $width = "55vw";
                    // echo "<img src='$newimage' id='image'/>";
                } elseif (!empty($imagee[0]['video'])) {
                    $width = "55vw";
                } else {
                    $width = "70vw";
                }
                ?>
                <img src="<?php echo $image ?>" alt="" />
                <p style="width: <?php echo $width ?>;"><?php echo $notification_user['user_name'] ?> Replied to your comment</p>
                <?php

                if (!empty($imagee[0]['image'])) {
                    $newimage = $imagee[0]['image'];
                    echo "<div class='image' id='image' style='background-image: url($newimage);'></div>";
                    // echo "<img src='$newimage' id='image'/>";
                } elseif (!empty($imagee[0]['video'])) {
                    $newvideo = $imagee[0]['video'];
                    echo "<div class='image' id='image' ><video autoplay muted loop playsinline><source src='" . $newvideo . "' type='video/mp4'></video></div>";                    // echo "<img src='$newimage' id='image'/>";
                }

                 ?>
            </div>
        </div>
    </a>

</section>