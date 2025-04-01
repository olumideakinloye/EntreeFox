<?php
include(ROOT . "autoload.php");
// Get the raw POST data
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $signup = new signup();
    $result = $signup->evaluate($_POST);
    echo $result;
}
class signup
{
    private $error;
    public function evaluate($data)
    {
        // die;
        foreach ($data as $key => $value) {
            if (empty($value)) {
                $this->error += $key . "is empty";
            }
        }
        $password =addslashes($data['password']);
        $encoded_password = hash("sha1", $password);
        if ($this->error == "") {
            $result = $this->eval_username($data['user_name']);
            if (!$result) {
                // return $result;
                $this->error = $this->create_user($data, $encoded_password);
            } else {
                $this->error = "User name is un available.";
                // return $this->error;
            }
        } else {
            return $this->error;
        }
        return $this->error;
    }
    public function create_user($data, $password)
    {
        if ($this->error == "") {
            // return "good";
            $user_id = $this->create_userid();
            $firstName = $data['First_name'];
            $lastName = $data['Last_name'];
            $userName = $data['user_name'];
            $gender = $data['gender'];
            $email = $data['email'];
            $url = $firstName . "." . $lastName;
            $about = addslashes($this->random_about(rand(0, 11)));
            $user_type = $_GET['user_type'];
            $query = "insert into users (userid, first_name, last_name, user_name, gender, email, password, url_address, About, user_type) values ('$user_id', '$firstName', '$lastName', '$userName', '$gender', '$email', '$password', '$url', '$about', '$user_type')";
            $DB = new Database();
            $DB->save($query);
            return "successful";
            // return"Location: " . ROOT . "Log-in";
            // header("Location: " . ROOT . "Log-in");
        }
    }
    public function create_userid()
    {
        $length = rand(4, 19);
        $number = "";
        for ($i = 0; $i < $length; $i++) {
            $new_rand = rand(0, 9);
            $number .= $new_rand;
        }
        return $number;
    }
    public function eval_username($username)
    {
        $DB = new Database();
        $query = "select * from users where user_name = '$username' limit 1";
        $result = $DB->read($query);
        return $result;
    }
    public function random_about($index)
    {
        $about_arr = [
            "Aspiring entrepreneur ready to change the world.",
            "Passionate about innovation and building the future.",
            "On a journey to turn ideas into impact.",
            "Looking to connect, collaborate, and grow.",
            "Open to partnerships and opportunities.",
            "Let's innovate together!",
            "Dreaming big and building bold.",
            "Founder in progress—stay tuned!",
            "Scaling ideas one step at a time.",
            "Share your mission and vision here!",
            "Tell the world about your business journey.",
            "Your story starts here—update your bio."
        ];
        return $about_arr[$index];
    }
}
