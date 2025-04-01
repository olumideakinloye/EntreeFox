<div class="user">
    <a href="Profile/<?= $USER['user_name'] ?>">
        <div class="image" style="background-image: url(<?= ROOT . $image = file_exists($USER["profile_image"]) ? $USER["profile_image"] : "Images/profile.png" ?>);">

        </div>
    </a>
    <p><?= $USER["user_name"] ?></p>
    <div class="cover">

    </div>
    <button id="follow" class="like_btn" data-id="<?= $USER['user_name'] ?>">
        <span class="text">Follow</span>
        <span class="loader">

        </span>
    </button>
</div>