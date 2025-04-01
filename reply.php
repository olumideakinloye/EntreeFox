<div class="reply_user">
    <div class="comment_user reply" id="<?= $reply_row['comment_id'] ?>">
        <div class="comment_user_info">
            <img src="<?php $image = "profile.png";
                        if (file_exists($reply_user['profile_image'])) {
                            $image = $reply_user['profile_image'];
                        }
                        echo ROOT . $image; ?>" alt="">
            <div class="comment_text_info reply_text_info">
                <h3><?php
                    $sql = "select * from comments where parent_comment_id = '$reply_row[postid]'";
                    $DB = new Database();
                    $read3 = $DB->read($sql);
                    if ($read3) {
                        if ($reply_user['userid'] == $_SESSION['entreefox_userid']) {
                            echo "You";
                        } else {
                            echo htmlspecialchars($reply_user['user_name']);
                        }
                    } else {

                        // echo $reply_row['postid'];
                        $sql = "select users from comments where comment_id = '$reply_row[postid]' limit 1";
                        $DB = new Database();
                        $read2 = $DB->read($sql);
                        if ($read2) {
                            $user = new User();
                            $reply2_user = $user->get_user($read2[0]['users']);
                            if ($reply_user['userid'] == $_SESSION['entreefox_userid']) {
                                echo "You" . " > " . htmlspecialchars($reply2_user['user_name']);
                            } elseif ($reply2_user['userid'] == $_SESSION['entreefox_userid']) {
                                echo htmlspecialchars($reply_user['user_name']) . " > You";
                            } else {
                                echo htmlspecialchars($reply_user['user_name']) . " > " . htmlspecialchars($reply2_user['user_name']);
                            }
                        } else {
                            if ($reply_user['userid'] == $_SESSION['entreefox_userid']) {
                                echo "You";
                            } else {
                                echo htmlspecialchars($reply_user['user_name']);
                            }
                        }
                    }
                    ?>
                </h3>
                <p>
                    <b>
                        <?php
                        $time = new Time();
                        $Time = $time->get_time2($reply_row['date']);
                        echo $Time;
                        // echo $reply_row['comment_id'];
                        ?>
                    </b>
                </p>
            </div>
        </div>
        <div class="comment_area">
            <p><?php echo htmlspecialchars($reply_row['comments']) ?></p>
        </div>
        <div class="likes_and_reply">
            <?php
            if ($reply_user['userid'] != $_SESSION['entreefox_userid']) {
                echo "<p><a onclick='handleClick_to_rely_comments(event, this)' href='$URL[1]/$URL[2]/$reply_row[comment_id]/$URL[1]/$comment_row[comment_id]'>Reply</a></p>";
                //ORDER BY: post-id, user-id, comment-id, parent-id, new-post-id.

            }
            ?>
        </div>
    </div>
</div>
<?php
$sql = "select * from comments where postid = '$reply_row[comment_id]' order by id asc";
$DB = new Database();
$read = $DB->read($sql);
if (is_array($read)) {
    foreach ($read as $reply_row) {
        $user = new User();
        $reply_user = $user->get_user($reply_row['users']);
        include("reply.php");
    }
}

?>