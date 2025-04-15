<?php
class User
{

    public function get_data($id)
    {

        $query = "select * from users where userid = '$id' limit 1";
        $DB = new Database();
        $result = $DB->read($query);

        if ($result) {

            $row = $result[0];
            return $row;
        } else {
            return false;
        }
    }
    public function get_user($id)
    {

        $query = "select * from users where userid = '$id' limit 1";
        $DB = new Database();
        $result = $DB->read($query);

        if ($result) {
            return $result[0];
        } else {
            return false;
        }
    }
    public function get_user_by_name($user_name)
    {

        $query = "select userid from users where user_name = '$user_name' limit 1";
        $DB = new Database();
        $result = $DB->read($query);

        if ($result) {
            return $result[0]['userid'];
        } else {
            return false;
        }
    }
    public function get_users_by_name($user_name, $id)
    {
        $query = "select userid from users where user_name like '%$user_name%' && userid != '$id'";
        $DB = new Database();
        $result = $DB->read($query);

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }
    public function get_following($id)
    {
        $query = "select following from users where userid = '$id' limit 1";
        $DB = new Database();
        $result = $DB->read($query);
        if ($result && !empty($result[0])) {
            $row = json_decode($result[0]['following'], true);
            return $row;
        } else {
            return false;
        }
    }
    public function get_friends($id)
    {

        $query = "select * from users where userid != '$id'";
        $DB = new Database();
        $result = $DB->read($query);

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }
    public function get_users_for_explore_page($id)
    {
        $query = "select * from users where userid != '$id' limit 5";
        $DB = new Database();
        $result = $DB->read($query);

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }
    public function create_shop($data, $userid)
    {
        $DB = new Database();
        $date = date("Y-m-d H:i:s");
        if (empty($data['shopname']) && empty($data['shoptype'])) {
            return "Invalid data";
        } else {

            if (strstr($data['shopname'], " ")) {
                $explode = explode(" ", $data['shopname']);
                $new_explode = array_map('ucfirst', $explode);
                $string = preg_replace('/\s+/', ' ', trim((implode(" ", $new_explode))));
            } else {
                $string = trim(ucfirst(addslashes($data['shopname'])));
            }
            $sql = "select shopname from shop";
            $reesult = $DB->read($sql);
            if ($reesult) {
                foreach ($reesult as $result) {
                    $arr[] = addslashes($result['shopname']);
                }
                $normalizedArray = array_map(function ($value) {
                    return preg_replace('/\s+/', ' ', trim($value));
                }, $arr);

                if (in_array($string, $normalizedArray)) {
                    return "Shop name already exists";
                    die;
                }
            }
            if (strstr($data['shoptype'], " ")) {
                $explode = explode(" ", $data['shoptype']);
                $new_explode = array_map('ucfirst', $explode);
                $string2 = implode(" ", $new_explode);
            } else {
                $string2 = ucfirst($data['shoptype']);
            }
            $sql = "select userid from shop where userid =  '$userid' limit 1";
            $result = $DB->read($sql);
            if (is_array($result)) {
                return "You already have a shop";
            } else {
                $shopid = $this->create_shopid();
                $query = "insert into shop (userid, shopid, shopname, shoptype, date) values('$userid', '$shopid', '$string', '$string2', '$date')";
                $DB->save($query);
            }
        }
    }

