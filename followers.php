<a href="<?=ROOT?>Profile/<?=$follow_user_data["user_name"]?>" style="text-decoration: none; color: black;">
    <div class="follow">
        <div class="profile" style="background-image:url(
        <?php
        if (isset($follow_user_data["profile_image"]) && !empty($follow_user_data["profile_image"])) {
            echo $follow_user_data["profile_image"];
        } else {
            echo "Images/profile.png";
        }
        ?>);">
        </div>
        <p><?= $follow_user_data["user_name"] ?></p>
    </div>
</a>