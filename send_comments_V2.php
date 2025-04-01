<?php
include("autoload.php");

$login = new Login();
$user_data = $login->check_login($_SESSION['entreefox_userid']);

$Post = new Post();
$ERROR = "";
$post_ID = "";

if (isset($URL[2])) {
    $answer_id = $URL[2];
}
$likesss = false;

if (isset($URL[1])) {

    // echo "good";
    $post_ID = $URL[1];
    $row = $Post->get_one_posts($post_ID);
    if (!$row) {
        $ERROR = "No such post was found";
    } else {

        $likesss = $Post->get_comments($post_ID);
        if ($likesss == "") {
            // $ERROR = "No data was found";
        } else {
            if (isset($URL[3])) {
                $post_ID = $URL[3];
                $sql = "select users from comments where comment_id = '$post_ID' limit 1";
                $DB = new Database();
                $answer = $DB->read($sql);
                $answer_id = $answer[0]['users'];
                $user = new User();
                $reply_user = $user->get_user($answer[0]['users']);
            }
        }
    }
    $parent = $post_ID;
} else {

    $ERROR = "No such post was found";
}

if (isset($URL[3])) {
    $post_ID = $URL[3];
}
if (isset($URL[4])) {
    $parent = $URL[4];
}
if (isset($URL[1])) {
        if (isset($URL[3])) {
            $_POST['parent'] = $URL[3];
            $_POST['parent_commentid'] = $URL[3];
            $post_ID = $URL[3];
            $sql = "select users from comments where comment_id = '$post_ID' limit 1";
            $DB = new Database();
            $answer = $DB->read($sql);
            $answer_id = $answer[0]['users'];
        }
        if (isset($URL[4])) {
            $parent = $URL[4];
        }
        if (isset($URL[5])) {
            $_POST['parent_commentid'] = $URL[5];
        }
        $post = new Post();
        $id = $_SESSION['entreefox_userid'];
        $result = $post->create_post($id, $parent, $_POST, $_FILES, $answer_id, $post_ID);
        // $result = $post->post_comment($id, $_POST);

        if (is_numeric($result)) {
            header("Location: " . ROOT . "comments/$URL[1]/$URL[2]#$result");
            //   die;
        } else {
            $ERROR = $result;
        }
}
