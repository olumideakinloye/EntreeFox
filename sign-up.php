<?php

include("Edit_image.php");
include("user.php");




// require 'config.php';

class Signup
{
    private $error = "";
    private $error2 = "";

    public function evaluate($data, $type)
    {
        foreach ($data as $key => $value) {
            if ($key == "email") {
                if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $value)) {
                    $this->error = $this->error . "invalid email address!<br>";
                }
            }
            if ($key == "First_name") {
                if (is_numeric($value)) {
                    $this->error = $this->error . "First name can't have numbers<br>";
                }
                if (strstr($value, " ")) {
                    $this->error = $this->error . "First name can't have spaces<br>";
                }
            }
            if ($key == "Last_name") {
                if (is_numeric($value)) {
                    $this->error = $this->error . "Last name can't have numbers<br>";
                }
                if (strstr($value, " ")) {
                    $this->error = $this->error . "Last name can't have spaces<br>";
                }
            }
            if ($key == "user_name") {
                if (is_numeric($value)) {
                    $this->error = $this->error . "User name can't have numbers<br>";
                }
                if (strstr($value, " ")) {
                    $this->error = $this->error . "User name can't have spaces<br>";
                }
            }
            if ($key == "password") {
                $pass = $value;
            }
            if ($key == "Confirm_password") {
                if ($pass != $value) {
                    $this->error = $this->error . $key . "must be the same as you password!<br>";
                }
            }
            if (empty($value)) {
                $this->error = $this->error . $key . " is empty!<br>";
            }
        }
        if ($this->error == "") {
            $this->error2 = $this->create_user($data, $type);
            if ($this->error2 != "") {
                return $this->error2;
            }
        } else {

            return $this->error;
        }
    }
    public function create_user($data, $type)
    {

        $userid = $this->create_userid();
        $firstname = ucfirst($data['First_name']);
        $lastname = ucfirst($data['Last_name']);
        $user_name = ucfirst($data['user_name']);
        $gender = $data['gender'];
        $email = $data['email'];
        $password = hash("sha1", $data['password']);
        $url_address = strtolower($firstname) . "." . strtolower($lastname);


        // $check_user = new Upload_parameters();

        $date = date("Y-m-d H:i:s");

        // $checker = $check_user->check_user_details($userid, $user_name);
        $checker = "";
        if ($checker != "") {
            $this->error = $this->error . $checker;
            return $this->error;
        } else {
            $About = addslashes("Hi i'm $lastname $firstname, a visual artist, Welcome to my world hoping to connect to you my potential customers in this. I want to say a very big thank you to Entreefox for giving us the opportunity to share our minds and products.");
            $query = "insert into users
            (userid, first_name, last_name, user_name, gender, email, password, url_address, About, user_type, date) 
            values('$userid', '$firstname', '$lastname', '$user_name', '$gender', '$email', '$password', '$url_address', '$About', '$type', '$date')";


            $DB = new Database();
            $DB->save($query);

            $state = "Online";
            $query2 = "insert into user_state (userid, state, date) values('$userid', '$state', '$date')";

            $DB->save($query2);
        }
    }
    private function create_userid()
    {
        $length = rand(4, 19);
        $number = "";
        for ($i = 0; $i < $length; $i++) {
            $new_rand = rand(0, 9);
            $number = $number . $new_rand;
        }
        return $number;
    }

    public function create_OTP()
    {
        $number = rand(100000, 999999);
        return $number;
    }

    public function check_user_email($email)
    {
        $query = "select * from users where email = '$email' limit 1";
        $DB = new Database();
        $result = $DB->read($query);
        if($result){
            $arr['confirmation'] = "good";
            $arr['name'] = $result[0]['user_name'];
            return $arr;
        }else{
            $arr['confirmation'] = "no_find";
            return $arr;
        }
    }
    public function change_password($email, $data)
    {
        $password = hash("sha1", $data['password']);
        $query = "update users set password = '$password' where email = '$email' limit 1";
        $DB = new Database();
        $DB->save($query);
        return "success";
    }
}
