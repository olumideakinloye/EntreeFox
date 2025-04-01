<?php
include("autoload.php");
require 'vendor/autoload.php'; // Load Stripe PHP library

\Stripe\Stripe::setApiKey('sk_test_51Pru802LbXrmAUAqBpB8yX5U2HH7uNT6I4rxMZPdvbqX8PeNMX5PWAL3F1v8M1Boq4dTbida4F2xM3etX6Ga26CL00aCuTy2l1'); // Replace with your secret key

if (isset($_GET['session_id'])) {
    $session_id = $_GET['session_id']; // Retrieve the session ID from the URL

    try {
        // Retrieve the session using the session ID
        $session = \Stripe\Checkout\Session::retrieve($session_id);

        // Now you have the session object and can access the payment intent, etc.
        $paymentIntentId = $session->payment_intent;

        if (isset($URL[1]) && is_numeric($URL[1]) && isset($URL[2]) && is_numeric($URL[2])) {
            $has_payed = false;
            if (!empty($product_info[0]['payed_buyer'])) {
                $payed_buyers = json_decode($product_info[0]['payed_buyer'], true);
                if (is_array($payed_buyers)) {
                    $payed_user_ids = array_column($payed_buyers, "buyer");
                    if (in_array($userid, $payed_user_ids)) {
                        $has_payed = true;
                    }
                }
            }
            $shopping = new Shopping();
            $product_info = $shopping->get_product($URL[1], $URL[2]);
            $buyers_arr = json_decode($product_info[0]['buyers'], true);
            $user_ids = array_column($buyers_arr, "userid");
            if (in_array($_SESSION['entreefox_userid'], $user_ids) && $has_payed === false) {
                $key = array_search($_SESSION['entreefox_userid'], $user_ids);
                $bought_pieces = $buyers_arr[$key]['pieces'];
                $pieces_left = $product_info[0]['product_pieces'] - $bought_pieces;
                $arr['buyer'] = $_SESSION['entreefox_userid'];
                $arr['payment_intent_id'] = $paymentIntentId;
                $payed_buyers = json_decode($product_info[0]['payed_buyer'], true);
                $payed_buyers[] = $arr;
                $buyers_arr = array_merge(array_slice($buyers_arr, 0, $key), array_slice($buyers_arr, $key + 1));
                $new_arr = json_encode($payed_buyers, true);
                $sql = "update products set payed_buyer = '$new_arr', product_pieces = '$pieces_left' where productid = '$URL[1]' && shopid = '$URL[2]' limit 1";
                $DB = new Database();
                $DB->save($sql);
                header("Location: " . ROOT . "Orders");
            }
        }
    } catch (Exception $e) {
        echo 'Error retrieving session: ' . $e->getMessage();
    }
} else {
    echo "No session ID found!";
}
