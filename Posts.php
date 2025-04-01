<?php
if (isset($row['post']) && $row['post'] !== "") {
  $padding = "0.5rem";
} else {
  $padding = "0";
}
?>
<div class="person1_Box" data-id="<?= $row['id'] ?>">
  <div class="person1_Boxtop">
    <div class="person1_Boxtop_left">
      <?php
      $image = "profile.png";
      if (file_exists($user_data['profile_image'])) {
        $image = $user_data['profile_image'];
      }
      ?>
      <img src="<?php echo entreefox . $image ?>" alt="" />
      <div class="person1_Boxtop_textBox">
        <h1>You</h1>
        <p>
          <b>
            <?php
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
            // print_r(new DateTime());
            ?>
          </b>
        </p>
      </div>
    </div>
    <div class="post_menu">
      <div class="edit_delete" id="<?= $row['postid'] ?>" style="display: flex; transition: all 0.5s; scale: 0;">
        <a href="<?php echo entreefox ?>edit/<?php echo $row['postid'] ?>" style="border-bottom: 1px solid #00000081;">Edit<img src="<?php echo entreefox ?>pencil2.png" class="postmenu"></a>
        <a href="<?php echo entreefox ?>delete/<?php echo $row['postid'] ?>">Delete<img class="cancel" src="<?php echo entreefox ?>bin.png"></a>
      </div>
      <div class="menu_icon" onclick="edit_<?= $row['postid'] ?>delete()">
        <i class="fa-solid fa-ellipsis-vertical post_icon_menu" id="menu<?= $row['postid'] ?>"></i>
      </div>
    </div>
  </div>
  <div class="intro">
    <p style="padding: <?= $padding; ?>">
      <?php echo htmlspecialchars($row['post']) ?>
    </p>
    <div class="image_post" id="video<?=$row['postid']?>img_container">
      <?php
      if (file_exists($row['image'])) {
        $original_file_name = $row['image'];
        echo "<a href='" . entreefox . "$original_file_name'><img src='" . entreefox . "$original_file_name'></a>";
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
    <div class="likess">
      <a onclick="fetchlikes(event, this)" href="<?php echo entreefox ?>View_likes/post/<?php echo $row['postid'] ?>">
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
        <p class="post_number">
          <?php
          if (isset($_SESSION['entreefox_userid'])) {
            $likess = 0;
            $DB = new Database();

            $sql = "select likes from likes where contentid = '$row[postid]' limit 1 ";
            $result = $DB->read($sql);
            if (!empty($result[0]['likes'])) {
              $sql = "select likes from likes where contentid = '$row[postid]' limit 1 ";
              $result2 = $DB->read($sql);
              if (is_array($result2)) {
                $likes = json_decode($result[0]['likes'], true);
                if (!empty($likes)) {
                  $user_ids = array_column($likes, "userid");
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
      </a>

    </div>
    <div class="comments_section">
      <a onclick="fetchComments(event, this)" href="<?php echo entreefox ?>view_comments/<?php echo $row['postid'] ?>">
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
    <div class="comments_section">
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
  </div>
</div>
<style>
  .post_icon_menu {
    font-size: 1rem;
  }

  .menu_icon {
    /* padding: 1rem; */
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    border: 1px solid rgba(0, 0, 0, 0.5);
  }

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
    overflow: hidden;
  }

  .post_icon2 {
    font-size: 1.5rem;
    color: black;
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

  .post_menu {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 120px;
    height: 50px;
    position: relative;
    z-index: 1;
  }

  .post_menu p {
    font-size: 12px;
    background-color: white;
    padding: 0;
    display: none;
  }

  .post_menu p:hover {
    opacity: 0.5;
  }

  .edit_delete {
    display: flex;
    flex-direction: column;
    /* background-color: bisque; */
    background-color: #f1f1f1;
    border: 1px solid #00000081;
    justify-content: space-between;
    /* padding: 5px; */
    width: 75px;
    /* height: 45px; */
  }

  .edit_delete a {
    display: flex;
    color: black;
    align-items: center;
    text-decoration: none;
    justify-content: space-between;
    font-size: 15px;
    padding: 5px;
  }

  .likess a {
    /* background-color: aqua; */
    width: auto;
    height: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    text-decoration: none;
  }
</style>
<script>
  function edit_<?= $row['postid'] ?>delete() {
    document.getElementById("<?= $row['postid'] ?>").style.scale = 1;
    document.getElementById("<?= $row['postid'] ?>").style.opacity = 1;
    // document.getElementById("menu<?= $row['postid'] ?>").src = "<?= entreefox ?>close2.png";
    setTimeout(() => {
      document.getElementById("<?= $row['postid'] ?>").style.opacity = 0;
      document.getElementById("<?= $row['postid'] ?>").style.scale = 0;

    }, 3000)
  }
</script>
<?php
if (file_exists($row['video'])) {
?>
  <script>
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



    document.addEventListener('DOMContentLoaded', () => {
      const video = document.getElementById('video<?= $row['postid'] ?>');
      const loadingAnimation = document.getElementById('loading<?= $row['postid'] ?>-container');
      const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {} else {
            video.pause(); // Pause the video if it's not in the viewport
            const playButton = document.getElementById('play<?= $row['postid'] ?>_pause');
            playButton.style.opacity = 1;
            playButton.classList.add('fa-circle-play');
            playButton.classList.remove('fa-circle-pause');
            loadingAnimation.style.display = 'none';
          }
        });
      }, {
        threshold: 0.33
      }); // Adjust threshold as needed

      // Observe the video element
      observer.observe(video);
    });


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