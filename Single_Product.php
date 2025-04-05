<?php

include("autoload.php");

$login =  new Login();
$first_visit = $login->check_new_user();
if ($first_visit === true) {
    header("Location: Welcome");
} else {
    if (isset($_SESSION['entreefox_userid']) && is_numeric($_SESSION['entreefox_userid'])) {

        $id = $_SESSION['entreefox_userid'];
        $result = $login->check_login($id);
        if ($result) {

            $user = new User();

            $user_data = $user->get_user($id);


            if ($user_data === false) {
                header("Location: Log_in");
                die;
            }
        } else {

            header("Location: Log_in");
            die;
        }
    } else {

        header("Location: Log_in");
        die;
    }
}

if (isset($URL[1])) {
    $shopping = new Shopping();
    $product_info = $shopping->get_product_with_productid($URL[1]);
    $shop_info = $shopping->get_shop_info($product_info[0]['shopid']);
    $shop_products = $shopping->get_user_products($product_info[0]['userid'], $product_info[0]['shopid']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Entreefox</title>
    <link rel="shortcut icon" href="<?= ROOT ?>Images/LOGO.PNG" type="image/x-icon">
    <script src="<?= ROOT ?>new_home_page.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= ROOT ?>CSS/Single_product_stylesheet.css" />
    <link rel="stylesheet" href="<?= ROOT ?>CSS/navigation_stylesheet.css" />
</head>
<style>
    .SVGs {
        height: 2rem;
        width: 2rem;
        color: black;
    }

    .users {
        display: flex;
        width: 95dvw;
        overflow-x: scroll;
        padding: 1rem 0 1rem 5vw;
        /* background-color: aqua; */
        gap: 0.5rem;
    }

    @media only screen and (max-width: 380px) {
        .menu {
            width: 80vw;
        }
    }
</style>

<body>
    <!-- <?php
            // if ($Error != "") {
            //     echo "<div class='error_shell' id='error_shell'>";
            //     echo " <div class='error'>";
            //     echo "  <P id='error_result'><b>$Error.</b></P>";
            //     echo "  <button onclick='error()' id='error'><b>close</b></button>";
            //     echo " </div>";
            //     echo "</div>";
            // }
            ?> -->
    <div class="top">
        <i class="fa-solid fa-bars menu_icon" id="menu"></i>
    </div>
    <header style="z-index: 100;">
        <div class="buttom">
            <div class="left">
                <a href="<?= ROOT ?>Home"><img src="<?= ROOT ?>Images/LOGO.PNG" alt="" /></a>
                <h1>ENTREEFOX</h1>
            </div>
            <div class="right">
                <a href="<?= ROOT ?>Orders" style="position:relative; z-index: 0">
                    <?php
                    $shopping = new Shopping();
                    $added = $shopping->check_cart2($_SESSION['entreefox_userid']);
                    if ($added) {
                        echo "<span style='background-color: #1777f9; height:10px; width:10px; position: absolute; right: 0; border-radius: 50%;'></span>";
                    }
                    ?>
                    <span id="span" style="background-color: #1777f9; height:10px; width:10px; position: absolute; right: 0; border-radius: 50%; display: none;"></span>
                    <i class="fa-solid fa-shopping-cart menu_icon"></i>
                </a>
            </div>
        </div>
    </header>
    <?php include("shopping_head.php") ?>
    <div class="container">
        <section class="content">
            <div class="images_container">
                <div class="images">
                    <?php
                    $image_array = json_decode($product_info[0]['product_image'], true);
                    foreach ($image_array as $image) {
                    ?>
                        <img src="<?= ROOT . $image ?>">
                    <?php
                    }
                    ?>
                </div>
                <div class="image-description">
                    <span id="current-image">1</span>/<span id="total-images">3</span>
                </div>
            </div>
            <h3><?= $product_info[0]['about_product'] ?></h3>
            <p>Brand: <a href="<?= ROOT ?>Home/<?= $product_info[0]['shopid'] ?>/<?= $shop_info[0]['userid'] ?>"><?= $shop_info[0]['shopname'] ?></a> | <a href="<?= ROOT ?>Home/<?= $product_info[0]['shopid'] ?>/<?= $shop_info[0]['userid'] ?>/<?= $URL[1] ?>">Slimilar products from <?= $shop_info[0]['shopname'] ?></a></p>
            <h2><?php $price = (int)$product_info[0]['product_price'] . ".00";
                $price2 = number_format($price, 2, '.', ',');
                echo "&#8358 " . $price2;
                $pieces = $shopping->get_pieces_left($_SESSION['entreefox_userid'], $URL[1], $product_info[0]['product_pieces']);
                ?></h2>
            <p>(<?= $pieces ?>) units left</p>
        </section>
        <section class="other">
            <a href="<?= ROOT ?>Home/<?= $product_info[0]['shopid'] ?>/<?= $shop_info[0]['userid'] ?>" class="shop_owner">
                <h3>
                    <?= $shop_info[0]['shopname'] ?>
                </h3>
                <button>></button>
            </a>
            <div class="other_products">
                <?php
                if (is_array($shop_products)) {
                    foreach ($shop_products as $Shop_product) {
                        $image_array = json_decode($Shop_product['product_image'], true);
                        $Shop_product['product_image'] = $image_array[0];
                        include("shop_products.php");
                    }
                }
                ?>
            </div>
        </section>
    </div>
    <div class="operation">
        <a href="<?= ROOT ?>Home">
            <button>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="#1777f9">
                    <path d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z" />
                </svg>
            </button>
        </a>
        <?php
        $added = $shopping->check_cart($URL[1], $_SESSION['entreefox_userid']);
        if ($added) {
            if ($added[0]['pieces'] >= $product_info[0]['product_pieces']) {
                $z_index = "-1";
                $back_ground = "rgba(0, 0, 0, 0.2)";
            } else {
                $z_index = "1";
                $back_ground = "#1777f9";

                // $z_index = "-1";
                // $back_ground = "rgba(0, 0, 0, 0.2)";
            }
        }
        if ($added) {
            $display = "flex";
            $display2 = "none";
        } else {
            $display2 = "flex";
            $display = "none";
        }
        ?>
        <div class="increase_decrease" style="display: <?= $display ?>;" id="pieces_number">
            <a onclick="add_to_cart(event, this)" href="<?= ROOT ?>add_to_cart/product_decrement/<?= $URL[1] ?>/<?= $product_info[0]['shopid'] ?>/<?= $added[0]['pieces'] ?>" id="decrease_button">
                <button>
                    -
                </button>
            </a>
            <p id="amount_pieces"><?= $added[0]['pieces'] ?></p>
            <? "activity[1], productid[2], shopid[3], added_pieces[4]" ?>
            <a onclick="add_to_cart(event, this)" href="<?= ROOT ?>add_to_cart/product_increment/<?= $URL[1] ?>/<?= $product_info[0]['shopid'] ?>/<?= $added[0]['pieces'] ?>" style="z-index: <?= $z_index ?>;" id="increase_button">
                <button style="background-color: <?= $back_ground ?>;" id="increase_second_button">
                    +
                </button>
            </a>
        </div>
        <a onclick="add_to_cart(event, this)" href="<?= ROOT ?>add_to_cart/add/<?= $URL[1] ?>/<?= $product_info[0]['shopid'] ?>" style="display: <?= $display2 ?>;" id="add_cart">
            <button id="btn_add">
                <div class="btn_content">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="white">
                        <path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z" />
                    </svg>
                    Add to cart
                </div>
                <div class="btn_load">

                </div>
            </button>
        </a>
    </div>
    <script>
        var lastScrollTop = 200;
        document.getElementById("menu").addEventListener("click", function(e) {
            const menu_icon = document.getElementById("menu");
            const menu_div = document.getElementById("menu_container");
            if (menu_icon.classList.contains("fa-bars")) {
                menu_icon.classList.replace("fa-bars", "fa-xmark");
                menu_div.classList.add("open_menu");
            } else {
                menu_icon.classList.replace("fa-xmark", "fa-bars");
                menu_div.classList.remove("open_menu");
            }
        })

        function ajax_send(data, element) {
            var ajax = new XMLHttpRequest();

            ajax.addEventListener("readystatechange", function() {
                if (ajax.readyState == 4 && ajax.status == 200) {
                    response(ajax.responseText, element);
                }
            });
            data = JSON.stringify(data);

            ajax.open("post", "<?= ROOT ?>ajax.php", true);
            // alert(link);
            ajax.send(data, element);
        }

        function response(result, element) {
            if (result != "") {
                var obj = JSON.parse(result);
                if (typeof obj.action != "undefined") {
                    remove_loader();
                    if (obj.action == "Added") {
                        // var amount = "";
                        // amount = parseInt(obj.amount) > 0 ? obj.amount : "";
                        amount = document.getElementById('amount_pieces');
                        if (isNaN(Number(amount.textContent.trim())) || amount.textContent.trim() === "") {
                            amount.textContent = 1;
                        }
                        document.getElementById('add_cart').style.display = "none";
                        document.getElementById('pieces_number').style.display = "flex";
                        document.getElementById('increase_button').style.zIndex = 1;
                        document.getElementById('increase_second_button').style.backgroundColor = "#1777f9";
                        if (obj.sum != 0) {
                            document.getElementById('span').style.display = "block";
                        }
                    } else if (obj.action == "decrement") {
                        amount = document.getElementById('amount_pieces');
                        if (!isNaN(Number(amount.textContent.trim())) || amount.textContent.trim() !== "") {
                            amount.textContent = parseInt(amount.textContent, 10) - 1;
                        }
                        document.getElementById('increase_button').style.zIndex = 1;
                        document.getElementById('increase_second_button').style.backgroundColor = "#1777f9";
                    } else if (obj.action == "increment") {
                        amount = document.getElementById('amount_pieces');
                        if (!isNaN(Number(amount.textContent.trim())) || amount.textContent.trim() !== "") {
                            amount.textContent = parseInt(amount.textContent, 10) + 1;
                        }
                    } else if (obj.action == "increment_limit") {
                        document.getElementById('add_cart').style.display = "none";
                        document.getElementById('pieces_number').style.display = "flex";
                        document.getElementById('increase_button').style.zIndex = -1;
                        document.getElementById('increase_second_button').style.backgroundColor = "rgba(0, 0, 0, 0.2)";
                        amount = document.getElementById('amount_pieces');
                        if (!isNaN(Number(amount.textContent.trim())) || amount.textContent.trim() !== "") {
                            if(isNaN(Number(amount.textContent.trim()))){
                                amount.textContent = 1;
                            }else{
                                amount.textContent = parseInt(amount.textContent, 10) + 1;
                            }
                        }else{
                            amount.textContent = "1";
                        }
                        if (obj.sum != 0) {
                            document.getElementById('span').style.display = "block";
                        }
                    } else if (obj.action == "Added_increment_limit") {
                        document.getElementById('add_cart').style.display = "none";
                        document.getElementById('pieces_number').style.display = "flex";
                        document.getElementById('increase_button').style.zIndex = -1;
                        document.getElementById('increase_second_button').style.backgroundColor = "rgba(0, 0, 0, 0.2)";
                        amount = document.getElementById('amount_pieces');
                        amount.textContent = 1;
                        if (obj.sum != 0) {
                            document.getElementById('span').style.display = "block";
                        }
                    } else if (obj.action == "decrement_limit") {
                        document.getElementById('add_cart').style.display = "flex";
                        document.getElementById('pieces_number').style.display = "none";
                        amount = document.getElementById('amount_pieces');
                        amount.textContent = "";
                        if (obj.sum == 0) {
                            document.getElementById('span').style.display = "none";
                        }
                    }
                }
            }
        }

        function add_to_cart(fuck, these) {
            fuck.preventDefault();
            display_loader();
            var link = these.getAttribute("href");
            // alert(link);
            var data = {};
            data.link = link;
            data.action = "Add_to_cart";
            ajax_send(data, fuck.target);
        }

        function display_loader() {
            const btn = document.getElementById("btn_add");
            btn.querySelector(".btn_content").classList.add("remove_btn_content");
            btn.querySelector(".btn_load").classList.add("display_btn_load");
            const inc_btn = document.getElementById('increase_button');
            const dec_btn = document.getElementById('decrease_button');
            inc_btn.style.zIndex = -1;
            dec_btn.style.zIndex = -1;
            dec_btn.style.opacity = 0.5;
            inc_btn.style.opacity = 0.5;
        }

        function remove_loader() {
            const btn = document.getElementById("btn_add");
            btn.querySelector(".btn_content").classList.remove("remove_btn_content");
            btn.querySelector(".btn_load").classList.remove("display_btn_load");
            const inc_btn = document.getElementById('increase_button');
            const dec_btn = document.getElementById('decrease_button');
            inc_btn.style.zIndex = 1;
            dec_btn.style.zIndex = 1;
            dec_btn.style.opacity = 1;
            inc_btn.style.opacity = 1;
        }
        const container = document.querySelector('.images_container');
        const imageWrapper = document.querySelector('.images');
        const images = document.querySelectorAll('.images img');
        const currentImageElement = document.getElementById('current-image');
        const totalImagesElement = document.getElementById('total-images');

        let isDragging = false;
        let startPos = 0;
        let currentTranslate = 0;
        let prevTranslate = 0;
        let animationID;
        let currentIndex = 0;
        let startY = 0; // Store the starting Y position to differentiate vertical scrolling

        totalImagesElement.textContent = images.length; // Set the total number of images

        images.forEach((image, index) => {
            const imageWidth = image.clientWidth;

            image.addEventListener('dragstart', (e) => e.preventDefault());

            // Touch events
            image.addEventListener('touchstart', touchStart(index));
            image.addEventListener('touchend', touchEnd);
            image.addEventListener('touchmove', touchMove);

            // Mouse events
            image.addEventListener('mousedown', touchStart(index));
            image.addEventListener('mouseup', touchEnd);
            image.addEventListener('mousemove', touchMove);
            image.addEventListener('mouseleave', touchEnd);
        });

        function touchStart(index) {
            return function(event) {
                isDragging = true;
                currentIndex = index;
                startPos = getPositionX(event);
                startY = getPositionY(event); // Record the starting Y position
                animationID = requestAnimationFrame(animation);
                container.classList.add('grabbing');
            };
        }

        function touchEnd() {
            isDragging = false;
            cancelAnimationFrame(animationID);

            const movedBy = currentTranslate - prevTranslate;

            if (movedBy < -100 && currentIndex < images.length - 1) currentIndex += 1;
            if (movedBy > 100 && currentIndex > 0) currentIndex -= 1;

            setPositionByIndex();
            container.classList.remove('grabbing');
        }

        function touchMove(event) {
            if (isDragging) {
                const currentPosition = getPositionX(event);
                currentTranslate = prevTranslate + currentPosition - startPos;

                // Prevent vertical scrolling
                const currentY = getPositionY(event);
                const diffY = Math.abs(currentY - startY);
                if (diffY > 0) {
                    event.preventDefault();
                }
            }
        }

        function getPositionX(event) {
            return event.type.includes('mouse') ? event.pageX : event.touches[0].clientX;
        }

        function getPositionY(event) {
            return event.type.includes('mouse') ? event.pageY : event.touches[0].clientY;
        }

        function animation() {
            setSliderPosition();
            if (isDragging) requestAnimationFrame(animation);
        }

        function setSliderPosition() {
            imageWrapper.style.transform = `translateX(${currentTranslate}px)`;
        }

        function setPositionByIndex() {
            currentTranslate = currentIndex * -images[0].clientWidth;
            prevTranslate = currentTranslate;
            setSliderPosition();
            updateDescription(); // Update the description after position change
        }

        function updateDescription() {
            currentImageElement.textContent = currentIndex + 1;
        }
    </script>
</body>

</html>