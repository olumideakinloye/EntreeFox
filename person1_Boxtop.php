<div class="person1_Boxtop">
    <div class="person1_Boxtop_left">
        <a href="User_profile.php">
            <?php
            $image = "profile.png";
            if (file_exists($user_data['profile_image'])) {
                $image = $user_data['profile_image'];
            }
            ?>
            <img src="<?php echo $image ?>" alt="" />
        </a>
        <div class="person1_Boxtop_textBox">
            <a href="User_profile.php">
                <h1><?php echo $user_data['user_name'] ?></h1>
                <p><b><?php echo $row['date'] ?></b></p>
            </a>
        </div>
    </div>
    <div class="post_menu">
        <p class="post2_menu" id="post2_menu"><b><a href="User_profile.php" style="text-decoration: none; color:black">Delete post</a></p></b>
        <img class="cancel" id="cancel" src="close2.png" style="display: none;">
        <img src="dots.png" id="<?php echo $row['date'] ?>" class="postmenu" onclick="postmenu()">
    </div>
</div>
<script>
function postmenu(){
    document.getElementById("<?php echo $row['date'] ?>").src = "close2.png";
    if(document.getElementById("<?php echo $row['date'] ?>").src == "close2.png"){
        document.getElementById("<?php echo $row['date'] ?>").src = "dots.png";
    }
}
</script>