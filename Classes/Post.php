<?php
// include("image.php");

class Post
{

    private $error = "";

    private function create_postid()
    {
        $length = rand(4, 19);
        $number = "";
        for ($i = 0; $i < $length; $i++) {
            $new_rand = rand(0, 9);
            $number = $number . $new_rand;
        }
        return $number;
    }

    public function delete_image($PROFILE, $arr_cover, $arr_profile, $USERID)
    {
        // $photo_name = $_GET['photo'];
        // print_r($arr);
        // die;
        // echo $PROFILE;
        // die;
        if ($PROFILE == "cover") {

            if (file_exists($arr_cover)) {
                $file_image = imagecreatefromjpeg($arr_cover);

                imagedestroy($file_image);
                unlink($arr_cover);
                $sql = "update users set cover_image = '' where userid = '$USERID' limit 1";

                $DB = new Database();
                $DB->save($sql);

                header("Location: " . ROOT . "User_profile");
            } else {
                echo "<div class='error_shell' id='error_shell'>";
                echo " <div class='error' ";
                echo "  <p><b>No image was found</b></p><br>";
                echo "  <button onclick='error()' id='error''><b>close</b></button>";
                echo " </div>";
                echo "</div>";
            }
        }
        if ($PROFILE == "profile") {
            if (file_exists($arr_profile)) {
                $file_image = imagecreatefromjpeg($arr_profile);

                imagedestroy($file_image);
                unlink($arr_profile);
                $sql = "update users set profile_image = '' where userid = '$USERID' limit 1";

                $DB = new Database();
                $DB->save($sql);
                // echo ROOT;
                // die;
                header("Location: " . ROOT . "User_profile");
            } else {
                echo "<div class='error_shell' id='error_shell'>";
                echo " <div class='error' ";
                echo "  <p><b>No image was found</b></p><br>";
                echo "  <button onclick='error()' id='error''><b>close</b></button>";
                echo " </div>";
                echo "</div>";
            }
        }
        if ($PROFILE != "profile" && $PROFILE != "cover") {
            echo "<div class='error_shell' id='error_shell'>";
            echo " <div class='error' ";
            echo "  <p><b>No image was found</b></p><br>";
            echo "  <button onclick='error()' id='error''><b>close</b></button>";
            echo " </div>";
            echo "</div>";
        }
    }
    public function delete_comments($parentid)
    {
        $sql = "delete from comments where parent_id = '$parentid'";
        $DB = new Database();
        $DB->save($sql);
    }
    public function delete_favorites($postid)
    {
        $sql = "delete from favorite where contentid = '$postid'";
        $DB = new Database();
        $DB->save($sql);
    }
    public function delete_posts($postid, $image_name, $video_name)
    {
        if (!is_numeric($postid)) {
            return false;
        }
        if (file_exists($video_name)) {
            unlink($video_name);
        }
        if (file_exists($image_name)) {
            $origin = imagecreatefromjpeg($image_name);

            imagedestroy($origin);
            unlink($image_name);
        }
        $query = "delete from posts where postid = '$postid' limit 1";

        $DB = new Database();
        $DB->read($query);
    }


