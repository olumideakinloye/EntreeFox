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
            $image = "Images/profile.png";
            if (!empty($user_data['profile_image'])) {
                if (file_exists($user_data['profile_image'])) {
                    $image = $user_data['profile_image'];
                }
            }
            $cover = "Images/BG1.jpg";
            if (!empty($user_data['cover_image'])) {
                if (file_exists($user_data['cover_image'])) {
                    $cover = $user_data['cover_image'];
                }
            }
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
    <title>Edit-Profile | Entreefox</title>
    <link rel="shortcut icon" href="<?= ROOT ?>Images/LOGO.PNG" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo ROOT ?>CSS/Edit_profile_stylesheet.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <header>
        <a href="<?= ROOT ?>Profile"><i class="fa-solid fa-arrow-left float"></i></a>

    </header>
    <div class="container">
        <div class="error_container" id="error">
            <div class="error_div">
                <p></p>
            </div>
        </div>
        <form id="edit_form" enctype="multipart/form-data">

            <div class="pictures">
                <div class="photo">
                    <div class="img" style="background-image: url(<?= ROOT . $image ?>);">
                        <input type="file" name="photo" accept=".jpg, .jpeg,.png">
                    </div>
                    <p id="picture"></p>
                </div>
                <div class="background">
                    <div class="img" style="background-image: url(<?= ROOT . $cover ?>);">
                        <input type="file" name="background[]" accept=".jpg, .jpeg, .png" multiple>
                    </div>
                    <p id="background"></p>
                </div>
            </div>
            <div class="text">
                <div class="username">
                    <h2>User name</h2>
                    <input type="text" name="username" id="username" value="<?= $user_data['user_name'] ?>">
                </div>
                <div class="about">
                    <h2>About</h2>
                    <textarea name="about" id="about" maxlength="150"><?= $user_data['About'] ?></textarea>
                </div>

            </div>
            <div class="btn" id="btn">
                <button type="submit">
                    <span>Update</span>
                    <span class="loader"></span>
                </button>
                <div class="cover"></div>
            </div>
        </form>

    </div>
    <script>
        const fileInputs = document.querySelectorAll('input[type="file"]');
        let is_changed = false;
        let loop = true;
        let submitted = false;
        const textarea = document.getElementById('about');
        const username = document.querySelector('input[type="text"]');
        const allowedTypes = ["image/png", "image/jpeg", "image/jpg"];
        const maxSize = 3 * 1024 * 1024; // 3MB in bytes
        for (const input of fileInputs) {
            // alert("good");
            if (!loop) break;
            input.addEventListener('change', function (event) {
                const files = event.target.files;
                for (const file of files) {
                    console.log(file.name);
                    // const file = event.target.files[0];
                    if (file) {
                        if (allowedTypes.includes(file.type) && file.size < maxSize) {
                            is_changed = true;
                            loop = true;
                            if (input.name === "photo") {
                                document.getElementById('picture').innerText = "";
                                document.querySelector('input[name="background"]').style.display = "block";
                            } else if (input.name === "background") {
                                document.getElementById('background').innerText = "";
                                document.querySelector('input[name="photo"]').style.display = "block";
                            }
                            const reader = new FileReader();
                            reader.readAsDataURL(file);
    
                            reader.onload = function() {
                                input.closest(".img").style.backgroundImage = `url('${reader.result}')`;
                            };
                            username.readOnly = false;
                            textarea.readOnly = false;
                        } else if (allowedTypes.includes(file.type) && file.size > maxSize) {
                            is_changed = false;
                            loop = false;
                            if (input.name === "photo") {
                                document.getElementById('picture').innerText = "File size is too large. Expected file size should be less than 3MB";
                                document.querySelector('input[name="background"]').style.display = "none";
                            } else if (input.name === "background") {
                                document.getElementById('background').innerText = "File size is too large. Expected file size should be less than 3MB";
                                document.querySelector('input[name="photo"]').style.display = "none";
                                break;
                            }
                            username.readOnly = true;
                            textarea.readOnly = true;
    
                        } else if (!allowedTypes.includes(file.type) && file.size < maxSize) {
                            is_changed = false;
                            loop = false;
                            if (input.name === "photo") {
                                document.getElementById('picture').innerText = "Invalid image file. Expected JPG/PNG file.";
                                document.querySelector('input[name="background"]').style.display = "none";
                            } else if (input.name === "background") {
                                document.getElementById('background').innerText = "Invalid image file. Expected JPG/PNG file.";
                                document.querySelector('input[name="photo"]').style.display = "none";
                                break;
                            }
                            username.readOnly = true;
                            textarea.readOnly = true;
                        } else {
                            is_changed = false;
                            loop = false;
                            if (input.name === "photo") {
                                document.getElementById('picture').innerText = "Invalid image file.";
                                document.querySelector('input[name="background"]').style.display = "none";
                            } else if (input.name === "background") {
                                document.getElementById('background').innerText = "Invalid image file.";
                                document.querySelector('input[name="photo"]').style.display = "none";
                                break;
                            }
                            username.readOnly = true;
                            textarea.readOnly = true;
                        }
                    }
                };

            });

        };
        const about = textarea.value;
        username.addEventListener('input', function() {
            if (username.value != "<?= $user_data['user_name'] ?>" && username.value != "") {
                is_changed = true;
            } else {
                is_changed = false;
            }
        })

        textarea.addEventListener('input', () => {
            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight + 'px';
            if (textarea.value != about && textarea.value != "") {
                is_changed = true;
            } else {
                is_changed = false;
            }
        });
        // Trigger resize on page load for pre-filled content
        textarea.dispatchEvent(new Event('input'));
        document.getElementById('edit_form').addEventListener("submit", () => {
            event.preventDefault();
            const btn = document.getElementById('btn');
            const loader = btn.querySelector(".loader");
            const cover = btn.querySelector(".cover");
            loader.style.display = "flex";
            cover.style.display = "flex";
            btn.querySelector('button').style.opacity = 0.5;
            if (is_changed === true) {
                const form = event.target;
                const formData = new FormData(form);
                formData.append(textarea.name, textarea.value);
                submitted = true;
                Edit(formData);
            }
            // alert("Edit");
        });
        setInterval(() => {
            if (submitted === false) {
                if (is_changed === true) {
                    if (username.value != "" && textarea.value != "") {
                        const btn = document.getElementById('btn');
                        btn.querySelector('button').style.opacity = 1;
                        const cover = btn.querySelector(".cover");
                        cover.style.display = "none";
                    } else {
                        const btn = document.getElementById('btn');
                        btn.querySelector('button').style.opacity = 0.5;
                        const cover = btn.querySelector(".cover");
                        cover.style.display = "flex";
                    }
                } else {
                    const btn = document.getElementById('btn');
                    btn.querySelector('button').style.opacity = 0.5;
                    const cover = btn.querySelector(".cover");
                    cover.style.display = "flex";
                }
            }
        }, 1000);

        function close_error() {
            setTimeout(() => {
                document.getElementById('error').style.display = "none";
            }, 3000);
        }
        async function Edit(form) {

            try {
                // Send form data to the backend using POST
                const response = await fetch("<?= ROOT ?>Server_side/Edit_profile_to_class.php", {
                    method: 'POST',
                    body: form,
                });
                // Handle the response
                if (!response.ok) {
                    const btn = document.getElementById('btn');
                    btn.querySelector('button').style.opacity = 1;
                    const cover = btn.querySelector(".cover");
                    cover.style.display = "none";
                    const loader = btn.querySelector(".loader");
                    loader.style.display = "none";
                    throw new Error("Network response was not ok");
                }


                const result = await response.text(); // Assuming backend sends JSON response
                if (!result) {
                    const btn = document.getElementById('btn');
                    btn.querySelector('button').style.opacity = 1;
                    const cover = btn.querySelector(".cover");
                    cover.style.display = "none";
                    const loader = btn.querySelector(".loader");
                    loader.style.display = "none";
                    document.querySelectorAll('input[type="file"]').forEach(input => {
                        input.value = "";
                    });
                    submitted = false;
                    is_changed = false;
                } else if (result === "Successful") {
                    window.location.href = "<?= ROOT ?>Profile";
                } else if (result === "User name not available.") {
                    username.value = "<?= $user_data['user_name'] ?>";
                    const btn = document.getElementById('btn');
                    btn.querySelector('button').style.opacity = 1;
                    const cover = btn.querySelector(".cover");
                    cover.style.display = "none";
                    const loader = btn.querySelector(".loader");
                    loader.style.display = "none";
                    const error = document.getElementById('error');
                    error.style.display = "flex";
                    error.querySelector(".error_div p").innerText = result;
                    submitted = false;
                    is_changed = false;
                    close_error();
                } else {
                    // document.querySelectorAll('input[type="text"]').forEach(input => {
                    //     input.value = "";
                    // });
                    const btn = document.getElementById('btn');
                    btn.querySelector('button').style.opacity = 1;
                    const cover = btn.querySelector(".cover");
                    cover.style.display = "none";
                    const loader = btn.querySelector(".loader");
                    loader.style.display = "none";
                    const error = document.getElementById('error');
                    error.style.display = "flex";
                    error.querySelector(".error_div p").innerText = result;
                    submitted = false;
                    is_changed = false;
                    close_error();
                }
            } catch (error) {
                const error_div = document.getElementById('error');
                error_div.style.display = "flex";
                error_div.querySelector(".error_div p").innerText = error;
                submitted = false;
                is_changed = false;
                close_error();
            }
        };
    </script>
</body>

</html>