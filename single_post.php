<div class="person1_Box">
    <div class="person1_Boxtop">
        <div class="person1_Boxtop_left">
            <?php
            $image = "profile.png";
            if (file_exists($user_data['profile_image'])) {
                $image = $user_data['profile_image'];
            }
            if ($user_data['userid'] == $_SESSION['entreefox_userid']) {
                $Url = ROOT . "Profile";
            } else {
                $Url = ROOT . "Profile/" . $user_data['user_name'];
            }
            ?>
            <a href="<?= $Url ?>">
                <img src="<?php echo ROOT . $image ?>" alt="" />
            </a>
            <div class="person1_Boxtop_textBox">
                <a href="<?php echo $Url ?>" style="color: black; text-decoration:none;">
                    <h1><?php echo htmlspecialchars($user_data['user_name']) ?></h1>
                    <p><b><?php
                            $sql = "select comments from posts where postid = '$row[postid]' limit 1";
                            $DB = new Database();
                            $result = $DB->read($sql);

                            if ($result) {
                                $result2 = $result[0]['comments'];
                                if ($result2 != 0) {
                                    echo $result2 . " ";
                                }
                            }
                            $time = new Time();
                            $Time = $time->get_time2($row['date']);
                            echo $Time;
                            ?>
                        </b>
                    </p>

                </a>
            </div>
        </div>
    </div>
    <div class="intro">
        <p>
            <?php echo htmlspecialchars($row['post']) ?>
        </p>
        <div class="image_post" id="video<?= $row['postid'] ?>img_container">

            <?php

            // $edited_post = new Edit_post();
            // $edited_post->crop_posted_image($row['image'], $row['image'], 1500, 1000);
            if (file_exists($row['image'])) {
                $original_image = imagecreatefromjpeg($row['image']);

                $original_width = imagesx($original_image);
                $original_height = imagesy($original_image);

                if ($original_width > $original_height) {
                    echo "<img src='" . ROOT . $row['image'] . "' style='width: 100%;'>";
                } elseif ($original_width == $original_height) {
                    echo "<img src='" . ROOT . $row['image'] . "' style='width: 100%;'>";
                } elseif ($original_height > $original_width) {
                    echo "<img src='" . ROOT . $row['image'] . "' style='width: 100%;'>";
                }
            }
            if (file_exists($row['video'])) {
                echo "<video class='VIDS' onclick='play_" . $row['postid'] . "close()' loop playsinline width='100%'  id='video" . $row['postid'] . "'><source src='" . ROOT . $row['video'] . "' type='video/mp4'></video>";
                echo "<i onclick='play" . $row['postid'] . "_pause()' id='play" . $row['postid'] . "_pause' class='fa-regular fa-circle-play paper' data-video-id='video" . $row['postid']  . "' ></i>";
                echo "<div class='loading-container' id='loading" . $row['postid'] . "-container'><div class='spinner'></div></div>";
            }
            ?>

        </div>

    </div>
    <div class="person1_Boxbottum">
        <a onclick="handleLikeClick(event, this)" href="<?php echo ROOT ?>like/post/<?php echo $row['postid'] ?>" class="my-link" id='like<?= $row['postid'] ?>' style="display: flex; align-items: center;" data-id="<?= $row['postid'] ?>">
            <?php
            if (isset($_SESSION['entreefox_userid'])) {
                $likes = "";
                $src = "fa-regular";
                $color = "black";
                $DB = new Database();

                $sql = "select likes from likes where contentid = '$row[postid]' limit 1 ";
                $result = $DB->read($sql);

                if (!empty($result[0]['likes'])) {
                    $likes = json_decode($result[0]['likes'], true);

                    $user_ids = array_column($likes, "userid");
                    if (in_array($_SESSION['entreefox_userid'], $user_ids)) {
                        $src = "fa-solid";
                        $color = "red";
                    }
                }
            }
            ?>
            <div class="likes">

                <i class="<?= $src ?> fa-heart post_icon2" id="info_<?php echo $row['postid'] ?>" style="color: <?= $color ?>;"> </i>
                <p id="like_<?= $row['postid'] ?>_number" class="post_number">
                    <?php
                    if (isset($_SESSION['entreefox_userid'])) {

                        $src = "heart.png";
                        $DB = new Database();

                        $sql = "select likes from likes where contentid = '$row[postid]' limit 1 ";
                        $result = $DB->read($sql);

                        if (!empty($result[0]['likes'])) {
                            $likes = json_decode($result[0]['likes'], true);
                            if (is_array($likes)) {
                                if (!empty($likes)) {
                                    $user_ids = array_column($likes, "userid");
                                    $likess = 0;
                                    foreach ($user_ids as $followers) {
                                        $likess++;
                                    }
                                    if ($likess > 0 && $likess < 999) {
                                        echo $likess;
                                    } elseif ($likess > 1000 && $likess < 1000000) {
                                        $thousand_comment = $likess / 1000;
                                        echo (int)$thousand_comment . "K";
                                    } elseif ($likess > 1000000 && $likess < 1099999) {
                                        $million_comments = $likess / 1000000;
                                        echo (int)$million_comments . "M";
                                    } elseif ($likess >= 1100000) {
                                        $million_comments = $likess / 1000000;
                                        $milli = explode('.', $million_comments);
                                        $decimal_milli = str_split($milli[1]);
                                        if ($decimal_milli[0] >= 1) {
                                            echo $milli[0] . "." . $decimal_milli[0] . "M";
                                        } else {
                                            echo $milli[0] . "M";
                                        }
                                    }
                                }
                            }
                        }
                    }
                    ?>
                </p>
            </div>
        </a>
        <div class="comments_section" style="display: flex; align-items: center;">
            <a onclick="fetchCommentsSecondFunction(event, this)" href="<?php echo ROOT ?>comments/<?php echo $row['postid'] ?>/<?php echo $user_data['userid'] ?>">
                <i class="fa-regular fa-comment-dots post_icon2"></i>
                <p id="comment<?= $row['postid'] ?>number" class="post_number">
                    <?php
                    $Commentss = 0;
                    $DB = new Database();
                    $Sql = "select * from comments where postid = '$row[postid]'";
                    $resultt = $DB->read($Sql);
                    if ($resultt) {
                        // print_r($resultt);
                        foreach ($resultt as $result_row) {
                            $Commentss++;
                        }
                        if ($Commentss > 0 && $Commentss < 999) {
                            echo $Commentss;
                        } elseif ($Commentss > 1000 && $Commentss < 1000000) {
                            $thousand_comment = $Commentss / 1000;
                            echo (int)$thousand_comment . "K";
                        } elseif ($Commentss > 1000000 && $Commentss < 1099999) {
                            $million_comments = $Commentss / 1000000;
                            echo (int)$million_comments . "M";
                        } elseif ($Commentss >= 1100000) {
                            $million_comments = $Commentss / 1000000;
                            $milli = explode('.', $million_comments);
                            $decimal_milli = str_split($milli[1]);
                            if ($decimal_milli[0] >= 1) {
                                echo $milli[0] . "." . $decimal_milli[0] . "M";
                            } else {
                                echo $milli[0] . "M";
                            }
                        }
                    }
                    ?>
                </p>
            </a>

        </div>
        <div class="likes">
            <a href="<?php echo ROOT ?>like/favorite/<?php echo $row['postid'] ?>/<?= $user_data['userid'] ?>" style="display: flex; align-items: center;">
                <i class="fa-regular fa-bookmark post_icon2"></i>
                <p id="comment<?= $row['postid'] ?>number" class="post_number">
                    <?php
                    $post = new Post();
                    $type = "favorite";
                    $favs = $post->get_all_favorites($row['postid'], $type);
                    if ($favs > 0 && $favs < 999) {
                        echo $favs;
                    } elseif ($favs > 1000 && $favs < 1000000) {
                        $thousand_comment = $favs / 1000;
                        echo (int)$thousand_comment . "K";
                    } elseif ($favs > 1000000 && $favs < 1099999) {
                        $million_comments = $favs / 1000000;
                        echo (int)$million_comments . "M";
                    } elseif ($favs >= 1100000) {
                        $million_comments = $likess / 1000000;
                        $milli = explode('.', $million_comments);
                        $decimal_milli = str_split($milli[1]);
                        if ($decimal_milli[0] >= 1) {
                            echo $milli[0] . "." . $decimal_milli[0] . "M";
                        } else {
                            echo $milli[0] . "M";
                        }
                    }
                    ?>
                </p>
            </a>
        </div>
    </div>
