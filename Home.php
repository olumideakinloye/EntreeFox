<?php
include("autoload.php");
$login = new Login();
$first_visit = $login->check_new_user();
if ($first_visit === true) {
  header("Location: " . ROOT . "Welcome");
}

if (isset($_SESSION['entreefox_userid']) && is_numeric($_SESSION['entreefox_userid'])) {

  $id = $_SESSION['entreefox_userid'];
  $login = new Login();
  $result = $login->check_login($id);
  if ($result) {

    $user = new User();

    $user_data = $user->get_user($id);

    $search_result = "";
    if ($user_data === false) {
      header("Location: " . ROOT . "Log_in");
      die;
    } else {
      $shopping = new Shopping();
      $Phones = $shopping->get_specific_products($category);
      if (isset($_GET['Search'])) {
        $DB = new Database();
        $find = addslashes($_GET['Search']);
        $sql2 = "select * from products where product_name like '%$find%' || product_type like '%$find%' || product_category like '%$find%'";
        $PROduct = $DB->read($sql2);
      }
      if (isset($URL[1]) && isset($URL[2]) && is_numeric($URL[1]) && is_numeric($URL[2]) && !isset($_GET['Search'])) {
        $PROduct = $shopping->get_user_products($URL[2], $URL[1]);
        $shop_info = $shopping->get_shop_info($URL[1]);
        if (isset($URL[3]) && is_numeric($URL[3])) {
          $DB = new Database();
          $product_info = $shopping->get_product($URL[3], $URL[1]);
          $find = $product_info[0]['product_name'];
          $find2 = $product_info[0]['product_type'];
          $sql2 = "select * from products where userid = '$URL[2]' && product_name like '%$find%' || product_type like '%$find2%' && productid != '$URL[3]'";
          $PROduct = $DB->read($sql2);
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

    .phonetablet p {
      font-size: 0.9rem;
      overflow: hidden;
      display: -webkit-box;
      -webkit-line-clamp: 1;
      line-clamp: 1;
      -webkit-box-orient: vertical;
      text-overflow: ellipsis;
    }

    .users p {
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

    #span {
      background-color: #1777f9;
      height: 10px;
      width: 10px;
      position: absolute;
      right: 0;
      top: 0;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    @keyframes loader {
      from {
        transform: rotateZ(0deg);
      }

      to {
        transform: rotateZ(360deg);
      }
    }

    .find {
      padding-top: 2rem;
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
      /* place-content: center; */
      justify-content: center;
      align-items: center;
      gap: 1rem;
      place-items: center;
    }

    .find a {
      width: 6.5rem;
    }
  </style>
</head>

<body>
  <?php
  if (isset($Error)) {
    if ($Error != "") {
      echo "<div class='error_shell' id='error_shell'>";
      echo " <div class='error'>";
      echo "  <P id='error_result'><b>$Error.</b></P>";
      echo "  <button onclick='error()' id='error'><b>close</b></button>";
      echo " </div>";
      echo "</div>";
    }
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
        <a href="<?= ROOT ?>Cart" style="z-index: 0;">
          <?php
          $cart = "none";
          $shopping = new Shopping();
          $added = $shopping->get_cart($_SESSION['entreefox_userid']);
          if ($added) {
            $cart = "block";
          }
          ?>
          <i class="fa-solid fa-shopping-cart menu_icon" style="position:relative;">
            <span id="span" style="display: <?= $cart ?>;">
            </span>
          </i>
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
        <input type="text" placeholder="Search" name="Search" value="<?= isset($_GET['Search']) ? $_GET['Search'] : "" ?>">
      </div>
    </form>
    <?php
    if (isset($URL[1]) && isset($URL[2]) && is_numeric($URL[1]) && is_numeric($URL[2]) && !isset($URL[3])) {
    ?>
      <h3><?= $shop_info[0]['shopname'] ?></h3>

    <?php
    }
    ?>
  </section>
  <?php
  if (isset($PROduct)) {
  ?>
    <section class="find">
      <?php
      foreach ($PROduct as $product) {
        $added = $shopping->check_cart($product['productid'], $_SESSION['entreefox_userid']);
        if ($added) {
          if ($added[0]['pieces'] >= $product['product_pieces']) {
            $z_index = "-1";
            $back_ground = "rgba(0, 0, 0, 0.2)";
          } else {
            $z_index = "1";
            $back_ground = "#1777f9";
          }
        } else {
          $z_index = "1";
          $back_ground = "#1777f9";
        }
        if ($added) {
          $display = "flex";
          $display2 = "none";
        } else {
          $display2 = "flex";
          $display = "none";
        }
        $img = $product['product_image'];
        $product_images = json_decode($product['product_image'], true);
        if (is_array($product_images)) {
          $img = $product_images[0];
        }

      ?>
        <div class="item">
          <a href="<?= ROOT ?>Single_Product/<?= $product['productid'] ?>" style="color: black; text-decoration:none;">
            <div class="image" style="background-color:rgba(0, 0, 0, 0.2); background-image:url(<?= ROOT . $img ?>)">

            </div>
            <p class="name"><?= $product['product_name'] ?></p>
            <p class="price">
              ₦<?php
                $price = (int)$product['product_price'] . ".00";
                $price2 = number_format($price, 2, '.', ',');;
                echo $price2;
                ?>
            </p>
          </a>
          <div class="controls">
            <div class="increase_decrease" style="display: <?= $display ?>;" id="pieces_number">
              <a onclick="add_to_cart(event, this)" href="<?= ROOT ?>add_to_cart/product_decrement/<?= $product['productid'] ?>/<?= $product['shopid'] ?>/<?= $added[0]['pieces'] ?>" id="decrease_button">
                <button>
                  -
                </button>
              </a>
              <p id="amount_pieces"><?= isset($added[0]['pieces']) ? $added[0]['pieces'] : "" ?></p>
              <a onclick="add_to_cart(event, this)" href="<?= ROOT ?>add_to_cart/product_increment/<?= $product['productid'] ?>/<?= $product['shopid'] ?>/<?= $added[0]['pieces'] ?>" style="z-index: <?= $z_index ?>;" id="increase_button">
                <button style="background-color: <?= $back_ground ?>;" id="increase_second_button">
                  +
                </button>
              </a>
            </div>
            <button class="btn_add" id="btn_add" style="display: <?= $display2 ?>;">
              <div class="add_btn_cover">

              </div>
              <a onclick="add_to_cart(event, this)" href="<?= ROOT ?>add_to_cart/add/<?= $product['productid'] ?>/<?= $product['shopid'] ?>" id="add_cart">
                Add to cart
              </a>
            </button>
          </div>
        </div>
      <?php
      }
      ?>
    </section>
    <?php
  } elseif (isset($search_result) && $search_result !== "") {
    print_r($search_result);
  } elseif ($Phones) {
    foreach ($Phones as $phone_row) {
    ?>
      <section class="phonetablet">
        <div class="title">
          <h3>Top <?= $phone_row[0]['product_category'] ?> Deals</h3>
          <a href="">See All</a>
        </div>
        <div class="items">
          <?php
          foreach ($phone_row as $row) {
            // print_r($row);

            if (is_array($row['product_image'])) {
            }
            $product_image = json_decode($row['product_image'], true);
          ?>
            <a href="<?= ROOT ?>Single_Product/<?= $row['productid'] ?>" style="color: black; text-decoration:none;">
              <div class="item">
                <div class="image" style="background-color:rgba(0, 0, 0, 0.2); background-image:url(<?= $product_image[0] ?>)">

                </div>
                <p class="name"><?= $row['product_name'] ?></p>
                <p class="price">
                  ₦<?php
                    $price = (int)$row['product_price'] . ".00";
                    $price2 = number_format($price, 2, '.', ',');;
                    echo $price2;
                    ?>
                </p>
              </div>
            </a>
          <?php
          }
          ?>
        </div>
      </section>
  <?php
    }
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

    function ajax_send(data, element, these, coverDiv, request_type) {
      var ajax = new XMLHttpRequest();

      ajax.addEventListener("readystatechange", function() {
        if (ajax.readyState == 4 && ajax.status == 200) {
          response(ajax.responseText, element, these, coverDiv, request_type);
        }
      });
      data = JSON.stringify(data);

      ajax.open("post", "<?= ROOT ?>ajax.php", true);
      // alert(link);
      ajax.send(data, element);
    }

    function response(result, element, content, coverDiv, request_type) {
      if (result != "") {
        var obj = JSON.parse(result);
        if (typeof obj.action != "undefined") {
          respond = true;
          if (request_type === "follow") {
            if (obj.action == "following") {
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
          } else {
            remove_loader(element);
            let parent_div = element.closest(".controls");
            const add_btn = parent_div.querySelector(".btn_add");
            const inc_dec_div = parent_div.querySelector(".increase_decrease");
            const amount = parent_div.querySelector(".increase_decrease p");
            const inc_btn = parent_div.querySelector(".increase_decrease a:nth-of-type(2)");
            const dec_btn = parent_div.querySelector(".increase_decrease a:nth-of-type(1)");
            const inc_btn2 = parent_div.querySelector(".increase_decrease a:nth-of-type(2) button");
            const dec_btn2 = parent_div.querySelector(".increase_decrease a:nth-of-type(1) button");
            if (obj.action == "Added") {
              if (isNaN(Number(amount.textContent.trim())) || amount.textContent.trim() === "") {
                amount.textContent = 1;
              }
              add_btn.style.display = "none";
              inc_dec_div.style.display = "flex";
              inc_btn.style.zIndex = 1;
              inc_btn2.style.backgroundColor = "#1777f9";
              if (obj.sum != 0) {
                document.getElementById('span').style.display = "block";
              }
            } else if (obj.action == "decrement") {
              if (!isNaN(Number(amount.textContent.trim())) || amount.textContent.trim() !== "") {
                amount.textContent = parseInt(amount.textContent, 10) - 1;
              }
              inc_btn.style.zIndex = 1;
              inc_btn2.style.backgroundColor = "#1777f9";
            } else if (obj.action == "increment") {
              if (!isNaN(Number(amount.textContent.trim())) || amount.textContent.trim() !== "") {
                amount.textContent = parseInt(amount.textContent, 10) + 1;
              }
            } else if (obj.action == "increment_limit") {
              add_btn.style.display = "none";
              inc_dec_div.style.display = "flex";
              inc_btn.style.zIndex = -1;
              inc_btn2.style.backgroundColor = "rgba(0, 0, 0, 0.2)";
              if (!isNaN(Number(amount.textContent.trim())) || amount.textContent.trim() !== "") {
                if (isNaN(Number(amount.textContent.trim()))) {
                  amount.textContent = 1;
                } else {
                  amount.textContent = parseInt(amount.textContent, 10) + 1;
                }
              } else {
                amount.textContent = "1";
              }
              if (obj.sum != 0) {
                document.getElementById('span').style.display = "block";
              }
            } else if (obj.action == "Added_increment_limit") {
              add_btn.style.display = "none";
              inc_dec_div.style.display = "flex";
              inc_btn.style.zIndex = -1;
              inc_btn2.style.backgroundColor = "rgba(0, 0, 0, 0.2)";
              amount.textContent = 1;
              if (obj.sum != 0) {
                document.getElementById('span').style.display = "block";
              }
            } else if (obj.action == "decrement_limit") {
              add_btn.style.display = "flex";
              inc_dec_div.style.display = "none";
              amount.textContent = "";
              if (obj.sum == 0) {
                document.getElementById('span').style.display = "none";
              }
            }
          }
        }
      }
    }

    function add_to_cart(fuck, these) {
      fuck.preventDefault();
      display_loader(fuck.target);
      var link = these.getAttribute("href");
      // alert(link);
      var data = {};
      data.link = link;
      data.action = "Add_to_cart";
      ajax_send(data, fuck.target);
    }

    function display_loader(element) {
      let parent_div = element.closest(".controls");
      const btn = parent_div.querySelector(".btn_add");
      btn.querySelector(".add_btn_cover").style.display = "block";
      const inc_btn = parent_div.querySelector(".increase_decrease a:nth-of-type(2)");
      const dec_btn = parent_div.querySelector(".increase_decrease a:nth-of-type(1)");
      inc_btn.style.zIndex = -1;
      dec_btn.style.zIndex = -1;
      dec_btn.style.opacity = 0.5;
      inc_btn.style.opacity = 0.5;
    }

    function remove_loader(element) {
      let parent_div = element.closest(".controls");
      const btn = parent_div.querySelector(".btn_add");
      btn.querySelector(".add_btn_cover").style.display = "none";
      const inc_btn = parent_div.querySelector(".increase_decrease a:nth-of-type(2)");
      const dec_btn = parent_div.querySelector(".increase_decrease a:nth-of-type(1)");
      inc_btn.style.zIndex = 1;
      dec_btn.style.zIndex = 1;
      dec_btn.style.opacity = 1;
      inc_btn.style.opacity = 1;
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
        ajax_send(data, event.target, this, coverDiv, "follow");

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