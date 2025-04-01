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
            $post = new Post();
            $posts = $post->get_posts($id);
            $shop = $user->get_shop($id);
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <?php if ($user_data['userid'] !== $_SESSION['entreefox_userid']) {
    ?>
        <title><?= $user_data['user_name'] ?> | Entreefox</title>
    <?php } else { ?>
        <title>Profile | Entreefox</title>
    <?php } ?>
    <link rel="shortcut icon" href="<?= ROOT ?>Images/LOGO.PNG" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo ROOT ?>CSS/User_profile_stylesheet.css">
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"> -->
    <!-- <script>
        let clickTimeout = null;
        const DOUBLE_CLICK_DELAY = 300;

        function checkPosition() {
            const overFlow2controller = document.getElementById('favss');
            const targetDiv = document.getElementById('post_about_favorite');
            const overFlowcontroller = document.getElementById('people');
            if (targetDiv !== null) {
                const rect = targetDiv.getBoundingClientRect();
                if (rect.top >= 0 && rect.top <= 20) {
                    overFlowcontroller.style.overflowY = "scroll";
                    overFlow2controller.style.overflowY = "scroll";
                } else {
                    overFlowcontroller.style.overflowY = "hidden";
                    overFlow2controller.style.overflowY = "hidden";
                }
            } else {
                // console.log(targetDiv);
            }
        }

        window.addEventListener('scroll', checkPosition);

        checkPosition();

        function ajax_send(data, element, id) {
            var ajax = new XMLHttpRequest();

            ajax.addEventListener("readystatechange", function() {
                if (ajax.readyState == 4 && ajax.status == 200) {
                    response(ajax.responseText, element, id);
                }
            });
            data = JSON.stringify(data);

            ajax.open("post", "<?= ROOT ?>ajax.php", true);
            // alert(link);
            ajax.send(data, element, id);
        }

        function response(result, element) {
            // alert("fuck");
            if (result != "") {
                // alert(result);
                var obj = JSON.parse(result);
                if (typeof obj.action != "undefined") {
                    if (obj.action == "liked") {
                        const like_number = document.getElementById(`like_${obj.id}_number`);
                        const like_icon = document.getElementById(`info_${obj.id}`);
                        like_icon.classList.remove('fa-regular');
                        like_icon.classList.add('fa-solid');
                        like_icon.style.color = "red";
                    } else if (obj.action == "unliked") {
                        const like_number = document.getElementById(`like_${obj.id}_number`);
                        const like_icon = document.getElementById(`info_${obj.id}`);
                        like_icon.classList.add('fa-regular');
                        like_icon.classList.remove('fa-solid');
                        like_icon.style.color = "black";
                    } else if (obj.action == "favorite") {
                        likes = parseInt(obj.favorite) > 0 ? obj.favorite : "";
                        element.innerHTML = likes;
                        alert("Added to favorite successflly")
                    }
                }
            }
        }

        function handleLikeClick(fuck, these) {
            fuck.preventDefault();
            const id = these.getAttribute('data-id');
            const like_number = document.getElementById(`like_${id}_number`);
            const like_icon = document.getElementById(`info_${id}`);
            if (like_number.textContent === "") {
                like_number.textContent = "1";
                like_icon.classList.remove('fa-regular');
                like_icon.classList.add('fa-solid');
                like_icon.style.color = "red";
            } else {
                let new_number = Number(like_number.textContent);
                if (like_icon.classList.contains('fa-regular')) {
                    like_icon.classList.remove('fa-regular');
                    like_icon.classList.add('fa-solid');
                    like_icon.style.color = "red";
                    like_number.textContent = ++new_number;
                } else {
                    like_icon.classList.add('fa-regular');
                    like_icon.classList.remove('fa-solid');
                    like_icon.style.color = "black";
                    if (new_number === 1) {
                        like_number.textContent = "";
                    } else {
                        like_number.textContent = --new_number;
                    }
                }
            }
            var link = these.getAttribute("href");
            var data = {};
            data.link = link;
            data.action = "like_post";
            ajax_send(data, fuck.target, id);
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
    </script> -->
</head>

<body>
    <div class="container">
        <div onclick="close_like(event)" class="LIKES-CONTAINER" id="LIKES-CONTAINER">
            <div class='loading-container-likes' id='loading-container'>
                <div class='spinner_likes'>

                </div>
            </div>
            <div class="LIKES_BOX" id="LIKES_BOX">
                <div class="control_position">

                </div>
                <div class="LIKES_BOX-BOTTOM" id="LIKES_BOX-BOTTOM">

                </div>
            </div>
        </div>
        <div class="like-animation-container" id="like-animation-container">
            <div class="like_content">
                <div class="like-box">
                    <div class="like1">

                    </div>
                    <div class="like2">

                    </div>
                </div>
            </div>
            <!-- <div class="heart"></div> -->
        </div>
        <div class="profile">
            <?php
            $cover = "Images/patrick-fore-850jTF12RSQ-unsplash.jpg";
            $covers = "";
            if (!empty($user_data['cover_image'])) {
                $covers = json_decode($user_data['cover_image']);
                if (is_array($covers)) {
                    $new_covers = array_column($covers, "cover");
                    // print_r($new_covers);
            ?>
                    <div class="background" id="animate">

                    </div>
                <?php
                } elseif (file_exists($user_data['cover_image'])) {
                    $cover = $user_data['cover_image'];
                ?>

                    <div class="background" style="background-image: url(<?php echo ROOT . $cover ?>);">

                    </div>
                <?php
                } else {
                ?>
                    <div class="background animate" id="animate">

                    </div>

                <?php
                }
            } else {
                ?>
                <div class="background animate" id="animate">

                </div>

            <?php
            }
            ?>
            <?php
            if ($user_data['userid'] === $_SESSION['entreefox_userid']) {
            ?>
                <a href="<?php echo ROOT ?>Edit_profile">
                    <div class="home edit" style="background-image: url(<?= ROOT ?>Images/2146430501586788045.svg);"></div>
                </a>
            <?php
            } ?>
            <a href="<?php echo ROOT ?>Home">
                <div style="background-image: url(<?php echo ROOT . 'Images/LOGO.PNG' ?>);" class="home"></div>
            </a>
            <?php
            $image = "Images/profile.png";
            if (!empty($user_data['profile_image'])) {
                if (file_exists($user_data['profile_image'])) {
                    $image = $user_data['profile_image'];
                }
            }
            ?>
            <div class="picture" style="background-image: url(<?php echo ROOT . $image; ?>);">

            </div>
        </div>
        <div class="user_info">
            <div class="details">
                <h2><?php echo htmlspecialchars($user_data['user_name']) ?></h2>
                <p>@<?php echo htmlspecialchars($user_data['user_name']) ?></p>
            </div>
            <?php if ($user_data['userid'] !== $_SESSION['entreefox_userid']) {
            ?>
                <a href="<?= ROOT ?>Message/<?= $user_data['user_name'] ?>">
                    <img src="<?= ROOT ?>Images/icons8-send-96.png" alt="">
                </a>
            <?php } ?>
        </div>
        <div class="about">
            <p><?= $user_data['About'] ?></p>
        </div>
        <div class="logo">
            <img src="<?= ROOT ?>Images/LOGO.PNG" alt="">
        </div>
        <?php
        if ($user_data['user_type'] == "Vendor") {
            if (!$shop) {

        ?>
                <div class="create_shop">
                    <a href="<?= ROOT ?>Create_shop">
                        <button>Create Shop</button>
                    </a>
                </div>
            <?php
            } else {
            ?>
                <div class="create_shop">
                    <a href="<?= ROOT ?>Add_product">
                        <button>Upload New Product</button>
                    </a>
                </div>
        <?php
            }
        }
        ?>

    </div>
    <?php if (!$shop) {
    ?>
        <style>
            .animation {
                left: 7vw;
            }

            .post_animation {
                left: 7vw;
            }

            .about_animation {
                left: 40vw;
            }

            .favss_animation {
                left: 76vw;
            }
        </style>
    <?php
    } ?>
    <script>
        <?php if ($covers) {
            if (is_array($covers)) { ?>
                window.addEventListener("load", () => {
                    let bgImages = [];
                    <?php
                    foreach ($new_covers as $single_cover) {
                    ?>
                        bgImages.push("<?= entreefox . "$single_cover" ?>")
                    <?php
                        // echo "../$single_cover,";
                    } ?>;
                    console.log(bgImages);
                    let index = 0;
                    const bgElement = document.getElementById("animate");

                    function changeBackground() {
                        const img = new Image();
                        img.src = bgImages[index]; // Preload image before applying
                        img.onload = () => {
                            bgElement.style.backgroundImage = `url(${bgImages[index]})`;
                            index = (index + 1) % bgImages.length;
                        };
                    }

                    setInterval(changeBackground, 4000); // Change every 2 seconds
                    changeBackground();
                });
        <?php }
        } ?>
        <?php if (empty($user_data['cover_image']) && !is_array($covers) || !file_exists($user_data['profile_image']) && !is_array($covers)) {
        ?>
            window.addEventListener("load", () => {
                const images = ["<?= entreefox ?>Images/BG1.jpg",
                    "<?= entreefox ?>Images/BG2.jpg",
                    "<?= entreefox ?>Images/BG3.jpg",
                    "<?= entreefox ?>Images/BG4.png",
                    "<?= entreefox ?>Images/BG5.png",
                    "<?= entreefox ?>Images/BG6.jpg",
                    "<?= entreefox ?>Images/BG7.jpg",
                    "<?= entreefox ?>Images/BG8.jpg",
                    "<?= entreefox ?>Images/BG9.jpeg",
                    "<?= entreefox ?>Images/BG10.jpg"
                ];;
                let index = 0;
                const bgElement = document.getElementById("animate");

                function changeBackground() {
                    const img = new Image();
                    img.src = images[index]; // Preload image before applying
                    img.onload = () => {
                        bgElement.style.backgroundImage = `url(${images[index]})`;
                        index = (index + 1) % images.length;
                    };
                }

                setInterval(changeBackground, 4000); // Change every 2 seconds
                changeBackground();
            });
        <?php } ?>
    </script>
    <!-- <script>
        function ajax_send_for_comments(data, element) {
            var ajax = new XMLHttpRequest();

            ajax.addEventListener("readystatechange", function() {
                if (ajax.readyState == 4 && ajax.status == 200) {
                    response_for_comments(ajax.responseText, element);
                }
            });
            data = JSON.stringify(data);

            ajax.open("post", "<?= ROOT ?>ajax.php", true);
            ajax.send(data, element);
        }

        function response_for_comments(result, element) {
            if (result != "") {
                // alert(result);
                var obj = JSON.parse(result);
                if (typeof obj.action != "undefined") {
                    if (obj.action == "reply_comments") {
                        const Holder = document.getElementById("comment_value");
                        Holder.placeholder = obj.place_holder;
                        Holder.addEventListener('input', () => autoResizeTextareaTextarea(event, Holder));
                        Holder.addEventListener('keydown', function(event) {
                            if (event.key == 'Backspace') {
                                if (Holder.scrollHeight < 65) {
                                    Holder.style.height = '20px';
                                } // Additional actions, e.g., custom logic
                                if (Holder.value == "") {
                                    Holder.style.height = '20px';
                                }
                            }
                        });
                        document.getElementById('comment').addEventListener('click', () => {

                            // e.preventDefault();
                            const comment = document.getElementById('comment_value');
                            // if (comment) {
                            //   alert("good");
                            // }
                            // alert(window.document.location.href);
                            const url = window.document.location.href.split("/");
                            url.splice(0, 5)
                            // alert(url[2]);
                            const new_url = url.join("/");
                            // alert(new_url);
                            if (comment.value.length > 2) {
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
                                    const link = `<?= ROOT ?>view_comments/${url[2]}`;
                                    fetchCommentsSecondEdition(link);

                                });
                            } else {
                                Holder.placeholder = "Can't send empty reply";
                                setTimeout(() => {
                                    Holder.placeholder = obj.place_holder;
                                }, 1000)
                            }
                        });

                    }
                }
            }
        }

        function handleClick(fuck, these) {
            fuck.preventDefault();
            const textarea = document.getElementById("input_comment");
            document.getElementById('comments').style.paddingBottom = "130px";
            textarea.style.display = "flex";
            var link = "<?= ROOT . "User_profile" ?>" + "/" + these.getAttribute("href");
            // history.pushState(null, "", link);
            var data = {};
            data.link = link;
            data.action = "Reply_comment";
            ajax_send_for_comments(data, fuck.target);
        }

        function autoResizeTextareaTextarea(event, value) {
            const text = value.value;
            const lines = text.split(/\r?\n/);
            const numberOfLines = lines.length;
            if (value.scrollHeight > 45) {
                value.style.height = 'auto';
                // Set the height to the scrollHeight, adding some padding if needed
                value.style.height = (value.scrollHeight - 20) + 'px';
            }

            // Additional actions, e.g., custom logic
            if (value.value == "") {
                value.style.height = '20px';
            }
        }
        let loadedScripts = [];

        function resize_large_vid() {
            const posts = document.getElementById('people');
            const videos = posts.querySelectorAll('.VIDS');
            videos.forEach(vid => {
                if (vid.clientHeight > window.innerHeight) {
                    const id = vid.getAttribute("data-id");
                    // console.log(vid.id + "is too large for this screen");
                    document.getElementById(`video${id}img_container`).style.maxHeight = "75vh";
                }
            });
        }

        function fetchComments(event, these) {
            event.preventDefault();
            scrollPosition = document.getElementById('LIKES_BOX').scrollTop;
            document.getElementById('loading-container').style.display = "flex";
            document.getElementById('LIKES-CONTAINER').classList.add("view_likes");
            document.getElementById('LIKES_BOX').classList.add("view_likes_box");
            document.getElementById('LIKES_BOX').style.height = "50dvh";
            document.getElementById('LIKES_BOX-BOTTOM').innerHTML = "";
            document.body.classList.add('no-scroll');
            loadedScripts.forEach(script => {
                document.body.removeChild(script);
            });
            loadedScripts = [];
            let link = these.getAttribute('href');
            fetch(link)
                .then(response => response.text())
                .then(html => {
                    const messageContainer = document.getElementById('LIKES_BOX-BOTTOM');
                    messageContainer.innerHTML = html;
                    document.getElementById('loading-container').style.display = "none";

                    const scripts = document.getElementById('LIKES_BOX').querySelectorAll('script');
                    scripts.forEach(script => {
                        const newScript = document.createElement('script');
                        newScript.textContent = script.textContent;
                        document.body.appendChild(newScript);
                        // loadedScripts.push(newScript);
                        loadedScripts.push({
                            src: script.src || null,
                            content: script.src ? null : script.textContent
                        });
                        script.remove();
                    });
                    // scripts.forEach(script => script.remove());
                    // document.getElementById('LIKES_BOX').scrollTop = scrollPosition;
                    const resizableDiv = document.getElementById('LIKES_BOX');
                    resizableDiv.addEventListener('mousedown', startResize);
                    resizableDiv.addEventListener('touchstart', startResize);

                });

            // setInterval(fetchComments(event, these), 100000)
        }

        function startResize(e) {
            isResizing = true;
            const resizableDiv = document.getElementById('LIKES_BOX');
            startY = e.type === 'mousedown' ? e.clientY : e.touches[0].clientY;
            startHeight = resizableDiv.offsetHeight;

            document.addEventListener('mousemove', resize);
            document.addEventListener('touchmove', resize);
            document.addEventListener('mouseup', stopResize);
            document.addEventListener('touchend', stopResize);
        }

        function resize(e) {
            const resizableDiv = document.getElementById('LIKES_BOX');
            if (e.target.closest('.LIKES_BOX-BOTTOM')) {} else {

                if (!isResizing) return;

                let currentY = e.type === 'mousemove' ? e.clientY : e.touches[0].clientY;
                let newHeight = startHeight - (currentY - startY);
                var middleViewHeight = (window.innerHeight / 2) - 50;

                // Ensure the div's height does not go below a minimum value
                if (newHeight < middleViewHeight || newHeight < 50 && resizableDiv.style.height == "50dvh") {
                    document.getElementById('LIKES-CONTAINER').classList.remove("view_likes");
                    document.getElementById('LIKES_BOX').classList.remove("view_likes_box");
                    document.getElementById('LIKES_BOX').style.height = 0;
                    const textarea = document.getElementById("input_comment");
                    if (textarea) {
                        textarea.style.display = "none";
                    }
                    const observer = new MutationObserver((mutations) => {
                        mutations.forEach((mutation) => {
                            mutation.addedNodes.forEach((node) => {
                                if (node.id === 'input_comment') {
                                    document.getElementById("input_comment").style.display = "none";
                                    observer.disconnect(); // Stop observing once found
                                }
                            });
                        });
                    });
                    loadedScripts.forEach(scriptInfo => {
                        const existingScript = Array.from(document.scripts).find(
                            existing => (scriptInfo.src && existing.src === scriptInfo.src)
                        );

                        if (existingScript) {
                            console.log(`Removing existing script: ${existingScript.src}`);
                            existingScript.remove();
                        }

                        // Re-insert stored scripts back into the document
                        const script = document.createElement('script');

                        if (scriptInfo.src) {
                            // Re-activate by setting the src attribute
                            script.src = scriptInfo.src;
                        } else if (scriptInfo.content) {
                            // Wrap the script content in an IIFE to avoid global scope pollution
                            script.textContent = `(function(){ ${scriptInfo.content} })();`;
                        }

                        // Append the script to the body (or a specific container)
                        document.body.appendChild(script);
                    });
                    loadedScripts = [];
                    link = "<?= ROOT ?>User_profile"
                    // history.pushState(null, "", link);
                    newHeight = 50;
                    setTimeout(() => {
                        document.body.classList.remove('no-scroll');
                    }, 300);
                } else {
                    if (newHeight > startHeight) {
                        resizableDiv.style.height = `${85}dvh`;
                        document.getElementById('LIKES_BOX-BOTTOM').style.height = `${85}dvh`;
                        document.getElementById('loading-container').style.top = "50%";

                    } else {
                        resizableDiv.style.height = `${50}dvh`;
                        document.getElementById('LIKES_BOX-BOTTOM').style.height = `${50}dvh`;
                        document.getElementById('loading-container').style.top = "70%";
                    }
                }
            }
        }


        function stopResize() {
            isResizing = false;

            document.removeEventListener('mousemove', resize);
            document.removeEventListener('touchmove', resize);
            document.removeEventListener('mouseup', stopResize);
            document.removeEventListener('touchend', stopResize);
        }


        function fetchCommentsSecondEdition(link) {
            document.getElementById('loading-container').style.display = "flex";
            fetch(link)
                .then(response => response.text())
                .then(html => {
                    const messageContainer = document.getElementById('LIKES_BOX');
                    messageContainer.innerHTML = html;
                    document.getElementById('loading-container').style.display = "none";
                    // document.getElementById('LIKES_BOX').scrollTop = scrollPosition;
                    const scripts = document.getElementById('LIKES_BOX').querySelectorAll('script');
                    scripts.forEach(script => {
                        const newScript = document.createElement('script');
                        newScript.textContent = script.textContent;
                        document.body.appendChild(newScript);
                        loadedScripts.push({
                            src: script.src || null,
                            content: script.src ? null : script.textContent
                        });
                        script.remove();
                    });
                    const resizableDiv = document.getElementById('LIKES_BOX');
                    resizableDiv.addEventListener('mousedown', startResize);
                    resizableDiv.addEventListener('touchstart', startResize);
                });

            // setInterval(fetchComments(event, these), 100000)
        }

        document.addEventListener('DOMContentLoaded', () => {
            const videos = document.querySelectorAll('.VIDS');
            const payButtons = document.querySelectorAll('.paper');

            // Function to hide all pay buttons
            function hideAllPayButtons() {
                payButtons.forEach(button => button.style.display = 'none');
            }

            // Function to show the pay button for a specific video
            function showPayButtonForVideo(videoId) {
                const button = document.querySelector(`.paper[data-video-id="${videoId}"]`);
                if (button) button.style.display = 'block';
                // if (button) button.style.opacity = 1;
            }

            function showPayButtonForVideosss() {
                payButtons.forEach(button => button.style.display = 'block');
                payButtons.forEach(button => button.style.opacity = 1);
            }

            videos.forEach(video => {
                video.addEventListener('play', () => {
                    // alert("fuck");
                    hideAllPayButtons();
                    showPayButtonForVideo(video.id);
                    // alert(video.id);
                });

                video.addEventListener('pause', () => {
                    showPayButtonForVideosss();
                });

            });
        });

        function fetchlikes(event, these) {
            event.preventDefault();
            document.getElementById('loading-container').style.display = "flex";
            document.getElementById('LIKES-CONTAINER').classList.add("view_likes");
            document.getElementById('LIKES_BOX').classList.add("view_likes_box");
            document.getElementById('LIKES_BOX').style.height = "50dvh";
            document.getElementById('LIKES_BOX-BOTTOM').innerHTML = "";
            let link = these.getAttribute('href');
            document.body.classList.add('no-scroll');
            fetch(link)
                .then(response => response.text())
                .then(html => {
                    const messageContainer = document.getElementById('LIKES_BOX-BOTTOM');
                    messageContainer.innerHTML = html;
                    document.getElementById('loading-container').style.display = "none";
                    const resizableDiv = document.getElementById('LIKES_BOX');
                    resizableDiv.addEventListener('mousedown', startResize);
                    resizableDiv.addEventListener('touchstart', startResize);

                });
            // window.scrollTo(0, document.body.scrollHeight);
        }

        function close_like(e) {
            if (e.target.closest('.LIKES_BOX') || e.target.closest('.input_comment')) {} else {
                // alert('bad');
                document.getElementById('LIKES-CONTAINER').classList.remove("view_likes");
                document.getElementById('LIKES_BOX').classList.remove("view_likes_box");
                document.getElementById('LIKES_BOX').style.height = 0;
                document.body.classList.remove('no-scroll');
                const textarea = document.getElementById("input_comment");
                if (textarea) {
                    textarea.style.display = "none";
                }
                const observer = new MutationObserver((mutations) => {
                    mutations.forEach((mutation) => {
                        mutation.addedNodes.forEach((node) => {
                            if (node.id === 'input_comment') {
                                document.getElementById("input_comment").style.display = "none";
                                observer.disconnect(); // Stop observing once found
                            }
                        });
                    });
                });

                // Start observing the document for changes
                observer.observe(document.body, {
                    childList: true,
                    subtree: true
                });

                // textarea.style.display = "none";
                loadedScripts.forEach(scriptInfo => {
                    const existingScript = Array.from(document.scripts).find(
                        existing => (scriptInfo.src && existing.src === scriptInfo.src)
                    );

                    if (existingScript) {
                        console.log(`Removing existing script: ${existingScript.src}`);
                        existingScript.remove();
                    }

                    // Re-insert stored scripts back into the document
                    const script = document.createElement('script');

                    if (scriptInfo.src) {
                        // Re-activate by setting the src attribute
                        script.src = scriptInfo.src;
                    } else if (scriptInfo.content) {
                        // Wrap the script content in an IIFE to avoid global scope pollution
                        script.textContent = `(function(){ ${scriptInfo.content} })();`;
                    }

                    // Append the script to the body (or a specific container)
                    document.body.appendChild(script);
                });
                loadedScripts = [];
                link = "<?= ROOT ?>Profile"
                history.pushState(null, "", link);
            }
        }

        function fetchCommentsSecondFunction(event, these) {
            event.preventDefault();
            let link = these.getAttribute('href');
            const url = link.split("/");
            url.splice(0, 5)
            const new_url = url.join("/");
            link2 = window.document.location.href + `/${new_url}`;
            // history.pushState(null, "", link2);
            document.getElementById('loading-container').style.display = "flex";
            document.getElementById('LIKES-CONTAINER').classList.add("view_likes");
            document.getElementById('LIKES_BOX').classList.add("view_likes_box");
            document.getElementById('LIKES_BOX').style.height = "50dvh";
            document.getElementById('LIKES_BOX-BOTTOM').innerHTML = "";
            document.body.classList.add('no-scroll');
            if (loadedScripts.forEach) {
                loadedScripts.forEach(script => {
                    document.body.removeChild(script);
                });
            }
            loadedScripts = [];

            fetch(link)
                .then(response => response.text())
                .then(html => {
                    const messageContainer = document.getElementById('LIKES_BOX-BOTTOM');
                    messageContainer.innerHTML = html;
                    document.getElementById('loading-container').style.display = "none";
                    document.getElementById('comments').style.paddingBottom = "130px";
                    const scripts = document.getElementById('LIKES_BOX-BOTTOM').querySelectorAll('script');
                    scripts.forEach(script => {

                        const newScript = document.createElement('script');
                        newScript.textContent = script.textContent;
                        document.body.appendChild(newScript);
                        // loadedScripts.push(newScript);
                        loadedScripts.push({
                            src: script.src || null,
                            content: script.src ? null : script.textContent
                        });
                        script.remove();
                    });
                    // scripts.forEach(script => script.remove());
                    // document.getElementById('LIKES_BOX').scrollTop = scrollPosition;
                    const resizableDiv = document.getElementById('LIKES_BOX');
                    resizableDiv.addEventListener('mousedown', startResize);
                    resizableDiv.addEventListener('touchstart', startResize);


                    const textarea = document.getElementById('comment_value');

                    // Add event listeners for input and change events
                    textarea.addEventListener('input', () => autoResizeTextarea(textarea));
                    textarea.addEventListener('keydown', function(event) {
                        if (event.key == 'Backspace') {
                            if (textarea.scrollHeight < 65) {
                                textarea.style.height = '20px';
                            } // Additional actions, e.g., custom logic
                            if (textarea.value == "") {
                                textarea.style.height = '20px';
                            }
                        }
                    });

                    if (textarea.value == "") {
                        textarea.style.height = '20px';
                    }

                    document.getElementById('comment').addEventListener('click', (e) => {
                        e.preventDefault();
                        const url = window.document.location.href.split("/");
                        url.splice(0, 5)
                        if (!url[2]) {
                            // const comment = document.getElementById('comment_value');
                            const new_url = `${url[0]}/${url[1]}`;
                            // alert(new_url);
                            if (textarea.value.length > 2) {
                                const parentID = document.getElementById('parentID').value;
                                var formData = new FormData();
                                formData.append('comment', textarea.value);
                                formData.append('parent', parentID);
                                document.getElementById('loading-container').style.display = "flex";
                                fetch("<?= ROOT . 'send_comments_V2/' ?>" + new_url, {
                                    method: 'POST',
                                    body: formData
                                }).then(() => {
                                    document.getElementById('comment_value').value = '';
                                    document.getElementById('comment_value').style.height = '20px';
                                    const link = `<?= ROOT ?>comments/${new_url}`;
                                    fetchCommentsSecondEdition_for_secondFunction(link);

                                });
                            } else {
                                textarea.placeholder = "Can't send empty comment";
                                if (textarea.scrollHeight > 45) {
                                    textarea.style.height = 'auto';
                                    // Set the height to the scrollHeight, adding some padding if needed
                                    textarea.style.height = (textarea.scrollHeight - 18) + 'px';
                                }
                                setTimeout(() => {
                                    textarea.placeholder = "Add comment...";
                                    textarea.style.height = "20px";
                                }, 1000)
                            }
                        }
                    });
                });

            // setInterval(fetchComments(event, these), 100000)
        }

        function fetchCommentsSecondEdition_for_secondFunction(link) {
            fetch(link)
                .then(response => response.text())
                .then(html => {
                    const messageContainer = document.getElementById('LIKES_BOX-BOTTOM');
                    messageContainer.innerHTML = html;
                    document.getElementById('loading-container').style.display = "none";
                    document.getElementById('comments').style.paddingBottom = "130px";
                    const scripts = document.getElementById('LIKES_BOX-BOTTOM').querySelectorAll('script');
                    scripts.forEach(script => {
                        const newScript = document.createElement('script');
                        newScript.textContent = script.textContent;
                        document.body.appendChild(newScript);
                        loadedScripts.push({
                            src: script.src || null,
                            content: script.src ? null : script.textContent
                        });
                        script.remove();
                    });
                    const resizableDiv = document.getElementById('LIKES_BOX');
                    resizableDiv.addEventListener('mousedown', startResize);
                    resizableDiv.addEventListener('touchstart', startResize);

                });

        }

        function ajax_send_for_comments_comments_page(data, element) {
            var ajax = new XMLHttpRequest();

            ajax.addEventListener("readystatechange", function() {
                if (ajax.readyState == 4 && ajax.status == 200) {
                    response_for_comments_comments_page(ajax.responseText, element);
                }
            });
            data = JSON.stringify(data);

            ajax.open("post", "<?= ROOT ?>ajax.php", true);
            // alert(link);
            ajax.send(data, element);
        }

        function response_for_comments_comments_page(result, element) {
            // alert("fuck");
            if (result != "") {
                var obj = JSON.parse(result);
                if (typeof obj.action != "undefined") {
                    if (obj.action == "reply_comments_comments") {
                        const Holder = document.getElementById("comment_value");
                        Holder.placeholder = obj.place_holder;
                        // document.getElementById('comment_value').focus();

                        // alert(url);
                        document.getElementById('comment').addEventListener('click', (e) => {
                            e.preventDefault();
                            const url = window.document.location.href.split("/");
                            url.splice(0, 5);
                            if (url[2]) {
                                const comment = document.getElementById('comment_value');

                                const new_url = url.join("/");
                                // alert(url);
                                if (comment.value.length > 2) {
                                    const parentID = document.getElementById('parentID').value;
                                    var formData = new FormData();
                                    formData.append('comment', comment.value);
                                    formData.append('parent', parentID);
                                    fetch("<?= ROOT . 'send_comments_V2/' ?>" + new_url, {
                                        method: 'POST',
                                        body: formData
                                    }).then(() => {
                                        document.getElementById('comment_value').value = '';
                                        document.getElementById('comment_value').style.height = '20px';
                                        const link = `<?= ROOT ?>comments/${url[0]}/${url[1]}`;
                                        fetchCommentsSecondEdition_for_secondFunction(link);

                                    });
                                } else {
                                    Holder.placeholder = "Can't send empty reply";
                                    setTimeout(() => {
                                        Holder.placeholder = obj.place_holder;
                                    }, 1000)
                                }
                            }
                        });
                    }
                }
            }
        }

        function handleClick_to_rely_comments(fuck, these) {
            fuck.preventDefault(); // Prevent the default action (navigation)
            document.getElementById('comments').style.paddingBottom = "130px";
            // document.getElementById('comments').scrollTo = document.getElementById('comments').scrollHeight;
            var link = "<?= ROOT . "User_profile" ?>" + "/" + these.getAttribute("href");
            // history.pushState(null, "", link);
            var data = {};
            data.link = link;
            data.action = "Reply_comment_comment_page";
            ajax_send_for_comments_comments_page(data, fuck.target);
        }

        function autoResizeTextarea(textarea) {
            const text = textarea.value;
            const lines = text.split(/\r?\n/);
            const numberOfLines = lines.length;
            if (textarea.scrollHeight > 45) {
                textarea.style.height = 'auto';
                // Set the height to the scrollHeight, adding some padding if needed
                textarea.style.height = (textarea.scrollHeight - 18) + 'px';
            }

            if (textarea.value == "") {
                textarea.style.height = '20px';
            }
        }
        resize_large_vid();
    </script> -->
</body>

</html>