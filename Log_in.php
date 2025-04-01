<?php
include("autoload.php");
$login = new Login();
$first_visit = $login->check_new_user();
if($first_visit === true){
  header("Location: Welcome");
}
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
    height: 100dvh;
    height: -webkit-fill-available;
    width: 100dvw;
    justify-content: space-between;
    align-items: center;
    animation-name: back;
    flex-direction: column;
  }

  header {
    display: flex;
    background-color: white;
    padding: 1vh 5vw;
    height: auto;
    width: 90dvw;
  }

  header h1 {
    font-size: 25px;
    padding: none;
  }

  .logo img {
    width: 25vw;
  }

  form {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    /* height: 45vh; */
    /* background-color: aqua; */
    width: 90vw;
    gap: 5vh;
    padding: 5vh 0;
  }

  form .form_section {
    position: relative;
    height: 50%;
    display: flex;
    align-items: start;
  }

  form .form_section .error_div {
    width: 0;
    /* padding: 0 5%; */
    border-radius: 20px;
    border: 1px solid red;
    background-color: bisque;
    transition: height 0.25s ease-in, padding-top 0.25s ease-in;
    /* border: 0 1px 1px 1px; */
  }

  form .form_section .error_div p {
    color: red;
    padding: 0.1rem 1rem;
    font-size: 0.8rem;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
  }

  .input {
    padding: 0.5rem 5%;
    border-radius: 20px;
    flex-grow: 1;
    display: flex;
    justify-content: space-between;
    border: 1px solid black;
    color: black;
    font-size: 23px;
    position: absolute;
    width: 89%;
    top: 0;
    z-index: 10;
    /* opacity: 0.5; */
    background-color: white;
    /* left: 0; */
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
  }

  .input i {
    scale: 1.3;
    padding: 5px;
  }

  .input input {
    outline: none;
    color: black;
    font-size: 18px;
    font-family: Verdana, Geneva, Tahoma, sans-serif;
    padding-left: 5%;
    flex-grow: 1;
    /* width: auto; */
  }

  .input input::placeholder {
    font-size: 20px;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
  }

  .password {
    gap: 5%;
  }

  .password input {
    outline: none;
    color: black;
    font-size: 18px;
    font-family: Verdana, Geneva, Tahoma, sans-serif;
    padding-left: 0;
    width: 70%;
    /* width: min-content; */
  }

  #submit_form {
    height: 8vh;
    background-color: black;
    color: white;
    border-radius: 32px;
    font-size: 18px;
    margin-top: 20px;
  }

  .button {
    transition: all 0.2s;
    position: relative;
  }

  .button_loader .button_text {
    visibility: hidden;
    opacity: 0;
  }

  .button_loader::after {
    content: "";
    position: absolute;
    height: 5vh;
    width: 5vh;
    left: 0;
    right: 0;
    bottom: 0;
    top: 0;
    border-radius: 50%;
    margin: auto;
    border: 4px solid white;
    border-left-width: 0px;
    border-bottom-width: 0px;
    border-right-width: 0px;
    border-top-color: white;
    animation: button 1s ease infinite;
  }

  @keyframes button {
    from {
      transform: rotate(0turn);
    }

    to {
      transform: rotate(1turn);
    }
  }

  footer {
    /* background-color: bisque; */
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-evenly;
    padding: 1rem 0;
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

  .error {
    height: 100dvh;
    width: 100dvw;
    background-color: rgba(0, 0, 0, 0.4);
    position: fixed;
    z-index: 20;
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
    display: none;
    justify-content: center;
    align-items: center;
    /* display: none; */
  }

  .error .error_box {
    width: 90dvw;
    background-color: white;
    height: auto;
    border-radius: 25px;
    padding: 1rem 0;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    min-height: 20dvh;
  }

  .error .error_box i {
    /* float: right; */
    position: absolute;
    top: 1rem;
    right: 0.5rem;
    margin-right: 1rem;
    scale: 2;
    /* background-color: bisque; */
  }

  .error .error_box h3 {
    padding: 0 1rem;
  }

  .page_loader {
    height: 100dvh;
    width: 100dvw;
    background-color: white;
    opacity: 0.3;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 10;
    display: none;
  }

  #sign_in_btn {
    background-color: black;
    color: white;
    border-radius: 32px;
    font-size: 20px;
    padding: 0.5rem;
    display: flex;
    justify-content: center;
    position: relative;
  }

  .button {
    transition: all 0.2s;
    position: relative;
    background-color: aqua;
  }

  #sign_in_btn .loader {
    /* visibility: hidden; */
    /* background-color: aqua; */
    /* opacity: 0; */
    /* height: 100%; */
    background-image: conic-gradient(black, white);
    /* display: block; */
    padding: 1rem;
    border-radius: 50%;
    position: relative;
    /* z-index: 2; */
    scale: 1;
    display: none;
    animation-name: loader;
    animation-duration: 1s;
    animation-timing-function: linear;
    animation-iteration-count: infinite;
    transform: rotateZ(0deg);
  }

  #sign_in_btn .loader::after {
    content: "";
    background-color: black;
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    margin: auto;
    border-radius: 50%;
    scale: 0.7;
    display: var(--after-display, none);

  }

  @keyframes loader {
    from {
      transform: rotateZ(0deg);
    }

    to {
      transform: rotateZ(360deg);
    }
  }
