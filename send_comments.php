<?php
include("autoload.php");

if (isset($URL[2])) {
    $post_ID = $URL[2];
    $sql = "select users from comments where comment_id = '$post_ID' limit 1";
    $D = new Database();
    $answer = $DB->read($sql);
    // $swer_id = $answer[0]['users'];
    $user = new User();
    $reply_user = $user->get_user($answer[0]['users']);
    $_POST['parent'] = $URL[2];
    $_POST['parent_commentid'] = $URL[2];
}
if (isset($URL[3])) {
    $parent = $URL[3];
}
$post = new Post();
$id = $_SESSION['entreefox_userid'];
if (isset($URL[2])) {
    $answer_id = $answer[0]['users'];
}
if (isset($URL[5])) {
    $_POST['parent_commentid'] = $URL[5];
}
$result = $post->create_post($id, $parent, $_POST, $_FILES, $answer_id, $post_ID);
    // $result = $post->post_comment($id, $_POST);
