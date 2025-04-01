<?php
include("autoload.php");

$login = new Login();
$user_data = $login->check_login($_SESSION['entreefox_userid']);
$User_data = $user_data;

$Post = new Post();
$ERROR = "";
$place_holder = "Comment";
$display = "flex";
$answer_id = $_SESSION['entreefox_userid'];
$result = "";
// $post_ID = $URL[1];

$post_ID = "";
$likesss = false;
if (isset($URL[1])) {
    $post_ID = $URL[1];
    $likesss = $Post->get_comments($URL[1]);
    $post_ID = $URL[1];
    $row = $Post->get_one_posts($post_ID);
    if (!$row) {
        $ERROR = "No such post was found";
    }
    if ($likesss == "") {
        $ERROR = "No comments";
    }
} else {

    $ERROR = "No comments";
}
$state = "Online";
$query2 = "update user_state set state = '$state' where userid = '$_SESSION[entreefox_userid]' limit 1";
$DB = new Database();
$DB->save($query2);
$date = date("Y-m-d H:i:s");
$query3 = "update user_state set date = '$date' where userid = '$_SESSION[entreefox_userid]' limit 1";
$DB = new Database();
$DB->save($query3);
?>
<!-- <script src="<?php echo ROOT ?>Edit_profile.js"></script> -->

<?php
if (!is_numeric($result) && $result != "") {
    echo "<div class='error_shell' id='error_shell'>";
    echo " <div class='error'>";
    echo "  <P id='error_result'><b>$result.</b></P>";
    echo "  <button id='error''><b>close</b></button>";
    echo " </div>";
    echo "</div>";
    // print_r($result);
}
?>
<?php
if ($ERROR != "") {
    $display = "none";
    echo "<div id='Error_shell'>";
    echo "  <h1 style='text-align: center; padding: 10px 0;'><b>$ERROR</b></h1>";
    echo "</div>";
}else{

?>

<div class="comments" id="comments">
    <?php
    // print_r($URL);
    if (isset($URL[1])) {
        $comments = $Post->get_comments($URL[1]);
        if (is_array($comments)) {
            foreach ($comments as $comment_row) {
                $user = new User();
                // echo "good";
                $comment_user = $user->get_user($comment_row['users']);

                include("view_chats.php");
            }
        }
    }
    ?>
</div>
<?php
}
include("input_comment.php");
?>