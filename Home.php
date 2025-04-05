<?php
include("autoload.php");
$login = new Login();
$first_visit = $login->check_new_user();
if ($first_visit === true) {
  header("Location: Welcome");
}

if (isset($_SESSION['entreefox_userid']) && is_numeric($_SESSION['entreefox_userid'])) {

  $id = $_SESSION['entreefox_userid'];
  $login = new Login();
  $result = $login->check_login($id);
  $answer_id = "";
  $post_ID = "";
  $Error = "";
  $shopping = new Shopping();
  $Phones = $shopping->get_specific_products("phone");
  if (isset($_GET['Search'])) {
    $DB = new Database();
    $find = addslashes($_GET['Search']);
    $sql2 = "select * from products where product_name like '%$find%' || product_type like '%$find%'";
    $PROduct = $DB->read($sql2);
  }
  if (isset($URL[1]) && isset($URL[2]) && is_numeric($URL[1]) && is_numeric($URL[2])) {
    $PROduct = $shopping->get_user_products($URL[2], $URL[1]);
    $shop_info = $shopping->get_shop_info($URL[1]);
    if (isset($URL[3]) && !isset($_GET['Search']) && is_numeric($URL[3])) {
      $DB = new Database();
      $product_info = $shopping->get_product($URL[3], $URL[1]);
      $find = $product_info[0]['product_name'];
      $find2 = $product_info[0]['product_type'];
      $sql2 = "select * from products where userid = '$URL[2]' && product_name like '%$find%' || product_type like '%$find2%' && productid != '$URL[3]'";
      $PROduct = $DB->read($sql2);
    } else {
      if (isset($_GET['Search'])) {
        $DB = new Database();
        $find = addslashes($_GET['Search']);
        $sql2 = "select * from products where product_name like '%$find%' || product_type like '%$find%' && userid = '$URL[2]'";
        $PROduct = $DB->read($sql2);
      }
    }
  }

  if ($result) {

    $user = new User();

    $user_data = $user->get_user($id);


    if ($user_data === false) {
      header("Location: Log_in");
      die;
    } else {
    }
  } else {

    header("Location: Log_in");
    die;
  }
} else {

  header("Location: Log_in");
  die;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Entreefox</title>
  <link rel="shortcut icon" href="<?= ROOT ?>Images/LOGO.PNG" type="image/x-icon">
  <!-- <script src="<?= ROOT ?>new_h+ome_page.js"></script> -->
  <link rel="stylesheet" href="<?= ROOT ?>CSS/Shopping_stylesheet.css" />
  <link rel="stylesheet" href="<?= ROOT ?>CSS/navigation_stylesheet.css" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    

    .SVGs {
      height: 2rem;
      width: 2rem;
      color: black;
    }

    .users {
      display: flex;
      width: 95dvw;
      overflow-x: scroll;
      padding: 1rem 0 1rem 5vw;
      /* background-color: aqua; */
      gap: 0.5rem;
    }

    @media only screen and (max-width: 380px) {
      .menu {
        width: 80vw;
      }
    }




    /* CSS for users */


    .user {
      display: flex;
      gap: 0.5rem;
      flex-direction: column;
      align-items: center;
      background-color: rgb(223, 223, 223);
      padding: 0.7rem 1.5rem;
      flex-shrink: 0;
      box-sizing: border-box;
      max-width: 7rem;
      overflow: hidden;
      position: relative;
    }
    .user .cover {
      position: absolute;
      width: 100%;
      height: 100%;
      display: flex;
      background-color: rgba(23, 117, 249, 0);
      top: 0;
      display: none;
      /* border-radius: 20px; */
      /* scale: 1.2; */
    }

    .user a {
      border-radius: 50%;
    }

    .user .image {
      height: 4rem;
      width: 4rem;
      border-radius: 50%;
      background-position: center;
      background-repeat: no-repeat;
      background-size: contain;
    }

    p {
      font-size: 0.9rem;
      overflow: hidden;
      display: -webkit-box;
      -webkit-line-clamp: 1;
      line-clamp: 1;
      -webkit-box-orient: vertical;
      text-overflow: ellipsis;
    }

    .like_btn {
      height: 1.3rem;
      min-width: 3rem;
      border-radius: 20px;
      background-color: black;
      color: white;
      overflow: visible;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 0.3rem;
    }

    .like_btn .loader {
      height: 0.9rem;
      width: 0.9rem;
      background-image: conic-gradient(black, white);
      display: flex;
      border-radius: 50%;
      position: relative;
      animation-name: loader;
      animation-duration: 1s;
      animation-timing-function: linear;
      animation-iteration-count: infinite;
      transform: rotateZ(0deg);
      display: none;
    }


    .like_btn .loader::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      margin: auto;
      height: 0.5rem;
      width: 0.5rem;
      background-color: black;
      border-radius: 50%;
    }

    .alter {
      background-color: white;
      border: 2px solid black;
      color: black;
      height: 1.3rem;
      min-width: 3rem;
      border-radius: 20px;
      overflow: visible;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 0.3rem;
      padding: 0.3rem;
    }

    .alter .loader {
      height: 0.9rem;
      width: 0.9rem;
      display: flex;
      border-radius: 50%;
      position: relative;
      animation-name: loader;
      animation-duration: 1s;
      animation-timing-function: linear;
      animation-iteration-count: infinite;
      transform: rotateZ(0deg);
      background-image: conic-gradient(white, black);
      display: flex;
    }


    .alter .loader::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      margin: auto;
      height: 0.5rem;
      width: 0.5rem;
      border-radius: 50%;
      background-color: white;
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
</head>