</style>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Log-in | Entreefox</title>
  <link rel="shortcut icon" href="<?= ROOT ?>Images/LOGO.PNG" type="image/x-icon">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

  <!-- <link rel="stylesheet" href="vendor_login_stylesheet.css" /> -->
</head>

<body>
  <section class="page_loader" id="page_loader">
  </section>
  <section class="error" id="error">
    <div class="error_box">
      <i class="fa-solid fa-xmark" id="cancel_error_alert"></i>
      <h3 id="error_content"></h3>
    </div>
  </section>
  <header>
    <h1>Sign-in to your <br> Account</h1>
  </header>
  <div class="logo">
    <img src="<?= ROOT ?>Images/LOGO.PNG" alt="" />
  </div>

  <form method="post" id="sign_up">
    <section class="form_section">
      <div class="error_div" id="user_name_error">
        <p></p>
      </div>
      <div class="input text">
        <i class="fa-solid fa-user"></i>
        <input autocomplete="nickname type="text" placeholder="User name" name="user_name" />
      </div>
    </section>
    <section class="form_section">
      <div class="error_div" id="password_error">
        <p></p>
      </div>
      <div class="input password">
        <i class="fa-solid fa-lock" id="padlock"></i>
        <input autocomplete="current-password" type="password" placeholder="Password" id="password" name="password" />
        <i class="fa-solid fa-eye" id="eye"></i>
      </div>
    </section>
    <button type="submit" class="button" id="sign_in_btn">
      <span class="button_text">Sign in</span>
      <div class="loader"></div>
    </button>
  </form>
  <footer>
    <div class="forgot">
      <p><a href="password_change">Forgot Password?</a></p>
    </div>
    <div class="signup">
      <p>
        Don't have an account? <a href="<?=ROOT?>Sign_up">Signup</a>
      </p>
    </div>
  </footer>
  <script>
    // const form_section = document.querySelector('.form_section');
    const input = document.querySelector('.input');
    document.querySelectorAll('.error_div').forEach(error => {
      error.style.height = `${input.clientHeight - 1}px`;
      error.style.width = `${((input.clientWidth /  window.innerWidth) * 100)}dvw`;
    })
    let padlock = document.getElementById("padlock");
    let eye = document.getElementById("eye");
    let password = document.getElementById("password");
    eye.addEventListener("click", () => {
      // alert("good");
      var padlock = document.getElementById("padlock");
      if (password.type == "password") {
        password.type = "text";
        eye.classList.replace('fa-eye', 'fa-eye-slash');
        padlock.classList.replace('fa-lock', 'fa-unlock');
      } else if (password.type != "password") {
        password.type = "password";
        eye.classList.replace('fa-eye-slash', 'fa-eye');
        padlock.classList.replace('fa-unlock', 'fa-lock');
      }
    });
    document.getElementById("cancel_error_alert").addEventListener('click', function() {
      document.getElementById('error').style.display = "none";
      remove_load_content();
      // document.getElementById('error').style.display = "none";
    });

    function remove_load_content() {
      const button = document.querySelector('.button');
      const page_loader = document.getElementById('page_loader');
      page_loader.style.display = "none"
      button.style.zIndex = 1;
      button.querySelector('.button_text').style.display = "block";
      button.querySelector('.loader').style.display = 'none';
      button.querySelector('.loader').style.setProperty("--after-display", "none");

    }
    document.getElementById('sign_up').addEventListener('submit', function(e) {
      e.preventDefault();
      evaluate(e);
      // login(e);
      // sigh_in(e);
    });
    async function login(form_object) {
      load_content();
      const formData = new FormData();
      for (const key in form_object) {
        if (form_object.hasOwnProperty(key)) {
          formData.append(key, form_object[key]);
        }
      }
      try {
        const response = await fetch("<?= ROOT ?>Classes/log_in.php", {
          method: "POST",
          body: formData, // Send form data
        });

        // Handle the response
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }

        const result = await response.text();
        if (!result) {
          remove_load_content();
        } else if (result == "Valid") {
          const url = "<?= ROOT ?>Home";
          window.location.replace(url);
        } else {
          document.getElementById('error').style.display = "flex";
          document.getElementById('error_content').innerText = result;
        }
      } catch (error) {
        document.getElementById('error').style.display = "flex";
        document.getElementById('error_content').innerText = error;
      }
    }

    function load_content() {
      const button = document.querySelector('.button');
      const page_loader = document.getElementById('page_loader');
      page_loader.style.display = "block"
      button.style.zIndex = -1;
      button.querySelector('.button_text').style.display = "none";
      button.querySelector('.loader').style.display = 'block';
      button.querySelector('.loader').style.setProperty("--after-display", "block");
    }

    function evaluate(event) {
      const evaluated_form_data = {};
      load_content();
      const inputs = document.querySelectorAll('.input input');
      const input_div = document.querySelector('.input');
      const user_name_error = document.getElementById('user_name_error');
      const password_error = document.getElementById('password_error');
      inputs.forEach(input => {
        switch (input.name) {
          case "user_name":
          case "password":
            if (input.value === "" && input.name === "user_name") {
              user_name_error.style.paddingTop = `${(input_div.clientHeight)}px`;
              user_name_error.querySelector('p').innerText = `Invalid ${input.getAttribute('placeholder')}`;
              user_name_error.style.height = `${user_name_error.querySelector('p').clientHeight}px`;
            } else if (input.value !== "" && input.name === "user_name") {
              evaluated_form_data[input.name] = input.value;
              user_name_error.style.paddingTop = '0px';
              user_name_error.style.height = `${(input_div.clientHeight - 1)}px`;
            } else if (input.value === "" && input.name === "password") {
              password_error.style.paddingTop = `${(input_div.clientHeight)}px`;
              password_error.style.height = `${user_name_error.querySelector('p').clientHeight}px`;
              password_error.querySelector('p').innerText = `Invalid ${input.getAttribute('placeholder')}`;
            } else if (input.value !== "" && input.name === "password") {
              evaluated_form_data[input.name] = input.value;
              password_error.style.paddingTop = '0px';
              password_error.style.height = `${(input_div.clientHeight - 1)}px`;
            }
            break;

          default:
            break;
        }
        if (Object.keys(evaluated_form_data).length == 2) {
          login(evaluated_form_data);
        } else {
          remove_load_content();
        }
      })
      // console.log(event.target);

    }
  </script>
</body>

</html>