    public function Edit_post($POSTID, $new_post, $new_image, $original_image, $original_video)
    {
        $Error = "";
        $coment = "Edited";
        $date = date("Y-m-d H:i:s");
        if (!empty($new_post)) {
            $string_form = addslashes(ucfirst($new_post));
            $DB = new Database();
            $sql = "update posts set post = '$string_form' where postid = '$POSTID' ";
            $DB->save($sql);

            $sql = "update posts set comments = '$coment' where postid = '$POSTID' ";
            $DB->save($sql);

            $query = "update posts set date = '$date' where postid = '$POSTID' limit 1";
            $DB->save($query);
        }

        $myimage = "";
        if (!empty($new_image['file']['name'])) {
            if ($new_image['file']['type'] == "video/mp4") {
                $allowed_video_size = 31457280;
                if ($new_image['file']['size'] < $allowed_video_size) {

                    $folder = "../uploads/" . $_SESSION['entreefox_userid'] . "/";
                    if (!file_exists($folder)) {
                        mkdir($folder, 0777, true);

                        file_put_contents($folder . "index.php", "");
                    }
                    if (file_exists($original_video)) {
                        unlink($original_video);
                    }
                    if (file_exists($original_image)) {
                        $my_empty_image = "";
                        $query = "update posts set image = '$my_empty_image' where postid = '$POSTID' limit 1";

                        $DB = new Database();
                        $DB->save($query);
                        $origin = imagecreatefromjpeg($original_image);

                        imagedestroy($origin);
                        unlink($original_image);
                    }
                    $image_class = new Image();

                    $myvideo = $folder . $image_class->generate_file_name(15) . ".mp4";
                    move_uploaded_file($_FILES['file']['tmp_name'], $myvideo);

                    // $this->create_tumb_nail($myvideo);
                    $query = "update posts set video = '$myvideo' where postid = '$POSTID' limit 1";

                    $DB = new Database();
                    $DB->save($query);

                    $query = "update posts set comments = '$coment' where postid = '$POSTID' limit 1";
                    $DB->save($query);
                    $query = "update posts set date = '$date' where postid = '$POSTID' limit 1";
                    $DB->save($query);
                } else {

                    $Error .= "Video size should be less than 40MB";
                }
            } elseif ($new_image['file']['type'] == "image/jpeg") {

                $allowed_size = 3145728;

                if ($new_image['file']['size'] < $allowed_size) {

                    $new_image_folder = "uploads/" . $_SESSION['entreefox_userid'] . "/";

                    if (!file_exists($new_image_folder)) {

                        // echo "Good";
                        // die;
                        mkdir($new_image_folder, 0777, true);
                        file_put_contents($new_image_folder . "index.php", "");
                    }

                    if (file_exists($original_image)) {
                        $origin = imagecreatefromjpeg($original_image);

                        imagedestroy($origin);
                        unlink($original_image);
                    }
                    if (file_exists($original_video)) {
                        $empty_video = "";
                        $query = "update posts set video = '$empty_video' where postid = '$POSTID' limit 1";

                        $DB = new Database();
                        $DB->save($query);

                        unlink($original_video);
                    }
                    $image_class = new Image();

                    $myimage = $new_image_folder . $image_class->generate_file_name(15) . ".jpg";
                    move_uploaded_file($new_image['file']['tmp_name'], $myimage);

                    // $image_class->crop_image($myimage, $myimage, 800, 800);


                    $query = "update posts set image = '$myimage' where postid = '$POSTID' limit 1";

                    $DB = new Database();
                    $DB->save($query);


                    $query = "update posts set comments = '$coment' where postid = '$POSTID' limit 1";
                    $DB->save($query);

                    $query = "update posts set date = '$date' where postid = '$POSTID' limit 1";
                    $DB->save($query);
                } else {
                    $Error .= "Image size should be less than 3MB";
                }
            } else {
                $Error .= "Only Jpg files are allowed";
            }
        }
        if ($Error != "") {
            return $Error;
        } else {
            header("Location: " . ROOT . "Home");
            die;
        }
    }

    public function get_likes($id, $type)
    {
        $likes = "";
        $DB = new Database();
        if ($type == "post" && is_numeric($id)) {
            //get like details

            $sql = "select likes from likes where type='post' && contentid = '$id' limit 1 ";
            $result = $DB->read($sql);

            if (is_array($result)) {
                if (!empty($result)) {
                    $likes = json_decode($result[0]['likes'], true);
                    return $likes;
                }
            }
        }
        return $likes;
    }

