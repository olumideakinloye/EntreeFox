<?php
$href = "";
if (isset($row['post']) && $row['post'] !== "") {
    $padding = "0.5rem";
} else {
    $padding = "0";
}
if ($URL[0] === "Profile" && isset($URL[1])) {
    if ($URL[1] == $User_data['user_name']) {
        $href = "";
    }else{
        $href = "href= '" . entreefox . "ProfileOV/" . $User_data['user_name'] . "'";
    }
} else {
    $href = "href= '" . entreefox . "ProfileOV/" . $User_data['user_name'] . "'";
}
?>
<div class="person1_Box" data-id="<?= $row['id'] ?>">
    <div class="person1_Boxtop">
        <div class="person1_Boxtop_left">
            <?php
            $image = "Images/profile.png";
            if (file_exists($User_data['profile_image'])) {
                $image = $User_data['profile_image'];
            }
            ?>

            <a <?= $href ?>" style="color: black; text-decoration:none;">
                <div class="post_profile" style="background-image: url(<?= entreefox . $image ?>);"></div>
            </a>
            <div class="person1_Boxtop_textBox">
                <h1><?php echo htmlspecialchars($User_data['user_name']) ?></h1>
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
            </div>
        </div>
    </div>
    <div class="intro">
        <p style="padding: <?= $padding; ?>">
            <?php echo htmlspecialchars($row['post']) ?>
        </p>
        <div class="image_post" id="video<?= $row['postid'] ?>img_container">

            <?php
            if (file_exists($row['image'])) {
                echo "<img src='" . entreefox . $row['image'] . "' style='width: 100%;'>";
            }
            if (file_exists($row['video'])) {
                echo "<video class='VIDS' onclick='play_" . $row['postid'] . "close()' loop playsinline width='100%'  id='video" . $row['postid'] . "' data-id='" . $row['postid'] . "'><source src='" . entreefox . $row['video'] . "' type='video/mp4'></video>";
                echo "<i onclick='play" . $row['postid'] . "_pause()' id='play" . $row['postid'] . "_pause' class='fa-regular fa-circle-play paper' data-video-id='video" . $row['postid']  . "' ></i>";
                echo "<div class='loading-container' id='loading" . $row['postid'] . "-container'><div class='spinner'></div></div>";
            }
            ?>

        </div>

    </div>
    <div class="person1_Boxbottum">

        <a onclick="handleLikeClick(event, this)" href="<?php echo entreefox ?>like/post/<?php echo $row['postid'] ?>" class="my-link" id='like<?= $row['postid'] ?>' style="text-decoration: none; color: black;" data-id="<?= $row['postid'] ?>">
            <div class="likes" style="display: flex; align-items: center;">
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
                <i class="<?= $src ?> fa-heart post_icon2" id="info_<?php echo $row['postid'] ?>" style="color: <?= $color ?>;"> </i>
                <p class="post_number" id="like_<?= $row['postid'] ?>_number">

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
            <a onclick="fetchCommentsSecondFunction(event, this)" href="<?php echo entreefox ?>comments/<?php echo $row['postid'] ?>/<?php echo $User_data['userid'] ?>">
                <i class="fa-regular fa-comment-dots post_icon2"></i>
                <p id="comment<?= $row['postid'] ?>number" class="post_number">
                    <?php
                    $Commentss = 0;
                    $DB = new Database();
                    $Sql = "select * from comments where postid = '$row[postid]'";
                    $resultt = $DB->read($Sql);
                    if ($resultt) {
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
        <a href="<?php echo entreefox ?>like/favorite/<?php echo $row['postid'] ?>/<?= $User_data['userid'] ?>" style="text-decoration: none; color:black;">
            <div class="likes" style="display: flex; align-items: center;">
                <i class="fa-regular fa-bookmark post_icon2"></i>
                <p class="post_number">
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
            </div>
        </a>
    </div>
</div>

<style>
    .paper {
        -webkit-backdrop-filter: blur(10px);
        backdrop-filter: blur(10px);
        position: absolute;
        font-size: 4rem;
        color: white;
        transition: 0.5s;
        background-color: #00000080;
        border-radius: 50%;
        z-index: 4;
    }

    .person1_Boxbottum {
        display: flex;
        align-items: center;
        justify-content: space-around;
        padding: 1.5vh 4vw 1.5vh 4vw;
        /* background-color: aqua; */
    }

    .person1_Boxbottum img {
        height: 20px;
        /* background-color: blue; */
    }

    .post_icon2 {
        font-size: 1.5rem;
        color: black;
    }

    .post_number {
        font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        padding-left: 5px;
        font-size: 1.2rem;
    }

    .likes a {
        /* position: absolute; */
        height: 20px;
        text-decoration: none;
        color: black;
        transition: 0.5s;
    }

    .comments_section a {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: black;
        font-size: 17px;
    }

    .comments_section a p {
        font-size: 17px;
        padding-left: 5px;
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
        position: absolute;
        width: auto;
        height: auto;
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

        // my<?= $row['postid'] ?>Post.addEventListener('click', handleDoubleClick);

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
            const link = "<?= entreefox . "number_of_comments/$row[postid]" ?>";
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