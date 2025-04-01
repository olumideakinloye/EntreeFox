<?php
include("Classes/connect.php");
include("Classes/login_&_signup_class.php");
$user_exist = "";
// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     $email_address = $_POST['email'];
//     $OTP = new Signup();
//     $user_exist = $OTP->check_user_email($email_address);
//     // echo $user_exist;
//     if (is_array($user_exist)) {
//         header("Location: " . ROOT . "input_OTP");
//         // $OTP_code = $OTP->create_OTP();
//         // echo $OTP_code;
//     }
// }

?>


<style>
    * {
        border: 0;
        margin: 0;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
    }

    html {
        height: -webkit-fill-available;
    }

    body {
        display: flex;
        height: 100vh;
        height: -webkit-fill-available;
        width: 100vw;
        justify-content: space-evenly;
        align-items: center;
        animation-name: back;
        flex-direction: column;
    }

    .error_shell {
        background-color: #00000080;
        display: flex;
        justify-content: center;
        height: 100vh;
        width: 100vw;
        position: fixed;
        display: flex;
        justify-content: center;
        align-items: center;
        /* top: 0; */
    }


    .error {
        background-color: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px;
        border-radius: 15px;
        color: red;
        font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        font-size: 17px;
        border: 1px solid red;
    }

    .error p {
        color: red;
    }

    #error_result {
        padding-bottom: 15px;
    }

    .error button {
        width: 40vw;
        background-color: red;
        color: white;
        height: 25px;
        border-radius: 10px;
        font-size: 17px;
    }

    header {
        display: flex;
        width: 100vw;
        /* justify-content: center; */
        /* height: 18vh; */
        /* position: relative; */
        /* padding: 0 20px 0 20px; */
        /* background-color: aqua; */
    }

    header h1 {
        /* background-color: bisque; */
        padding: 0 20px 0 20px;
        /* width: 80vw; */
    }

    .logo img {
        height: 100px;
        /* padding-top: 67px; */
        /* background-color: aquamarine; */
    }

    form {
        display: flex;
        flex-direction: column;
        justify-content: space-evenly;
        height: 50vh;
        width: 90vw;
        /* background-color: blue; */
    }

    /* form p {
        display: none;
    } */

    .input {
        /* height: 10vh; */
        padding: 0.5rem 0;
        width: 90vw;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: space-evenly;
        border: 1px solid black;
        color: black;
        font-size: 23px;
        font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    }

    .text input {
        height: 30px;
        width: 70vw;
        outline: none;
        color: black;
        font-size: 18px;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
    }

    .input input::placeholder {
        font-size: 20px;
        font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    }

    .input img {
        height: 3vh;
    }

    .password input {
        height: 30px;
        width: 65vw;
        outline: none;
        color: black;
        font-size: 18px;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
    }

    #eye {
        opacity: 0.5;
        height: 4vh;
    }

    #eye2 {
        opacity: 0.5;
        height: 4vh;
    }

    #submit_form {
        height: 8vh;
        background-color: black;
        color: white;
        border-radius: 32px;
        font-size: 18px;
        margin-top: 20px;
    }

    .forgot a {
        color: rgba(0, 0, 0, 0.438);
        font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        font-size: large;
        margin-top: 20px;
        text-decoration: none;
        /* background-color: blueviolet; */
    }

    .signup p {
        color: rgba(0, 0, 0, 0.438);
        font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        font-size: large;
        padding-bottom: 10px;
        /* background-color: brown; */
    }

    .signup a {
        opacity: 1;
        color: rgb(29, 29, 73);
        font-size: 23px;
        text-decoration: none;
        font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;

    }

    .alert {
        height: 100vh;
        width: 100vw;
        -webkit-backdrop-filter: blur(10px);
        backdrop-filter: blur(10px);
        background-color: rgba(0, 0, 0, 0.308);
        display: none;
        justify-content: center;
        align-items: center;
        position: fixed;
        top: 0;
        left: 0;
    }

    .alert-box {
        width: 80vw;
        padding: 15px;
        background-color: white;
        border-radius: 20px;
    }

    .alert-box h2 {
        text-align: center;
        font-size: 17px;
    }
    .input_icon{
        font-size: 2rem;
    }
