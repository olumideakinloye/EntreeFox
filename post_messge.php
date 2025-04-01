<?php
include("autoload.php");

$msg_class = new Messages;
$update = "";
if (isset($URL[2])) {
    $update = $URL[2];
}
$ERROR = $msg_class->send($_POST, $_FILES, $URL[1], $update);
