<?php
include("autoload.php");
$row['postid'] = $URL[1];
$Commentss = 0;
$DB = new Database();
$Sql = "select * from comments where postid = '$row[postid]'";
$resultt = $DB->read($Sql);
if ($resultt) {
    // print_r($resultt);
    foreach ($resultt as $result_row) {
        $Commentss++;
    }
    if ($Commentss > 0 && $Commentss < 999) {
        echo $Commentss;
    } elseif ($Commentss > 1000 && $Commentss < 1000000) {
        $thousand_comment = $Commentss / 1000;
        echo (int)$thousand_comment . "K";
    } elseif ($Commentss > 1000000 && $Commentss < 1099999) {
        $million_comments = $Commentss / 1000000;
        echo (int)$million_comments . "M";
    } elseif ($Commentss >= 1100000) {
        $million_comments = $Commentss / 1000000;
        $milli = explode('.', $million_comments);
        $decimal_milli = str_split($milli[1]);
        if ($decimal_milli[0] >= 1) {
            echo $milli[0] . "." . $decimal_milli[0] . "M";
        } else {
            echo $milli[0] . "M";
        }
    }
}
?>