<body>
  <?php
  if ($Error != "") {
    echo "<div class='error_shell' id='error_shell'>";
    echo " <div class='error'>";
    echo "  <P id='error_result'><b>$Error.</b></P>";
    echo "  <button onclick='error()' id='error'><b>close</b></button>";
    echo " </div>";
    echo "</div>";
  }
  ?>
  <div class="top">
    <i class="fa-solid fa-bars menu_icon" id="menu"></i>
  </div>
  <header style="z-index: 100;">
    <div class="buttom">
      <div class="left">
        <a href="<?= ROOT ?>Shopping"><img src="<?= ROOT ?>Images/LOGO.PNG" alt="" /></a>
        <h1>ENTREEFOX</h1>
      </div>
      <div class="right">
        <a href="<?= ROOT ?>Orders" style="position:relative; z-index: 0">
          <?php
          $shopping = new Shopping();
          $added = $shopping->check_cart2($_SESSION['entreefox_userid']);
          if ($added) {
            echo "<span style='background-color: #1777f9; height:10px; width:10px; position: absolute; right: 0; border-radius: 50%;'></span>";
          }
          ?>
          <span id="span" style="background-color: #1777f9; height:10px; width:10px; position: absolute; right: 0; border-radius: 50%; display: none;"></span>
          <i class="fa-solid fa-shopping-cart menu_icon"></i>
        </a>
      </div>
    </div>
  </header>
  <?php include("shopping_head.php") ?>
  <?php
  if (isset($URL[1]) && $URL[1] == "success") {
  ?>
    <div class='error_shell' id='error_shell'>
      <div class='error alert'>
        <P id='error_result'><b>Your request was successful and is now pending for confarmation from the seller.<br><br> Visit the pendint orders page for more details</b></P>
        <a onclick='success(event, this)' href="<?= ROOT ?>Shopping"><button id='error'><b>close</b></button></a>
      </div>
    </div>
  <?php
  }
  ?>
  <section class="search">
    <form method="get">
      <div class="search_box">
        <button type="submit"><i class="fa-solid fa-magnifying-glass menu_icon"></i></button>
        <input type="text" placeholder="Search" name="Search">
      </div>
    </form>
    <?php
    if (isset($URL[1]) && isset($URL[2]) && is_numeric($URL[1]) && is_numeric($URL[2])) {
    ?>
      <h3><?= $shop_info[0]['shopname'] ?></h3>
    <?php
    }
    ?>
  </section>
  <?php
  if ($Phones) {
  ?>
    <section class="phonetablet">
      <div class="title">
        <h3>Top Phone Deals</h3>
        <a href="">See All</a>
      </div>
      <div class="items">
        <?php
        foreach ($Phones as $phone_row) {
          include("phones.php");
        }
        ?>
      </div>
    </section>
  <?php
  }
  ?>
  <section class="users">
    <?php
    $users = $user->get_users($_SESSION['entreefox_userid']);
    // print_r($users);
    foreach ($users as $USER) {
      include("user.php");
    }
    ?>
  </section>
  <!-- <?php
        if ($PROduct) {
        ?>
    <section class='shop' id='shop'>
      <div class="boxes">
        <?php
          foreach ($PROduct as $Products) {
            $image_array = json_decode($Products["product_image"], true);
            $Products['product_image'] = $image_array[0];
            include("All_products.php");
          }
        ?>
      </div>
    </section>
  <?php
        } else {
  ?>
    <section class='shop' id='shop'>
      <h2>No products</h2>
    </section>
  <?php
        }

  ?> -->
  <script>
    let respond = false;
    var lastScrollTop = 200;
    document.getElementById("menu").addEventListener("click", function(e) {
      const menu_icon = document.getElementById("menu");
      const menu_div = document.getElementById("menu_container");
      if (menu_icon.classList.contains("fa-bars")) {
        menu_icon.classList.replace("fa-bars", "fa-xmark");
        menu_div.classList.add("open_menu");
      } else {
        menu_icon.classList.replace("fa-xmark", "fa-bars");
        menu_div.classList.remove("open_menu");
      }
    })

    function ajax_send(data, element, these, coverDiv) {
      var ajax = new XMLHttpRequest();

      ajax.addEventListener("readystatechange", function() {
        if (ajax.readyState == 4 && ajax.status == 200) {
          response(ajax.responseText, element, these, coverDiv);
        }
      });
      data = JSON.stringify(data);

      ajax.open("post", "<?= ROOT ?>ajax.php", true);
      // alert(link);
      ajax.send(data, element);
    }

    function response(result, element, content, coverDiv) {
      if (result != "") {
        // alert(result);
        var obj = JSON.parse(result);
        if (typeof obj.action != "undefined") {
          // alert(obj.action);
          respond = true;
          if (obj.action == "Add") {
            var amount = "";
            amount = parseInt(obj.amount) > 0 ? obj.amount : "";
            document.getElementById('amount' + obj.id + 'pieces').innerHTML = amount;
            document.getElementById('add' + obj.id + 'cart').style.display = "none";
            document.getElementById('pieces' + obj.id + 'number').style.display = "flex";
            document.getElementById('increase' + obj.id + 'button').style.zIndex = 1;
            document.getElementById('increase_second' + obj.id + 'button').style.backgroundColor = "#1777f9";
            if (obj.sum != 0) {
              document.getElementById('span').style.display = "block";
            }
          } else if (obj.action == "increase_limit") {
            document.getElementById('add' + obj.id + 'cart').style.display = "none";
            document.getElementById('pieces' + obj.id + 'number').style.display = "flex";
            document.getElementById('increase' + obj.id + 'button').style.zIndex = -1;
            document.getElementById('increase_second' + obj.id + 'button').style.backgroundColor = "rgba(0, 0, 0, 0.2)";
            amount = parseInt(obj.amount) > 0 ? obj.amount : "";
            document.getElementById('amount' + obj.id + 'pieces').innerHTML = amount;
            if (obj.sum != 0) {
              document.getElementById('span').style.display = "block";
            }
          } else if (obj.action == "decrease_limit") {
            document.getElementById('add' + obj.id + 'cart').style.display = "block";
            document.getElementById('pieces' + obj.id + 'number').style.display = "none";
            if (obj.sum == 0) {
              document.getElementById('span').style.display = "none";
            }
          } else if (obj.action == "following") {
            content.classList.replace("like_btn", "alter");
            const loader = content.querySelector(".loader");
            content.style.opacity = 1;
            coverDiv.style.display = "none";
            loader.style.display = "none";
            let text = content.querySelector(".text");
            text.innerHTML = "Following";
          } else if (obj.action == "unfollow") {
            content.classList.replace("alter", "like_btn");
            const loader = content.querySelector(".loader");
            content.style.opacity = 1;
            coverDiv.style.display = "none";
            loader.style.display = "none";
            let text = content.querySelector(".text");
            text.innerHTML = "Follow";
          }
        }
      }
    }

    function handleClick(fuck, these) {
      fuck.preventDefault();
      var link = these.getAttribute("href");
      respond = false;
      var data = {};
      data.link = link;
      data.action = "Add_to_cart";
      ajax_send(data, fuck.target);
    }

    function error() {
      const error_shell = document.getElementById("error_shell");
      error_shell.style.display = "none";
    }

    function success(fuck, these) {
      fuck.preventDefault();
      const error_shell = document.getElementById("error_shell");
      error_shell.style.display = "none";
      var link = these.getAttribute("href");
      history.pushState(null, "", link);
    }
    document.querySelectorAll("#follow").forEach(follow => {
      follow.addEventListener("click", function(event) {
        event.preventDefault();
        respond = false;
        this.style.opacity = 0.5;
        let userContainer = this.closest('.user'); // Find the closest parent .user div
        let coverDiv = userContainer.querySelector('.cover');
        coverDiv.style.display = "flex";
        const loader = this.querySelector(".loader");
        const content = this.querySelector(".text").innerHTML;
        loader.style.display = "flex";
        var data = {};
        data.name = this.getAttribute("data-id");
        // alert(data.name);
        data.action = "follow";
        ajax_send(data, event.target, this, coverDiv);

        // setTimeout(() => {
        //   loader.style.display = "none";
        //   if (content === this.querySelector(".text").innerHTML && respond === false) {
        //     if (content.innerHTML == "Following") {
        //       this.classList.replace("alter", "like_btn");
        //       content.innerHTML = "Follow";
        //     } else {
        //       content.innerHTML = "Following"
        //       this.classList.replace("like_btn", "alter");
        //     }
        //   }
        // }, 2000)

      })
    })
  </script>
</body>

</html>