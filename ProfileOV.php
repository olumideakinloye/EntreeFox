<?php
include("autoload.php");
if (isset($_SESSION['entreefox_userid']) && is_numeric($_SESSION['entreefox_userid'])) {

    $login = new Login();
    $result = $login->check_login($_SESSION['entreefox_userid']);
    $user = new User();
    if (isset($URL[1])) {
        $id = $user->get_user_by_name($URL[1]);
    } else {
        $id = $_SESSION['entreefox_userid'];
    }

    if ($result) {
        $user_data = $user->get_user($id);
        if ($user_data === false) {
            header("Location: Log_in");
            die;
        } else {
            $post = new Post();
            $posts = $post->get_posts($id);
            $shop = $user->get_shop($id);
        }
    } else {

        header("Location: " . ROOT . "Log_in");
        die;
    }
} else {

    header("Location: " . ROOT . "Log_in");
    die;
}
// $state = "Online";
// $query2 = "insert into user_state (userid, state) values ('$id', '$state')";

// $DB = new Database();
// $DB->save($query2);
$state = "Online";
$query2 = "update user_state set state = '$state' where userid = '$id' limit 1";
$DB = new Database();
$DB->save($query2);
$date = date("Y-m-d H:i:s");
$query3 = "update user_state set date = '$date' where userid = '$_SESSION[entreefox_userid]' limit 1";
$DB->save($query3);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= $user_data['user_name'] ?> | Entreefox</title>
    <link rel="shortcut icon" href="<?= ROOT ?>Images/LOGO.PNG" type="image/x-icon">
    <!-- <script src="<?= ROOT ?>User_profile.js"></script> -->
    <!-- <link rel="stylesheet" href="<?php echo ROOT ?>view_comments_stylesheet.css"> -->
    <link rel="stylesheet" href="<?php echo ROOT ?>CSS/stylesheet_for_posts.css">
    <link rel="stylesheet" href="<?php echo ROOT ?>CSS/User_profile_stylesheet OV.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        let clickTimeout = null;
        const DOUBLE_CLICK_DELAY = 300;

        function checkPosition() {
            const overFlow2controller = document.getElementById('favss');
            const targetDiv = document.getElementById('post_about_favorite');
            const overFlowcontroller = document.getElementById('people');
            if (targetDiv !== null) {
                const rect = targetDiv.getBoundingClientRect();
                if (rect.top >= 0 && rect.top <= 20) {
                    overFlowcontroller.style.overflowY = "scroll";
                    overFlow2controller.style.overflowY = "scroll";
                } else {
                    overFlowcontroller.style.overflowY = "hidden";
                    overFlow2controller.style.overflowY = "hidden";
                }
            } else {
                // console.log(targetDiv);
            }
        }

        window.addEventListener('scroll', checkPosition);

        checkPosition();

        function ajax_send(data, element, id) {
            var ajax = new XMLHttpRequest();

            ajax.addEventListener("readystatechange", function() {
                if (ajax.readyState == 4 && ajax.status == 200) {
                    response(ajax.responseText, element, id);
                }
            });
            data = JSON.stringify(data);

            ajax.open("post", "<?= ROOT ?>ajax.php", true);
            // alert(link);
            ajax.send(data, element, id);
        }

        function response(result, element) {
            // alert("fuck");
            if (result != "") {
                // alert(result);
                var obj = JSON.parse(result);
                if (typeof obj.action != "undefined") {
                    if (obj.action == "liked") {
                        const like_number = document.getElementById(`like_${obj.id}_number`);
                        const like_icon = document.getElementById(`info_${obj.id}`);
                        like_icon.classList.remove('fa-regular');
                        like_icon.classList.add('fa-solid');
                        like_icon.style.color = "red";
                    } else if (obj.action == "unliked") {
                        const like_number = document.getElementById(`like_${obj.id}_number`);
                        const like_icon = document.getElementById(`info_${obj.id}`);
                        like_icon.classList.add('fa-regular');
                        like_icon.classList.remove('fa-solid');
                        like_icon.style.color = "black";
                    } else if (obj.action == "favorite") {
                        likes = parseInt(obj.favorite) > 0 ? obj.favorite : "";
                        element.innerHTML = likes;
                        alert("Added to favorite successflly")
                    }
                }
            }
        }

        function handleLikeClick(fuck, these) {
            fuck.preventDefault();
            const id = these.getAttribute('data-id');
            const like_number = document.getElementById(`like_${id}_number`);
            const like_icon = document.getElementById(`info_${id}`);
            if (like_number.textContent === "") {
                like_number.textContent = "1";
                like_icon.classList.remove('fa-regular');
                like_icon.classList.add('fa-solid');
                like_icon.style.color = "red";
            } else {
                let new_number = Number(like_number.textContent);
                if (like_icon.classList.contains('fa-regular')) {
                    like_icon.classList.remove('fa-regular');
                    like_icon.classList.add('fa-solid');
                    like_icon.style.color = "red";
                    like_number.textContent = ++new_number;
                } else {
                    like_icon.classList.add('fa-regular');
                    like_icon.classList.remove('fa-solid');
                    like_icon.style.color = "black";
                    if (new_number === 1) {
                        like_number.textContent = "";
                    } else {
                        like_number.textContent = --new_number;
                    }
                }
            }
            var link = these.getAttribute("href");
            var data = {};
            data.link = link;
            data.action = "like_post";
            ajax_send(data, fuck.target, id);
        }

        function handleClickdiubletap(fuck, these) {
            fuck.preventDefault();
            // var link = these.getAttribute("href");
            var data = {};
            data.link = these;
            data.action = "like_post";
            ajax_send(data, fuck.target);
        }

        function handleClickFavorite(fuck, these) {
            fuck.preventDefault(); // Prevent the default action (navigation)
            var link = these.getAttribute("href");
            //   alert(link);

            var data = {};
            data.link = link;
            data.action = "favorite";
            // alert(data.link);
            ajax_send(data, fuck.target);
        }
        <?php if ($shop) { ?>

            function About() {
                const post_section = document.querySelector(".people");
                post_section.classList.add("close_post");
                post_section.classList.remove("close2_post");
                post_section.classList.remove("close3_post");

                // document.body.classList.add('no-scroll');

                const About_section = document.querySelector(".Aboutt");
                About_section.classList.add("open_about");
                About_section.classList.remove("close_about");
                About_section.classList.remove("close2_about");

                ////////////////////////////////////////////////////////////

                const Favorite_section = document.querySelector(".favoritesss");
                Favorite_section.classList.remove("open_favorite");
                Favorite_section.classList.add("close_favorite");
                Favorite_section.classList.remove("close2_favorite");

                /////////////////////////////////////////////////////////

                const Shop_section = document.querySelector(".shop");
                Shop_section.classList.remove("open_shop");
                Shop_section.classList.remove("close_shop");
                Shop_section.classList.add("close2_shop");
                Shop_section.classList.remove("close3_shop");

                ///////////////////////////////////////////////////////////

                const animation = document.querySelector(".animation");
                animation.classList.add("about_animation");
                animation.classList.remove("post_animation");
                animation.classList.remove("favss_animation");
                animation.classList.remove("shop_animation");
            }

            function post() {
                const post_section = document.querySelector(".people");
                post_section.classList.remove("close_post");
                post_section.classList.remove("close2_post");
                post_section.classList.remove("close3_post");

                // document.body.classList.add('no-scroll');

                const About_section = document.querySelector(".Aboutt");
                About_section.classList.remove("open_about");
                About_section.classList.remove("close_about");
                About_section.classList.remove("close2_about");

                ///////////////////////////////////////////////

                const Favorite_section = document.querySelector(".favoritesss");
                Favorite_section.classList.remove("open_favorite");
                Favorite_section.classList.remove("close_favorite");
                Favorite_section.classList.remove("close2_favorite");

                /////////////////////////////////////////////////////////

                const Shop_section = document.querySelector(".shop");
                Shop_section.classList.remove("open_shop");
                Shop_section.classList.remove("close_shop");
                Shop_section.classList.remove("close2_shop");
                Shop_section.classList.add("close3_shop");


                //////////////////////////////////////////////////////

                const animation = document.querySelector(".animation");
                animation.classList.remove("about_animation");
                animation.classList.add("post_animation");
                animation.classList.remove("favss_animation");
                animation.classList.remove("shop_animation");
            }

            function Favorite() {
                const post_section = document.querySelector(".people");
                post_section.classList.remove("close_post");
                post_section.classList.remove("close3_post");
                post_section.classList.add("close2_post");

                // // document.body.classList.add('no-scroll');

                const About_section = document.querySelector(".Aboutt");
                About_section.classList.remove("open_about");
                About_section.classList.remove("close2_about");
                About_section.classList.add("close_about");

                /////////////////////////////////////////////////////////

                const Favorite_section = document.querySelector(".favoritesss");
                Favorite_section.classList.add("open_favorite");
                Favorite_section.classList.remove("close_favorite");
                Favorite_section.classList.remove("close2_favorite");


                /////////////////////////////////////////////////////////

                const Shop_section = document.querySelector(".shop");
                Shop_section.classList.remove("open_shop");
                Shop_section.classList.add("close_shop");
                Shop_section.classList.remove("close2_shop");
                Shop_section.classList.remove("close3_shop");

                ///////////////////////////////////////////////////////////

                const animation = document.querySelector(".animation");
                animation.classList.remove("about_animation");
                animation.classList.remove("post_animation");
                animation.classList.add("favss_animation");
                animation.classList.remove("shop_animation");
            }

            function shop() {
                const post_section = document.querySelector(".people");
                post_section.classList.remove("close_post");
                post_section.classList.remove("close2_post");
                post_section.classList.add("close3_post");

                // // document.body.classList.add('no-scroll');

                const About_section = document.querySelector(".Aboutt");
                About_section.classList.remove("open_about");
                About_section.classList.remove("close_about");
                About_section.classList.add("close2_about");

                /////////////////////////////////////////////////////////

                const Favorite_section = document.querySelector(".favoritesss");
                Favorite_section.classList.remove("open_favorite");
                Favorite_section.classList.remove("close_favorite");
                Favorite_section.classList.add("close2_favorite");


                /////////////////////////////////////////////////////////

                const Shop_section = document.querySelector(".shop");
                Shop_section.classList.remove("open_shop");
                Shop_section.classList.remove("close_shop");
                Shop_section.classList.remove("close2_shop");
                Shop_section.classList.remove("close3_shop");
                Shop_section.classList.add("open_shop");

                ///////////////////////////////////////////////////////////

                const animation = document.querySelector(".animation");
                animation.classList.remove("about_animation");
                animation.classList.remove("post_animation");
                animation.classList.remove("favss_animation");
                animation.classList.add("shop_animation");
            }
        <?php
        } else {
        ?>

            function About() {
                const post_section = document.querySelector(".people");
                post_section.classList.add("close_post");
                post_section.classList.remove("close2_post");

                // document.body.classList.add('no-scroll');

                const About_section = document.querySelector(".Aboutt");
                About_section.classList.add("open_about");
                About_section.classList.remove("close_about");

                ////////////////////////////////////////////////////////////

                const Favorite_section = document.querySelector(".favoritesss");
                Favorite_section.classList.remove("open_favorite");
                Favorite_section.classList.add("close_favorite");
                // Favorite_section.classList.remove("close2_favorite");

                /////////////////////////////////////////////////////////


                const animation = document.querySelector(".animation");
                animation.classList.add("about_animation");
                animation.classList.remove("post_animation");
                animation.classList.remove("favss_animation");
                // animation.classList.remove("shop_animation");
            }

            function post() {
                const post_section = document.querySelector(".people");
                post_section.classList.remove("close_post");
                post_section.classList.remove("close2_post");

                // document.body.classList.add('no-scroll');

                const About_section = document.querySelector(".Aboutt");
                About_section.classList.remove("open_about");
                About_section.classList.remove("close_about");

                ///////////////////////////////////////////////

                const Favorite_section = document.querySelector(".favoritesss");
                Favorite_section.classList.remove("open_favorite");
                Favorite_section.classList.remove("close_favorite");


                //////////////////////////////////////////////////////

                const animation = document.querySelector(".animation");
                animation.classList.remove("about_animation");
                animation.classList.add("post_animation");
                animation.classList.remove("favss_animation");
                // animation.classList.remove("shop_animation");
            }

            function Favorite() {
                const post_section = document.querySelector(".people");
                post_section.classList.remove("close_post");
                post_section.classList.add("close2_post");

                // // document.body.classList.add('no-scroll');

                const About_section = document.querySelector(".Aboutt");
                About_section.classList.remove("open_about");
                About_section.classList.add("close_about");

                /////////////////////////////////////////////////////////

                const Favorite_section = document.querySelector(".favoritesss");
                Favorite_section.classList.add("open_favorite");
                Favorite_section.classList.remove("close_favorite");
                Favorite_section.classList.remove("close2_favorite");

                ///////////////////////////////////////////////////////////

                const animation = document.querySelector(".animation");
                animation.classList.remove("about_animation");
                animation.classList.remove("post_animation");
                animation.classList.add("favss_animation");
                // animation.classList.remove("shop_animation");
            }

            function shop() {
                const post_section = document.querySelector(".people");
                post_section.classList.remove("close_post");
                post_section.classList.remove("close2_post");
                post_section.classList.add("close3_post");

                // // document.body.classList.add('no-scroll');

                const About_section = document.querySelector(".Aboutt");
                About_section.classList.remove("open_about");
                About_section.classList.remove("close_about");
                About_section.classList.add("close2_about");

                /////////////////////////////////////////////////////////

                const Favorite_section = document.querySelector(".favoritesss");
                Favorite_section.classList.remove("open_favorite");
                Favorite_section.classList.remove("close_favorite");
                Favorite_section.classList.add("close2_favorite");

                ///////////////////////////////////////////////////////////

                const animation = document.querySelector(".animation");
                animation.classList.remove("about_animation");
                animation.classList.remove("post_animation");
                animation.classList.remove("favss_animation");
                // animation.classList.add("shop_animation");
            }
        <?php
        }
        ?>
    </script>
