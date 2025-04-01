<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Welcome to Entreefox</title>
  <link rel="shortcut icon" href="<?= ROOT ?>Images/LOGO.PNG" type="image/x-icon">
  <link rel="stylesheet" href="<?= ROOT ?>CSS/Welcome_stylesheet.css" />
</head>

<body id="body">
  <div class="container">
    <div class="logo">
      <img src="<?= ROOT ?>Images/LOGO.PNG" alt="" />
    </div>
    <h1 id="welcome">Welcome!</h1>
    <a href="<?= ROOT ?>Sign_up/Vendor">
      <input type="button" value="Vendor" id="vendor" />
    </a>
    <a href="<?= ROOT ?>Sign_up/Customer">
      <input type="button" value="Customer" id="Customer" />
    </a>
    <button id="start">Get Started</button>
  </div>
  <script>
    let start = document.getElementById("start");
    start.addEventListener("click", () => {
      document.getElementById("welcome").style.display = "none";
      document.getElementById("welcome").style.transition = "2s";
      document.getElementById("Customer").style.display = "block";
      document.getElementById("vendor").style.display = "block";
      start.style.display = "none";
      document.getElementById("body").style.backgroundImage = "radial-gradient(powderblue, white)"
    })
  </script>
</body>

</html>