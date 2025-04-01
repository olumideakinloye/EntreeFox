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
    height: 100vh;
    height: -webkit-fill-available;
    width: 100vw;
    justify-content: space-between;
    align-items: center;
    animation-name: back;
    flex-direction: column;
  }

  header {
    display: flex;
    height: 15vh;
    background-color: white;
    padding: 1vh 5vw;
  }

  header img {
    height: 100%;
    /* align-self: end */
    /* justify-self: end; */
  }

  header h1 {
    font-size: 25px;
    padding: none;
  }

  form {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 70vh;
    width: 90vw;
    position: relative;
    /* background-color: bisque; */
  }

  section {
    position: relative;
    padding-bottom: 5%;
    display: flex;
    flex-direction: column;
    transition: height 0.25s ease-in;
    height: auto;
  }

  .input {
    border-radius: 20px;
    display: flex;
    align-items: center;
    border: 1px solid black;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    padding: 0.5rem 5vw;
    background-color: white;
    position: relative;
    position: absolute;
    width: 79.5dvw;
  }

  .text input {
    height: fit-content;
    width: 70vw;
    outline: none;
    font-family: Verdana, Geneva, Tahoma, sans-serif;
  }

  .text select {
    height: fit-content;
    width: 70vw;
    outline: none;
    font-family: Verdana, Geneva, Tahoma, sans-serif;
  }

  .input i {
    scale: 1.5;
    padding: 5px;
    /* background-color: aqua; */
  }

  .input input {
    font-size: 1.2rem;
    background-color: white;
    padding-left: 7px;
    padding-right: 0;
    width: 100%;
    margin-left: 5px;
    /* border-left: 2px solid black; */
    /* background-color: bisque; */
  }

  .input select {
    font-size: 1.2rem;
    background-color: white;
    border-left: 2px solid black;
    /* background-color: bisque; */
  }

  .input input:-webkit-autofill {
    background-color: white !important;
    color: black !important;
    transition: background-color 5000s ease-in-out 0s;
  }

  .input input:-moz-autofill {
    background-color: white !important;
    color: black !important;
  }

  .input input:-ms-autofill {
    background-color: white !important;
    color: black !important;
  }

  section span {
    border: 1px solid red;
    border-radius: 20px;
    overflow: hidden;
    display: flex;
    transition: height 0.25s ease-in, padding-top 0.25s ease-in;
    background-color: rgba(0, 0, 0, 0.015);
  }

  section span p {
    color: red;
    padding: 0.1rem 1rem;
    font-size: 0.8rem;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
  }

  .small section {
    width: 50%;
    position: relative;
    display: flex;
    flex-direction: column;
    transition: height 0.25s ease;
    height: auto;
  }

  .small section span {
    border: 1px solid red;
    border-radius: 20px;
    overflow: hidden;
    display: flex;
    transition: height 0.25s ease-in, padding-top 0.25s ease-in;
    background-color: rgba(0, 0, 0, 0.015);
  }

  .small section:nth-last-child(1) .input {
    right: 0;
  }

  .small {
    display: flex;
    flex-direction: row;
    width: 90vw;
    position: relative;
  }

  .small .input {
    outline: none;
    width: 34.5vw;
  }

  .small input {
    width: 100%;
    outline: none;
  }

  .small select {
    outline: none;
    width: 100%;
    /* background-color: aqua; */
    padding-left: 7px;
    padding-right: 0;
    font-size: 1.2rem;
    margin-left: 5px;
    color: black;
  }

  /* .small section .input select option[selected] {
    font-size: 1.2rem;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    opacity: 0.5;
    color: brown;
  } */

  .input input::placeholder {
    font-size: 1.2rem;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
  }

  .password input {
    /* width: 73%; */
    outline: none;
    padding-right: 1rem;
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

  .sign_in {
    display: flex;
    justify-content: center;
  }

  p {
    color: rgba(0, 0, 0, 0.438);

    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    font-size: large;
    padding-bottom: 10px;
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
    padding: 5%;
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

  p a {
    opacity: 1;
    color: rgb(29, 29, 73);
    font-size: 20px;
    text-decoration: none;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
  }
</style>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign-up | EntreeFox</title>
  <link rel="shortcut icon" href="<?= ROOT ?>Images/LOGO.PNG" type="image/x-icon">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <!-- <link rel="stylesheet" href="vendor_sign-up_stylesheet.css" /> -->
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
    <h1>Create your Account</h1>
    <!-- <p><?= ROOT ?>Log-in</p> -->
    <img src="<?= ROOT ?>Images/LOGO.PNG" alt="" />
  </header>
  <form id="sign_up" method="post" enctype="multipart/form-data">
    <section>
      <div class="input text" id="name">

        <i class="fa-solid fa-user"></i>
        <input autocomplete="cc-family-name type=" text" placeholder="First name" id="First_Name" name="First_name" />
      </div>
      <span id="name_error">
        <p></p>
      </span>
    </section>
    <section>
      <div class="input text" id="name">
        <i class="fa-solid fa-user"></i>
        <input autocomplete="cc-given-name" type="text" placeholder="Last name" id="Last_Name" name="Last_name" />
      </div>
      <span id="last_name_error">
        <p></p>
      </span>
    </section>
    <div class="small">
      <section>

        <div class="input">
          <i class="fa-solid fa-user"></i>
          <input autocomplete="nickname" type="text" placeholder="User name" id="User_Name" name="user_name" />
        </div>
        <span id="nickname_error">
          <p></p>
        </span>
      </section>
      <section id="gender_section">

        <div class="input">
          <i class="fa-solid fa-user"></i>
          <select id="gender" name="gender">
            <option value="" selected disabled>Gender</option> <!-- Placeholder -->
            <option value="Male">Male</option> <!-- Default selected -->
            <option value="Female">Female</option>
          </select>
        </div>
        <span id="gender_error">
          <p></p>
        </span>
      </section>
    </div>
    <section id="email_section">

      <div class="input text" id="emailBox">
        <i class="fa-solid fa-envelope"></i>
        <input autocomplete="email" type="email" placeholder="Email" id="email" name="email" />
      </div>
      <span id="email_error">
        <p></p>
      </span>
    </section>
    <section id="password_section">

      <div class="input password" id="passwordBox">
        <i class="fa-solid fa-lock" id="padlock"></i>
        <input autocomplete="new-password" type="password" placeholder="Password" id="password" name="password" />
        <i class="fa-solid fa-eye" id="eye"></i>
      </div>
      <span id="password_error">
        <p></p>
      </span>
    </section>
    <section id="password2_section">

      <div class="input password" id="passwordBox2">
        <i class="fa-solid fa-lock" id="padlock2"></i>
        <input autocomplete="new-password" type="password" placeholder="Confirm password" id="password2" name="Confirm_password" />
        <i class="fa-solid fa-eye" id="eye2"></i>
      </div>
      <span id="password2_error">
        <p></p>
      </span>
    </section>
    <button type="submit" class="button" id="sign_in_btn">
      <span class="button_text">Sign up</span>
      <div class="loader"></div>
    </button>
    <div class="sign_in">
      <p>
        Already have an account? <a href="<?= ROOT ?>Log_in">Sign in</a>
      </p>
    </div>

    </div>
  </form>

  <script>
    const errors = document.querySelectorAll('section span');
    const input_div = document.querySelector(".input");
    errors.forEach(error => {
      error.style.height = `${input_div.clientHeight}px`
    });
    const eye = document.getElementById("eye");
    const eye2 = document.getElementById("eye2");
    const password = document.getElementById("password");
    const password2 = document.getElementById("password2");
    //     genderInput.addEventListener('input', function() {
    //     const selectedGender = genderInput.value;
    //     console.log(selectedGender);  // Log the selected value
    // });

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
    eye2.addEventListener("click", () => {
      padlock2 = document.getElementById("padlock2");
      if (password2.type == "password") {
        password2.type = "text";
        eye2.classList.replace('fa-eye', 'fa-eye-slash');
        padlock2.classList.replace('fa-lock', 'fa-unlock');
      } else if (password2.type != "password") {
        password2.type = "password";
        eye2.classList.replace('fa-eye-slash', 'fa-eye');
        padlock2.classList.replace('fa-unlock', 'fa-lock');
      }
    });
    document.getElementById('sign_up').addEventListener('submit', function(e) {
      e.preventDefault();
      evaluate(e);
      // sigh_in(e);
    });
    document.getElementById("cancel_error_alert").addEventListener('click', function() {
      document.getElementById('error').style.display = "none";
      remove_load_content();
      // document.getElementById('error').style.display = "none";
    });

    function load_content() {
      const button = document.querySelector('.button');
      const page_loader = document.getElementById('page_loader');
      page_loader.style.display = "block"
      button.style.zIndex = -1;
      button.querySelector('.button_text').style.display = "none";
      button.querySelector('.loader').style.display = 'block';
      button.querySelector('.loader').style.setProperty("--after-display", "block");
    }

    function remove_load_content() {
      const button = document.querySelector('.button');
      const page_loader = document.getElementById('page_loader');
      page_loader.style.display = "none"
      button.style.zIndex = 1;
      button.querySelector('.button_text').style.display = "block";
      button.querySelector('.loader').style.display = 'none';
      button.querySelector('.loader').style.setProperty("--after-display", "none");

    }
    // catch (error) {
    //     alert(error);
    //     // console.error("Error:", error);
    //     // document.getElementById('error').style.display = "flex";
    //     // document.getElementById("error_content").innerText = `Error: ${error}.`;
    //   }
    async function evaluate(event) {
      load_content();
      try {
        const hasSpecialChars = /[^a-zA-Z0-9_ ]/; // Excludes underscores and spaces
        const hasNumbers = /\d/;
        let password_confirm = "";
        const inputs = document.querySelectorAll('.input input');
        let evaluated_form_data = {};
        const names = ['First_name', 'Last_name', 'user_name'];
        const genders = ['Male', 'Female'];
        const input_div = document.querySelector(".input");
        if (inputs.length < 6) {
          // console.log("good");
          throw new Error("Insufficient inputs");
        } else {
          inputs.forEach(input => {
            switch (input.name) {
              case 'First_name':
              case 'Last_name':
              case 'user_name':
                if (input.value.length > 2) {
                  const arr_name = input.value.split(" ");
                  const new_first_name = arr_name[0].charAt(0).toUpperCase() + arr_name[0].slice(1);
                  evaluated_form_data[input.name] = new_first_name;

                  let span_element = "";
                  if (input.name === "First_name") {
                    span_element = document.getElementById('name_error');
                  } else if (input.name === "Last_name") {
                    span_element = document.getElementById('last_name_error');
                  } else if (input.name === "user_name") {
                    span_element = document.getElementById('nickname_error');
                  }
                  const p_element_name = span_element.querySelector('p');

                  if (p_element_name) {
                    span_element.style.height = `${(input_div.clientHeight)}px`;
                    span_element.style.paddingTop = `0px`;
                    // span_element.style.marginTop = `-1.5rem`;
                    // span_element.querySelector('p').textContent = '';
                  }
                } else {
                  let span_element = "";
                  if (input.name === "First_name") {
                    span_element = document.getElementById('name_error');
                  } else if (input.name === "Last_name") {
                    span_element = document.getElementById('last_name_error');
                  } else if (input.name === "user_name") {
                    span_element = document.getElementById('nickname_error');
                  }
                  const p_element_name = span_element.querySelector('p');

                  if (p_element_name) {
                    span_element.style.height = "auto";
                    span_element.style.paddingTop = `${(input_div.clientHeight)}px`;
                    // span_element.style.marginTop = `-${(input_div.clientHeight)}px`;
                    span_element.querySelector('p').textContent = `Invalid ${input.getAttribute('placeholder')}.`;
                  }
                }
                break;
              case "email":
                const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                if (emailRegex.test(input.value)) {
                  evaluated_form_data[input.name] = input.value;
                  const span_element = document.getElementById('email_error');
                  const p_element = span_element.querySelector('p');

                  if (p_element) {
                    span_element.style.height = `${(input_div.clientHeight)}px`;
                    span_element.style.paddingTop = `0px`;
                    // span_element.style.marginTop = `-1.5rem`;
                    // p_element.textContent = "";
                  }
                } else {
                  // console.log("good");
                  const span_element = document.getElementById('email_error');
                  const p_element = span_element.querySelector('p');

                  if (p_element) {
                    span_element.style.height = "auto";
                    span_element.style.paddingTop = `${(input_div.clientHeight)}px`;
                    // span_element.style.marginTop = `-${(input_div.clientHeight)}px`;
                    p_element.textContent = "Invalid email.";
                  }

                }
                break
              case "password":
                if (password_confirm === "" && input.value.length >= 8 && hasSpecialChars.test(input.value) && hasNumbers.test(input.value)) {
                  const span_element = document.getElementById('password_error');
                  const p_element = span_element.querySelector('p');

                  if (p_element) {
                    span_element.style.height = `${(input_div.clientHeight)}px`;
                    span_element.style.paddingTop = `0px`;
                  }

                  password_confirm = input.value;
                } else if (password_confirm === "" && input.value === "") {
                  const span_element = document.getElementById('password_error');
                  const p_element = span_element.querySelector('p');

                  if (p_element) {
                    span_element.style.height = "auto";
                    span_element.style.paddingTop = `${(input_div.clientHeight)}px`;
                    // span_element.style.marginTop = `-${(input_div.clientHeight)}px`;
                    p_element.textContent = "Invalid Password";
                  }
                } else if (password_confirm === "" && input.value.length < 8 && !hasSpecialChars.test(input.value) && !hasNumbers.test(input.value)) {

                  const span_element = document.getElementById('password_error');
                  const p_element = span_element.querySelector('p');

                  if (p_element) {
                    span_element.style.height = "auto";
                    span_element.style.paddingTop = `${(input_div.clientHeight)}px`;
                    // span_element.style.marginTop = `-${(input_div.clientHeight)}px`;
                    p_element.textContent = "Password must contain at least 8 letters, 1 special character and 1 number.";
                  }
                  if (input.value != "") {
                    password_confirm = input.value;
                  }
                } else if (password_confirm === "" && input.value.length < 8 && hasSpecialChars.test(input.value) && hasNumbers.test(input.value)) {

                  const span_element = document.getElementById('password_error');
                  const p_element = span_element.querySelector('p');

                  if (p_element) {
                    span_element.style.height = "auto";
                    span_element.style.paddingTop = `${(input_div.clientHeight)}px`;
                    // span_element.style.marginTop = `-${(input_div.clientHeight)}px`;
                    p_element.textContent = "Password must contain at least 8 letters, 1 special character and 1 number.";
                  }
                  if (input.value != "") {
                    password_confirm = input.value;
                  }
                }
                break
              case "Confirm_password":

                if (password_confirm === input.value && input.value != "" && input.value.length >= 8 && hasSpecialChars.test(input.value) && hasNumbers.test(input.value)) {
                  evaluated_form_data["password"] = input.value;
                  const span_element = document.getElementById('password2_error');
                  const p_element = span_element.querySelector('p');

                  if (p_element) {

                    span_element.style.height = `${(input_div.clientHeight)}px`;
                    span_element.style.paddingTop = `0px`;
                    // span_element.style.marginTop = `-1.5rem`;
                    // p_element.textContent = "";
                  }
                } else if (password_confirm === input.value && input.value === "" || password_confirm !== input.value) {
                  const span_element = document.getElementById('password2_error');
                  const p_element = span_element.querySelector('p');

                  if (p_element) {

                    span_element.style.height = "auto";
                    span_element.style.paddingTop = `${(input_div.clientHeight)}px`;
                    // span_element.style.marginTop = `-${(input_div.clientHeight)}px`;
                    p_element.textContent = input.value === "" ? "Invalid Password" : "Passwords do not match.";
                  }
                } else if (password_confirm === input.value && input.value != "" && !hasSpecialChars.test(input.value) && !hasNumbers.test(input.value)) {
                  const span_element = document.getElementById('password2_error');
                  const p_element = span_element.querySelector('p');

                  if (p_element) {

                    span_element.style.height = "auto";
                    span_element.style.paddingTop = `${(input_div.clientHeight)}px`;
                    // span_element.style.marginTop = `-${(input_div.clientHeight)}px`;
                    p_element.textContent = "Password must contain at least 8 letters, 1 special character and 1 number.";
                  }
                } else if (password_confirm === input.value && input.value != "" && input.value.length < 8 && hasSpecialChars.test(input.value) && hasNumbers.test(input.value)) {
                  const span_element = document.getElementById('password2_error');
                  const p_element = span_element.querySelector('p');

                  if (p_element) {

                    span_element.style.height = "auto";
                    span_element.style.paddingTop = `${(input_div.clientHeight)}px`;
                    // span_element.style.marginTop = `-${(input_div.clientHeight)}px`;
                    p_element.textContent = "Password must contain at least 8 letters, 1 special character and 1 number.";
                  }
                }
                break
              default:
                break;
            }

          });
          const genderSelect = document.getElementById("gender");
          // console.log(genderSelect.value);

          if (genderSelect.value === 'Male' || genderSelect.value === 'Female') {
            const new_gender = genderSelect.value.charAt(0).toUpperCase() + genderSelect.value.slice(1);
            evaluated_form_data[genderSelect.name] = new_gender;
            // console.log(genderSelect.value);
            const span_element = document.getElementById('gender_error');
            const p_element = span_element.querySelector('p');

            if (p_element) {
              span_element.style.height = `${(input_div.clientHeight)}px`;
              span_element.style.paddingTop = "0px";
            }

          } else {
            const span_element = document.getElementById('gender_error');
            const p_element = span_element.querySelector('p');

            if (p_element) {
              // console.log("good");
              span_element.style.height = "auto";
              span_element.style.paddingTop = `${(input_div.clientHeight)}px`;
              // span_element.style.marginTop = `-${(input_div.clientHeight)}px`;
              p_element.textContent = `Invalid Gender`;
            }

          }

          if (Object.keys(evaluated_form_data).length == 6) {
            sigh_in(evaluated_form_data);
          } else {
            remove_load_content();
          }
        }
        // console.log(inputs);
      } catch (error) {
        console.log(error);

      };
      // return;
    }
    async function sigh_in(from_object) {

      try {

        const formData = new FormData();
        for (const key in from_object) {
          if (from_object.hasOwnProperty(key)) {
            formData.append(key, from_object[key]);
          }
        }
        // console.log(formData);

        // Send form data to the backend using POST
        const response = await fetch("<?= ROOT ?>Classes/signup.php?user_type=<?= isset($URL[1]) ? $UR[1] : "Customer" ?>", {
          method: "POST",
          body: formData, // Send form data
        });

        // Handle the response
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }


        const result = await response.text(); // Assuming backend sends JSON response
        console.log(response);
        if (!result) {
          remove_load_content();
        } else if (result == "successful") {
          // alert("<?= ROOT ?>Log-in.php");
          const url = "<?= ROOT ?>Log_in";
          window.location.replace(url);
        } else {
          document.getElementById('error').style.display = "flex";
          document.getElementById('error_content').innerText = result;
        }
        // document.getElementById("response").innerText = result.message;
      } catch (error) {
        document.getElementById('error').style.display = "flex";
        document.getElementById('error_content').innerText = error;
      }
    }
    
  </script>
</body>

</html>