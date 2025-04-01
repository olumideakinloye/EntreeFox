<div class="navigation" id="nav">
    <div class="nav_right">
        <ul>
            <li>
                <a href="<?=ROOT?>ProfileOV">
                    <div class="my_profile" style="background-image: url(<?php if(isset($user_data['profile_image']) && file_exists($user_data['profile_image'])){echo $user_data['profile_image']; } else{ echo "Images/profile.png"; } ?>);" alt=""></div>
                </a>
            </li>
            <li><a href="">Shop<i class="fa-solid fa-shop"></i></a></li>
            <li><a href="<?=ROOT?>Log_out">Log out<i class="fa-solid fa-right-from-bracket"></a></i></li>
        </ul>
    </div>
</div>