</div>
<style>
    .person1_Boxbottum {
        display: flex;
        align-items: center;
        justify-content: space-evenly;
        padding: 1.5vh 4vw 1.5vh 4vw;

        /* padding: 1.5vh 4vw 1.5vh 4vw; */
        /* background-color: aqua; */
    }

    .person1_Boxbottum img {
        height: 20px;
        /* background-color: blue; */
    }

    .post_number {
        font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        padding-left: 5px;
        font-size: 1rem;
    }

    .comments_section a {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: black;
        font-size: 17px;
    }

    .likes a {
        text-decoration: none;
        color: black
    }

    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid transparent;
        border-top: 5px solid #1777f9;
        border-bottom: 5px solid #1777f9;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: auto;
        display: block;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .loading-container {
        /* position: fixed; */
        position: absolute;
        /* top: 0; */
        /* left: 0; */
        width: auto;
        height: auto;
        /* background: rgba(255, 255, 255, 0.8); */
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 2;
        display: none;
    }
</style>
<?php
if (file_exists($row['video'])) {
?>
    <script>
        // const video = document.getElementById('video<?= $row['postid'] ?>');
        // const loadingAnimation = document.getElementById('loading<?= $row['postid'] ?>-container');
        const my<?= $row['postid'] ?>Post = document.getElementById('video<?= $row['postid'] ?>');
        const like<?= $row['postid'] ?>Post = document.getElementById('like<?= $row['postid'] ?>');

        my<?= $row['postid'] ?>Post.addEventListener('click', handleDoubleClick);

        function handleDoubleClick(event) {
            if (clickTimeout) {
                clearTimeout(clickTimeout);
                clickTimeout = null;
                const likee = document.querySelector('.like-animation-container');
                likee.style.display = "flex";
                setTimeout(() => {
                    likee.style.display = "none";
                }, 1000);
                const hrefValue = like<?= $row['postid'] ?>Post.getAttribute('href');
                handleClickdiubletap(event, hrefValue);
            } else {
                clickTimeout = setTimeout(() => {
                    clickTimeout = null;
                    // Handle single-click if needed
                }, DOUBLE_CLICK_DELAY);
            }
        }

        function play<?= $row['postid'] ?>_pause() {
            const playButton = document.getElementById('play<?= $row['postid'] ?>_pause');
            const video = document.getElementById('video<?= $row['postid'] ?>');
            const loadingAnimation = document.getElementById('loading<?= $row['postid'] ?>-container');
            if (video.paused) {
                loadingAnimation.style.display = 'flex';
                video.play();
                playButton.style.opacity = 0;
                playButton.classList.remove('fa-circle-play');
                playButton.classList.add('fa-circle-pause');
            } else if (video.play) {
                video.pause();
                playButton.style.opacity = 1;
                playButton.classList.add('fa-circle-play');
                playButton.classList.remove('fa-circle-pause');
                loadingAnimation.style.display = 'none';
            }
            video.addEventListener('error', () => {
                loadingAnimation.style.display = 'none';
            });
            video.addEventListener('waiting', () => {
                loadingAnimation.style.display = 'flex';
            });
            video.addEventListener('playing', () => {
                loadingAnimation.style.display = 'none';
            });
            video.addEventListener('pause', () => {
                playButton.style.opacity = 1;
                playButton.classList.add('fa-circle-play');
                playButton.classList.remove('fa-circle-pause');
            });
            video.addEventListener('play', () => {
                playButton.style.opacity = 0;
                playButton.classList.remove('fa-circle-play');
                playButton.classList.add('fa-circle-pause');
            });
        }


        function play_<?= $row['postid'] ?>close() {
            const playButton = document.getElementById('play<?= $row['postid'] ?>_pause');
            if (playButton.style.opacity == 1) {
                playButton.style.opacity = 0;
            } else {
                playButton.style.opacity = 1;
            }
        }



        const loading<?= $row['postid'] ?>container = document.getElementById('loading<?= $row['postid'] ?>-container');
        const observer<?= $row['postid'] ?> = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // video.play(); // Play the video if it's in the viewport
                } else {
                    my<?= $row['postid'] ?>Post.pause(); // Pause the video if it's not in the viewport
                    const playButton = document.getElementById('play<?= $row['postid'] ?>_pause');
                    playButton.style.opacity = 1;
                    playButton.classList.add('fa-circle-play');
                    playButton.classList.remove('fa-circle-pause');
                    loading<?= $row['postid'] ?>container.style.display = 'none';
                }
            });
        }, {
            threshold: 0.33
        }); // Adjust threshold as needed

        // Observe the video element
        if (my<?= $row['postid'] ?>Post) {
            observer<?= $row['postid'] ?>.observe(my<?= $row['postid'] ?>Post);
        }

        function fetchNumberOfComments() {
            const link = "<?= ROOT . "number_of_comments/$row[postid]" ?>";
            fetch(link)
                .then(response => response.text())
                .then(html => {
                    const messageContainer = document.getElementById('comment<?= $row['postid'] ?>number');
                    messageContainer.innerHTML = html;
                });
        }
        setInterval(fetchNumberOfComments, 1000);
    </script>
<?php
}
?>