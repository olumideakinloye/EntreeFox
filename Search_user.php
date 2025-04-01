<div class="person_Box">
  <div class="person_Boxtop">
    <div class="person_Boxtop_left">
      <a href="friends_profile/<?php echo $row['userid'] ?>">
        <?php
        $image = "profile.png";
        if (file_exists($row['profile_image'])) {
          $image = $row['profile_image'];
        }
        ?>
        <img src="<?php echo $image ?>" alt="" />
      </a>
      <div class="person_Boxtop_textBox">
        <a href="friends_profile/<?php echo $row['userid'] ?>">
          <h1><?php echo htmlspecialchars($row['user_name']) ?></h1>
          <p>
            <b><?php $time = new Time();
                $Time = $time->get_time2($row['date']);
                echo $Time ?>
            </b>
          </p>
        </a>
      </div>
    </div>
  </div>
  <div class="person_intro">
    <p>
      <?php echo htmlspecialchars($row['About']) ?>
    </p>
  </div>
</div>