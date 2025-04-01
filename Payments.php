<?php
include("autoload.php");
if (isset($URL[1]) && is_numeric($URL[1]) && isset($URL[2]) && is_numeric($URL[2])) {
    $shopping = new Shopping();
    $product_info = $shopping->get_product($URL[1], $URL[2]);
    $buyers_arr = json_decode($product_info[0]['buyers'], true);
    $user_ids = array_column($buyers_arr, "userid");
    $delivery_price = 0;
    if (!empty($product_info[0]['approved_buyers'])) {
        $approved_buyers = json_decode($product_info[0]['approved_buyers'], true);
        if (is_array($approved_buyers)) {
            $approved_user_ids = array_column($approved_buyers, "buyer");
            if (in_array($_SESSION['entreefox_userid'], $approved_user_ids)) {
                $key = array_search($_SESSION['entreefox_userid'], $approved_user_ids);
                $delivery_price = $approved_buyers[$key]['delivery_price'];
            }
        }
    }
    if (in_array($_SESSION['entreefox_userid'], $user_ids)) {
        $key = array_search($_SESSION['entreefox_userid'], $user_ids);
        $total_price = ($buyers_arr[$key]['pieces'] * $product_info[0]['product_price']);

        $amountInNaira = $product_info[0]['product_price'];
        $amountInKobo = $amountInNaira * 100;
        $deliveryAmountInNaira = $delivery_price;
        $deliveryAmountInKobo = $deliveryAmountInNaira * 100;


        require __DIR__ . "/vendor/autoload.php";
        $secreate_key = "sk_test_51Pru802LbXrmAUAqBpB8yX5U2HH7uNT6I4rxMZPdvbqX8PeNMX5PWAL3F1v8M1Boq4dTbida4F2xM3etX6Ga26CL00aCuTy2l1";
        try {
            \Stripe\Stripe::setApiKey($secreate_key);

            $checkout_session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                "line_items" => [
                    [
                        "quantity" => $buyers_arr[$key]['pieces'],
                        "price_data" => [
                            "currency" => "ngn",
                            "unit_amount" => $amountInKobo,
                            "product_data" => [
                                "name" => $product_info[0]['product_name'],
                            ]
                        ]
                    ],
                    [
                        'price_data' => [
                            'currency' => 'ngn', // Set the currency
                            'product_data' => [
                                'name' => 'Delivery Charge', // Set this line item as delivery price
                            ],
                            'unit_amount' => $deliveryAmountInKobo, // Delivery price in kobo (e.g., 1500 kobo = 15 NGN)
                        ],
                        'quantity' => 1,
                    ]
                ],
                "mode" => "payment",
                "success_url" => ROOT . "success/" . $URL[1] . "/" . $URL[2] . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => ROOT . "cancel/" . $URL[1] . "/" . $URL[2],
            ]);
            header("Location: " . $checkout_session->url);
            exit();
        } catch (Exception $e) {
            echo 'Error creating session: ' . $e->getMessage();
        }
    }
}
