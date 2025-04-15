<?php

use BcMath\Number;

include("autoload.php");
if (isset($_SESSION['entreefox_userid']) && is_numeric($_SESSION['entreefox_userid'])) {

    $login = new Login();
    $result = $login->check_login($_SESSION['entreefox_userid']);
    $user = new User();
    $id = $_SESSION['entreefox_userid'];
    if ($result) {
        $user_data = $user->get_user($id);
        if ($user_data === false) {
            header("Location: " . ROOT . "Log_in");
            die;
        } else {
            $MSG = new Message;
            $time = new Time;
            $chats = $MSG->get_chats($id);
        }
    } else {

        header("Location: " . ROOT . "Log_in");
        die;
    }
} else {

    header("Location: " . ROOT . "Log_in");
    die;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <title>Chats | Entreefox</title>
    <link rel="shortcut icon" href="<?= ROOT ?>Images/LOGO.PNG" type="image/x-icon">
    <link rel="stylesheet" href="<?= ROOT ?>CSS/Messages_stylesheet.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script type="module" src="https://cdn.socket.io/4.0.0/socket.io.min.js"></script>
    <script type="module" src="<?= ROOT ?>JS/message.js"></script>

</head>

<body>
    <header>
        <a href="<?= ROOT ?>Home"><img src="<?= ROOT ?>Images/LOGO.PNG" alt="" /></a>
        <h1>CHATS</h1>
    </header>
    <section class="error" id="error">
        <div class="error_content">
            <p></p>
        </div>
    </section>
    <section class="chat" id="chat">

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
            <button id="send"><img src="./Images/paper2.png" alt=""></button>
        </div>
        <div class="chat_head">
            <i class="fa-solid fa-arrow-left float" id="close_chat"></i>
            <a href="" id="link_to_profile">
                <div class="profile" id="chat_head_profile" style="background-image: url(<?= ROOT ?>Images/profile.png);">
                </div>
            </a>
            <h2 id="chat_head_name"></h2>
        </div>
        <div class="chat_content">
            <div class="space"></div>
            <div id="chat_content">

            </div>
            <div class="space"></div>
        </div>

    </section>
    <div class="container">
        <section class="search">
            <form id="search_form">
                <div class="search_box">
                    <button type="submit"><i class="fa-solid fa-magnifying-glass menu_icon"></i></button>
                    <input type="text" placeholder="Search" name="Search" id="search">
                </div>
            </form>
            <i class="fa-solid fa-xmark menu_icon" id="close_search" style="font-size: 2rem;" onclick="close_search()"></i>
        </section>
        <section class="chats">
            <?php
            $chat_id = 0;
            if ($chats) {
                if (is_array($chats)) {
                    foreach ($chats as $chat) {
                        if ($chat[0]['sender'] === $_SESSION['entreefox_userid']) {
                            $chat_id = $chat[0]['receiver'];
                        } elseif ($chat[0]['receiver'] === $_SESSION['entreefox_userid']) {
                            $chat_id = $chat[0]['sender'];
                        }
                        $chater_info = $user->get_data($chat_id);
                        include("Messagees.php");
                    }
                } else {
                    echo "<h1 class='no_chat'>" . $chats . "</h1>";
                }
            }
            ?>
            <section class="find_user">
                <div id="find_user">

                </div>
            </section>
        </section>
    </div>
    <!-- <script src="https://cdn.socket.io/4.6.1/socket.io.min.js"></script> -->
    <script>
        const serv = "<?=Server?>";
        let username = "";
        let PFP = "";
        let sender = "<?= $_SESSION['entreefox_userid'] ?>";
        const textarea = document.getElementById("message");
        const ini_height = textarea.clientHeight;
        textarea.addEventListener('input', () => {
            if (textarea.scrollHeight > ini_height) {
                textarea.style.height = 'auto';
                textarea.style.height = (textarea.scrollHeight - 16) + 'px';
            }

        });

        // Trigger resize on page load for pre-filled content
        textarea.dispatchEvent(new Event('input'));
        document.getElementById("search_form").addEventListener('submit', (e) => {
            e.preventDefault();
        })

        document.getElementById('close_chat').addEventListener('click', () => {
            document.getElementById('chat').style.right = "-100dvw";
            document.body.style.overflow = "scroll";
        })
        document.querySelectorAll(".unseen_num").forEach(unseen_num => {
            // alert(unseen_num.client);
            if (unseen_num.clientWidth > 40) {
                num = unseen_num.innerHTML;
                division = (num / 1000);
                if (unseen_num.innerHTML > 99999) {
                    unseen_num.innerHTML = division.toFixed(1);
                    unseen_num.style.borderRadius = "20px";
                    unseen_num.style.padding = "0.3rem";
                } else {
                    unseen_num.innerHTML = division.toFixed(1);
                    unseen_num.style.height = ((unseen_num.clientWidth + 7) + "px");
                    unseen_num.style.width = ((unseen_num.clientWidth + 7) + "px");
                }
            } else {
                // alert("small");
                if (unseen_num.innerHTML <= 9) {
                    unseen_num.style.height = ((unseen_num.clientWidth + 17) + "px");
                    unseen_num.style.width = ((unseen_num.clientWidth + 17) + "px");
                } else {
                    unseen_num.style.height = ((unseen_num.clientWidth + 7) + "px");
                    unseen_num.style.width = ((unseen_num.clientWidth + 7) + "px");
                }
            }
        })
        const search = document.getElementById('search');
        search.addEventListener('focus', () => {
            const find_div = document.getElementById('find_user');
            document.getElementById('close_search').style.display = "block";
            // find_div.innerHTML = result;
            find_div.style.height = "calc(100dvh - 8rem)";
            find_div.style.paddingBottom = "2rem";
            document.body.classList.add('no-scroll');
        })

        function close_search() {
            search.value = "";
            const find_div = document.getElementById('find_user');
            document.getElementById('close_search').style.display = "none";
            find_div.innerHTML = "";
            find_div.style.height = "0px";
            find_div.style.paddingBottom = "0";
            find_div.style.paddingTop = "0";
            document.body.classList.remove('no-scroll');
        }
        search.addEventListener('keydown', async (event) => {
            if (event.key === "Backspace" && event.target.value.length === 1) {
                const find_div = document.getElementById('find_user');
                find_div.innerHTML = "";
            }
        })
        search.addEventListener('keyup', (event) => {
            if (event.target.value.length >= 1) {
                find_user(event.target.value);
            }
        })


        async function find_user(username) {
            try {
                // Send form data to the backend using POST
                const response = await fetch(`<?= ROOT ?>Server_side/find_user_to_chat.php?username=${username}`, {});
                // Handle the response
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }


                const result = await response.text(); // Assuming backend sends JSON response
                if (!result) {
                    const find_div = document.getElementById('find_user');
                    find_div.innerHTML = "";
                } else {
                    const find_div = document.getElementById('find_user');
                    find_div.innerHTML = result;
                    username_div = find_div.querySelectorAll(".messagee .text h2");
                    if (username_div) {
                        replacements = {};
                        username_div.forEach(user_name => {
                            name = user_name.innerHTML;
                            save_name = "";
                            for (let i = 0; i < username.length; i++) {
                                let letter = username[i];
                                if (letter === letter.toUpperCase()) {
                                    letter2 = username[i].toLowerCase(); // Selects the current letter
                                } else {
                                    letter2 = username[i].toUpperCase(); // Selects the current letter
                                } // Selects the current letter
                                replacements[letter] = `<b style= 'color: blue;'>${letter}</b>`
                                replacements[letter2] = `<b style= 'color: blue;'>${letter2}</b>`
                            }
                            let lettersToReplace = Object.keys(replacements).join("");
                            let regex = new RegExp(`[${lettersToReplace}]`, "g");
                            new_name = name.replace(regex, match => replacements[match])
                            user_name.innerHTML = new_name;
                        })
                    }
                    find_div.style.height = "calc(100dvh - 10rem)";
                    find_div.style.paddingBottom = "2rem";
                    find_div.style.paddingTop = "1rem";
                    document.body.classList.add('no-scroll');
                }
            } catch (error) {
                const Error = document.getElementById('error');
                Error.style.display = "flex";
                Error.querySelector('.error_content p').innerHTML = error;
                setTimeout(() => {
                    Error.style.display = "none";
                }, 3000)
            }
        }
        // alert(roomid);
    </script>
    <script>
        async function get_chat(these) {
            document.getElementById('BG3').style.display = "flex";
            document.getElementById('chat').style.right = 0;
            document.body.style.overflow = "hidden";
            username = these.getAttribute('data-name');
            PFP = these.querySelector(".profile").style.backgroundImage;
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
                    document.getElementById('chat_head_name').innerHTML = username;
                    document.getElementById('chat_head_profile').style.backgroundImage = PFP;
                    document.getElementById("link_to_profile").href = `<?= ROOT ?>Profile/${username}`;
                    document.getElementById('BG3').style.display = "none";
                    chat_content.innerHTML = "";
                } else {
                    const chat_content = document.getElementById('chat_content');
                    document.getElementById('chat_head_name').innerHTML = username;
                    document.getElementById("link_to_profile").href = `<?= ROOT ?>Profile/${username}`;
                    const chat_content2 = document.querySelector('.chat_content');
                    chat_content.innerHTML = result;
                    chat_content2.scrollTo({
                        top: chat_content2.scrollHeight,
                        behavior: 'smooth'
                    })
                    document.getElementById('chat_head_profile').style.backgroundImage = PFP;
                    document.getElementById('BG3').style.display = "none";
                }
            } catch (error) {
                const Error = document.getElementById('error');
                Error.style.display = "flex";
                Error.querySelector('.error_content p').innerHTML = error;
                setTimeout(() => {
                    Error.style.display = "none";
                }, 3000)
                // document.getElementById('error_content').innerText = error;
            }
            join_room();
        }
    </script>
</body>

</html>