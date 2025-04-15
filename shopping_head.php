<div class="menu_container" id="menu_container">
    <div class="menu">
        <?php
        $image = "Images/profile.png";
        if (file_exists($user_data['profile_image'])) {
            $image = $user_data['profile_image'];
        }
        ?>
        <a href="<?= ROOT ?>Profile" style="border-bottom: none;">
            <img src="<?php echo ROOT . $image ?>" alt="" class="close" />
        </a>
        <a href="<?= ROOT ?>Orders" class="nav_link">

            Orders
            <i class="fa-solid fa-box-open menu_icon"></i>
        </a>
        <a href="<?= ROOT ?>Messages" class="nav_link">Chat<i class="fa-regular fa-comment menu_icon"></i></a>
        <a href="<?= ROOT ?>Log_out" class="nav_link">Log out<i class="fa-solid fa-right-from-bracket menu_icon"></i></a>

        <ul>
            <div class="category">
                <p><b>OUR CATEGORIES</b></p>
                <a href="">See All</a>
            </div>
            <?php
            $limit = 0;
            foreach ($category as $list) {
                if ($limit > 10) {
                    continue;
                } else {
            ?>
                    <li>
                        <a href="<?=ROOT?>Home?Category=<?= addslashes(str_replace("\\", "", $list))?>"><?= str_replace("\\", "", $list) ?>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" class="SVGs">
                                <path d="M80-160v-120h80v-440q0-33 23.5-56.5T240-800h600v80H240v440h240v120H80Zm520 0q-17 0-28.5-11.5T560-200v-400q0-17 11.5-28.5T600-640h240q17 0 28.5 11.5T880-600v400q0 17-11.5 28.5T840-160H600Zm40-120h160v-280H640v280Zm0 0h160-160Z" />
                            </svg>
                        </a>
                    </li>
            <?php
                }
                $limit++;
            }
            ?>
            <!-- <li> <a href=""></a>Phone & Tablets <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" class="SVGs">
                    <path d="M80-160v-120h80v-440q0-33 23.5-56.5T240-800h600v80H240v440h240v120H80Zm520 0q-17 0-28.5-11.5T560-200v-400q0-17 11.5-28.5T600-640h240q17 0 28.5 11.5T880-600v400q0 17-11.5 28.5T840-160H600Zm40-120h160v-280H640v280Zm0 0h160-160Z" />
                </svg></li>
            <li> <a href=""></a>Appliances <i class="fa-solid fa-blender-phone menu_icon"></i></li>
            <li>Electronics <i class="fa-solid fa-tv menu_icon"></i></li>
            <li>Supermarket <i class="fa-regular fa-lemon menu_icon"></i></li>
            <li>Men's Fashion <i class="fa-regular fa-shirt menu_icon"></i></li>
            <li>Women's Fashion<i class="fa-solid fa-person-dress menu_icon"></i></li>
            <li>Health & Beauty <i class="fa-solid fa-bed-pulse menu_icon"></i></li>
            <li>Baby Products <i class="fa-solid fa-baby menu_icon"></i></li>
            <li>Sporting Goods <i class="fa-solid fa-table-tennis-paddle-ball menu_icon"></i></li>
            <li>Automobile <i class="fa-solid fa-car menu_icon"></i></li>
            <li>Books & Stationery <i class="fa-solid fa-book menu_icon"></i></li> -->
        </ul>
    </div>

</div>

<?php
$state = "Online";
$query2 = "update user_state set state = '$state' where userid = '$_SESSION[entreefox_userid]' limit 1";
$DB = new Database();
$DB->save($query2);
$date = date("Y-m-d H:i:s");
$query3 = "update user_state set date = '$date' where userid = '$_SESSION[entreefox_userid]' limit 1";
$DB = new Database();
$DB->save($query3);
?>