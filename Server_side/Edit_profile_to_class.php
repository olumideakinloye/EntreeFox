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
            if ($_SERVER['REQUEST_METHOD'] === "POST") {
                $result = $user->edit_profile($_POST, $_FILES, $id);
                if($result){
                    // echo $result;
                    print_r($result);
                    // echo $result[0]['profile_image'];
                }else{
                    echo "ERROR";
                }
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
