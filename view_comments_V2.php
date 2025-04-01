<?php
include("autoload.php");

$login = new Login();
$user_data = $login->check_login($_SESSION['entreefox_userid']);
$User_data = $user_data;

$Post = new Post();
$ERROR = "";
$place_holder = "Comment";
$display = "flex";
$answer_id = $_SESSION['entreefox_userid'];
$result = "";
// $post_ID = $URL[1];

$post_ID = "";
$likesss = false;
if (isset($URL[1])) {
    $post_ID = $URL[1];
    $likesss = $Post->get_comments($URL[1]);
    $post_ID = $URL[1];
    $row = $Post->get_one_posts($post_ID);
    if (!$row) {
        $ERROR = "No such post was found";
    }
    if ($likesss == "") {
        $ERROR = "No comments";
    }
} else {

    $ERROR = "No comments";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($URL[2])) {
        $post_ID = $URL[2];
        $sql = "select users from comments where comment_id = '$post_ID' limit 1";
        $D = new Database();
        $answer = $DB->read($sql);
        // $swer_id = $answer[0]['users'];
        $user = new User();
        $reply_user = $user->get_user($answer[0]['users']);
        $_POST['parent'] = $URL[2];
        $_POST['parent_commentid'] = $URL[2];
    }
    if (isset($URL[3])) {
        $parent = $URL[3];
    }
    $post = new Post();
    $id = $_SESSION['entreefox_userid'];
    if (isset($URL[2])) {
        $answer_id = $answer[0]['users'];
    }
    if (isset($URL[5])) {
        $_POST['parent_commentid'] = $URL[5];
    }
    $result = $post->create_post($id, $parent, $_POST, $_FILES, $answer_id, $post_ID);
    // $result = $post->post_comment($id, $_POST);
    if (is_numeric($result)) {
        header("Location: " . ROOT . "view_comments/$URL[1]#$result");
    }
}
$state = "Online";
$query2 = "update user_state set state = '$state' where userid = '$_SESSION[entreefox_userid]' limit 1";
$DB = new Database();
$DB->save($query2);
$date = date("Y-m-d H:i:s");
$query3 = "update user_state set date = '$date' where userid = '$_SESSION[entreefox_userid]' limit 1";
$DB = new Database();
$DB->save($query3);
?>

<style>
    #Error_shell {
        display: flex;
        justify-content: center;
    }
