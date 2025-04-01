<?php
include("../autoload.php");
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $signup = new Login();
    $result = $signup->evaluate($_POST);
    echo $result;
}
