<?php
include("autoload.php");

$login = new Login();
$User_data = $login->check_login($_SESSION['entreefox_userid']);
$user_data = $User_data;
$Post = new Post();
$ERROR = "";
$likesss = false;
if (isset($URL[2]) && isset($URL[1])) {

    $row = $Post->get_one_posts($URL[2]);
    if (!$row) {
        $ERROR = "No such post was found";
    } else {
        if ($row['userid'] != $_SESSION['entreefox_userid']) {
            $ERROR = "Access denied";
        }
    }
    $likesss = $Post->get_likes($URL[2], $URL[1]);
    if (empty($likesss)) {
        $ERROR = "No likes";
    }
} else {

    $ERROR = "No likes";
}

$state = "Online";
$date = date("Y-m-d H:i:s");
$query2 = "update user_state set state = '$state', date = '$date' where userid = '$_SESSION[entreefox_userid]' limit 1";
$DB = new Database();
$DB->save($query2);
?>
<div class="view_likes_container" style="padding-bottom: 50px;">

<?php
$user = new user();

if ($ERROR == "") {
    if (is_array($likesss)) {
        foreach ($likesss as $row) {
            $like_row = $user->get_user($row['userid']);
            include('likes.php');
        }
    }
} else {?>

<h1 style="text-align: center; font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;"><?=$ERROR?></h1>
<?php
}
?>
</div>