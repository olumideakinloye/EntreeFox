<?php
include("autoload.php");

$login = new Login();
$_SESSION['entreefox_userid'] = isset($_SESSION['entreefox_userid']) ? $_SESSION['entreefox_userid'] : 0;
$user_data = $login->check_login($_SESSION['entreefox_userid'], false);


$create = "";
$arr[] = "";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Orders</title>
    <link rel="shortcut icon" href="<?= ROOT ?>Images/LOGO.PNG" type="image/x-icon">
    <link rel="stylesheet" href="<?= ROOT ?>CSS/Orders_stylesheet.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        function cancel(fuck, these) {
            fuck.preventDefault();
            var link = these.getAttribute("href");
            history.pushState(null, "", link);
            document.getElementById("cancel").style.display = "flex";
        }

        function error() {
            const error_shell = document.getElementById("error_shell");
            error_shell.style.display = "none";
        }

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
                // alert(result);
                var obj = JSON.parse(result);
                if (typeof obj.action != "undefined") {
                    if (obj.action == "Add") {
                        var amount = "";
                        amount = parseInt(obj.amount) > 0 ? obj.amount : "";
                        document.getElementById('amount' + obj.id + 'pieces').innerHTML = amount;
                        document.getElementById('sum').innerHTML = "Checkout(&#8358 " + obj.sum + ")";
                        document.getElementById('decrease' + obj.id + 'button').style.zIndex = 1;
                        document.getElementById('decrease_second' + obj.id + 'button').style.backgroundColor = "#1777f9";
                        document.getElementById('increase' + obj.id + 'button').style.zIndex = 1;
                        document.getElementById('increase_second' + obj.id + 'button').style.backgroundColor = "#1777f9";
                    } else if (obj.action == "increase_limit") {
                        document.getElementById('increase' + obj.id + 'button').style.zIndex = -1;
                        document.getElementById('increase_second' + obj.id + 'button').style.backgroundColor = "rgba(0, 0, 0, 0.2)";
                        amount = parseInt(obj.amount) > 0 ? obj.amount : "";
                        document.getElementById('sum').innerHTML = "Checkout(&#8358 " + obj.sum + ")";
                        document.getElementById('amount' + obj.id + 'pieces').innerHTML = amount;
                    } else if (obj.action == "decrease_limit") {
                        document.getElementById('decrease' + obj.id + 'button').style.zIndex = -1;
                        document.getElementById('decrease_second' + obj.id + 'button').style.backgroundColor = "rgba(0, 0, 0, 0.2)";
                        amount = parseInt(obj.amount) > 0 ? obj.amount : "";
                        document.getElementById('amount' + obj.id + 'pieces').innerHTML = amount;
                        document.getElementById('sum').innerHTML = "Checkout(&#8358 " + obj.sum + ")";
                    } else if (obj.action == "Remove") {
                        document.getElementById('add' + obj.id + 'cart').style.display = "none";
                        document.getElementById('sum').innerHTML = "Checkout(&#8358 " + obj.sum + ")";
                        if (obj.sum == 0) {
                            document.getElementById('empty').style.display = "block";
                            document.getElementById('check_out').style.display = "none";
                        }
                    } else if (obj.action == "delete_order") {
                        document.getElementById("section" + obj.id + "confirmation").style.display = "none";
                        document.getElementById("cancel").style.display = "none";
                    }
                }
            }
        }

        function handleClick(fuck, these) {
            fuck.preventDefault();
            var link = these.getAttribute("href");
            // alert(link);
            var data = {};
            data.link = link;
            data.action = "Add_to_cart_from_cart";
            ajax_send(data, fuck.target);
        }

        function remove_order() {
            var link = window.document.location.href;
            const url = window.document.location.href.split("/");
            // alert(url[5] + url[6] + url[7]);
            window.location.href = `<?= ROOT ?>cancel/${url[5]}/${url[6]}`;
        }


        function cancel_close() {
            var link = "<?= ROOT . "Orders" ?>";
            history.pushState(null, "", link);
            document.getElementById("cancel").style.display = "none";
        }

        function Confirmed() {
            const imageWrapper = document.querySelector('.section');
            imageWrapper.style.transform = `translateX(${-100}vw)`;

            document.getElementById("nav1gation").style.color = "black";
            document.getElementById("nav2gation").style.color = "#1777f9";
            document.getElementById("nav3gation").style.color = "black";
            const animation = document.querySelector(".scroll");
            animation.style.transform = `translateX(${34}vw)`;
        }

        function Pending() {
            const imageWrapper = document.querySelector('.section');
            imageWrapper.style.transform = `translateX(${0}vw)`;

            document.getElementById("nav1gation").style.color = "#1777f9";
            document.getElementById("nav2gation").style.color = "black";
            document.getElementById("nav3gation").style.color = "black";

            const animation = document.querySelector(".scroll");
            animation.style.transform = `translateX(${0}vw)`;
        }

        function Delivered() {
            const imageWrapper = document.querySelector('.section');
            imageWrapper.style.transform = `translateX(${-200}vw)`;

            document.getElementById("nav1gation").style.color = "black";
            document.getElementById("nav2gation").style.color = "black";
            document.getElementById("nav3gation").style.color = "#1777f9";

            const animation = document.querySelector(".scroll");
            animation.style.transform = `translateX(${67}vw)`;
        }
    </script>
    <style>
        header i {
            font-size: 40px;
            /* width: 70px; */
            color: black;
        }

        header a {
            margin-left: 20px;
        }
    </style>
