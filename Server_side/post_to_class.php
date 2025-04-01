<?php
include("../autoload.php");
if (isset($_SESSION['entreefox_userid']) && is_numeric($_SESSION['entreefox_userid'])) {

    $id = $_SESSION['entreefox_userid'];
    $login = new Login();
    $result = $login->check_login($id);

    if ($result) {

        $user = new User();

        $user_data = $user->get_user($id);

        if ($user_data === false) {
            header("Location: " . ROOT .  "Log_in");
            die;
        } else {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $post  = new Post();
                $result = $post->create_post($_SESSION['entreefox_userid'], $_POST, $_FILES);
                echo $result;
            }
        }
    } else {

        header("Location: " . ROOT .  "Log_in");
        die;
    }
} else {

    header("Location: " . ROOT .  "Log_in");
    die;
}
