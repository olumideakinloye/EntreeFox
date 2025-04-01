<?php

class Image
{
    public function generate_file_name($limit) {
         $arr = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
         $text = "";
         for($i = 0; $i < $limit; $i++){
            $random = rand(0, 61);
            $text .=$arr[$random];
         }
         return $text;
    }
    public function crop_image($original_file_name, $cropped_file_name, $max_x, $max_h, $image_type, $id)
    {
        // return $original_file_name; 
        if (file_exists($original_file_name)) {
            if ($image_type === "image/jpeg") {
                $original_image = imagecreatefromjpeg($original_file_name);
            } elseif ($image_type === "image/png") {
                $original_image = imagecreatefrompng($original_file_name);
            } elseif ($image_type === "image/jpg") {
                $original_image = imagecreatefromjpeg($original_file_name);
            }
            $original_x =  imagesx($original_image);
            $original_h = imagesy($original_image);

            if ($original_h > $original_x) {
                $ratio = $max_x / $original_x;
                $new_x = $max_x;
                $new_h = $original_h * $ratio;
            } else {
                $ratio = $max_h / $original_h;
                $new_h = $max_h;
                $new_x = $original_x * $ratio;
            }

            if ($max_x != $max_h) {
                if ($max_h > $max_x) {
                    if ($max_h > $new_h) {
                        $addjustment = ($max_h / $new_h);
                    } else {
                        $addjustment = ($new_h / $max_h);
                    }
                    $new_x = $new_x * $addjustment;
                    $new_h = $new_h * $addjustment;
                } else {
                    if ($max_x > $new_x) {
                        $addjustment = ($max_x / $new_x);
                    } else {
                        $addjustment = ($new_x / $max_x);
                    }
                    $new_x = $new_x * $addjustment;
                    $new_h = $new_h * $addjustment;
                }
            }
            $new_image = imagecreatetruecolor($new_x, $new_h);
            imagecopyresampled($new_image, $original_image, 0, 0, 0, 0, $new_x, $new_h, $original_x, $original_h);
            imagedestroy($original_image);
            if ($max_x != $max_h) {
                if ($max_x > $max_h) {
                    $diff = ($new_h - $max_h);
                    if($diff < 0){
                        $diff = $diff * -1;
                    }
                    $y = round($diff / 2);
                    $x = 0;
                } else {
                    $diff = ($new_x - $max_h);
                    if($diff < 0){
                        $diff = $diff * -1;
                    }
                    $x = round($diff / 2);
                    $y = 0;
                }
            } else {

                if ($new_h > $new_x) {
                    $diff = ($new_h - $new_x);
                    $y = round($diff / 2);
                    $x = 0;
                } else {
                    $diff = ($new_x - $new_h);
                    $x = round($diff / 2);
                    $y = 0;
                }
            }

            $new_cropped_image = imagecreatetruecolor($max_x, $max_h);
            imagecopyresampled($new_cropped_image, $new_image, 0, 0, $x, $y, $max_x, $max_h, $max_x, $max_h);
            imagedestroy($new_image);
            imagejpeg($new_cropped_image, $cropped_file_name, 90);
            // if ($image_type === "image/jpeg") {
            // } elseif ($image_type === "image/png") {
            //     imagepng($new_cropped_image, $cropped_file_name, 90);
            // } elseif ($image_type === "image/jpg") {
            //     imagejpeg($new_cropped_image, $cropped_file_name, 90);
            // }
            imagedestroy($new_cropped_image);
            // return "cropped";
        }
    }
}
