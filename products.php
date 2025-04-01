<a href="<?= ROOT ?>Buyers/<?= $Products['productid'] ?>" style="color: black; text-decoration: none;">
    <div class="box box1">
        <?php
        $shopping = new Shopping();
        $shopid = $shopping->get_shopid($_SESSION['entreefox_userid']);
        $product_info = $shopping->get_product($Products['productid'], $shopid[0]['shopid']);
        if (!empty($product_info[0]['buyers'])) {
            $buyer_arr = json_decode($product_info[0]['buyers'], true);
            if(!empty($buyer_arr) && is_array($buyer_arr)){
        ?>
            <span></span>
        <?php
            }
        }
        ?>
        <img src="<?= ROOT . $Products['product_image'] ?>">
        <p><?= $Products['product_name'] ?></p>
        <h4>
            <b>&#8358
                <?php
                $shopping = new Shopping();
                $price = (int)$Products['product_price'] . ".00";
                $price2 = number_format($price, 2, '.', ',');;
                echo $price2;
                ?>
            </b>
        </h4>
    </div>
</a>