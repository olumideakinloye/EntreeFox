<?php
class Shopping
{
    public function get_specific_products($product_type)
    {
        $DB = new Database();
        $query = "select * from products order by id desc limit 10";
        $result = $DB->read($query);
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    public function get_user_products($userid, $shopid)
    {

        $DB = new Database();
        $query = "select * from products where userid = '$userid' && shopid = '$shopid'";
        return $DB->read($query);
    }
    public function get_all_products($userid)
    {
        $DB = new Database();
        $query = "select * from products where userid != '$userid'";
        return $DB->read($query);
    }
    public function get_shopid($userid)
    {
        $DB = new Database();
        $query = "select shopid from shop where userid = '$userid' limit 1";
        return $DB->read($query);
    }
    public function get_shopid_by_product_id($productid)
    {
        $DB = new Database();
        $query = "select shopid from products where productid = '$productid' limit 1";
        return $DB->read($query);
    }
    public function get_shop_info($shopid)
    {
        $DB = new Database();
        $query = "select * from shop where shopid = '$shopid' limit 1";
        return $DB->read($query);
    }
    public function approve_buyer_request($data, $productid, $buyerid)
    {
        if (!empty($data)) {
            $product_info = $this->get_product_with_productid($productid);
            if (!empty($product_info[0]['approved_buyers'])) {
                $approved_buyers = json_decode($product_info[0]['approved_buyers'], true);
                if (is_array($approved_buyers)) {
                    $approved_user_ids = array_column($approved_buyers, "buyer");
                    if (!in_array($buyerid, $approved_user_ids)) {
                        $arr['buyer'] = $buyerid;
                        $arr['delivery_price'] = $data['delivery_price'];
                        $arr['delivery_date'] = $data['delivery_date'];
                        $approved_buyers[] = $arr;
                        $new_arr = json_encode($approved_buyers, true);
                    }
                } else {
                    $arr['buyer'] = $buyerid;
                    $arr['delivery_price'] = $data['delivery_price'];
                    $arr['delivery_date'] = $data['delivery_date'];
                    $approved_buyers[] = $arr;
                    $new_arr = json_encode($approved_buyers, true);
                }
            } else {
                $arr['buyer'] = $buyerid;
                $arr['delivery_price'] = $data['delivery_price'];
                $arr['delivery_date'] = $data['delivery_date'];
                $approved_buyers[] = $arr;
                $new_arr = json_encode($approved_buyers, true);
            }
            $deliver_price =  $data['delivery_price'];
            $deliver_date = $data['delivery_date'];
            $ordered_pieces = 0;
            if (!empty($product_info[0]['buyers'])) {
                $buyers = json_decode($product_info[0]['buyers'], true);
                if (is_array($buyers)) {
                    $buyers_ids = array_column($buyers, "userid");
                    if (in_array($buyerid, $buyers_ids)) {
                        $key = array_search($buyerid, $buyers_ids);
                        $ordered_pieces = $buyers[$key]['pieces'];
                    }
                }
            }
            $temprary_qua_left = $product_info[0]['temporary_product_quantity'] - $ordered_pieces;
            if (empty($product_info[0]['temporary_product_quantity']) || $product_info[0]['temporary_product_quantity'] == 0) {
                $temprary_qua_left = $product_info[0]['product_pieces'] - $ordered_pieces;
            }
            $DB = new Database();
            $query = "update products set approved_buyers = '$new_arr', current_approved_buyer = '$buyerid', product_state = 'processing', temporary_product_quantity = '$temprary_qua_left' where productid = '$productid' && userid = '$_SESSION[entreefox_userid]'";
            $DB->save($query);
            $activity = "delivery_confirmation";
            $query2 = "insert into notification (userid, activity, contentid, contentid2, delivery_date, notifier_id) values ('$buyerid', '$activity', '$productid', '$deliver_price', '$deliver_date', '$_SESSION[entreefox_userid]')";
            $DB->save($query2);
            return "good";
        } else {
            return "Invalid data";
        }
    }
    public function Add_to_cart($userid, $productid, $shopid, $action)
    {
        $DB = new Database();
        $sql = "select product_pieces from products where productid = '$productid' limit 1 ";
        $result = $DB->read($sql);
        $product_pieces = $result[0]['product_pieces'];
        $sql2 = "select pieces from cart where productid = '$productid' limit 1 ";
        $result = $DB->read($sql2);
        // return $result;
        if($result && is_array($result) && isset($result[0]['pieces'])){
            $pieces = $result[0]['pieces'];
        }else{
            $pieces = 1;
        }
        if ($action === "add") {
            $pieces = 1;
            $sql = "insert into cart (userid, productid, pieces, shopid) values ('$userid', '$productid', '$pieces', '$shopid')";
            $DB->save($sql);
            if($product_pieces == $pieces){
                return "Added_increment_limit";
            }else{
                return "Added";
            }
        } elseif ($action === "decrement") {

            if ($pieces > 1) {
                $pieces = $pieces - 1;
                $sql = "update cart set pieces = '$pieces' where productid = '$productid' limit 1 ";
                $DB->save($sql);
                return "decrement";
            } else {
                $sql = "delete from cart where productid = '$productid' limit 1 ";
                $DB->save($sql);
                return "decrement_limit";
            }
            
        }elseif ($action === "increment") {
            $pieces = $pieces + 1;
            if($pieces < $product_pieces){
                $sql = "update cart set pieces = '$pieces' where productid = '$productid' limit 1 ";
                $DB->save($sql);
                return "increment";
            }elseif ($pieces == $product_pieces) {
                $sql = "update cart set pieces = '$pieces' where productid = '$productid' limit 1 ";
                $DB->save($sql);
                return "increment_limit";
            }
        }
    }
    public function Upload_product($data, $userid, $file)
    {
        // "product_image, date";
        $DB = new Database();
        $shop_array = $this->get_shopid($userid);
        $shopid = $shop_array[0]['shopid'];
        $productid = $this->create_productid();
        $product_name = $data['Product_name'];
        $product_type = $data['Type'];
        $product_category = $data['Product_category'];
        $product_price = $data['Product_price'];
        $about_product = addslashes(ucfirst($data['Description']));
        $product_pieces = $data['Product_quantity'];
        $date = date("Y-m-d H:i:s");
        $fileArray = array();
        $files = $file['Product_pic'];
        $folder = "uploads/" . $userid . "/";
        $result = $this->check_image_authentication($files, $userid);
        if (isset($product_name) && isset($product_type) && isset($product_category) && isset($product_price) && is_numeric($product_price)) {
            if (strstr($product_name, " ")) {
                $explode = explode(" ", $product_name);
                $new_explode = array_map('ucfirst', $explode);
                $product_Name = addslashes(implode(" ", $new_explode));
            } else {
                $product_Name = addslashes(ucfirst($product_name));
            }
            if (strstr($product_type, " ")) {
                $explode = explode(" ", $product_type);
                $new_explode = array_map('ucfirst', $explode);
                $product_Type = addslashes(implode(" ", $new_explode));
            } else {
                $product_Type = addslashes(ucfirst($product_type));
            }
            if (strstr($product_category, " ")) {
                $explode = explode(" ", $product_category);
                $new_explode = array_map('ucfirst', $explode);
                $product_category = addslashes(implode(" ", $new_explode));
            } else {
                $product_category = addslashes(ucfirst($product_category));
            }
            if (!is_numeric($product_price) || !is_numeric($product_pieces)) {
                return "Invalid product price.";
                die;
            }

            if ($result == "") {
                if (!file_exists("../$folder")) {

                    mkdir("../$folder", 0777, true);
                    file_put_contents("../$folder" . "index.php", "");
                }

                foreach ($files["tmp_name"] as $index => $tmpName) {
                    $fileType = mime_content_type($tmpName);
                    $image = new Image();

                    $filename = $image->generate_file_name(15) . ".jpg";
                    $filedir = "../$folder" . $filename;
                    move_uploaded_file($tmpName, $filedir);
                    $image->crop_image($filedir, $filedir, 1000, 1000, $fileType, $userid);
                    $fileArray[] = $folder . $filename;
                }
                $fileArray2 = json_encode($fileArray, true);
            } else {
                return $result;
                die;
            }

            // $shopid = $this->create_shopid();
            $query = "insert into products (userid, shopid, productid, product_name, product_category,  product_type, product_image, product_price, temporary_product_quantity, about_product, product_pieces, date) values('$userid', '$shopid', '$productid', '$product_Name', '$product_category', '$product_Type', '$fileArray2', '$product_price', '$product_pieces', '$about_product', '$product_pieces', '$date')";
            $DB->save($query);
            return "Successful";
        } else {
            return "Missing information";
        }
    }
    public function check_image_authentication($files, $userid)
    {
        $error = "";
        $valid_img_types = ["image/jpg", "image/jpeg", "image/png"];
        foreach ($files["tmp_name"] as $index => $tmpName) {
            if ($files["error"][$index] !== UPLOAD_ERR_OK) {
                $error = "Error uploading file: " . $files["name"][$index];
                continue;
            }
            $fileName = basename($files["name"][$index]);
            $fileType = mime_content_type($tmpName);
            if (in_array($fileType, $valid_img_types)) {

                $allowed_size = 3145728;

                if ($files["size"][$index] < $allowed_size) {
                } else {

                    $error = "Image size should be less than 3MB";
                }
            } else {

                $error = "Invalid image type";
            }
        }
        return $error;
    }
    private function create_productid()
    {
        $length = rand(4, 19);
        $number = "";
        for ($i = 0; $i < $length; $i++) {
            $new_rand = rand(0, 9);
            $number = $number . $new_rand;
        }
        return $number;
    }
    public function formatPrice($price, $currencyCode, $locale = 'en_US')
    {
        $fmt = new NumberFormatter($locale, NumberFormatter::CURRENCY);
        return $fmt->formatCurrency($price, $currencyCode);
    }
    public function check_cart($productid, $userid)
    {
        $DB = new Database();
        $sql = "select * from cart where userid = '$userid' && productid = '$productid' limit 1";
        $result = $DB->read($sql);
        return $result;
    }
    public function check_cart2($userid)
    {
        $DB = new Database();
        $sql = "select * from cart where userid = '$userid'";
        $result = $DB->read($sql);
        return $result;
    }
    public function get_product($productid, $shopid)
    {
        $sql = "select * from products where productid = '$productid' && shopid = '$shopid' limit 1";
        $DB = new Database();
        $result = $DB->read($sql);
        return $result;
    }
    public function get_product_with_productid($productid)
    {
        $sql = "select * from products where productid = '$productid' limit 1";
        $DB = new Database();
        $result = $DB->read($sql);
        return $result;
    }
    public function check_user_type_for_customers($userid)
    {
        $sql = "select * from users where userid = '$userid' limit 1";
        $DB = new Database();
        $result = $DB->read($sql);
        if ($result[0]['user_type'] == "Customer") {
            header("Location: " . ROOT . "Home");
        }
    }
    public function check_out($productid, $sellerid, $request_pieces, $userid)
    {
        $DB = new Database();
        //save likes details
        $sql = "select buyers from products where productid='$productid' limit 1 ";
        $result = $DB->read($sql);

        if (!empty($result[0]['buyers'])) {
            $followers = json_decode($result[0]['buyers'], true);
            if (is_array($followers)) {
                $followers = json_decode($result[0]['buyers'], true);
                $user_ids = array_column($followers, "userid");
                if (!in_array($userid, $user_ids)) {
                    $arr["userid"] = $userid;
                    $arr["pieces"] = $request_pieces;
                    $arr["date"] = date("Y-m-d H:i:s");

                    $followers[] = $arr;
                    $follow_string = json_encode($followers);

                    $sql = "update products set buyers = '$follow_string' where productid = '$productid' limit 1 ";
                    $DB->save($sql);

                    $activity = "buyers_request";
                    $content_id = $productid;
                    $content_id2 = $request_pieces;
                    $sql = "insert into notification (userid, activity, contentid, contentid2, notifier_id) values ('$sellerid', '$activity', '$content_id', '$content_id2', '$userid')";
                    $DB->save($sql);
                    $query = "delete from cart where productid = '$productid' && userid = '$userid' limit 1";
                    $DB->save($query);
                } else {
                    if (!empty($result[0]['new_order'])) {
                        $new_order = json_decode($result[0]['new_order'], true);
                        if (is_array($new_order)) {
                            $new_order = json_decode($result[0]['buyers'], true);
                            $user_ids = array_column($new_order, "userid");
                            if (!in_array($userid, $user_ids)) {
                                $arr["userid"] = $userid;
                                $arr["pieces"] = $request_pieces;
                                $arr["date"] = date("Y-m-d H:i:s");

                                $new_order[] = $arr;
                                $new_order_string = json_encode($new_order);

                                $sql = "update products set new_order = '$new_order_string' where productid = '$productid' limit 1 ";
                                $DB->save($sql);

                                $activity = "buyers_request";
                                $content_id = $productid;
                                $content_id2 = $request_pieces;
                                $sql = "insert into notification (userid, activity, contentid, contentid2, notifier_id) values ('$sellerid', '$activity', '$content_id', '$content_id2', '$userid')";
                                $DB->save($sql);
                                $query = "delete from cart where productid = '$productid' && userid = '$userid' limit 1";
                                $DB->save($query);
                            } else {
                                $key = array_search($userid, $user_ids);
                                $new_request_pieces = $request_pieces + $new_order[$key]['pieces'];
                                $new_order[$key]['pieces'] = $new_request_pieces;
                                $new_order_string = json_encode($new_order);

                                $sql = "update products set new_order = '$new_order_string' where productid = '$productid' limit 1 ";
                                $DB->save($sql);
                                $query = "delete from cart where productid = '$productid' && userid = '$userid' limit 1";
                                $DB->save($query);
                            }
                        } else {
                            $arr["userid"] = $userid;
                            $arr["pieces"] = $request_pieces;
                            $arr["date"] = date("Y-m-d H:i:s");

                            $new_order[] = $arr;
                            $new_order_string = json_encode($new_order);

                            $sql = "update products set new_order = '$new_order_string' where productid = '$productid' limit 1 ";
                            $DB->save($sql);

                            $activity = "buyers_request";
                            $content_id = $productid;
                            $content_id2 = $request_pieces;
                            $sql = "insert into notification (userid, activity, contentid, contentid2, notifier_id) values ('$sellerid', '$activity', '$content_id', '$content_id2', '$userid')";
                            $DB->save($sql);
                            $query = "delete from cart where productid = '$productid' && userid = '$userid' limit 1";
                            $DB->save($query);
                        }
                    } else {
                        $arr["userid"] = $userid;
                        $arr["pieces"] = $request_pieces;
                        $arr["date"] = date("Y-m-d H:i:s");

                        $new_order[] = $arr;
                        $new_order_string = json_encode($new_order);

                        $sql = "update products set new_order = '$new_order_string' where productid = '$productid' limit 1 ";
                        $DB->save($sql);

                        $activity = "buyers_request";
                        $content_id = $productid;
                        $content_id2 = $request_pieces;
                        $sql = "insert into notification (userid, activity, contentid, contentid2, notifier_id) values ('$sellerid', '$activity', '$content_id', '$content_id2', '$userid')";
                        $DB->save($sql);
                        $query = "delete from cart where productid = '$productid' && userid = '$userid' limit 1";
                        $DB->save($query);
                    }
                }
            } else {
                $arr["userid"] = $userid;
                $arr["pieces"] = $request_pieces;
                $arr["date"] = date("Y-m-d H:i:s");

                $followers[] = $arr;
                $follow_string = json_encode($followers);

                $sql = "update products set buyers = '$follow_string' where productid = '$productid' limit 1 ";
                $DB->save($sql);

                $activity = "buyers_request";
                $content_id = $productid;
                $content_id2 = $request_pieces;
                $sql = "insert into notification (userid, activity, contentid, contentid2, notifier_id) values ('$sellerid', '$activity', '$content_id', '$content_id2', '$userid')";
                $DB->save($sql);
                $query = "delete from cart where productid = '$productid' && userid = '$userid' limit 1";
                $DB->save($query);
            }
        } else {
            $arr["userid"] = $userid;
            $arr["pieces"] = $request_pieces;
            $arr["date"] = date("Y-m-d H:i:s");

            $followers[] = $arr;
            $follow_string = json_encode($followers);

            $sql = "update products set buyers = '$follow_string' where productid = '$productid' limit 1 ";
            $DB->save($sql);

            $activity = "buyers_request";
            $content_id = $productid;
            $content_id2 = $request_pieces;
            $sql = "insert into notification (userid, activity, contentid, contentid2, notifier_id) values ('$sellerid', '$activity', '$content_id', '$content_id2', '$userid')";
            $DB->save($sql);
            $query = "delete from cart where productid = '$productid' && userid = '$userid' limit 1";
            $DB->save($query);
        }
    }
    public function get_pieces_left($userid, $productid, $product_pieces)
    {
        $DB = new Database();
        $has_payed = false;
        //save likes details
        $sql = "select buyers from products where productid='$productid' limit 1 ";
        $result = $DB->read($sql);
        $product_info = $this->get_product_with_productid($productid);
        $pieces = $product_info[0]['product_pieces'];
        $shopid = $product_info[0]['shopid'];
        $new_pieces_left = 0;

        if (!empty($product_info[0]['new_order'])) {
            // $new_pieces_left = 5;
            $new_order = json_decode($product_info[0]['new_order'], true);
            $payed_buyers_arr = json_decode($product_info[0]['payed_buyer'], true);
            if (is_array($new_order)) {
                $new_order = json_decode($product_info[0]['new_order'], true);
                $user_ids2 = array_column($new_order, "userid");
                if (is_array($payed_buyers_arr)) {
                    $payed_user_ids = array_column($payed_buyers_arr, "buyer");
                    if (in_array($userid, $payed_user_ids)) {
                        $has_payed = true;
                    }
                }
                if (in_array($userid, $user_ids2) && $has_payed == false) {
                    $key = array_search($userid, $user_ids2);
                    $ordered_pieces2 = $new_order[$key]['pieces'];
                    if ($ordered_pieces2 > $product_pieces) {
                        $new_order = array_merge(array_slice($new_order, 0, $key), array_slice($new_order, $key + 1));
                        $follow_string = json_encode($new_order);
                        $sql = "update products set buyers = '$follow_string' where productid='$productid' limit 1 ";
                        $DB->save($sql);
                        $sql = "update products set new_order = '$follow_string' where productid = '$productid' limit 1 ";
                        $DB->save($sql);
                        $sql = "insert into cart (userid, productid, pieces, shopid) values('$userid', '$productid', '$pieces', '$shopid')";
                        $DB->save($sql);
                    } else {
                        $new_pieces_left = (int)$ordered_pieces2;
                    }
                }
            }
        } else {
            $new_pieces_left = 5;
        }


        if (!empty($result[0]['buyers'])) {
            $followers = json_decode($result[0]['buyers'], true);
            $payed_buyers_arr = json_decode($product_info[0]['payed_buyer'], true);
            if (is_array($followers)) {
                $followers = json_decode($result[0]['buyers'], true);
                $user_ids = array_column($followers, "userid");
                if (is_array($payed_buyers_arr)) {
                    $payed_user_ids = array_column($payed_buyers_arr, "buyer");
                    if (in_array($userid, $payed_user_ids)) {
                        $has_payed = true;
                    }
                }
                if (in_array($userid, $user_ids) && $has_payed == false) {
                    $key = array_search($userid, $user_ids);
                    $ordered_pieces = $followers[$key]['pieces'];
                    if ($ordered_pieces > $product_pieces) {
                        $followers = array_merge(array_slice($followers, 0, $key), array_slice($followers, $key + 1));
                        $follow_string = json_encode($followers);
                        $sql = "update products set buyers = '$follow_string' where productid='$productid' limit 1 ";
                        $DB->save($sql);
                        $sql = "insert into cart (userid, productid, pieces, shopid) values('$userid', '$productid', '$pieces', '$shopid')";
                        $DB->save($sql);
                        return $product_pieces;
                    } else {
                        $pieces_left = (int)$product_pieces - ((int)$ordered_pieces + (int)$new_pieces_left);
                    }
                    return $pieces_left;
                } else {
                    return $product_pieces;
                }
            } else {
                return $product_pieces;
            }
        } else {
            return $product_pieces;
        }
    }
    public function check_pending_order($userid)
    {
        $sql = "select * from products";
        $DB = new Database();
        $result = $DB->read($sql);
        $pending_product3 = "";
        if ($result) {
            foreach ($result as $product) {
                $has_payed = false;
                if (!empty($product['payed_buyer'])) {
                    $payed_buyers = json_decode($product['payed_buyer'], true);
                    if (is_array($payed_buyers)) {
                        $payed_user_ids = array_column($payed_buyers, "buyer");
                        if (in_array($userid, $payed_user_ids)) {
                            $has_payed = true;
                        }
                    }
                }
                if (!empty($product['buyers'])) {
                    $buyers = json_decode($product['buyers'], true);
                    $user_ids = array_column($buyers, "userid");
                    if (in_array($userid, $user_ids) && $has_payed === false) {
                        $key = array_search($userid, $user_ids);
                        $pending_product['productid'] = $product['productid'];
                        $pending_product['date'] = $buyers[$key]['date'];
                        $pending_product2[] = $pending_product;
                        $pending_product3 = json_encode($pending_product2);
                    }
                }
            }
            if ($pending_product3 != "") {
                return $pending_product3;
            } else {
                return "No pending Orders";
            }
        }
    }

    public function check_confirmed_order($userid)
    {
        $sql = "select * from products";
        $DB = new Database();
        $result = $DB->read($sql);
        $pending_product3 = "";
        if ($result) {
            foreach ($result as $product) {
                $is_a_buyer = false;
                if (!empty($product['buyers'])) {
                    $buyers = json_decode($product['buyers'], true);
                    if (is_array($buyers)) {
                        $user_ids = array_column($buyers, "userid");
                        if (in_array($userid, $user_ids)) {
                            $is_a_buyer = true;
                        }
                    }
                }
                if (!empty($product['buyers']) && !empty($product['payed_buyer'])) {
                    $payed_buyers = json_decode($product['payed_buyer'], true);
                    $payed_user_ids = array_column($payed_buyers, "buyer");
                    if ($is_a_buyer === true && in_array($userid, $payed_user_ids)) {
                        $key = array_search($userid, $payed_user_ids);
                        if (!isset($payed_buyers[$key]['delivery_status'])) {
                            $pending_product['productid'] = $product['productid'];
                            $pending_product2[] = $pending_product;
                            $pending_product3 = json_encode($pending_product2);
                        } else if ($payed_buyers[$key]['delivery_status'] != "delivered") {
                            $pending_product['productid'] = $product['productid'];
                            $pending_product2[] = $pending_product;
                            $pending_product3 = json_encode($pending_product2);
                        }
                    }
                }
            }
            if ($pending_product3 != "") {
                return $pending_product3;
            } else {
                return "No confirmed Orders";
            }
        }
    }
    public function check_delivered_order($userid)
    {
        $sql = "select * from products";
        $DB = new Database();
        $result = $DB->read($sql);
        $pending_product3 = "";
        if ($result) {
            foreach ($result as $product) {
                $is_a_buyer = false;
                if (!empty($product['buyers'])) {
                    $buyers = json_decode($product['buyers'], true);
                    if (is_array($buyers)) {
                        $user_ids = array_column($buyers, "userid");
                        if (in_array($userid, $user_ids)) {
                            $is_a_buyer = true;
                        }
                    }
                }
                if (!empty($product['buyers']) && !empty($product['payed_buyer'])) {
                    $payed_buyers = json_decode($product['payed_buyer'], true);
                    $payed_user_ids = array_column($payed_buyers, "buyer");
                    if ($is_a_buyer === false && in_array($userid, $payed_user_ids)) {
                        $key = array_search($userid, $payed_user_ids);
                        if (isset($payed_buyers[$key]['delivery_status']) && $payed_buyers[$key]['delivery_status'] == "delivered") {
                            $pending_product['productid'] = $product['productid'];
                            $pending_product2[] = $pending_product;
                            $pending_product3 = json_encode($pending_product2);
                        }
                    }
                }
            }
            if ($pending_product3 != "") {
                return $pending_product3;
            } else {
                return "No confirmed Orders";
            }
        }
    }
}