</style>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comments | Entreefox</title>
    <link rel="shortcut icon" href="<?php echo ROOT ?>JHHW9351.PNG" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo ROOT ?>view_comments_V2_stylesheet.css">
    <script>
        var lastScrollTop = 0;
        const header = document.getElementById("header");
        // const about = document.getElementById("About");
        window.addEventListener("scroll", function() {
            var scrollTop = window.pageYoffset || document.documentElement.scrollTop;
            if (scrollTop > lastScrollTop) {
                document.getElementById("header").style.top = "-80px"
            } else {
                document.getElementById("header").style.top = "0"
            }
            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
        });

        function ajax_send(data, element) {
            var ajax = new XMLHttpRequest();

            ajax.addEventListener("readystatechange", function() {
                if (ajax.readyState == 4 && ajax.status == 200) {
                    response(ajax.responseText, element);
                }
            });
            data = JSON.stringify(data);

            ajax.open("post", "<?= ROOT ?>ajax.php", true);
            // alert(link);
            ajax.send(data, element);
        }

        function response(result, element) {
            // alert("fuck");
            if (result != "") {
                // alert(result);
                var obj = JSON.parse(result);
                if (typeof obj.action != "undefined") {
                    if (obj.action == "like_post") {
                        var likes = "";
                        likes = parseInt(obj.likes) > 0 ? obj.likes : "";
                        element.innerHTML = likes;
                        document.getElementById(obj.id).src = obj.src;
                        document.getElementById('like' + obj.postid).innerHTML = likes;
                    } else if (obj.action == "favorite") {
                        likes = parseInt(obj.favorite) > 0 ? obj.favorite : "";
                        element.innerHTML = likes;
                        // alert("Added to favorite successflly")
                    } else if (obj.action == "reply_comments") {
                        const Holder = document.getElementById("comment_value");
                        Holder.placeholder = obj.place_holder;
                        submit_comment(obj.place_holder);
                    }
                }
            }
        }

        function submit_comment(place_holder) {
            // e.preventDefault();
            const post_comment = document.getElementById('comment');
            const Holder = document.getElementById("comment_value");
            post_comment.addEventListener('click', function(){
                const comment = document.getElementById('comment_value');
                const url = window.document.location.href.split("/");
                url.splice(0, 5)
                const new_url = url.join("/");
                // alert(new_url);
                if (comment.value.length > 0) {
                    const parentID = document.getElementById('parentID').value;
                    var formData = new FormData();
                    formData.append('comment', comment.value);
                    formData.append('parent', parentID);
                    fetch("<?= ROOT . 'send_comments/' ?>" + new_url, {
                        method: 'POST',
                        body: formData
                    }).then(() => {
                        document.getElementById('comment_value').value = '';
                        document.getElementById('comment_value').style.height = '20px';
                        const link = `<?= ROOT ?>view_comments_V2/${url[2]}`;
                        window.location.href = link;
                        // fetchCommentsSecondEdition(link);
                    });
                } else {
                    Holder.placeholder = "Can't send empty reply";
                    autoResizeTextarea(Holder)
                    setTimeout(() => {
                        Holder.placeholder = place_holder;
                        Holder.style.height = '40px';
                    }, 1000)
                }
            });
        }

        function handleClick(fuck, these) {
            fuck.preventDefault(); // Prevent the default action (navigation)
            const display = document.getElementById("input_comment");
            display.style.display = "flex";
            var link = these.getAttribute("href");
            // alert(link);
            history.pushState(null, "", link);

            var data = {};
            data.link = link;
            data.action = "Reply_comment_V2";
            ajax_send(data, fuck.target);
        }

        function handleClickdiubletap(fuck, these) {
            fuck.preventDefault();
            // var link = these.getAttribute("href");
            var data = {};
            data.link = these;
            data.action = "like_post";
            ajax_send(data, fuck.target);
        }

        function handleClickFavorite(fuck, these) {
            fuck.preventDefault(); // Prevent the default action (navigation)
            var link = these.getAttribute("href");
            //   alert(link);

            var data = {};
            data.link = link;
            data.action = "favorite";
            // alert(data.link);
            ajax_send(data, fuck.target);
        }

        const error = document.getElementById("error");
        const error_shell = document.getElementById("error_shell");
        error.addEventListener("click", () => {
            error_shell.style.display = "none";
        });

        function postmenu() {
            const elements = document.querySelectorAll(".postmenu");

            elements.forEach((element) => {
                element.addEventListener("click", () => {
                    element.src = "close2.png";

                    if (element.src == "close2.png") {
                        element.src = "dots.png";
                    }
                });
            });
        }

        function notification() {
            document.getElementById("notification").style.width = "100vw";
            document.getElementById("notification").style.transition = "ease-in-out 0.2s";
            document.getElementById("notification").style.opacity = 1;
            document.getElementById("content").style.display = "none";
        }

        function back() {
            document.getElementById("notification").style.width = 0;
            document.getElementById("notification").style.opacity = 0;
            document.getElementById("content").style.display = "flex";
        }

        function menu() {
            document.getElementById("menu").style.display = "none";
            document.getElementById("close").style.display = "block";
            const menu_container = document.querySelector(".menu_container");
            menu_container.classList.toggle("close");

            const menu_section = document.querySelector(".menu_section");
            menu_section.classList.toggle("close");

            const menu = document.querySelector(".menu");
            menu.classList.toggle("close");
            document.body.classList.add('no-scroll');
        }

        function close_menu() {
            document.getElementById("close").style.display = "none";
            document.getElementById("menu").style.display = "block";
            const menu_container = document.querySelector(".menu_container");
            menu_container.classList.toggle("close");

            const menu_section = document.querySelector(".menu_section");
            menu_section.classList.toggle("close");

            const menu = document.querySelector(".menu");
            menu.classList.toggle("close");
            document.body.classList.remove('no-scroll');
        }
    </script>
    <!-- <script src="<?php echo ROOT ?>Edit_profile.js"></script> -->
