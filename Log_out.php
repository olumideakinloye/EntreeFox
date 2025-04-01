<?php
session_start();
if(isset($_SESSION['entreefox_userid'])){
    $_SESSION['entreefox_userid'] = null;
    unset($_SESSION['entreefox_userid']);
}
header("Location: Log_in");
die;