    public function set_notification_as_seen($userid, $like_userid, $postid)
    {
        $DB = new Database();
        $sql = "select seen from notification where userid = '$userid' && notifier_id = '$like_userid' && contentid = '$postid' && activity = 'like' limit 1";
        $result = $DB->read($sql);
        if ($result) {
            if ($result[0]['seen'] == 0) {
                $sql = "update notification set seen = 1 where userid = '$userid' && notifier_id = '$like_userid' && contentid = '$postid' && activity = 'like' limit 1";
                $result = $DB->save($sql);
            }
        }
    }

    public function delete_like($Postid)
    {
        $DB = new Database();
        $sql = "delete from likes where contentid = '$Postid' limit 1";
        $DB->save($sql);
    }


    public function get_one_posts($postid)
    {
        if (!is_numeric($postid)) {
            return false;
        }
        $query = "select * from posts where postid = '$postid' limit 1";

        $DB = new Database();
        $result = $DB->read($query);

        if ($result) {
            return $result[0];
        } else {
            return false;
        }
    }

    public function get_posts($id)
    {
        $query = "select * from posts where userid = '$id' order by id desc limit 10";

        $DB = new Database();
        $result = $DB->read($query);

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }
    public function get_comments($id)
    {
        $query = "select * from comments where postid = '$id' order by id desc";

        $DB = new Database();
        $result = $DB->read($query);
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }
    public function get_all_posts()
    {
        $query = "select * from posts";

        $DB = new Database();
        $result = $DB->read($query);

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    public function like_post($id, $type, $entreefox_userid)
    {

        if ($type == "post") {
            $DB = new Database();


            //save likes details

            $sql = "select likes from likes where contentid = '$id' limit 1 ";
            $result = $DB->read($sql);
            $date = date("Y-m-d H:i:s");
            if (!empty($result[0]['likes'])) {
                $likes = json_decode($result[0]['likes'], true);
                if (is_array($likes)) {
                    $likes = json_decode($result[0]['likes'], true);
                    $user_ids = array_column($likes, "userid");
                    if (!in_array($entreefox_userid, $user_ids)) {
                        $arr["userid"] = $entreefox_userid;
                        $likes[] = $arr;
                        $likes_string = json_encode($likes);

                        $sql = "update likes set likes = '$likes_string' where type='post' && contentid = '$id' limit 1 ";
                        $DB->save($sql);

                        //insert into notification
                        $sql = "select userid from posts where postid = '$id' limit 1";
                        $userid = $DB->read($sql);
                        $new_userid = $userid[0]['userid'];
                        $activity = "like";
                        $sql = "insert into notification (userid, activity, contentid, notifier_id, date) values ('$new_userid', '$activity', '$id', '$entreefox_userid', '$date')";
                        $DB->save($sql);
                        return "liked";
                    } else {
                        $key = array_search($entreefox_userid, $user_ids);
                        $likes = array_merge(array_slice($likes, 0, $key), array_slice($likes, $key + 1));

                        $likes_string = json_encode($likes);
                        $sql = "update likes set likes = '$likes_string' where type='post' && contentid = '$id' limit 1 ";
                        $DB->save($sql);

                        $sql = "delete from notification where notifier_id = '$entreefox_userid' && contentid = '$id' && activity = 'like' limit 1";
                        $DB->save($sql);
                        return "unliked";
                    }
                } else {
                    $arr["userid"] = $entreefox_userid;

                    $arr2[] = $arr;
                    $likes2 = json_encode($arr2);

                    $sql = "update likes set likes = '$likes2' where type='post' && contentid = '$id' limit 1 ";
                    $DB->save($sql);

                    //insert into notification
                    $sql = "select userid from posts where postid = '$id' limit 1";
                    $userid = $DB->read($sql);
                    $new_userid = $userid[0]['userid'];
                    $activity = "like";
                    $sql = "insert into notification (userid, activity, contentid, notifier_id, date) values ('$new_userid', '$activity', '$id', '$entreefox_userid', '$date')";
                    $DB->save($sql);
                    return "liked";
                }
            } else {
                $arr["userid"] = $entreefox_userid;

                $arr2[] = $arr;
                $likes3 = json_encode($arr2);

                $sql = "insert into likes (type,contentid, likes) values ('$type','$id', '$likes3')";
                $DB->save($sql);


                //insert into notification
                $sql = "select userid from posts where postid = '$id' limit 1";
                $userid = $DB->read($sql);
                $new_userid = $userid[0]['userid'];
                $activity = "like";
                $sql = "insert into notification (userid, activity, contentid, notifier_id, date) values ('$new_userid', '$activity', '$id', '$entreefox_userid', '$date')";
                $DB->save($sql);
                return "liked";
            }
        }
    }
    public function add_to_favorite($id, $type, $entreefox_userid, $ownerid)
    {

        if ($type == "favorite") {
            $DB = new Database();
            //add post to favorite details
            $sql = "select * from favorite where contentid = '$id' && userid = '$entreefox_userid' limit 1 ";
            $result = $DB->read($sql);
            $date = date("Y-m-d H:i:s");
            if (!is_array($result)) {
                $query = "insert into favorite (userid, contentid, contentid2, date) values ('$entreefox_userid', '$id', '$ownerid', '$date')";
                $DB->save($query);
            } else {
                $query = "delete from favorite where userid = '$entreefox_userid' && contentid = '$id' && contentid2 = '$ownerid' limit 1";
                $DB->save($query);
            }
        }
    }
    public function get_all_favorites($id, $type)
    {

        $favorites = 0;
        if ($type == "favorite") {
            $DB = new Database();
            //add post to favorite details
            $sql = "select * from favorite where contentid = '$id'";
            $result = $DB->read($sql);
            if ($result) {
                foreach ($result as $key) {
                    $favorites++;
                }
            }
        }
        return $favorites;
    }
    public function get_user_favorites($entreefox_userid)
    {
        $result = "";
        $DB = new Database();
        //add post to favorite details
        $sql = "select * from favorite where userid = '$entreefox_userid' order by id desc";
        $result = $DB->read($sql);
        return $result;
    }
    public function create_post($userid, $data, $files)
    {
        $myimage = "";
        $myvideo = "";
        $coment = "Posted";
        $has_imag = 0;

        $valid_vid_types = ["video/mp4", "video/mov", "video/avi"];

        $valid_img_types = ["image/jpg", "image/jpeg", "image/png"];


        if (!empty($files["image"]['name'])) {


            if (in_array($files["image"]['type'], $valid_img_types)) {

                $allowed_size = 3145728;

                if ($files["image"]['size'] < $allowed_size) {

                    $folder = "../uploads/" . $userid . "/";

                    if (!file_exists($folder)) {

                        mkdir($folder, 0777, true);
                        file_put_contents($folder . "index.php", "");
                    }

                    $image_class = new Image();
                    $file_name = $image_class->generate_file_name(15) . ".jpg";

                    $myimage = $folder . $file_name;
                    move_uploaded_file($_FILES["image"]['tmp_name'], $myimage);
                    $folder2 = "uploads/" . $userid . "/" . $file_name;

                    $post = "";
                    if (!empty($data['text'])) {
                        $post = addslashes(ucfirst($data['text']));
                    }
                    $postid = $this->create_postid();

                    $query = "insert into posts (userid,postid,post,image,comments) values ('$userid','$postid','$post','$folder2','$coment')";

                    $DB = new Database();
                    $DB->save($query);
                    $this->error = "Successful";
                } else {

                    $this->error = "Image size should be less than 3MB";
                }
            } else {

                $this->error = "Invalid image type";
            }
        } elseif (!empty($files["video"]['name'])) {
            if (in_array($files["video"]['type'], $valid_vid_types)) {
                $allowed_video_size = 31457280;
                if ($files["video"]['size'] < $allowed_video_size) {

                    $folder = "../uploads/" . $userid . "/";
                    if (!file_exists($folder)) {
                        mkdir($folder, 0777, true);

                        file_put_contents($folder . "index.php", "");
                    }
                    $image_class = new Image();
                    $file_name = $image_class->generate_file_name(15) . ".mp4";
                    $myvideo = $folder . $file_name;
                    move_uploaded_file($_FILES["video"]['tmp_name'], $myvideo);
                    $folder2 = "uploads/" . $userid . "/" . $file_name;
                    $post = "";
                    if (!empty($data['text'])) {
                        $post = addslashes(ucfirst($data['text']));
                    }
                    // $this->create_tumb_nail($myvideo);
                    $postid = $this->create_postid();

                    $query = "insert into posts (userid,postid,post,video,comments) values ('$userid','$postid','$post','$folder2','$coment')";

                    $DB = new Database();
                    $DB->save($query);
                    $this->error = "Successful";
                } else {

                    $this->error = "Video size should be less than 40MB";
                }
            }
        } elseif (!empty($files["story"]['name'])) {
            if (in_array($files["story"]['type'], $valid_vid_types)) {
                $allowed_video_size = 20971520;
                if ($files["story"]['size'] < $allowed_video_size) {

                    $folder = "../uploads/" . $userid . "/";
                    if (!file_exists($folder)) {
                        mkdir($folder, 0777, true);

                        file_put_contents($folder . "index.php", "");
                    }
                    $image_class = new Image();
                    $file_name = $image_class->generate_file_name(15) . ".mp4";
                    $myvideo = $folder . $file_name;
                    move_uploaded_file($_FILES["story"]['tmp_name'], $myvideo);
                    $folder2 = "uploads/" . $userid . "/" . $file_name;
                    $post = "";
                    if (!empty($data['text'])) {
                        $post = addslashes(ucfirst($data['text']));
                    }
                    // $this->create_tumb_nail($myvideo);
                    $postid = $this->create_postid();

                    $query = "insert into posts (userid,postid,post,video,comments) values ('$userid','$postid','$post','$folder2','$coment')";

                    $DB = new Database();
                    $DB->save($query);
                    $this->error = "Successful";
                } else {

                    $this->error = "Video size should be less than 40MB";
                }
            } elseif (in_array($files["story"]['type'], $valid_img_types)) {

                $allowed_size = 3145728;

                if ($files["story"]['size'] < $allowed_size) {

                    $folder = "../uploads/" . $userid . "/";

                    if (!file_exists($folder)) {

                        mkdir($folder, 0777, true);
                        file_put_contents($folder . "index.php", "");
                    }

                    $image_class = new Image();
                    $file_name = $image_class->generate_file_name(15) . ".jpg";
                    $myimage = $folder . $file_name;
                    move_uploaded_file($_FILES["story"]['tmp_name'], $myimage);
                    $folder2 = "uploads/" . $userid . "/" . $file_name;

                    $post = "";
                    if (!empty($data['text'])) {
                        $post = addslashes(ucfirst($data['text']));
                    }
                    $postid = $this->create_postid();

                    $query = "insert into posts (userid,postid,post,image,comments) values ('$userid','$postid','$post','$folder2','$coment')";

                    $DB = new Database();
                    $DB->save($query);
                    $this->error = "Successful";
                } else {
                    $this->error = "Image size should be less than 3MB";
                }
            } else {
                $this->error = "Invalid image type";
            }
        } elseif (!empty($data['text'])) {

            $postid = $this->create_postid();

            $post = addslashes(ucfirst($data['text']));
            $query = "insert into posts (userid,postid,post,image,comments) values ('$userid','$postid','$post','$myimage','$coment')";

            $DB = new Database();
            $DB->save($query);
            $this->error = "Successful";
        }

        if (empty($files['image']['name']) && empty($data['text']) && empty($files['video']['name']) && empty($files['story']['name'])) {
            $this->error = "Unable to post";
        }
        return $this->error;
    }
    public function comment($data, $parent, $notifier_id, $content_id, $userid)
    {
        $date = date("Y-m-d H:i:s");

        if (!empty($data['comment'])) {
            $comments = addslashes(ucfirst($data['comment']));
            if (isset($data['parent']) && is_numeric($data['parent'])) {
                $postid_comment = $data['parent'];
                $parent2 = $parent;
                if (!empty($data['parent_commentid'])) {
                    $parent_comment_id = $data['parent_commentid'];
                } else {
                    $parent_comment_id = 0;
                }
                $commentid = $this->create_postid();
                $query = "insert into comments (postid,comment_id,parent_id, parent_comment_id,comments,users,date) values ('$postid_comment','$commentid', '$parent2', '$parent_comment_id', '$comments','$userid','$date')";
                $DB = new Database();
                $DB->save($query);

                // insert into notification
                $sql2 = "select userid from posts where postid = '$parent' limit 1";
                $USERid = $DB->read($sql2);
                $new_user = $USERid[0]['userid'];
                $activity = "comment";
                if ($postid_comment != $parent2) {
                    $activity = "reply";
                }
                $sql = "insert into notification (userid, activity, contentid, contentid2, notifier_id, date) values ('$notifier_id', '$activity', '$content_id', '$parent2', '$userid', '$date')";
                $DB->save($sql);
                // return $commentid;
                // header("Location: " . ROOT . "view_comments/" . $parent2 . "#" . $commentid . "");
            }
        }
    }
    public function get_recent_notification($userid)
    {
        $sql = "select * from notification where userid = '$userid' && seen = 0 order by id desc";
        $DB = new Database();
        $notification = $DB->read($sql);
        if ($notification) {
            return $notification;
        } else {
            return false;
        }
    }
    public function get_old_notification($userid)
    {
        $sql = "select * from notification where userid = '$userid' && seen = 1 order by id desc";
        $DB = new Database();
        $notification = $DB->read($sql);
        if ($notification) {
            return $notification;
        } else {
            return false;
        }
    }
    public function maintain_seen_notification($userid)
    {
        $sql = "select * from notification where userid = '$userid' && seen = 1 order by id desc";
        $DB = new Database();
        $seen = 0;
        $notification = $DB->read($sql);
        if ($notification) {
            foreach ($notification as $notif) {
                $seen++;
            }
            if ($seen >= 100) {
                $sql2 = "delete from notification where userid = '$userid' && seen = 1 limit 50";
                $DB->save($sql2);
            }
        }
    }
    public function set_seen_notification($userid)
    {
        $DB = new Database();
        $sql2 = "update notification set seen = 1 where userid = '$userid'";
        $DB->save($sql2);
    }

    public function create_tumb_nail($video)
    {
        $vid = explode('/', $video);

        // Define the path to the video and output image
        $videoFile = "C:\xampp\htdocs\Homepage2\uploads\20234249633256172\"$vid[2]";
        $outputImage = $video . "_image.jpg";

        // Get the video duration
        $command = "ffmpeg -i \"$videoFile\" 2>&1 | grep 'Duration' | awk '{print $2}' | tr -d ,";
        $duration = trim(shell_exec($command));

        // Convert duration to seconds
        // print_r($vid);
        echo $duration;
        echo "bvbvbv";
        // die;
        list($hours, $minutes, $seconds) = explode(':', $duration);
        $totalSeconds = ($hours * 3600) + ($minutes * 60) + $seconds;
        $middleTime = $totalSeconds / 2;

        // Format the middle time as HH:MM:SS
        $middleHour = floor($middleTime / 3600);
        $middleMinute = floor(($middleTime % 3600) / 60);
        $middleSecond = $middleTime % 60;
        $formattedTime = sprintf('%02d:%02d:%02d', $middleHour, $middleMinute, $middleSecond);

        // Extract the middle frame
        $ffmpegCommand = "ffmpeg -i $videoFile -ss $formattedTime -vframes 1 $outputImage";
        shell_exec($ffmpegCommand);

        echo "Poster image generated: <a href='$outputImage'>View Poster</a>";
    }
}