</head>

<body>
    <header id="header">
        <?php
        if (isset($_SERVER['HTTP_REFERER'])) {
            $return_to = $_SERVER['HTTP_REFERER'];
        } else {
            $return_to = "Home";
        }
        ?>
        <div class="left">
            <a href="<?php echo $return_to ?>"><img src="<?php echo ROOT ?>arrow.png"></a>
            <h1>Comments</h1>
        </div>
        <div class="top">
            <img src="<?php echo ROOT ?>close2.png" id="close" style="display: none;" onclick="close_menu()" />
            <img src="<?php echo ROOT ?>menu.png" id="menu" onclick="menu()" />
        </div>
    </header>
    <?php include("Head.php") ?>
    <div class="container">
        <?php
        if (!is_numeric($result) && $result != "") {
            echo "<div class='error_shell' id='error_shell'>";
            echo " <div class='error'>";
            echo "  <P id='error_result'><b>$result.</b></P>";
            echo "  <button id='error''><b>close</b></button>";
            echo " </div>";
            echo "</div>";
            // print_r($result);
        }
        ?>
        <?php
        if (isset($URL[1])) {
            if ($row) {
                // echo "good";
                include("post_delete.php");
            }
        }
        ?>
        <?php
        if ($ERROR != "") {
            $display = "none";
            echo "<div id='Error_shell'>";
            echo "  <P><b>$ERROR.</b></P>";
            echo "</div>";
        }

        ?>
        <section class="chats">
            <div class="chatbox" id="chat_box">
                <div class="comments">
                    <?php
                    // print_r($URL);
                    if (isset($URL[1])) {
                        $comments = $Post->get_comments($URL[1]);
                        if (is_array($comments)) {
                            foreach ($comments as $comment_row) {
                                $user = new User();
                                // echo "good";
                                $comment_user = $user->get_user($comment_row['users']);

                                include("view_chats_V2.php");
                            }
                        }
                    }
                    ?>
                </div>
            </div>
            <?php
            include("input_comment.php");
            ?>
        </section>
    </div>
    <script>
        function autoResizeTextarea(textarea) {
            const text = textarea.value;
            const lines = text.split(/\r?\n/);
            const numberOfLines = lines.length;
            if (textarea.scrollHeight > 40) {
                textarea.style.height = 'auto';
                // Set the height to the scrollHeight, adding some padding if needed
                textarea.style.height = (textarea.scrollHeight) + 'px';
            }
        }

        function autoResizeTextare2(textarea) {
            textarea.style.height = '40px';
        }

        function handleKeydown(event) {
            // event.preventDefault();
            if (event.key == 'Backspace') {
                if (textarea.scrollHeight <= 85) {
                    textarea.style.height = '40px';
                } // Additional actions, e.g., custom logic
                if (textarea.value == "") {
                    textarea.style.height = '40px';
                }
            }
        }
        const textarea = document.getElementById('comment_value');

        // Add event listeners for input and change events
        textarea.addEventListener('input', () => autoResizeTextarea(textarea));
        textarea.addEventListener('keydown', handleKeydown);

        if (textarea.value == "") {
            textarea.style.height = '40px';
        }
        // Initial adjustment
        // autoResizeTextarea2(textarea);
    </script>
</body>

</html>