</style>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Change Password | Entreefox</title>
    <link rel="shortcut icon" href="Images/LOGO.PNG" type="image/x-icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="vendor_login_stylesheet.css" /> -->
    <script>
        function error() {
            const error_shell = document.getElementById("error_shell");
            error_shell.style.display = "none";
        }
    </script>
</head>

<body>
    <?php
    if ($user_exist == "no_find") {
        echo "<div class='error_shell' id='error_shell'>";
        echo " <div class='error'>";
        echo "  <P id='error_result'><b>Can't find user.</b></P>";
        echo "  <button id='error' onclick='error()'><b>close</b></button>";
        echo " </div>";
        echo "</div>";
    }
    ?>
    <header>
        <h1>Find your Account</h1>
    </header>
    <div class="logo">
        <img src="Images/LOGO.PNG" alt="" />
    </div>

    <form>
        <div class="content1" id="content1" style="display: block;">
            <h4 id="enter_email">Enter your registered email address</h4>
            <div class="input text" style="margin-top: 20px;">
            <i class="fa-solid fa-user input_icon"></i>
            <input type="email" required autofocus autocomplete="language" value="" placeholder="Email Address" name="email" id="email" />
            </div>
        </div>
        <div class="content2" id="content2" style="display: none;">
            <h4 id="otp_code" style="text-align: center;"></h4>
            <div class="input text" style="margin-top: 20px;">
                <img src="user.png" alt="" />
                <input type="number" required autofocus autocomplete="language" value="" placeholder="Enter OTP" name="OTP" id="OTPnumber" />
            </div>
        </div>
        <div class="content3" id="content3" style="display: none;">
            <h4 id="confirmation" style="text-align: center;"></h4>
            <div class="input password" id="passwordBox" style="margin-top: 20px;">
                <img src="padlock.png" alt="" id="padlock" />
                <input type="password" required autofocus autocomplete="language" value="" placeholder="Password" id="password" name="password" />
                <img src="3917126.png" alt="" id="eye" />
            </div>
            <div class="input password" id="passwordBox2" style="margin-top: 20px;">
                <img src="padlock.png" alt="" id="padlock2" />
                <input type="password" required autofocus autocomplete="language" value="" placeholder="Confirm password" id="password2" name="Confirm_password" />
                <img src="3917126.png" alt="" id="eye2" />
            </div>
        </div>
        <div class="alert" id="alert">
            <div class="alert-box">
                <h2>
                    Your password has been successfully change, all you have to do now is to log-in with the new password.
                </h2>
            </div>
        </div>
        <input type="button" value="Sumbit" id="submit_form" />
    </form>
    <script>
        let OTP;
        let email;
        document.getElementById('submit_form').style.zIndex = -1;
        document.getElementById('submit_form').style.opacity = 0.5;


        document.getElementById('email').addEventListener('input', () => {
            if (document.getElementById('email').value.length < 15) {
                document.getElementById('submit_form').style.zIndex = -1;
                document.getElementById('submit_form').style.opacity = 0.5;
            } else {
                document.getElementById('submit_form').style.zIndex = 1;
                document.getElementById('submit_form').style.opacity = 1;
            }

        })

        document.getElementById('submit_form').onclick = function() {
            if (document.getElementById('content1').style.display == "block" && document.getElementById('content2').style.display == "none" && document.getElementById('content3').style.display == "none") {
                email = document.getElementById('email').value;
                var formData = new FormData();
                formData.append('email', email);

                fetch(`<?= ROOT ?>`, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(html => {
                        // alert(html);
                        var obj = JSON.parse(html);
                        if (obj.confirmation === "good") {
                            document.getElementById('content1').style.display = "none";
                            document.getElementById('content2').style.display = "block";
                            OTP = obj.otp;
                            document.getElementById('otp_code').innerHTML = `Welcome back ${obj.name}, your OTP code is ${OTP}`;
                            document.getElementById('submit_form').style.zIndex = -1;
                            document.getElementById('submit_form').style.opacity = 0.5;
                        } else if (obj.confirmation === "no_find") {
                            document.getElementById('enter_email').innerHTML = "Could not find any user with this email";
                        }
                    });
            } else if (document.getElementById('content1').style.display === "none" && document.getElementById('content2').style.display === "block" && document.getElementById('content3').style.display === "none") {
                const OTP_CODE = document.getElementById('OTPnumber').value;
                if (OTP_CODE == OTP) {
                    document.getElementById('content1').style.display = "none";
                    document.getElementById('content2').style.display = "none";
                    document.getElementById('content3').style.display = "block";
                    document.getElementById('submit_form').style.zIndex = -1;
                    document.getElementById('submit_form').style.opacity = 0.5;

                } else {
                    alert('Invalid code providid');
                }
            } else if (document.getElementById('content1').style.display === "none" && document.getElementById('content2').style.display === "none") {
                const password_value = document.getElementById("password").value;
                var formData = new FormData();
                formData.append('password', password_value);

                fetch(`<?= ROOT ?>input_OTP/${email}`, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(html => {
                        // alert(html);
                        var obj = JSON.parse(html);
                        // alert(obj);

                        if (obj.confirmation === "success") {
                            document.getElementById('alert').style.display = "flex";
                            setTimeout(() => {
                                window.location.href = "<?= ROOT ?>Vendor_log-in";
                            }, 2000)
                        }
                    });
            }
        }
        document.getElementById('OTPnumber').addEventListener('input', () => {
            if (document.getElementById('OTPnumber').value.length >= 6) {
                document.getElementById('submit_form').style.zIndex = 1;
                document.getElementById('submit_form').style.opacity = 1;
            } else {
                document.getElementById('submit_form').style.zIndex = -1;
                document.getElementById('submit_form').style.opacity = 0.5;
            }
        });


        const eye = document.getElementById("eye");
        const eye2 = document.getElementById("eye2");
        const password = document.getElementById("password");
        const password2 = document.getElementById("password2");

        password2.addEventListener('input', function() {
            if (password2.value != password.value) {
                document.getElementById('confirmation').innerHTML = "Confirm password must be the same as password";
                document.getElementById('confirmation').style.color = "red";
                document.getElementById('submit_form').style.zIndex = -1;
                document.getElementById('submit_form').style.opacity = 0.5;
            } else if (password2.value.length <= 5) {
                document.getElementById('confirmation').innerHTML = "Password must be more than 5 characters";
                document.getElementById('confirmation').style.color = "red";
                document.getElementById('submit_form').style.zIndex = -1;
                document.getElementById('submit_form').style.opacity = 0.5;
            } else {
                document.getElementById('confirmation').innerHTML = "Good";
                document.getElementById('confirmation').style.color = "green";
                document.getElementById('submit_form').style.zIndex = 1;
                document.getElementById('submit_form').style.opacity = 1;
            }
        });
        password.addEventListener('input', function() {
            if (password.value.length <= 5) {
                document.getElementById('confirmation').innerHTML = "Password must be more than 5 characters";
                document.getElementById('confirmation').style.color = "red";
                document.getElementById('submit_form').style.zIndex = -1;
                document.getElementById('submit_form').style.opacity = 0.5;
            }
        });
        eye.addEventListener("click", () => {
            var padlock = document.getElementById("padlock");
            if (password.type == "password") {
                password.type = "text";
                eye.src = "eye.png";
                padlock.src = "open-padlock.png";
            } else if (password.type != "password") {
                password.type = "password";
                eye.src = "3917126.png";
                padlock.src = "padlock.png";
            }
        });
        eye2.addEventListener("click", () => {
            padlock2 = document.getElementById("padlock2");
            if (password2.type == "password") {
                password2.type = "text";
                eye2.src = "eye.png";
                padlock2.src = "open-padlock.png";
            } else if (password2.type != "password") {
                password2.type = "password";
                eye2.src = "3917126.png";
                padlock2.src = "padlock.png";
            }
        });
    </script>
</body>

</html>