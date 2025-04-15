<?php
include("autoload.php");
if (isset($_SESSION['entreefox_userid']) && is_numeric($_SESSION['entreefox_userid'])) {

    $login = new Login();
    $result = $login->check_login($_SESSION['entreefox_userid']);
    $user = new User();
    if (isset($URL[1])) {
        $id = $user->get_user_by_name($URL[1]);
    } else {
        $id = $_SESSION['entreefox_userid'];
    }

    if ($result) {
        $user_data = $user->get_user($id);
        if ($user_data === false) {
            header("Location: Log_in");
            die;
        } else {
            // $post = new Post();
            // $posts = $post->get_posts($id);
            // $shop = $user->get_shop($id);
        }
    } else {

        header("Location: " . ROOT . "Log_in");
        die;
    }
} else {

    header("Location: " . ROOT . "Log_in");
    die;
}
// $state = "Online";
// $query2 = "insert into user_state (userid, state) values ('$id', '$state')";

// $DB = new Database();
// $DB->save($query2);
$state = "Online";
$query2 = "update user_state set state = '$state' where userid = '$id' limit 1";
$DB = new Database();
$DB->save($query2);
$date = date("Y-m-d H:i:s");
$query3 = "update user_state set date = '$date' where userid = '$_SESSION[entreefox_userid]' limit 1";
$DB->save($query3);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat <?= $user_data['user_name'] ?> | Entreefox</title>
    <link rel="shortcut icon" href="<?= ROOT ?>Images/LOGO.PNG" type="image/x-icon">
    <link rel="stylesheet" href="<?= ROOT ?>CSS/Messages_stylesheet.css">
    <link rel="stylesheet" href="<?= ROOT ?>CSS/Message_stylesheet.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script type="module" src="https://cdn.socket.io/4.0.0/socket.io.min.js"></script>
    <script type="module" src="<?= ROOT ?>JS/message.js"></script>
</head>

<body>
    <?php
    if (isset($_SERVER['HTTP_REFERER'])) {
        $return_to = $_SERVER['HTTP_REFERER'];
    } else {
        $return_to = ROOT . "Home";
    }
    ?>
    <section class="chat chat2" id="chat">

        <div class="BG1"></div>
        <div class="BG2"></div>
        <div class="BG3" id="BG3">
            <div class="loader">
                <span>

                </span>
                <span>

                </span>
            </div>
        </div>
        <div class="send">
            <textarea name="message" id="message" rows="1" maxlength="150" placeholder=""></textarea>
            <button id="send"><img src="<?= entreefox ?>/Images/paper2.png" alt=""></button>
        </div>
        <div class="chat_head">
            <a href="<?= $return_to ?>">
                <i class="fa-solid fa-arrow-left float" id="close_chat"></i>
            </a>
            <div class="profile" id="chat_head_profile" style="background-image: url(<?= file_exists($user_data['profile_image']) ? entreefox . $user_data['profile_image'] : entreefox . "Images/profile.png" ?>);">
            </div>
            <h2 id="chat_head_name"><?= $user_data['user_name'] ?></h2>
        </div>
        <div class="chat_content">
            <div class="space"></div>
            <div id="chat_content">

            </div>
            <div class="space"></div>
        </div>

    </section>
    <script>
        const serv = "<?= Server ?>";
        const textarea = document.getElementById("message");
        const ini_height = textarea.clientHeight;
        let PFP = document.getElementById("chat_head_profile").style.backgroundImage;
        let sender = "<?= $_SESSION['entreefox_userid'] ?>";
        textarea.addEventListener('input', () => {
            if (textarea.scrollHeight > ini_height) {
                textarea.style.height = 'auto';
                textarea.style.height = (textarea.scrollHeight - 16) + 'px';
            }

        });
        // alert("<?= $URL[1] ?>")
        async function get_chat() {
            document.getElementById('BG3').style.display = "flex";
            username = "<?= $URL[1] ?>";
            try {
                // Send form data to the backend using POST
                const response = await fetch(`<?= ROOT ?>Chat.php?username=${username}`, {});
                // Handle the response
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }


                const result = await response.text(); // Assuming backend sends JSON response
                if (!result) {
                    const chat_content = document.getElementById('chat_content');
                    document.getElementById('BG3').style.display = "none";
                    chat_content.innerHTML = "";
                } else {
                    const chat_content = document.getElementById('chat_content');
                    const chat_content2 = document.querySelector('.chat_content');
                    chat_content.innerHTML = result;
                    chat_content2.scrollTo({
                        top: chat_content2.scrollHeight,
                        behavior: 'smooth'
                    })
                    document.getElementById('BG3').style.display = "none";
                }
            } catch (error) {
                const Error = document.getElementById('error');
                Error.style.display = "flex";
                Error.querySelector('.error_content p').innerHTML = error;
                setTimeout(() => {
                    Error.style.display = "none";
                }, 3000)
            }
            join_room();
        }
        get_chat();
    </script>
</body>

</html>