<div class="people">
    <div class="person1_Box">
        <div class="person1_Boxtop">
            <div class="person1_Boxtop_left">
                <?php
                $image = "profile.png";
                if (file_exists($user_data['profile_image'])) {
                    $image = $user_data['profile_image'];
                }
                if ($user_data['userid'] == $_SESSION['entreefox_userid']) {
                    $Url = ROOT . "User_profile";
                } else {
                    $Url = ROOT . "friends_profile/" . $user_data['userid'];
                }
                ?>

                <img src="<?php echo ROOT . $image ?>" alt="" />
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
            <div class="image_post">

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
                    echo "<video class='VIDS' id='video" . $row['postid']  . "' loop playsinline width='100%'  ><source src='" . ROOT . $row['video'] . "' type='video/mp4'></video>";
                    echo "<img onclick='play" . $row['postid'] . "_pause()' id='play" . $row['postid'] . "pause' src='" . ROOT . "play-button.png' class='paper' style='border-radius: 50%; width: 4rem;' data-video-id='video" . $row['postid']  . "'>";
                    echo "<div class='loading-container' id='loading" . $row['postid'] . "-container'><div class='spinner'></div></div>";
                }
                ?>

            </div>

        </div>
    </div>
</div>
<style>
    .paper {
        position: absolute;
        height: 4rem;
        width: 20px;
        transition: 0.5s;
        background-color: #00000080;
        -webkit-backdrop-filter: blur(10px);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        z-index: 5;
    }

    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #1777f9;
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
        function play<?= $row['postid'] ?>_pause() {
            const playButton = document.getElementById('play<?= $row['postid'] ?>pause');
            const video = document.getElementById('video<?= $row['postid'] ?>');
            const loadingAnimation = document.getElementById('loading<?= $row['postid'] ?>-container');
            if (video.paused) {
                loadingAnimation.style.display = 'flex';
                video.play();
                playButton.style.opacity = 0;
                playButton.src = "<?= ROOT ?>video-pause-button.png";
            } else if (video.play) {
                video.pause();
                playButton.style.opacity = 1;
                playButton.src = "<?= ROOT ?>play-button.png";
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
                playButton.src = '<?= ROOT ?>play-button.png';
            });
            video.addEventListener('play', () => {
                playButton.style.opacity = 0;
                playButton.src = '<?= ROOT ?>video-pause-button.png';
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const video = document.getElementById('video<?= $row['postid'] ?>');
            const loadingAnimation = document.getElementById('loading<?= $row['postid'] ?>-container');
            const playButton = document.getElementById('play<?= $row['postid'] ?>pause');
            video.addEventListener('click', () => {
                if (playButton.style.opacity == 1) {
                    playButton.style.opacity = 0;
                } else {
                    playButton.style.opacity = 1;
                }
            });
            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        // video.play(); // Play the video if it's in the viewport
                    } else {
                        video.pause(); // Pause the video if it's not in the viewport
                        const playButton = document.getElementById('play<?= $row['postid'] ?>pause');
                        playButton.style.opacity = 1;
                        playButton.src = '<?= ROOT ?>play-button.png';
                        loadingAnimation.style.display = 'none';
                    }
                });
            }, {
                threshold: 0.33
            }); // Adjust threshold as needed

            // Observe the video element
            observer.observe(video);
        });
    </script>
    <?php
    if ($URL[0] == "view_comments" || $URL[0] == "comments") {
    ?>
        <script>
            window.addEventListener('load', function() {
                const video<?= $row['postid'] ?> = document.getElementById('video<?= $row['postid'] ?>');
                const video<?= $row['postid'] ?>width = video<?= $row['postid'] ?>.videoWidth;
                const video<?= $row['postid'] ?>height = video<?= $row['postid'] ?>.videoHeight;
                const diff<?= $row['postid'] ?> = (video<?= $row['postid'] ?>width / 1.5) + video<?= $row['postid'] ?>width;

                if (document.getElementById('video<?= $row['postid'] ?>').videoHeight > diff<?= $row['postid'] ?>) {
                    document.getElementById('video<?= $row['postid'] ?>').style.height = "70vh";
                    document.getElementById('video<?= $row['postid'] ?>').style.backgroundColor = "black";
                }

            });
        </script>
    <?php
    } else {
    ?>
        <script>
            window.addEventListener('load', function() {
                const video<?= $row['postid'] ?> = document.getElementById('video<?= $row['postid'] ?>');
                const video<?= $row['postid'] ?>width = video<?= $row['postid'] ?>.videoWidth;
                const video<?= $row['postid'] ?>height = video<?= $row['postid'] ?>.videoHeight;
                const diff<?= $row['postid'] ?> = (video<?= $row['postid'] ?>width / 1.5) + video<?= $row['postid'] ?>width;

                if (document.getElementById('video<?= $row['postid'] ?>').videoHeight > diff<?= $row['postid'] ?>) {
                    document.getElementById('video<?= $row['postid'] ?>').style.height = "80vh";
                    document.getElementById('video<?= $row['postid'] ?>').style.backgroundColor = "black";
                }

            });
        </script>
<?php
    }
}
?>