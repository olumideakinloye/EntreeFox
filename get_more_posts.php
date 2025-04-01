<?php
include("autoload.php");
if (isset($_SESSION['entreefox_userid']) && is_numeric($_SESSION['entreefox_userid'])) {

    $id = $_SESSION['entreefox_userid'];
    $login = new Login();
    $result = $login->check_login($id);

    if ($result) {

        $user = new User();

        $user_data = $user->get_user($id);

        if ($user_data === false) {
            header("Location: Log_in");
            die;
        } else {
            $result = $user->get_following_post($_SESSION['entreefox_userid'], $_GET['id']);
            if ($result) {
                foreach ($result as $row) {
                    $User_data = $user->get_user($row['userid']);
                    if ($row['userid'] === $_SESSION['entreefox_userid']) {
                        include("Posts.php");
                    } else {
                        include("friends_posts.php");
                    }
                }
            }
        }
    } else {

        header("Location: Log_in");
        die;
    }
} else {

    header("Location: Log_in");
    die;
}