</head>

<body>
    <div class="container">
        <div onclick="close_like(event)" class="LIKES-CONTAINER" id="LIKES-CONTAINER">
            <div class='loading-container-likes' id='loading-container'>
                <div class='spinner_likes'>

                </div>
            </div>
            <div class="LIKES_BOX" id="LIKES_BOX">
                <div class="control_position">

                </div>
                <div class="LIKES_BOX-BOTTOM" id="LIKES_BOX-BOTTOM">

                </div>
            </div>
        </div>
        <div class="like-animation-container" id="like-animation-container">
            <div class="like_content">
                <div class="like-box">
                    <div class="like1">

                    </div>
                    <div class="like2">

                    </div>
                </div>
            </div>
            <!-- <div class="heart"></div> -->
        </div>
        <div class="top">
            <div class="profile">
                <?php
                $cover = "Images/patrick-fore-850jTF12RSQ-unsplash.jpg";
                if (!empty($user_data['cover_image'])) {
                    if (file_exists($user_data['cover_image'])) {
                        $cover = $user_data['cover_image'];
                    }
                }
                ?>
                <img src="<?php echo ROOT . $cover ?>" alt="" class="background">
                <a href="<?php echo ROOT ?>Edit_profile"><i class="fa-regular fa-pen-to-square fa-flip-horizontal float_icon"></i></a>
                <a href="<?php echo ROOT ?>Home">
                    <div style="background-image: url(<?php echo ROOT . 'Images/LOGO.PNG' ?>);" class="home"></div>
                </a>
            </div>
            <div class="profile_picture">
                <?php
                $image = "Images/profile.png";
                if (!empty($user_data['profile_image'])) {
                    if (file_exists($user_data['profile_image'])) {
                        $image = $user_data['profile_image'];
                    }
                }
                ?>
                <img src="<?php echo ROOT . $image; ?>" alt="" class="picture">
            </div>
            <div class="user_info">
                <div class="posts">

                    <p><b>Posts</b></p>
                    <h3><?php
                        // echo ROOT;
                        $i = 0;
                        if ($posts) {
                            foreach ($posts as $row) {
                                $user = new User();
                                $row_user = $user->get_user($row['userid']);
                                $i++;
                            }
                        }
                        echo $i;

                        ?>
                    </h3>
                </div>
                <div class="followers">
                    <h2><?php echo htmlspecialchars($user_data['user_name']) ?></h2>
                    <p>@<?php echo htmlspecialchars($user_data['user_name']) ?></p>
                    <p><b>Followers</b></p>
                    <h3>
                        <?php
                        if (isset($_SESSION['entreefox_userid'])) {
                            $Followers = 0;
                            $DB = new Database();

                            $sql = "select followers from users where userid = '$user_data[userid]' limit 1 ";
                            $result = $DB->read($sql);
                            if (!empty($result[0]['followers'])) {
                                $sql = "select followers from users where userid = '$user_data[userid]' limit 1 ";
                                $result2 = $DB->read($sql);
                                if (is_array($result2)) {
                                    $likes = json_decode($result[0]['followers'], true);

                                    $user_ids = array_column($likes, "userid");
                                    foreach ($user_ids as $followers) {
                                        $Followers++;
                                    }
                                }
                            }
                            echo $Followers;

                            // $likes = ($row['likes'] > 0) ? $row['likes'] : "";
                        }
                        ?>
                    </h3>
                </div>
                <div class="following">
                    <p><b><a href="<?php echo ROOT . 'User_profile/following' ?>" style="color: black; text-decoration: none">Following</a></b></p>
                    <h3>
                        <?php
                        if (isset($_SESSION['entreefox_userid'])) {
                            $Following = 0;

                            $DB = new Database();
                            $sql = "select following from users where userid = '$user_data[userid]' limit 1 ";
                            $result = $DB->read($sql);
                            if (!empty($result[0]['following'])) {
                                $sql = "select following from users where userid = '$user_data[userid]' limit 1 ";
                                $result2 = $DB->read($sql);
                                if (is_array($result2)) {
                                    $likes = json_decode($result[0]['following'], true);

                                    $user_ids = array_column($likes, "userid");
                                    foreach ($user_ids as $followers) {
                                        $Following++;
                                    }
                                }
                            }
                            echo $Following;

                            // $likes = ($row['likes'] > 0) ? $row['likes'] : "";
                        }
                        ?>
                    </h3>
                </div>
            </div>
            <?php
            if ($user_data['user_type'] == "Vendor") {
                if (!$shop) {

            ?>
                    <div class="create_shop">
                        <a href="<?= ROOT ?>Create_shop">
                            <button>Create Shop</button>
                        </a>
                    </div>
            <?php
                }
            }
            ?>
            <div class="post_about_favorite" id="post_about_favorite">
                <p><b><a onclick="post()">Posts</a></b></p>
                <p><b><a onclick="About()">About</a></b></p>
                <p id="favorite"><b><a onclick="Favorite()">Favorite</a></b></p>
                <?php if ($shop) { ?>
                    <p><b><a onclick="shop()">Shop</a></b></p>
                <?php
                } ?>
                <div class="animation" id="start-home"></div>
            </div>
            <div class="sect" id="sect">
                <?php
                $page = "default";
                if (isset($URL[1])) {
                    $page = $URL[1];
                }
                if ($posts) {
                    echo "<section class='people' id='people'>";
                    foreach ($posts as $row) {
                        $User_data = $user->get_user($row['userid']);
                        if ($row['userid'] === $_SESSION['entreefox_userid']) {
                            include("Posts.php");
                        } else {
                            include("friends_posts.php");
                        }
                    }
                    echo "</section>";
                } else {
                    echo "<section class='people' id='people'>";
                    echo "<h1><b>No posts</b></h1>";
                    echo "</section>";
                }
                $sql_about = "select About from users where userid = '$user_data[userid]' limit 1";
                $about = $DB->read($sql_about);
                if ($about) {
                    echo "<section class='Aboutt close'>";
                    $About = $about[0]['About'];
                    include("About_user.php");
                    echo "</section>";
                }
                $favs = "";
                $favs = $post->get_user_favorites($user_data["userid"]);
                // print_r($favs);

                if ($favs != "") {
                    if (is_array($favs)) {
                        echo "<section class='favoritesss' id='favss'>";
                        foreach ($favs as $FAVS) {
                            $user = new User();
                            $user_data = $user->get_user($FAVS['contentid2']);
                            // echo $FAVS['contentid2'];
                            $row = $post->get_one_posts($FAVS['contentid']);
                            // print_r($row);
                            include('single_post.php');
                        }
                        echo "</section>";
                    }
                } else {
                    echo "<section class='favoritesss' id='favss'>";
                    echo "<h1>You has no favorites</h1>";
                    echo "</section>";
                }
                if ($shop) {
                    // $shopping = new Shopping();
                    // $shopid = $shopping->get_shopid($_SESSION['entreefox_userid']);
                    // $product = $shopping->get_user_products($_SESSION['entreefox_userid'], $shopid[0]['shopid']);
                    // if (is_array($product)) { 
                ?>
                    <section class='shop' id='shop'>
                        <div class="add_product2">
                            <a href="<?= ROOT ?>Add_product"><button><b>Add product</b></button></a>
                        </div>
                        <div class="boxes">
                            <?php
                            // foreach ($product as $Products) {
                            //     $image_array = json_decode($Products["product_image"], true);
                            //     $Products['product_image'] = $image_array[0];
                            //     // echo $image_array;
                            //     include("products.php");
                            // }
                            ?>
                        </div>
                    </section>
                    <?php
                    // } else { 
                    ?>
                    <section class='shop' id='shop'>
                        <div class="add_product">
                            <a href="<?= ROOT ?>Add_product"><button><b>Add product</b></button></a>
                        </div>
                    </section>
                <?php
                    // }
                }
                ?>
            </div>
        </div>
    </div>
    <?php if (!$shop) {
    ?>
        <style>
            .animation {
                left: 7vw;
                /* background-color: aqua; */
            }

            .post_animation {
                left: 7vw;
            }

            .about_animation {
                left: 40vw;
            }

            .favss_animation {
                left: 76vw;
            }

            /* .shop_animation {
                left: 77.5vw;
            } */
        </style>
    <?php
    } ?>
    <script>
        function ajax_send_for_comments(data, element) {
            var ajax = new XMLHttpRequest();

            ajax.addEventListener("readystatechange", function() {
                if (ajax.readyState == 4 && ajax.status == 200) {
                    response_for_comments(ajax.responseText, element);
                }
            });
            data = JSON.stringify(data);

            ajax.open("post", "<?= ROOT ?>ajax.php", true);
            ajax.send(data, element);
        }

        function response_for_comments(result, element) {
            if (result != "") {
                // alert(result);
                var obj = JSON.parse(result);
                if (typeof obj.action != "undefined") {
                    if (obj.action == "reply_comments") {
                        const Holder = document.getElementById("comment_value");
                        Holder.placeholder = obj.place_holder;
                        Holder.addEventListener('input', () => autoResizeTextareaTextarea(event, Holder));
                        Holder.addEventListener('keydown', function(event) {
                            if (event.key == 'Backspace') {
                                if (Holder.scrollHeight < 65) {
                                    Holder.style.height = '20px';
                                } // Additional actions, e.g., custom logic
                                if (Holder.value == "") {
                                    Holder.style.height = '20px';
                                }
                            }
                        });
                        document.getElementById('comment').addEventListener('click', () => {

                            // e.preventDefault();
                            const comment = document.getElementById('comment_value');
                            // if (comment) {
                            //   alert("good");
                            // }
                            // alert(window.document.location.href);
                            const url = window.document.location.href.split("/");
                            url.splice(0, 5)
                            // alert(url[2]);
                            const new_url = url.join("/");
                            // alert(new_url);
                            if (comment.value.length > 2) {
                                const parentID = document.getElementById('parentID').value;
                                var formData = new FormData();
                                formData.append('comment', comment.value);
                                formData.append('parent', parentID);
                                fetch("<?= ROOT . 'send_comments/' ?>" + new_url, {
                                    method: 'POST',
                                    body: formData
                                }).then(() => {
                                    document.getElementById('comment_value').value = '';
                                    document.getElementById('comment_value').style.height = '20px';
                                    const link = `<?= ROOT ?>view_comments/${url[2]}`;
                                    fetchCommentsSecondEdition(link);

                                });
                            } else {
                                Holder.placeholder = "Can't send empty reply";
                                setTimeout(() => {
                                    Holder.placeholder = obj.place_holder;
                                }, 1000)
                            }
                        });

                    }
                }
            }
        }

        function handleClick(fuck, these) {
            fuck.preventDefault();
            const textarea = document.getElementById("input_comment");
            document.getElementById('comments').style.paddingBottom = "130px";
            textarea.style.display = "flex";
            var link = "<?= ROOT . "User_profile" ?>" + "/" + these.getAttribute("href");
            // history.pushState(null, "", link);
            var data = {};
            data.link = link;
            data.action = "Reply_comment";
            ajax_send_for_comments(data, fuck.target);
        }

        function autoResizeTextareaTextarea(event, value) {
            const text = value.value;
            const lines = text.split(/\r?\n/);
            const numberOfLines = lines.length;
            if (value.scrollHeight > 45) {
                value.style.height = 'auto';
                // Set the height to the scrollHeight, adding some padding if needed
                value.style.height = (value.scrollHeight - 20) + 'px';
            }

            // Additional actions, e.g., custom logic
            if (value.value == "") {
                value.style.height = '20px';
            }
        }
        let loadedScripts = [];

        function resize_large_vid() {
            const posts = document.getElementById('people');
            const videos = posts.querySelectorAll('.VIDS');
            videos.forEach(vid => {
                if (vid.clientHeight > window.innerHeight) {
                    const id = vid.getAttribute("data-id");
                    // console.log(vid.id + "is too large for this screen");
                    document.getElementById(`video${id}img_container`).style.maxHeight = "75vh";
                }
            });
        }

        function fetchComments(event, these) {
            event.preventDefault();
            scrollPosition = document.getElementById('LIKES_BOX').scrollTop;
            document.getElementById('loading-container').style.display = "flex";
            document.getElementById('LIKES-CONTAINER').classList.add("view_likes");
            document.getElementById('LIKES_BOX').classList.add("view_likes_box");
            document.getElementById('LIKES_BOX').style.height = "50dvh";
            document.getElementById('LIKES_BOX-BOTTOM').innerHTML = "";
            document.body.classList.add('no-scroll');
            loadedScripts.forEach(script => {
                document.body.removeChild(script);
            });
            loadedScripts = [];
            let link = these.getAttribute('href');
            fetch(link)
                .then(response => response.text())
                .then(html => {
                    const messageContainer = document.getElementById('LIKES_BOX-BOTTOM');
                    messageContainer.innerHTML = html;
                    document.getElementById('loading-container').style.display = "none";

                    const scripts = document.getElementById('LIKES_BOX').querySelectorAll('script');
                    scripts.forEach(script => {
                        const newScript = document.createElement('script');
                        newScript.textContent = script.textContent;
                        document.body.appendChild(newScript);
                        // loadedScripts.push(newScript);
                        loadedScripts.push({
                            src: script.src || null,
                            content: script.src ? null : script.textContent
                        });
                        script.remove();
                    });
                    // scripts.forEach(script => script.remove());
                    // document.getElementById('LIKES_BOX').scrollTop = scrollPosition;
                    const resizableDiv = document.getElementById('LIKES_BOX');
                    resizableDiv.addEventListener('mousedown', startResize);
                    resizableDiv.addEventListener('touchstart', startResize);

                });

            // setInterval(fetchComments(event, these), 100000)
        }

        function startResize(e) {
            isResizing = true;
            const resizableDiv = document.getElementById('LIKES_BOX');
            startY = e.type === 'mousedown' ? e.clientY : e.touches[0].clientY;
            startHeight = resizableDiv.offsetHeight;

            document.addEventListener('mousemove', resize);
            document.addEventListener('touchmove', resize);
            document.addEventListener('mouseup', stopResize);
            document.addEventListener('touchend', stopResize);
        }

        function resize(e) {
            const resizableDiv = document.getElementById('LIKES_BOX');
            if (e.target.closest('.LIKES_BOX-BOTTOM')) {} else {

                if (!isResizing) return;

                let currentY = e.type === 'mousemove' ? e.clientY : e.touches[0].clientY;
                let newHeight = startHeight - (currentY - startY);
                var middleViewHeight = (window.innerHeight / 2) - 50;

                // Ensure the div's height does not go below a minimum value
                if (newHeight < middleViewHeight || newHeight < 50 && resizableDiv.style.height == "50dvh") {
                    document.getElementById('LIKES-CONTAINER').classList.remove("view_likes");
                    document.getElementById('LIKES_BOX').classList.remove("view_likes_box");
                    document.getElementById('LIKES_BOX').style.height = 0;
                    const textarea = document.getElementById("input_comment");
                    if (textarea) {
                        textarea.style.display = "none";
                    }
                    const observer = new MutationObserver((mutations) => {
                        mutations.forEach((mutation) => {
                            mutation.addedNodes.forEach((node) => {
                                if (node.id === 'input_comment') {
                                    document.getElementById("input_comment").style.display = "none";
                                    observer.disconnect(); // Stop observing once found
                                }
                            });
                        });
                    });
                    loadedScripts.forEach(scriptInfo => {
                        const existingScript = Array.from(document.scripts).find(
                            existing => (scriptInfo.src && existing.src === scriptInfo.src)
                        );

                        if (existingScript) {
                            console.log(`Removing existing script: ${existingScript.src}`);
                            existingScript.remove();
                        }

                        // Re-insert stored scripts back into the document
                        const script = document.createElement('script');

                        if (scriptInfo.src) {
                            // Re-activate by setting the src attribute
                            script.src = scriptInfo.src;
                        } else if (scriptInfo.content) {
                            // Wrap the script content in an IIFE to avoid global scope pollution
                            script.textContent = `(function(){ ${scriptInfo.content} })();`;
                        }

                        // Append the script to the body (or a specific container)
                        document.body.appendChild(script);
                    });
                    loadedScripts = [];
                    link = "<?= ROOT ?>User_profile"
                    // history.pushState(null, "", link);
                    newHeight = 50;
                    setTimeout(() => {
                        document.body.classList.remove('no-scroll');
                    }, 300);
                } else {
                    if (newHeight > startHeight) {
                        resizableDiv.style.height = `${85}dvh`;
                        document.getElementById('LIKES_BOX-BOTTOM').style.height = `${85}dvh`;
                        document.getElementById('loading-container').style.top = "50%";

                    } else {
                        resizableDiv.style.height = `${50}dvh`;
                        document.getElementById('LIKES_BOX-BOTTOM').style.height = `${50}dvh`;
                        document.getElementById('loading-container').style.top = "70%";
                    }
                }
            }
        }


        function stopResize() {
            isResizing = false;

            document.removeEventListener('mousemove', resize);
            document.removeEventListener('touchmove', resize);
            document.removeEventListener('mouseup', stopResize);
            document.removeEventListener('touchend', stopResize);
        }


        function fetchCommentsSecondEdition(link) {
            document.getElementById('loading-container').style.display = "flex";
            fetch(link)
                .then(response => response.text())
                .then(html => {
                    const messageContainer = document.getElementById('LIKES_BOX');
                    messageContainer.innerHTML = html;
                    document.getElementById('loading-container').style.display = "none";
                    // document.getElementById('LIKES_BOX').scrollTop = scrollPosition;
                    const scripts = document.getElementById('LIKES_BOX').querySelectorAll('script');
                    scripts.forEach(script => {
                        const newScript = document.createElement('script');
                        newScript.textContent = script.textContent;
                        document.body.appendChild(newScript);
                        loadedScripts.push({
                            src: script.src || null,
                            content: script.src ? null : script.textContent
                        });
                        script.remove();
                    });
                    const resizableDiv = document.getElementById('LIKES_BOX');
                    resizableDiv.addEventListener('mousedown', startResize);
                    resizableDiv.addEventListener('touchstart', startResize);
                });

            // setInterval(fetchComments(event, these), 100000)
        }

        document.addEventListener('DOMContentLoaded', () => {
            const videos = document.querySelectorAll('.VIDS');
            const payButtons = document.querySelectorAll('.paper');

            // Function to hide all pay buttons
            function hideAllPayButtons() {
                payButtons.forEach(button => button.style.display = 'none');
            }

            // Function to show the pay button for a specific video
            function showPayButtonForVideo(videoId) {
                const button = document.querySelector(`.paper[data-video-id="${videoId}"]`);
                if (button) button.style.display = 'block';
                // if (button) button.style.opacity = 1;
            }

            function showPayButtonForVideosss() {
                payButtons.forEach(button => button.style.display = 'block');
                payButtons.forEach(button => button.style.opacity = 1);
            }

            videos.forEach(video => {
                video.addEventListener('play', () => {
                    // alert("fuck");
                    hideAllPayButtons();
                    showPayButtonForVideo(video.id);
                    // alert(video.id);
                });

                video.addEventListener('pause', () => {
                    showPayButtonForVideosss();
                });

            });
        });

        function fetchlikes(event, these) {
            event.preventDefault();
            document.getElementById('loading-container').style.display = "flex";
            document.getElementById('LIKES-CONTAINER').classList.add("view_likes");
            document.getElementById('LIKES_BOX').classList.add("view_likes_box");
            document.getElementById('LIKES_BOX').style.height = "50dvh";
            document.getElementById('LIKES_BOX-BOTTOM').innerHTML = "";
            let link = these.getAttribute('href');
            document.body.classList.add('no-scroll');
            fetch(link)
                .then(response => response.text())
                .then(html => {
                    const messageContainer = document.getElementById('LIKES_BOX-BOTTOM');
                    messageContainer.innerHTML = html;
                    document.getElementById('loading-container').style.display = "none";
                    const resizableDiv = document.getElementById('LIKES_BOX');
                    resizableDiv.addEventListener('mousedown', startResize);
                    resizableDiv.addEventListener('touchstart', startResize);

                });
            // window.scrollTo(0, document.body.scrollHeight);
        }

        function close_like(e) {
            if (e.target.closest('.LIKES_BOX') || e.target.closest('.input_comment')) {} else {
                // alert('bad');
                document.getElementById('LIKES-CONTAINER').classList.remove("view_likes");
                document.getElementById('LIKES_BOX').classList.remove("view_likes_box");
                document.getElementById('LIKES_BOX').style.height = 0;
                document.body.classList.remove('no-scroll');
                const textarea = document.getElementById("input_comment");
                if (textarea) {
                    textarea.style.display = "none";
                }
                const observer = new MutationObserver((mutations) => {
                    mutations.forEach((mutation) => {
                        mutation.addedNodes.forEach((node) => {
                            if (node.id === 'input_comment') {
                                document.getElementById("input_comment").style.display = "none";
                                observer.disconnect(); // Stop observing once found
                            }
                        });
                    });
                });

                // Start observing the document for changes
                observer.observe(document.body, {
                    childList: true,
                    subtree: true
                });

                // textarea.style.display = "none";
                loadedScripts.forEach(scriptInfo => {
                    const existingScript = Array.from(document.scripts).find(
                        existing => (scriptInfo.src && existing.src === scriptInfo.src)
                    );

                    if (existingScript) {
                        console.log(`Removing existing script: ${existingScript.src}`);
                        existingScript.remove();
                    }

                    // Re-insert stored scripts back into the document
                    const script = document.createElement('script');

                    if (scriptInfo.src) {
                        // Re-activate by setting the src attribute
                        script.src = scriptInfo.src;
                    } else if (scriptInfo.content) {
                        // Wrap the script content in an IIFE to avoid global scope pollution
                        script.textContent = `(function(){ ${scriptInfo.content} })();`;
                    }

                    // Append the script to the body (or a specific container)
                    document.body.appendChild(script);
                });
                loadedScripts = [];
                link = "<?= ROOT ?>Profile"
                history.pushState(null, "", link);
            }
        }

        function fetchCommentsSecondFunction(event, these) {
            event.preventDefault();
            let link = these.getAttribute('href');
            const url = link.split("/");
            url.splice(0, 5)
            const new_url = url.join("/");
            link2 = window.document.location.href + `/${new_url}`;
            // history.pushState(null, "", link2);
            document.getElementById('loading-container').style.display = "flex";
            document.getElementById('LIKES-CONTAINER').classList.add("view_likes");
            document.getElementById('LIKES_BOX').classList.add("view_likes_box");
            document.getElementById('LIKES_BOX').style.height = "50dvh";
            document.getElementById('LIKES_BOX-BOTTOM').innerHTML = "";
            document.body.classList.add('no-scroll');
            if (loadedScripts.forEach) {
                loadedScripts.forEach(script => {
                    document.body.removeChild(script);
                });
            }
            loadedScripts = [];

            fetch(link)
                .then(response => response.text())
                .then(html => {
                    const messageContainer = document.getElementById('LIKES_BOX-BOTTOM');
                    messageContainer.innerHTML = html;
                    document.getElementById('loading-container').style.display = "none";
                    document.getElementById('comments').style.paddingBottom = "130px";
                    const scripts = document.getElementById('LIKES_BOX-BOTTOM').querySelectorAll('script');
                    scripts.forEach(script => {

                        const newScript = document.createElement('script');
                        newScript.textContent = script.textContent;
                        document.body.appendChild(newScript);
                        // loadedScripts.push(newScript);
                        loadedScripts.push({
                            src: script.src || null,
                            content: script.src ? null : script.textContent
                        });
                        script.remove();
                    });
                    // scripts.forEach(script => script.remove());
                    // document.getElementById('LIKES_BOX').scrollTop = scrollPosition;
                    const resizableDiv = document.getElementById('LIKES_BOX');
                    resizableDiv.addEventListener('mousedown', startResize);
                    resizableDiv.addEventListener('touchstart', startResize);


                    const textarea = document.getElementById('comment_value');

                    // Add event listeners for input and change events
                    textarea.addEventListener('input', () => autoResizeTextarea(textarea));
                    textarea.addEventListener('keydown', function(event) {
                        if (event.key == 'Backspace') {
                            if (textarea.scrollHeight < 65) {
                                textarea.style.height = '20px';
                            } // Additional actions, e.g., custom logic
                            if (textarea.value == "") {
                                textarea.style.height = '20px';
                            }
                        }
                    });

                    if (textarea.value == "") {
                        textarea.style.height = '20px';
                    }

                    document.getElementById('comment').addEventListener('click', (e) => {
                        e.preventDefault();
                        const url = window.document.location.href.split("/");
                        url.splice(0, 5)
                        if (!url[2]) {
                            // const comment = document.getElementById('comment_value');
                            const new_url = `${url[0]}/${url[1]}`;
                            // alert(new_url);
                            if (textarea.value.length > 2) {
                                const parentID = document.getElementById('parentID').value;
                                var formData = new FormData();
                                formData.append('comment', textarea.value);
                                formData.append('parent', parentID);
                                document.getElementById('loading-container').style.display = "flex";
                                fetch("<?= ROOT . 'send_comments_V2/' ?>" + new_url, {
                                    method: 'POST',
                                    body: formData
                                }).then(() => {
                                    document.getElementById('comment_value').value = '';
                                    document.getElementById('comment_value').style.height = '20px';
                                    const link = `<?= ROOT ?>comments/${new_url}`;
                                    fetchCommentsSecondEdition_for_secondFunction(link);

                                });
                            } else {
                                textarea.placeholder = "Can't send empty comment";
                                if (textarea.scrollHeight > 45) {
                                    textarea.style.height = 'auto';
                                    // Set the height to the scrollHeight, adding some padding if needed
                                    textarea.style.height = (textarea.scrollHeight - 18) + 'px';
                                }
                                setTimeout(() => {
                                    textarea.placeholder = "Add comment...";
                                    textarea.style.height = "20px";
                                }, 1000)
                            }
                        }
                    });
                });

            // setInterval(fetchComments(event, these), 100000)
        }

        function fetchCommentsSecondEdition_for_secondFunction(link) {
            fetch(link)
                .then(response => response.text())
                .then(html => {
                    const messageContainer = document.getElementById('LIKES_BOX-BOTTOM');
                    messageContainer.innerHTML = html;
                    document.getElementById('loading-container').style.display = "none";
                    document.getElementById('comments').style.paddingBottom = "130px";
                    const scripts = document.getElementById('LIKES_BOX-BOTTOM').querySelectorAll('script');
                    scripts.forEach(script => {
                        const newScript = document.createElement('script');
                        newScript.textContent = script.textContent;
                        document.body.appendChild(newScript);
                        loadedScripts.push({
                            src: script.src || null,
                            content: script.src ? null : script.textContent
                        });
                        script.remove();
                    });
                    const resizableDiv = document.getElementById('LIKES_BOX');
                    resizableDiv.addEventListener('mousedown', startResize);
                    resizableDiv.addEventListener('touchstart', startResize);

                });

        }

        function ajax_send_for_comments_comments_page(data, element) {
            var ajax = new XMLHttpRequest();

            ajax.addEventListener("readystatechange", function() {
                if (ajax.readyState == 4 && ajax.status == 200) {
                    response_for_comments_comments_page(ajax.responseText, element);
                }
            });
            data = JSON.stringify(data);

            ajax.open("post", "<?= ROOT ?>ajax.php", true);
            // alert(link);
            ajax.send(data, element);
        }

        function response_for_comments_comments_page(result, element) {
            // alert("fuck");
            if (result != "") {
                var obj = JSON.parse(result);
                if (typeof obj.action != "undefined") {
                    if (obj.action == "reply_comments_comments") {
                        const Holder = document.getElementById("comment_value");
                        Holder.placeholder = obj.place_holder;
                        // document.getElementById('comment_value').focus();

                        // alert(url);
                        document.getElementById('comment').addEventListener('click', (e) => {
                            e.preventDefault();
                            const url = window.document.location.href.split("/");
                            url.splice(0, 5);
                            if (url[2]) {
                                const comment = document.getElementById('comment_value');

                                const new_url = url.join("/");
                                // alert(url);
                                if (comment.value.length > 2) {
                                    const parentID = document.getElementById('parentID').value;
                                    var formData = new FormData();
                                    formData.append('comment', comment.value);
                                    formData.append('parent', parentID);
                                    fetch("<?= ROOT . 'send_comments_V2/' ?>" + new_url, {
                                        method: 'POST',
                                        body: formData
                                    }).then(() => {
                                        document.getElementById('comment_value').value = '';
                                        document.getElementById('comment_value').style.height = '20px';
                                        const link = `<?= ROOT ?>comments/${url[0]}/${url[1]}`;
                                        fetchCommentsSecondEdition_for_secondFunction(link);

                                    });
                                } else {
                                    Holder.placeholder = "Can't send empty reply";
                                    setTimeout(() => {
                                        Holder.placeholder = obj.place_holder;
                                    }, 1000)
                                }
                            }
                        });
                    }
                }
            }
        }

        function handleClick_to_rely_comments(fuck, these) {
            fuck.preventDefault(); // Prevent the default action (navigation)
            document.getElementById('comments').style.paddingBottom = "130px";
            // document.getElementById('comments').scrollTo = document.getElementById('comments').scrollHeight;
            var link = "<?= ROOT . "User_profile" ?>" + "/" + these.getAttribute("href");
            // history.pushState(null, "", link);
            var data = {};
            data.link = link;
            data.action = "Reply_comment_comment_page";
            ajax_send_for_comments_comments_page(data, fuck.target);
        }

        function autoResizeTextarea(textarea) {
            const text = textarea.value;
            const lines = text.split(/\r?\n/);
            const numberOfLines = lines.length;
            if (textarea.scrollHeight > 45) {
                textarea.style.height = 'auto';
                // Set the height to the scrollHeight, adding some padding if needed
                textarea.style.height = (textarea.scrollHeight - 18) + 'px';
            }

            if (textarea.value == "") {
                textarea.style.height = '20px';
            }
        }
        resize_large_vid();
    </script>
</body>

</html>