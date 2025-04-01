<?php
$shopping = new Shopping();
$shop_id = $shopping->get_shopid_by_product_id($product['productid']);
$product_info = $shopping->get_product($product['productid'], $shop_id[0]['shopid']);
$image_array = json_decode($product_info[0]['product_image'], true);
$product_info[0]['product_image'] = $image_array[0];
?>
<div class="order">
    <img src="<?= ROOT . $image_array[0] ?>" alt="">
    <div class="order_info">
        <div class="order_info_text">
            <p><?= $product_info[0]['about_product'] ?></p>
            <p>Order: #<?= $product['productid'] ?></p>
        </div>
        <div class="order_info_buttom">
            <?php
            $is_approved = false;
            if (!empty($product_info[0]['approved_buyers'])) {
                $payed_buyers = json_decode($product_info[0]['approved_buyers'], true);
                if (is_array($payed_buyers)) {
                    $payed_user_ids = array_column($payed_buyers, "buyer");
                    if (in_array($_SESSION['entreefox_userid'], $payed_user_ids)) {
                        $is_approved = true;
                    }
                }
            }
            if ($is_approved === true) {
            ?>
                <a href="<?= ROOT . 'Payments/' . $product['productid'] . '/' . $shop_id[0]['shopid'] ?>"><button>Confirm</button></a>
            <?php
            } else {
            ?>
                <button>Pending</button>
            <?php
            }
            ?>
            <p>On
                <?php
                $time = new Time();

                $Time = $time->get_time2($product['date']);
                echo $Time
                ?>
            </p>
        </div>
    </div>
</div>