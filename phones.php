<?php
if (is_array($phone_row['product_image'])) {
}
$product_image = json_decode($phone_row['product_image'], true);
?>
<a href="<?=ROOT?>Single_Product/<?= $phone_row['productid']?>" style="color: black; text-decoration:none;">
    <div class="item">
        <div class="image" style="background-color:rgba(0, 0, 0, 0.2); background-image:url(<?= $product_image[0] ?>)">

        </div>
        <p class="name"><?= $phone_row['product_name'] ?></p>
        <p class="price">
            &#8358;
            <?php
            $price = (int)$phone_row['product_price'] . ".00";
            $price2 = number_format($price, 2, '.', ',');;
            echo $price2;
            ?>
        </p>
    </div>
</a>