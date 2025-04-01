<?php
class User{
    public function get_user_info($user_info) {
        $query = "select * from users where userid = '$user_info' limit 1";
        $DB = new Database();
        $result = $DB->read($query);
        if($result){
            $row = $result[0];
            return $row;
        }else{
            return false; 
        }
    }
}