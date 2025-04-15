<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
// header("Location: " . ROOT .  "Log_in");
// die;
$data = json_decode(file_get_contents("php://input"), true);
print_r($data);
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
            }
            $post  = new Message();
            $result = $post->send_message($data['receiver'], $data['msg'], $data['sender']);
        }
    } else {

        header("Location: " . ROOT .  "Log_in");
        die;
    }
} else {

    header("Location: " . ROOT .  "Log_in");
    die;
}