    private function create_shopid()
    {
        $length = rand(4, 19);
        $number = "";
        for ($i = 0; $i < $length; $i++) {
            $new_rand = rand(0, 9);
            $number = $number . $new_rand;
        }
        return $number;
    }
    public function get_shop($userid)
    {
        $DB = new Database();
        $sql = "select * from shop where userid = '$userid' limit 1";
        $result = $DB->read($sql);
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }
    public function get_users($entreefox_userid)
    {
        $followers = $this->get_following($entreefox_userid);
        if($followers){
            $user_ids_arr = array_column($followers, "userid");
        }
        $user_ids_arr[] = $entreefox_userid;
        // return $user_ids;
        if ($followers !== false) {
            $user_ids = implode(',', array_map('intval', $user_ids_arr));
            $query = "select * from users where userid NOT IN ($user_ids)";
            $DB = new Database();
            $result = $DB->read($query);
            if ($result) {
                return $result;
            } else {
                return false;
            }
            // return $user_ids;
        } else {
            return false;
        }
    }
    public function edit_profile($post, $file, $userid)
    {
        $DB = new Database();
        $date = date("Y-m-d H:i:s");
        $valid_img_types = ["image/jpg", "image/jpeg", "image/png"];
        $username = "";
        $photo = "";
        $cover = [];
        $about = "";
        $error = "";
        $empty = true;
        if (!empty($post['username'])) {
            $sql = "select * from users where user_name = '$post[username]' && userid <> '$userid' limit 1";
            $result = $DB->read($sql);
            if ($result != false) {
                $error = "User name not available.";
            } else {
                $username = addslashes(ucfirst($post['username']));
                $query = "update users set user_name = '$username' where userid = '$userid' limit 1";

                $DB->save($query);
                $empty = false;
            }
        }
        if (!empty($post['about'])) {
            $about = addslashes(ucfirst($post['about']));
            $query = "update users set About = '$about' where userid = '$userid' limit 1";

            $DB->save($query);
            $empty = false;
        }

        if (!empty($file["photo"]['name'])) {
            $empty = false;
            if (in_array($file["photo"]['type'], $valid_img_types)) {

                $allowed_size = 3145728;

                if ($file["photo"]['size'] < $allowed_size) {

                    $folder = "../uploads/" . $userid . "/";

                    if (!file_exists($folder)) {

                        mkdir($folder, 0777, true);
                        file_put_contents($folder . "index.php", "");
                    }

                    $image_class = new Image();
                    $file_name = $image_class->generate_file_name(15) . ".jpg";
                    $myimage = $folder . $file_name;
                    move_uploaded_file($file["photo"]['tmp_name'], $myimage);
                    $image_class->crop_image($myimage, $myimage, 1000, 1000, $file["photo"]['type'], $userid);

                    ///detete previous image
                    $query1 = "select profile_image from users where userid = '$userid' limit 1";
                    $result2 = $DB->read($query1);
                    $query1 = "select profile_image from users where userid = '$userid' limit 1";
                    $result2 = $DB->read($query1);
                    if (file_exists("../" . $result2[0]['profile_image'])) {
                        // return $result2;
                        unlink("../" . $result2[0]['profile_image']);
                    }
                    $photo = "uploads/" . $userid . "/" . $file_name;
                    $query = "update users set profile_image = '$photo' where userid = '$userid' limit 1";

                    $DB->save($query);
                } else {

                    $error = "Image size should be less than 3MB";
                }
            } else {

                $error = "Invalid image type";
            }
        }
        if (!empty($file["background"])) {
            $empty = false;
            $files = $_FILES["background"];
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

                        $folder = "../uploads/" . $userid . "/";

                        if (!file_exists($folder)) {

                            mkdir($folder, 0777, true);
                            file_put_contents($folder . "index.php", "");
                        }

                        $image_class = new Image();
                        $file_name = $image_class->generate_file_name(15) . ".jpg";
                        $myimage = $folder . $file_name;
                        move_uploaded_file($tmpName, $myimage);
                        $image_class->crop_image($myimage, $myimage, 4000, 2100, $fileType, $userid);


                        /////detete previous image
                        $cover2["cover"] = "uploads/" . $userid . "/" . $file_name;
                        $cover[] = $cover2;

                    } else {

                        $error = "Image size should be less than 3MB";
                    }
                } else {

                    $error = "Invalid image type";
                }
            }
            if ($error === "") {
                $new_cover = json_encode($cover, true);
                $query = "select cover_image from users where userid = '$userid' limit 1";
                $result2 = $DB->read($query);
                if (file_exists("../" . $result2[0]['cover_image'])) {
                    unlink("../" . $result2[0]['cover_image']);
                }
                $query = "update users set cover_image = '$new_cover' where userid = '$userid' limit 1";
                $DB->save($query);
            }
        }
        if ($empty === false && $error == "") {
            return "Successful";
        } elseif ($error != "") {
            return $error;
        }
        return $error;
    }
    public function add_to_following($userid, $entreefox_userid)
    {
        $DB = new Database();
        $sql = "select following from users where userid = '$entreefox_userid' limit 1 ";
        $result = $DB->read($sql);
        $date = date("Y-m-d H:i:s");
        if (!empty($result[0]['following'])) {
            $following = json_decode($result[0]['following'], true);
            if (is_array($following)) {
                $user_ids = array_column($following, "userid");
                if (!in_array($userid, $user_ids)) {
                    $arr["userid"] = $userid;
                    $following[] = $arr;
                    $following_string = json_encode($following);

                    $sql = "update users set following = '$following_string' where userid = '$entreefox_userid' limit 1";
                    $DB->save($sql);
                }
            }
        }
    }

    public function remove_from_following($userid, $entreefox_userid)
    {
        $DB = new Database();
        $sql = "select following from users where userid = '$entreefox_userid' limit 1 ";
        $result = $DB->read($sql);
        $date = date("Y-m-d H:i:s");
        if (!empty($result[0]['following'])) {
            $following = json_decode($result[0]['following'], true);
            if (is_array($following)) {
                $user_ids = array_column($following, "userid");
                if (in_array($userid, $user_ids)) {
                    $key = array_search($userid, $user_ids);
                    $following = array_merge(array_slice($following, 0, $key), array_slice($following, $key + 1));

                    $following_string = json_encode($following);
                    $sql = "update users set following = '$following_string' where userid = '$entreefox_userid' limit 1";
                    $DB->save($sql);
                }
            }
        }
    }
    public function follow($username, $entreefox_userid)
    {
        $DB = new Database();
        $query = "select userid from users where user_name = '$username' limit 1";
        $result = $DB->read($query);
        $userid = $result[0]['userid'];
        $sql = "select followers from users where userid = '$userid' limit 1 ";
        $result2 = $DB->read($sql);
        $date = date("Y-m-d H:i:s");
        if (!empty($result2[0]['followers'])) {
            $followers = json_decode($result2[0]['followers'], true);
            if (is_array($followers)) {
                $user_ids = array_column($followers, "userid");
                if (!in_array($entreefox_userid, $user_ids)) {
                    $arr["userid"] = $entreefox_userid;
                    $followers[] = $arr;
                    $followers_string = json_encode($followers);

                    $sql = "update users set followers = '$followers_string' where user_name = '$username' && userid = '$userid' limit 1";
                    $DB->save($sql);

                    // add to following
                    $this->add_to_following($userid, $entreefox_userid);


                    //insert into notification
                    $activity = "follow";
                    $sql = "insert into notification (userid, activity, contentid, notifier_id, date) values ('$userid', '$activity', '$userid', '$entreefox_userid', '$date')";
                    $DB->save($sql);
                    return "following";
                } else {
                    $key = array_search($entreefox_userid, $user_ids);
                    $followers = array_merge(array_slice($followers, 0, $key), array_slice($followers, $key + 1));

                    $followers_string = json_encode($followers);
                    $sql = "update users set followers = '$followers_string' where user_name = '$username' && userid = '$userid' limit 1";
                    $DB->save($sql);

                    //remove from following
                    $this->remove_from_following($userid, $entreefox_userid);

                    //delete notification
                    $sql = "delete from notification where notifier_id = '$entreefox_userid' && contentid = '$userid' && activity = 'follow' && userid = '$userid' limit 1";
                    $DB->save($sql);
                    return "unfollow";
                }
            } else {
                $arr["userid"] = $entreefox_userid;
                $followers[] = $arr;
                $followers_string = json_encode($followers);

                $sql = "update users set followers = '$followers_string' where user_name = '$username' && userid = '$userid' limit 1";
                $DB->save($sql);

                // add to following
                $this->add_to_following($userid, $entreefox_userid);


                //insert into notification
                $activity = "follow";
                $sql = "insert into notification (userid, activity, contentid, notifier_id, date) values ('$userid', '$activity', '$userid', '$entreefox_userid', '$date')";
                $DB->save($sql);
                return "following";
            }
        } else {
            $arr["userid"] = $entreefox_userid;
            $followers[] = $arr;
            $followers_string = json_encode($followers);

            $sql = "update users set followers = '$followers_string' where user_name = '$username' && userid = '$userid' limit 1";
            $DB->save($sql);

            // add to following
            $this->add_to_following($userid, $entreefox_userid);


            //insert into notification
            $activity = "follow";
            $sql = "insert into notification (userid, activity, contentid, notifier_id, date) values ('$userid', '$activity', '$userid', '$entreefox_userid', '$date')";
            $DB->save($sql);
            return "following";
        }
    }
    public function get_following_post($id, $more = 0)
    {
        $DB = new Database();
        $sql = "select following from users where userid = '$id' limit 1 ";
        $result = $DB->read($sql);
        if (!empty($result[0]['following'])) {
            if (is_array($result)) {
                $following = json_decode($result[0]['following'], true);

                $user_ids = array_column($following, "userid");
                $userIdsList = implode(',', $user_ids);
                if ($more == 0) {
                    $query = "SELECT * FROM posts WHERE userid IN ($userIdsList) ORDER BY id DESC LIMIT 5";
                } else {
                    $query = "SELECT * FROM posts WHERE userid IN ($userIdsList) AND id < '$more' ORDER BY id DESC LIMIT 5";
                }
                $following_posts = $DB->read($query);
                if ($following_posts) {
                    return $following_posts;
                }
            }
        } else {
            if ($more == 0) {
                $query2 = "SELECT * FROM posts WHERE userid = '$id' ORDER BY id DESC LIMIT 5";
            } else {
                $query2 = "SELECT * FROM posts WHERE userid = '$id' AND id < '$more' ORDER BY id DESC LIMIT 5";
            }
            $user_posts = $DB->read($query2);
            if ($user_posts) {
                return $user_posts;
            }
        }
        // echo $Following;

        // $likes = ($row['likes'] > 0) ? $row['likes'] : "";
    }
}
