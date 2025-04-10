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
            } else {
                $shop = new Shopping();
                $cart_products = $shop->get_cart($_SESSION['entreefox_userid']);
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

// if (isset($URL[1])) {
//     $shopping = new Shopping();
//     $product_info = $shopping->get_product_with_productid($URL[1]);
//     $shop_info = $shopping->get_shop_info($product_info[0]['shopid']);
//     $shop_products = $shopping->get_user_products($product_info[0]['userid'], $product_info[0]['shopid']);
// }
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
    <!-- <link rel="stylesheet" href="<?= ROOT ?>CSS/Single_product_stylesheet.css" />
    <link rel="stylesheet" href="<?= ROOT ?>CSS/navigation_stylesheet.css" /> -->
    <link rel="stylesheet" href="<?= ROOT ?>CSS/Cart_stylesheet.css" />
</head>

<body>
    <header id="header">
        <a href="<?= isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ROOT . "Home" ?>"><i class="fa-classic fa-solid fa-arrow-left fa-fw float"></i></a>
        <h1>Cart</h1>
    </header>
    <div class="container">
        <?php
        if ($cart_products  && is_array($cart_products)) {
            $sum = 0;
            foreach ($cart_products as $product) {
                $product_info = $shop->get_product_with_productid($product['productid']);
                $sum += ($product_info[0]['product_price'] * $product['pieces']);
                $imgs = json_decode($product_info[0]['product_image'], true);
                if (is_array($imgs)) {
                    $img = $imgs[0];
                } else {
                    $img = $product_info[0]['product_image'];
                }
                // print_r($product_info);
        ?>
                <div class="products" id="<?= $product['productid'] ?>">
                    <a href="<?= ROOT ?>Single_Product/<?= $product['productid'] ?>" style="color: black; text-decoration: none;">
                        <div class="product_info">
                            <img src="<?= ROOT . $img ?>" alt="">
                            <div class="about">
                                <p>
                                    <?= htmlspecialchars($product_info[0]['about_product']) ?>
                                </p>
                                <p><?php $price = (int)$product_info[0]['product_price'] . ".00";
                                    $price2 = number_format($price, 2, '.', ',');
                                    echo htmlspecialchars("₦" . $price2); ?></p>
                            </div>
                        </div>
                    </a>
                    <?php $added = $shop->check_cart($product['productid'], $_SESSION['entreefox_userid']);
                    if ($added) {
                        if ($added[0]['pieces'] >= $product_info[0]['product_pieces']) {
                            $z_index = "-1";
                            $back_ground = "rgba(0, 0, 0, 0.2)";
                            $z_index_2 = "1";
                            $back_ground_2 = "#1777f9";
                            if ($added[0]['pieces'] == 1) {
                                $z_index = "-1";
                                $back_ground = "rgba(0, 0, 0, 0.2)";

                                $z_index_2 = "-1";
                                $back_ground_2 = "rgba(0, 0, 0, 0.2)";
                            }
                        } elseif ($added[0]['pieces'] == 1) {
                            $z_index = "1";
                            $back_ground = "#1777f9";

                            $z_index_2 = "-1";
                            $back_ground_2 = "rgba(0, 0, 0, 0.2)";
                        } else {
                            $z_index = "1";
                            $back_ground = "#1777f9";
                            $z_index_2 = "1";
                            $back_ground_2 = "#1777f9";
                        }
                    } ?>
                    <div class="controls">
                        <a onclick="add_to_cart(event, this)" href="<?= ROOT ?>add_to_cart/Remove/<?= $product['productid'] ?>/<?= $product_info[0]['shopid'] ?>/<?= $product['pieces'] ?>">
                            <button>Remove</button>
                        </a>
                        <div class="inc_dec">
                            <a onclick="add_to_cart(event, this)" href="<?= ROOT ?>add_to_cart/product_decremen_cart/<?= $product['productid'] ?>/<?= $product_info[0]['shopid'] ?>/<?= $product['pieces'] ?>" style="z-index: <?= $z_index_2 ?>;" id="decrease_button">
                                <button style="z-index: <?= $z_index_2 ?>; background-color: <?= $back_ground_2 ?>" id="decrease_second_button">
                                    -
                                </button>
                            </a>
                            <p id="amount_pieces">
                                <?= htmlspecialchars($product['pieces']) ?>
                            </p>
                            <a onclick="add_to_cart(event, this)" href="<?= ROOT ?>add_to_cart/product_increment/<?= $product['productid'] ?>/<?= $product_info[0]['shopid'] ?>/<?= $product['pieces'] ?>" style="z-index: <?= $z_index ?>;" id="increase_button">
                                <button style="z-index: <?= $z_index ?>; background-color: <?= $back_ground ?>" id="increase_second_button">
                                    +
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
            <footer id="footer">
                <p>Total:
                    <?php $price = (int)$sum . ".00";
                    $price2 = number_format($price, 2, '.', ',');
                    echo htmlspecialchars("₦" . $price2);
                    ?>
                </p>
                <button>Checkout</button>
            </footer>
        <?php
        } else {
        ?>
            <h1 style="text-align: center;">Your cart is empty</h1>
        <?php
        }
        ?>

    </div>
    <script>
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
                    remove_loader(element);
                    let parent_div = element.closest(".controls");
                    amount = parent_div.querySelector(".inc_dec p");
                    const inc_btn = parent_div.querySelector(".inc_dec a:nth-of-type(2)");
                    const dec_btn = parent_div.querySelector(".inc_dec a:nth-of-type(1)");
                    const inc_btn2 = parent_div.querySelector(".inc_dec a:nth-of-type(2) button");
                    const dec_btn2 = parent_div.querySelector(".inc_dec a:nth-of-type(1) button");

                    if (obj.action == "decrement") {
                        if (!isNaN(Number(amount.textContent.trim())) || amount.textContent.trim() !== "") {
                            amount.textContent = parseInt(amount.textContent, 10) - 1;
                        }
                        inc_btn.style.zIndex = 1;
                        inc_btn2.style.backgroundColor = "#1777f9";
                    } else if (obj.action == "increment") {
                        dec_btn.style.zIndex = 1;
                        dec_btn2.style.backgroundColor = "#1777f9";
                        if (!isNaN(Number(amount.textContent.trim())) || amount.textContent.trim() !== "") {
                            amount.textContent = parseInt(amount.textContent, 10) + 1;
                        }
                    } else if (obj.action == "increment_limit") {
                        inc_btn.style.zIndex = -1;
                        inc_btn2.style.backgroundColor = "rgba(0, 0, 0, 0.2)";
                        if (!isNaN(Number(amount.textContent.trim())) || amount.textContent.trim() !== "") {
                            if (isNaN(Number(amount.textContent.trim()))) {
                                amount.textContent = 1;
                            } else {
                                amount.textContent = parseInt(amount.textContent, 10) + 1;
                            }
                        } else {
                            amount.textContent = "1";
                        }
                    } else if (obj.action == "Added_increment_limit") {
                        // document.getElementById('add_cart').style.display = "none";
                        // document.getElementById('pieces_number').style.display = "flex";
                        inc_btn.style.zIndex = -1;
                        inc_btn2.style.backgroundColor = "rgba(0, 0, 0, 0.2)";
                        amount.textContent = 1;
                        if (obj.sum != 0) {
                            document.getElementById('span').style.display = "block";
                        }
                    } else if (obj.action == "decrement_limit") {
                        // document.getElementById('add_cart').style.display = "flex";
                        // document.getElementById('pieces_number').style.display = "none";
                        dec_btn.style.zIndex = -1;
                        dec_btn2.style.backgroundColor = "rgba(0, 0, 0, 0.2)";
                        amount.textContent = 1;
                        if (obj.sum == 0) {
                            document.getElementById('span').style.display = "none";
                        }
                    } else if (obj.action == "Removed") {
                        document.querySelector(".container").removeChild(document.getElementById(`${obj.id}`))
                        // document.getElementById('pieces_number').style.display = "none";
                        // amount = document.getElementById('amount_pieces');
                        // amount.textContent = "";
                        if (obj.sum == 0) {
                            // document.getElementById('span').style.display = "none";
                            document.querySelector(".container").innerHTML = "<h1 style='text-align: center;'>Your cart is empty</h1>";
                        }
                    }
                    document.getElementById("footer").querySelector("p").innerText = `Total: ${obj.total}`;
                }
            }
        }

        function add_to_cart(fuck, these) {
            fuck.preventDefault();
            display_loader(fuck.target);
            var link = these.getAttribute("href");
            // alert(link);
            var data = {};
            data.link = link;
            data.action = "Add_to_cart";
            ajax_send(data, fuck.target);
        }

        function display_loader(element) {
            let parent_div = element.closest(".controls");
            const inc_btn = parent_div.querySelector(".inc_dec a:nth-of-type(2)");
            const dec_btn = parent_div.querySelector(".inc_dec a:nth-of-type(1)");
            inc_btn.style.zIndex = -1;
            dec_btn.style.zIndex = -1;
            dec_btn.style.opacity = 0.5;
            inc_btn.style.opacity = 0.5;
        }

        function remove_loader(element) {
            let parent_div = element.closest(".controls");
            const inc_btn = parent_div.querySelector(".inc_dec a:nth-of-type(2)");
            const dec_btn = parent_div.querySelector(".inc_dec a:nth-of-type(1)");
            inc_btn.style.zIndex = 1;
            dec_btn.style.zIndex = 1;
            dec_btn.style.opacity = 1;
            inc_btn.style.opacity = 1;
        }
        window.addEventListener("load", () => {
            document.querySelector(".container").style.paddingTop = `${document.getElementById("header").clientHeight + 20}px`
            document.querySelector(".container").style.paddingBottom = `${document.getElementById("footer").clientHeight + 20}px`
        })
        window.addEventListener("resize", () => {
            document.querySelector(".container").style.paddingTop = `${document.getElementById("header").clientHeight + 20}px`
            document.querySelector(".container").style.paddingBottom = `${document.getElementById("footer").clientHeight + 20}px`
        })
    </script>
</body>

</html>