</head>

<body>
    <?php
    if ($create != "") {
        echo "<div class='error_shell' id='error_shell'>";
        echo " <div class='error'>";
        echo "  <P id='error_result'><b>$create.</b></P>";
        echo "  <button onclick='error()' id='error'><b>close</b></button>";
        echo " </div>";
        echo "</div>";
    }
    ?>
    <header id="header">
        <div class="left">
            <a href="<?= isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ROOT . "Home" ?>"><i class="fa-classic fa-solid fa-arrow-left fa-fw"></i></a>
            <h1>Orders</h1>
        </div>
    </header>
    <div class="content" id="content">
        <div class="navigation">
            <nav class="navigation_navigation">
                <div class="nav" onclick="Pending()" id="nav1gation">Pending Orders</div>
                <div class="nav" onclick="Confirmed()" id="nav2gation">Confirmed Orders</div>
                <div class="nav" onclick="Delivered()" id="nav3gation">Delivered Orders</div>
                <div class="scroll">
                </div>
            </nav>
        </div>
        <div class="container">
            <div class="cancel_container" id="cancel" style="display: none;">
                <div class="cancel_box">
                    <p><b>Are you sure you want to cancel this order</b></p>
                    <div class="buttons">
                        <button onclick="remove_order()">Yes</button>
                        <button onclick="cancel_close()">No</button>
                    </div>
                </div>
            </div>
            <div class="section">
                <section class="pending">
                    <?php
                    $shopping = new Shopping();
                    $pending_products = $shopping->check_pending_order($_SESSION['entreefox_userid']);
                    $pending_products2 = json_decode($pending_products, true);
                    if (is_array($pending_products2)) {
                        // echo "<pre>";
                        // print_r($pending_products2);
                        // echo "</pre>";
                        foreach ($pending_products2 as $product) {
                            include("order.php");
                        }
                    } else {
                        echo "<h3>No pending orders</h3>";
                    }
                    ?>
                </section>
                <section class="confirmed">
                    <?php
                    $shopping = new Shopping();
                    $pending_products = $shopping->check_confirmed_order($_SESSION['entreefox_userid']);
                    $pending_products2 = json_decode($pending_products, true);
                    if (is_array($pending_products2)) {
                        // echo "<pre>";
                        // print_r($pending_products2);
                        // echo "</pre>";
                        foreach ($pending_products2 as $product) {
                            include("confirmed_orders.php");
                        }
                    } else {
                        echo "<h3>No confirmed orders</h3>";
                    }
                    ?>
                </section>
                <section class="delivered">
                    <?php
                    $shopping = new Shopping();
                    $pending_products = $shopping->check_delivered_order($_SESSION['entreefox_userid']);
                    $pending_products2 = json_decode($pending_products, true);
                    if (is_array($pending_products2)) {
                        // echo "<pre>";
                        // print_r($pending_products2);
                        // echo "</pre>";
                        foreach ($pending_products2 as $product) {
                            include("delivered_orders.php");
                        }
                    } else {
                        echo "<h3>No delivered orders</h3>";
                    }
                    ?>
                </section>
            </div>
        </div>
    </div>
    <script>
        const container = document.querySelector('.container');
        const imageWrapper = document.querySelector('.section');
        const images = document.querySelectorAll('.section section');
        const animation_nav = document.querySelector(".scroll");
        // const currentImageElement = document.getElementById('current-image');
        // const totalImagesElement = document.getElementById('total-images');
        const indicators = document.querySelectorAll('.navigation_navigation');

        let isDragging = false;
        let startPos = 0;
        let currentTranslate = 0;
        let prevTranslate = 0;
        let animationID;
        let currentIndex = 0;
        let startY = 0; // Store the starting Y position to differentiate vertical scrolling


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
            }
        }

        function getPositionX(event) {
            return event.type.includes('mouse') ? event.pageX : event.touches[0].clientX;
        }

        function animation() {
            setSliderPosition();
            if (isDragging) requestAnimationFrame(animation);
        }

        function setSliderPosition() {
            imageWrapper.style.transform = `translateX(${currentTranslate}px)`;
            animation_nav.style.transform = `translateX(${-currentTranslate / 3}px)`;
            updateIndicators();
        }

        function setPositionByIndex() {
            currentTranslate = currentIndex * -images[0].clientWidth;
            prevTranslate = currentTranslate;
            setSliderPosition();
        }

        function updateIndicators() {
            // currentImageElement.textContent = currentIndex + 1;
            const i = (currentIndex + 1);
            if (i == 1) {
                document.getElementById("nav1gation").style.color = "#1777f9";
                document.getElementById("nav2gation").style.color = "black";
                document.getElementById("nav3gation").style.color = "black";
            } else if (i == 2) {
                document.getElementById("nav1gation").style.color = "black";
                document.getElementById("nav2gation").style.color = "#1777f9";
                document.getElementById("nav3gation").style.color = "black";
            } else if (i == 3) {
                document.getElementById("nav1gation").style.color = "black";
                document.getElementById("nav2gation").style.color = "black";
                document.getElementById("nav3gation").style.color = "#1777f9";
            } else {
                document.getElementById("nav1gation").style.color = "#1777f9";
                document.getElementById("nav2gation").style.color = "black";
                document.getElementById("nav3gation").style.color = "black";
            }

        }
        updateIndicators();
        window.addEventListener("load", () => {
            document.querySelector(".content").style.marginTop = `${document.getElementById("header").clientHeight}px`
        })
    </script>
</body>

</html>