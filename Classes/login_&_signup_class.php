<?php
class Login
{

    public function evaluate($data)
    {
        $user_name = addslashes(ucfirst($data['user_name']));
        $password = hash("sha1", addslashes($data['password']));
        $result = $this->get_user($user_name, $password);
        if ($result !== false) {
            $_SESSION['entreefox_userid'] = $result[0]['userid'];
            return "Valid";
        } else {
            return "Invalid email or password";
        }
    }

    public function get_user($user_name, $password)
    {
        $query = "select * from users where user_name = '$user_name' && password = '$password' limit 1";
        $DB = new Database();
        $result = $DB->read($query);
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }
    public function check_login($id)
    {
        $DB = new Database();
        $query = "select * from users where userid = '$id' limit 1";
        $result = $DB->read($query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    public function check_new_user()
    {
        if (!isset($_COOKIE['first_time_visitor'])) {
            // First-time visitor logic
            // Set a cookie that expires in 1 year
            setcookie('first_time_visitor', '1', time() + (365 * 24 * 60 * 60), "/");
            return true;
        } else {
            // Returning visitor logic
            return false;
        }
    }
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
        $password = addslashes($data['password']);
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
