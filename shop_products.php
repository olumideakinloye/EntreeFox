<?php
if ($Shop_product['productid'] != $URL[1]) {
?>
    <a href="<?= ROOT ?>Single_Product/<?= $Shop_product['productid'] ?>/<?= $product_info[0]['shopid'] ?>">
        <div class="box">
            <img src="<?= ROOT . $Shop_product['product_image'] ?>">
            <p><?= $Shop_product['product_name'] ?></p>
            <h4><?php $price = (int)$Shop_product['product_price'] . ".00";
                $price2 = number_format($price, 2, '.', ',');
                echo "&#8358 " . $price2; ?>
            </h4>
        </div>
    </a>
<?php
}
?>