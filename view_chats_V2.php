<div class="comment_user" id="<?= $comment_row['comment_id'] ?>">
    <div class="comment_user_info">
        <img src="
        <?php $image = "profile.png";
        if (file_exists($comment_user['profile_image'])) {
            $image = $comment_user['profile_image'];
        }
        echo ROOT . $image; ?>" alt="">
        <div class="comment_text_info">
            <h3><?php
                if ($comment_user['userid'] == $_SESSION['entreefox_userid']) {
                    echo "You";
                } else {
                    echo htmlspecialchars($comment_user['user_name']);
                }
                ?>
            </h3>
            <p>
                <b><?php
                    $time = new Time();
                    $Time = $time->get_time2($comment_row['date']);
                    echo $Time;
                    // echo $comment_row['comment_id'];
                    ?>
                </b>
            </p>
        </div>
    </div>
    <div class="comment_area">
        <p><?php echo htmlspecialchars($comment_row['comments']) ?></p>
    </div>
    <div class="likes_and_reply">
        <?php
        $sql2 = "select * from comments where parent_comment_id = $comment_row[comment_id] order by id desc";
        $DB = new Database();
        $reply = 0;
        $rea2 = $DB->read($sql2);
        if (is_array($rea2)) {
            foreach ($rea2 as $reply_ro2) {
                $reply++;
            }
        }
        if ($reply == 0) {
            $display = "none";
            // $display = "block";
            // echo "fuck";
        } else {
            $display = "block";
        }
        if ($comment_user['userid'] != $_SESSION['entreefox_userid']) {
            echo "<p><a onclick='handleClick(event, this)' href='$comment_row[postid]/$comment_row[comment_id]/$URL[1]'>Reply</a></p>";
        }
        ?>
        <p onclick="View_<?= $comment_row['comment_id'] ?>_replies()" id="view<?= $comment_row['comment_id'] ?>" style="display: <?= $display ?>;">View replies<?= "(" . $reply . ")" ?></p>

    </div>
</div>
<section class="reply_to_comments" id="reply<?= $comment_row['comment_id'] ?>comments">
    <?php
    $sql = "select * from comments where postid = $comment_row[comment_id] order by id asc";
    $DB = new Database();
    $read = $DB->read($sql);
    if (is_array($read)) {
        foreach ($read as $reply_row) {
            $user = new User();
            $reply_user = $user->get_user($reply_row['users']);
            include("view_replys_V2.php");
        } ?>

        <p onclick="View_<?= $comment_row['comment_id'] ?>_more()" class="view_more" id="View_<?= $comment_row['comment_id'] ?>_more">View more replies</p>
    <?php
    } else {
    ?>
        <p class="view_more">No replies</p>
    <?php
    }

    ?>
</section>
<script>
    typeof boxesTo<?= $comment_row['comment_id'] ?> !== 'undefined' ? "" : boxesTo<?= $comment_row['comment_id'] ?> = 0

    function View_<?= $comment_row['comment_id'] ?>_replies() {
        // alert('good');
        const containersection = document.getElementById('reply<?= $comment_row['comment_id'] ?>comments');
        const boxes = containersection.getElementsByClassName('reply_user');
        const totalBoxes = boxes.length;
        const view = document.getElementById('View_<?= $comment_row['comment_id'] ?>_more');

        // Show the next batch of boxes


        // Update the number of boxes to show for the next click
        if (document.getElementById('view<?= $comment_row['comment_id'] ?>').innerHTML == "View replies<?= "(" . $reply . ")" ?>") {
            // Set the height to the height of one box
            containersection.style.maxHeight = "100%";

            if (totalBoxes <= 2) {
                boxesTo<?= $comment_row['comment_id'] ?> = totalBoxes;
            } else {
                boxesTo<?= $comment_row['comment_id'] ?> += 3;
            }
            const diff = totalBoxes - boxesTo<?= $comment_row['comment_id'] ?>;
            for (let i = 0; i < boxesTo<?= $comment_row['comment_id'] ?> && i < totalBoxes; i++) {
                boxes[i].classList.add('visible');
            }
            document.getElementById('view<?= $comment_row['comment_id'] ?>').innerHTML = "Hide all replies<?= "(" . $reply . ")" ?>";
            if (boxesTo<?= $comment_row['comment_id'] ?> >= totalBoxes) {
                // alert('All boxes are now visible.');
                if (view) {
                    document.getElementById('View_<?= $comment_row['comment_id'] ?>_more').innerHTML = "Hide all replies";
                }
            } else {
                if (view) {
                    document.getElementById('View_<?= $comment_row['comment_id'] ?>_more').innerHTML = "View (" + diff + ") more replies";
                }
            }
        } else {
            // alert("bad");
            containersection.style.maxHeight = 0;
            document.getElementById('view<?= $comment_row['comment_id'] ?>').innerHTML = "View replies<?= "(" . $reply . ")" ?>";
            for (let i = 0; i < totalBoxes; i++) {
                boxes[i].classList.remove('visible');
            }
            boxesTo<?= $comment_row['comment_id'] ?> = 0;
        }
        // alert(boxesTo<?= $comment_row['comment_id'] ?>);
    }

    function View_<?= $comment_row['comment_id'] ?>_more() {
        const containersection = document.getElementById('reply<?= $comment_row['comment_id'] ?>comments');
        const boxes = containersection.getElementsByClassName('reply_user');
        const totalBoxes = boxes.length;


        if (document.getElementById('View_<?= $comment_row['comment_id'] ?>_more').innerHTML == "Hide all replies") {
            containersection.style.maxHeight = 0;
            document.getElementById('view<?= $comment_row['comment_id'] ?>').innerHTML = "View replies<?= "(" . $reply . ")" ?>";
            for (let i = 0; i < totalBoxes; i++) {
                boxes[i].classList.remove('visible');
            }
            boxesTo<?= $comment_row['comment_id'] ?> = 0;
        } else {
            const diff = totalBoxes - boxesTo<?= $comment_row['comment_id'] ?>;
            if (diff >= 1 && diff < 3) {
                boxesTo<?= $comment_row['comment_id'] ?> = totalBoxes;
            } else {
                boxesTo<?= $comment_row['comment_id'] ?> += 3;
            }
            const newdiff = totalBoxes - boxesTo<?= $comment_row['comment_id'] ?>;
            for (let i = 0; i < boxesTo<?= $comment_row['comment_id'] ?> && i < totalBoxes; i++) {
                boxes[i].classList.add('visible');
            }
            document.getElementById('View_<?= $comment_row['comment_id'] ?>_more').innerHTML = "View (" + newdiff + ") more replies";
        }
        if (boxesTo<?= $comment_row['comment_id'] ?> >= totalBoxes) {
            // alert('All boxes are now visible.');
            document.getElementById('View_<?= $comment_row['comment_id'] ?>_more').innerHTML = "Hide all replies";
        }
        // alert(boxesToShow);
    }
</script>