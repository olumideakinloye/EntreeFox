<?php
include("autoload.php");
$login = new Login();
$first_visit = $login->check_new_user();
if ($first_visit === true) {
  header("Location: Welcome");
}
// print_r($_SESSION);

if (isset($_SESSION['entreefox_userid']) && is_numeric($_SESSION['entreefox_userid'])) {

  $id = $_SESSION['entreefox_userid'];
  // $login = new Login();
  $result = $login->check_login($id);
  $answer_id = "";
  $post_ID = "";
  $Error = "";
  // $shopping = new Shopping();
  // $Phones = $shopping->get_specific_products("phone");
  // $shopping->check_user_type_for_vendor($_SESSION['entreefox_userid']);

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
<style>
  * {
    margin: 0;
    padding: 0;
  }

  body {
    overflow: scroll;
    background-color: black;
  }

  header {
    position: fixed;
    top: 0;
    left: 0;
    width: 90vw;
    padding: 2.3rem 5vw 0.1rem 5vw;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 5;
    box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.4);
    background-color: white;
    transition: top 0.3s ease;
  }

  header .left {
    display: flex;
    align-items: center;
    flex-grow: 1;
  }

  header .right {
    display: flex;
    align-items: center;
    flex-grow: 0.5;
    padding-right: 0;
    justify-content: space-between;
  }

  header .left img {
    width: 3rem;
  }

  header .left h1 {
    font-size: 0.8rem;
    padding: 0 0.5rem;
  }

  .notification {
    background-color: white;
    height: 0px;
    width: 100dvw;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 10;
    display: block;
    transition: height 0.3s;
    overflow: hidden;
  }

  .notification .loader_container {
    height: 100%;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .notification .loader_container .loader {
    padding: 3rem;
    border-radius: 50%;
    background-image: conic-gradient(white, black);
    position: relative;
    animation-name: loader;
    animation-duration: 2s;
    animation-timing-function: linear;
    animation-fill-mode: forwards;
    animation-iteration-count: infinite;
  }

  @keyframes loader {
    from {
      transform: rotateZ(0deg);
    }

    to {
      transform: rotateZ(360deg);
    }
  }

  .notification .loader_container .loader::after {
    content: '';
    background-color: white;
    padding: 2.5rem;
    border-radius: 50%;
    position: fixed;
    top: 0.5rem;
    left: 0.5rem;
  }

  .menu {
    position: fixed;
    top: 0;
    right: -0.5rem;
    height: auto;
    width: auto;
    border-radius: 20px 0 0 20px;
    z-index: 20;
    padding: 0.2rem 1rem;
    display: flex;
    /* background-color: white; */
    /* border: 0.5px solid rgba(0, 0, 0, 0.3); */
    background-color: rgb(114, 147, 255);
  }

  .custom-icon {
    font-size: 1.5rem;
    height: auto;
    width: auto;
    display: inline-block;
    text-align: center;
    transition: transform 0.3s ease, opacity 0.3s ease;
    color: white;
  }

  .custom-icon.rotate {
    transform: rotate(180deg);
  }

  .custom-icon.fade {
    opacity: 0.5;
  }

  .custom-icon2 {
    font-size: 2rem;
    height: auto;
    width: auto;
    display: inline-block;
    text-align: center;
  }

  .add {
    color: white;
    font-size: 2.5rem;
  }

  .navigation {
    height: 100dvh;
    width: 1px;
    background-color: rgba(0, 0, 0, 0.4);
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
    position: fixed;
    right: -1px;
    top: 0;
    z-index: 15;
    display: flex;
    justify-content: end;
    overflow: scroll;
    transition: width 0.3s;
  }

  .navigation .nav_right {
    background-color: white;
    padding-top: 3rem;
    height: auto;
    width: 50%;
    display: flex;
  }

  .navigation .nav_right ul {
    width: 100%;
    display: flex;
    flex-direction: column;
    flex-grow: 0;
    justify-content: space-between;
    align-items: center;
    padding-left: 0;
    height: 70%;
  }

  .navigation .nav_right ul li {
    list-style-type: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 80%;
    overflow: hidden;
  }

  .navigation .nav_right ul li .my_profile {
    height: 8rem;
    width: 8rem;
    padding: 0;
    padding-right: 0;
    border-radius: 50%;
    background-color: rgba(0, 0, 0, 0.5);
    background-size: contain;
    background-clip: border-box;
    background-repeat: no-repeat;
    background-position: center;
  }

  .navigation .nav_right ul li:not(:first-of-type) {
    border-radius: 50px;
    background-color: rgb(114, 147, 255);
    width: 75%;
    flex-direction: row;

  }

  .navigation .nav_right ul li:not(:first-of-type) a {
    text-decoration: none;
    display: flex;
    white-space: nowrap;
    align-items: center;
    justify-content: space-between;
    color: white;
    font-size: 1.2rem;
    padding: 0.8rem 0.5rem;
    overflow: hidden;
    font-weight: 900;
    width: 100%;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
  }

  .navigation .nav_right ul li:not(:first-of-type) i {
    font-size: 2rem;
    height: auto;
    width: auto;
    display: inline-block;
    color: white;
  }

  .container {
    display: flex;
    flex-direction: column;
    background-color: white;
    /* padding: 0 5vw; */
  }

  .followers {
    display: flex;
    gap: 1rem;
    overflow-x: scroll;
    width: 100vw;
    align-items: start;
  }

  .add_story {
    /* height: 3.5rem; */
    width: 4.5rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding-left: 5vw;
    /* flex-grow: 10; */
  }

  .add_story .box {
    position: relative;
    height: 4.5rem;
    width: 4.5rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background-color: rgb(114, 147, 255);
    border-radius: 50%;
  }

  .add_story p {
    width: 100%;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    font-size: 0.9rem;
    text-align: center;
    font-weight: 800;
  }

  .add_story .box input {
    position: absolute;
    height: 4.5rem;
    width: 4.5rem;
    top: 0;
    left: 0;
    border-radius: 50%;
    overflow: hidden;
    opacity: 0;
    background-color: rgba(0, 0, 0, 0.16);
  }

  .follow {
    width: 4.5rem;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .follow .profile {
    height: 4.5rem;
    width: 4.5rem;
    border-radius: 50%;
    background-color: rgba(0, 0, 0, 0.5);
    background-size: contain;
    background-clip: border-box;
    background-repeat: no-repeat;
    background-position: center;
  }

  .follow p {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
    width: 100%;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    font-size: 0.9rem;
    text-align: center;
    font-weight: 800;
  }

  .post_div {
    padding: 1.5rem 5vw;
  }

  .post {
    border-radius: 20px;
    border: 1.5px solid black;
    padding: 1rem 5vw;
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }

  .post_top {
    display: flex;
    gap: 1rem;
    align-items: center;
  }

  .post_top textarea {
    flex-grow: 1;
    border: none;
    font-size: 0.9rem;
    resize: none;
    outline: none;
  }

  .post_buttom {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .inputs {
    display: flex;
    gap: 1rem;
  }

  .post_image {
    position: relative;
    height: 3.5rem;
    width: 3.5rem;
    border-radius: 50%;
    border: 1.5px solid rgb(114, 147, 255);
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .post_image input {
    height: 3.5rem;
    width: 3.5rem;
    position: absolute;
    top: 0;
    left: 0;
    border-radius: 50%;
    background-color: rgba(0, 0, 0, 0.33);
    overflow: hidden;
    opacity: 0;
  }

  .post_video {
    position: relative;
    height: 3.5rem;
    width: 3.5rem;
    border-radius: 50%;
    border: 1.5px solid rgb(114, 147, 255);
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .post_video input {
    height: 3.5rem;
    width: 3.5rem;
    position: absolute;
    top: 0;
    left: 0;
    border-radius: 50%;
    background-color: rgba(0, 0, 0, 0.33);
    overflow: hidden;
    opacity: 0;
  }

  .post_buttom .btn {
    /* background-color: bisque; */
    position: relative;
  }

  .post_buttom button {
    padding: 0 0.5rem;
    height: 2.5rem;
    line-height: 0;
    border-radius: 10px;
    background-color: rgb(114, 147, 255);
    color: white;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    font-weight: 800;
    font-size: 1.2rem;
    border: none;
    display: flex;
    justify-content: center;
    align-items: center;
    opacity: 0.5;
  }

  .cover {
    position: absolute;
    height: 100%;
    width: 100%;
    top: 0;
    left: 0;
    /* background-color: aqua; */
    border-radius: 10px;
    opacity: 0.5;
    z-index: 5;
    overflow: hidden;
  }

  .post_icon {
    font-size: 2rem;
    height: auto;
    width: auto;
    display: inline-block;
    text-align: center;
    color: rgb(114, 147, 255);
  }

  .post_buttom button .loader {
    display: none;
    margin-left: 0.5rem;
    height: 1.5rem;
    width: 1.5rem;
    background-image: conic-gradient(rgb(114, 147, 255), white);
    border-radius: 50%;
    position: relative;
    animation: loader 1s ease infinite;
  }

  @keyframes loader {
    from {
      transform: rotate(0turn);
    }

    to {
      transform: rotate(1turn);
    }
  }

  .post_buttom button .loader::after {
    content: '';
    position: absolute;
    width: 70%;
    height: 70%;
    background-color: rgb(114, 147, 255);
    border-radius: 50%;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    margin: auto;
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
    overflow: scroll;
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
    overflow: scroll;
  }

  .error .error_box i {
    position: absolute;
    top: 1rem;
    right: 0.5rem;
    margin-right: 1rem;
    scale: 2;
  }

  .error .error_box h3 {
    padding: 0 1rem;
    overflow: scroll;
  }

  .posts {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.5rem;
  }
  footer{
    width: 100%;
    height: 10dvh;
  }

  /*CSS FOR POSTS*/
  .person1_Box {
    border: 1.5px solid black;
    border-radius: 20px;
    width: 90vw;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .person1_Boxtop {
    display: flex;
    justify-content: space-between;
    padding: 1.5dvh 4vw 0 4vw;
  }

  .person1_Boxtop_left {
    display: flex;
    justify-content: space-between;
  }

  .person1_Boxtop_left img {
    height: 45px;
    width: 45px;
    border-radius: 50%;
  }
  .post_profile {
    height: 3rem;
    width: 3rem;
    border-radius: 50%;
    background-color: rgba(0, 0, 0, 0.5);
    background-size: contain;
    background-clip: border-box;
    background-repeat: no-repeat;
    background-position: center;
  }

  .person1_Boxbottum {
    display: flex;
    align-items: center;
    justify-content: space-evenly;
    padding: 1.5vh 4vw 1.5vh 4vw;
  }

  .person1_Boxbottum img {
    height: 20px;
  }

  .person1_Boxtop_textBox {
    color: black;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding-left: 10px;
  }

  .person1_Boxtop_textBox h1 {
    line-height: 1;
    font-size: 1.3rem;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
  }

  .person1_Boxtop_textBox p {
    font-size: 0.6rem;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
  }

  .intro {
    padding: 20px 4vw 5px 4vw;
  }

  .intro p {
    font-size: 1rem;
    overflow-wrap: break-word;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    background-color: rgba(0, 0, 0, 0.1);
    border-radius: 20px;
  }

  .image_post {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    border-radius: 20px;
  }

  .image_post img {
    border-radius: 20px;
    width: 100%;
  }

  .image_post video {
    border-radius: 20px;
    cursor: pointer;
    width: 100%;
    max-width: 100%;
    background-color: #0000008c;
    object-fit: contain;
    -webkit-user-select: none;
    user-select: none;
    -webkit-touch-callout: none;
    -webkit-user-drag: none;
    touch-action: manipulation;
  }

  .image_post video:focus {
    outline: none;
  }

  .postmenu {
    display: block;
  }

  .likes {
    width: 20px;
    height: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .likes p {
    font-size: 17px;
    height: 20px;
  }

  .comments_section {
    font-size: 17px;
    display: flex;
    align-items: center;
    text-decoration: none;
    color: var(--color);
  }

  .comments_section svg {
    height: 20px;
    transform: rotate(90deg);
  }

  .like-animation-container {
    z-index: 1000;
    top: 0;
    height: 100vh;
    width: 100vw;
    background-color: rgba(0, 0, 0, 0);
    display: none;
    align-items: center;
    justify-content: center;
    position: fixed;
  }

  .like-box {
    position: relative;
    transform: rotate(45deg);
  }

  @keyframes heartanimation {
    from {
      transform: scale(0);
    }

    to {
      transform: scale(0.5);
      filter: drop-shadow(100px 100px 100px red) drop-shadow(-100px -100px 100px red) drop-shadow(100px -100px 100px red) drop-shadow(-100px 100px 100px red);
    }
  }

  .like_content {
    animation: heartanimation 0.5s infinite alternate;
  }

  .like1 {
    background-color: red;
    height: 75px;
    width: 115px;
    border-radius: 200px 100% 0 200px;
  }

  .like2 {
    background-color: red;
    height: 115px;
    width: 78px;
    border-radius: 200px 200px 100% 0;
    position: absolute;
    right: 0;
    bottom: 0;
  }

  .load_more_posts {
    width: 100vw;
    display: flex;
    justify-content: center;
    padding: 1rem 0;
  }

  .load_more_posts_loader {
    background-image: conic-gradient(white 0%, rgb(114, 147, 255) 100%);
    height: 3rem;
    width: 3rem;
    border-radius: 50%;
    position: relative;
    animation-name: loader;
    animation-duration: 2s;
    animation-timing-function: linear;
    animation-fill-mode: forwards;
    animation-iteration-count: infinite;
  }

  .load_more_posts_loader::after {
    content: '';
    height: 2.5rem;
    width: 2.5rem;
    background-color: white;
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    margin: auto;
    border-radius: 50%;
  }

  /*END CSS FOR POSTS*/



  /* CSS for comments, likes and favorites */




  .LIKES-CONTAINER {
    position: fixed;
    z-index: 1000;
    background-color: rgba(0, 0, 0, 0.308);
    -webkit-backdrop-filter: blur(10px);
    backdrop-filter: blur(10px);
    width: 100vw;
    bottom: -1px;
    /* top: 0; */
    height: 1px;
    display: flex;
    flex-direction: column;
    /* align-items: end; */
    justify-content: end;
    transition: all 0.3s;
    /* position: relative; */
  }

  .view_likes {
    height: 100dvh;
    bottom: 0;
    /* background-color: #1777f9; */
  }

  .LIKES_BOX {
    height: 0;
    background-color: rgb(240, 12, 12);
    width: 100vw;
    transition: all 0.3s;
    z-index: 10;
    /* overflow: scroll; */
    background-color: white;
    border-radius: 20px 20px 0 0;
  }

  .view_likes_box {
    height: 50vh;
    height: 50dvh;
    transition: all 0.3s;
    /* background-color: aqua; */
  }

  .LIKES_BOX-BOTTOM {
    overflow: scroll;
    height: 50dvh;
    transition: all 0.3s;
  }

  .control_position {
    position: sticky;
    top: 0px;
    background-color: rgb(255, 255, 255);
    height: 30px;
    width: 100vw;
    border-radius: 20px 20px 0 0;
    box-shadow: 0 0 5px 0 rgba(0, 0, 0, 0.3);
  }

  .control_position::after {
    content: "";
    position: absolute;
    top: 15px;
    left: 0;
    right: 0;
    width: 20vw;
    height: 5px;
    background-color: rgb(202, 202, 202);
    border-radius: 10px;
    margin: auto;
  }

  .like {
    padding: 20px;
    /* background-color: aqua; */
  }

  .like a {
    text-decoration: none;
    color: black;
  }

  .no-scroll {
    overflow: hidden;
    position: fixed;
    touch-action: none;
  }

  .section_box {
    padding: 5px 0 5px 0;
    border: 1px solid black;
    border-radius: 15px;
    display: flex;
    align-items: center;
  }

  .like img {
    height: 40px;
    width: 40px;
    border-radius: 50%;
    margin-left: 10px;
  }

  .like p {
    padding-left: 10px;
    font-size: 18px;
  }

  .spinner_likes {
    width: 50px;
    height: 50px;
    border: 5px solid rgba(255, 255, 255, 0);
    border-top: 5px solid #1777f9;
    border-bottom: 5px solid #1777f9;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: auto;
    /* display: block; */
  }

  @keyframes spin {
    0% {
      transform: rotate(0deg);
    }

    100% {
      transform: rotate(360deg);
    }
  }

  .loading-container-likes {
    /* position: fixed; */
    position: absolute;
    top: 70%;
    /* left: 0; */
    width: 100vw;
    height: auto;
    /* background: rgba(255, 0, 0, 0.8); */
    display: flex;
    justify-content: center;
    align-items: center;
    /* z-index: 2; */
    /* display: none; */
    z-index: 1000;
    transition: all 0.3s;
  }

  .username img {
    height: 50px;
    width: 50px;
    border: 1px solid rgba(0, 0, 0, 0.5);
    border-radius: 50%;
    /* position: relative; */
  }

  .posting_image {
    position: relative;
    /* background-color: aqua; */
  }

  .username p {
    font-size: 19px;
  }

  #post_image {
    position: absolute;
    height: 50px;
    width: 50px;
    border-radius: 50%;
    left: 0;
    opacity: 0;
  }

  #home {
    background-color: white;
    margin-top: 10px;
    margin-left: 10px;
    height: 60px;
    border-radius: 50%;
    border: 1px solid rgba(0, 0, 0, 0.5);
  }

  #home2 {
    background-color: white;
    top: 10px;
    left: 10px;
    position: absolute;
    height: 60px;
    border-radius: 50%;
    border: 1px solid rgba(0, 0, 0, 0.5);
  }

  .buttom {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .buttom img {
    height: 50px;
  }

  .buttom h1 {
    font-size: 16px;
  }

  .right {
    display: flex;
    justify-content: space-between;
    align-items: center;
    /* background-color: aqua; */
    padding-right: 3vw;
    width: 110px;
  }

  .right img {
    height: 30px;
  }

  .edit_photo {
    /* background-color: aqua; */
    height: 25vh;
    display: flex;
    flex-direction: column;
    justify-content: space-evenly;
    align-items: center;
  }

  .submit {
    height: 60px;
    display: flex;
    justify-content: center;
    align-items: center;
    /* background-color: bisque; */
  }

  .submit button {
    width: 40vw;
    background-color: black;
    color: white;
    height: 25px;
    border-radius: 10px;
  }

  .submit input {
    width: 40vw;
    background-color: black;
    color: white;
    height: 25px;
    border-radius: 10px;
  }

  .alart {
    height: 100vh;
    width: 100vw;
    background-color: rgba(0, 0, 0, 0.459);
    position: fixed;
    top: 0;
    display: none;
    justify-content: center;
    align-items: center;
  }

  .alartbox {
    height: 30vh;
    width: 70vw;
    border-radius: 10px;
    display: flex;
    flex-direction: column;
    background-color: white;
  }

  .alartbox h3 {
    padding: 20px;
  }

  .alartbox form {
    /* background-color: aqua; */
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    padding: 20px;
  }

  .alartbox input {
    width: 60px;
    background-color: black;
    color: white;
    height: 25px;
    border-radius: 10px;
    opacity: 1;
  }

  .alartbox button {
    width: 60px;
    background-color: black;
    color: white;
    height: 25px;
    border-radius: 10px;
  }

  .alartbox a {
    text-decoration: none;
    width: 60px;
    background-color: black;
    color: white;
    height: 25px;
    border-radius: 10px;
  }

  .chats {
    padding-bottom: 80px;
    padding-top: 20px;
    /* background-color: aqua; */
  }

  .chats_top {
    height: 50vh;
    width: 100vw;
    background-color: #00000080;
  }

  .chatbox {
    /* background-color: rgb(136, 40, 40); */
    width: 100vw;
  }

  .comments {
    width: 100vw;
    background-color: white;
    padding-bottom: 80px;
    /* margin-top: 20px; */
    display: flex;
    flex-direction: column;
    align-items: center;
    overflow: scroll;
    padding-top: 2rem;
    /* max-height: 70dvh; */
    /* background-color: aqua; */
  }

  .view_more {
    position: sticky;
    bottom: 5px;
    /* margin-right: 15vw; */
    color: rgba(0, 0, 0, 0.5);
    text-decoration: none;
    background-color: white;
    /* background-color: aqua; */
    width: 70vw;
    margin-top: 5px;
    padding: 5px 0;
    text-align: center;
    font-size: 0.6rem;
    font-family: "Playwrite IS", serif;
  }

  .comment_user {
    background-color: rgb(230, 230, 230);
    margin-bottom: 20px;
    width: 90vw;
    border-radius: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding-top: 10px;
  }

  .reply {
    width: 70vw;
    margin-left: 20vw;
    margin-bottom: 0;
  }

  .reply_user {
    /* background-color: aqua; */
    padding: 10px 0;
    display: none;
  }

  .reply_user.visible {
    display: block;
  }

  .reply_text_info h3 {
    /* background-color: #1777f9; */
    font-size: 14px;
  }

  .likes_and_reply {
    /* background-color: rgba(0, 255, 255, 0.486); */
    padding: 13px;
    display: flex;
    justify-content: space-around;
  }

  .chats {
    /* background-color: rgba(0, 255, 255, 0.486); */
    /* padding: 13px; */
    display: flex;
    justify-content: space-around;
  }

  .likes_and_reply a {
    color: rgba(0, 0, 0, 0.5);
    text-decoration: none;
  }

  .likes_and_reply p {
    color: rgba(0, 0, 0, 0.5);
    text-decoration: none;
    font-size: 0.6rem;
    font-family: "Playwrite IS", serif;
  }

  .comment_user_info {
    display: flex;
    /* padding: 10px 0; */
    width: 85vw;
    align-items: center;
    background-color: white;
    border-radius: 20px;
  }

  .reply_user .comment_user_info {
    width: 65vw;
  }

  .comment_user_info img {
    height: 40px;
    width: 40px;
    border-radius: 50%;
  }

  .comment_text_info {
    padding-left: 10px;
    display: flex;
    flex-direction: column;
    justify-content: space-evenly;
    height: 40px;
  }

  .comment_text_info p {
    font-size: 0.5rem;
    font-family: "Playwrite IS", serif;
    /* font-optical-sizing: auto; */
  }

  .comment_text_info h3 {
    font-size: 1rem;
    font-family: "Playwrite IS", serif;
    line-height: 1;
  }

  .comment_area {
    padding: 10px 40px;
  }

  .comment_area p {
    font-size: 1.2rem;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    width: 85vw;
  }

  .reply_user .comment_area p {
    width: 65vw;
  }

  .reply_to_comments {
    max-height: 0px;
    overflow: hidden;
    /* transition: max-height 0.5s ease; */
    /* max-height: 100vh; */
    display: flex;
    flex-direction: column;
    /* justify-content: end; */
    align-items: end;
    /* background-color: aqua; */
    /* padding-bottom: 10px; */
  }

  .input_comment {
    background-color: rgb(255, 255, 255);
    position: fixed;
    bottom: 0;
    width: 100vw;
    /* min-height: 50px; */
    max-height: 250px;
    display: flex;
    justify-content: space-evenly;
    align-items: center;
    padding: 10px 0;
    z-index: 0;
    box-shadow: -0 -0 20px rgba(0, 0, 0, 0.473);
  }

  .input_comment form {
    width: 80vw;
    height: min-content;
    /* min-height: 10px; */
    padding: 5px;
    background-color: rgb(214, 214, 214);
    display: flex;
    justify-content: center;
    /* align-items: center; */
    border-radius: 30px;
    /* transition: 0.25s; */
    /* padding-bottom: 5px; */
  }

  .input_comment img {
    height: 55px;
    border-radius: 50%;
    /* margin-top: 3px; */
  }

  .input_comment textarea {
    height: 20px;
    width: 62vw;
    border-radius: 20px;
    font-size: 18px;
    padding: 8px;
    outline: none;
    position: relative;
    background-color: white;
    z-index: 0;
    margin-top: 2.5px;
    overflow-wrap: break-word;
    line-height: 1;
    /* margin-bottom: 0; */
  }

  .input_comment input::placeholder {
    font-size: 20px;
  }

  #comment {
    width: 30px;
    height: 30px;
    background-color: rgba(0, 0, 2, 0.726);
    position: absolute;
    opacity: 0;
  }

  .input_comment_right {
    position: relative;
    margin-left: 10px;
    z-index: 0;
  }

  .input_comment_right img {
    height: 37px;
    background-color: #1777f9;
    padding: 1px;
    border-radius: 50%;
    margin-top: 0;
  }

  .reply_to_comments .reply_user:nth-child(-n + var(--boxes-to-show)) {
    display: block;
    /* Show the boxes up to the value of --boxes-to-show */
  }

  /* End of CSS for comments, likes and favorites */
</style>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
  <title>EntreeFox | Home</title>
  <link rel="stylesheet" href="<?= ROOT ?>CSS/notification_stylesheet_V2.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playwrite+IS:wght@100..400&display=swap" rel="stylesheet">
  <link rel="shortcut icon" href="<?= ROOT ?>Images/LOGO.PNG" type="image/x-icon">
</head>

<body>
  <section class="notification" id="notif_div">
    <div class="loader_container" id="loader">
      <div class="loader">
      </div>
    </div>
    <div class="notif_content" id="notif_div_content">
    </div>

  </section>
  <section class="error" id="error">
    <div class="error_box">
      <i class="fa-solid fa-xmark" id="cancel_error_alert"></i>
      <h3 id="error_content"></h3>
    </div>
  </section>
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
  <div class="menu" id="menu">
    <i class="fa-solid fa-bars custom-icon" id="navigation"></i>
  </div>
  <?php
  include("navigation.php");
  ?>
  <header id="header">

    <div class="left">
      <img src="<?= ROOT ?>Images/LOGO.PNG" alt="">
      <h1>
        ENTREEFOX.COM
      </h1>
    </div>
    <div class="right">
      <i class="fa-regular fa-heart custom-icon2" id="notification"></i>
      <i class="fa-sharp fa-solid fa-compass custom-icon2"></i>
      <i class="fa-regular fa-message custom-icon2"></i>
    </div>

  </header>
  <div class="container">
    <form id="post" enctype="multipart/form-data">
      <div class="followers">
        <div class="add_story">
          <div class="box">
            <i class="fa-solid fa-plus custom-icon2 add"></i>
            <input type="file" accept=".jpg, .jpeg,.png, .mp4, .mov, .avi" name="story" id="story">
          </div>
          <p>Add Story</p>
        </div>

        <?php
        $following = $user->get_following($_SESSION['entreefox_userid']);
        if ($following !== null) {
          foreach ($following as $Follow) {
            $follow_user_data = $user->get_user($Follow['userid']);
            if ($follow_user_data !== false) {
              include("followers.php");
            }
          }
        }
        ?>
      </div>
      <div class="post_div">
        <div class="post">
          <div class="post_top">
            <div class="post_profile" style="background-image: url(
            <?php if (isset($user_data["profile_image"]) && file_exists($user_data["profile_image"])) {
              echo $user_data['profile_image'];
            } else {
              echo 'Images/profile.png';
            } ?>
            );">
            </div>
            <textarea maxlength="150" rows="1" name="text" id="post_text" placeholder="Write something here..."></textarea>
          </div>
          <div class="post_buttom">
            <div class="inputs">
              <div class="post_image">
                <i class="fa-regular fa-image post_icon"></i>
                <input type="file" name="image" id="image" accept=".jpg, .jpeg,.png">
              </div>
              <div class="post_video">
                <i class="fa-solid fa-video post_icon"></i>
                <input type="file" name="video" id="video" accept=".mp4, .mov, .avi">
              </div>
            </div>
            <div class="btn">
              <span class="cover"></span>
              <button type="submit" id="submit_post_btn">Share <span class="loader" id="post_loader"></span></button>
            </div>
          </div>
        </div>
      </div>
    </form>
    <div class="posts" id="posts">
      <?php
      $result = $user->get_following_post($_SESSION['entreefox_userid'], 0);
      if ($result) {
        $i = 0;
        foreach ($result as $row) {
          $User_data = $user->get_user($row['userid']);
          if ($row['userid'] === $_SESSION['entreefox_userid']) {
            include("Posts.php");
          } else {
            include("friends_posts.php");
          }
        }
      }
      ?>
    </div>
    <div class="load_more_posts">
      <div class="load_more_posts_loader" id="more_posts">

      </div>
    </div>
    <footer>

    </footer>
  </div>




  <script>
    const header = document.getElementById("header");
    var lastScrollTop = header.clientHeight;
    window.addEventListener('scroll', () => {

      const currentScrollTop = window.pageYOffset || document.documentElement.scrollTop;

      if (currentScrollTop > lastScrollTop && currentScrollTop > header.clientHeight) {
        // Scrolling down
        header.style.top = '-100px'; // Hide the header (adjust height as needed)
      } else if (currentScrollTop <= header.clientHeight) {
        header.style.top = '0';
      } else if (currentScrollTop == 0) {
        header.style.top = '0'; // Show the header
      } else {
        // Scrolling up
        header.style.top = '0'; // Show the header
      }

      lastScrollTop = currentScrollTop;
    });
    let scrollPosition = 0;
    let scrollPosition_nav = 0;
    let scrollPosition_comments = 0;
    let observed = false;

    function resize_large_vid() {
      const posts = document.getElementById('posts');
      const videos = posts.querySelectorAll('.VIDS');
      videos.forEach(vid => {
        if (vid.clientHeight > window.innerHeight) {
          const id = vid.getAttribute("data-id");
          // console.log(vid.id + "is too large for this screen");
          document.getElementById(`video${id}img_container`).style.maxHeight = "75vh";
        }
      });
    }

    function check_double_play() {
      const posts = document.getElementById('posts');
      const videos = posts.querySelectorAll('.VIDS');
      const payButtons = posts.querySelectorAll('.paper');

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
    }

    check_double_play();

    let clickTimeout = 500;
    const more_posts = document.getElementById('more_posts');
    let id = 0;
    let ids = [];
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting && observed === false) {
          observed = true;
          ids = [];
          document.getElementById('posts').querySelectorAll('.person1_Box').forEach(single_post => {
            ids.push(single_post.getAttribute('data-id'));
          })
          id = Math.min(...ids);
          console.log(ids);

          get_more_posts(id);
        }
      });
    }, {
      threshold: 0.9
    });



    observer.observe(more_posts);

    async function get_more_posts(id) {
      try {
        // Send form data to the backend using POST
        const response = await fetch(`<?= ROOT ?>get_more_posts.php?id=${id}`, {});
        // Handle the response
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }


        const result = await response.text(); // Assuming backend sends JSON response
        if (!result) {
          document.querySelector('.load_more_posts').style.display = "none";
        } else {
          //   document.getElementById('error').style.display = "flex";
          // document.getElementById('error_content').innerText = result;
          const posts = document.getElementById('posts');

          const tempDiv = document.createElement('div');
          tempDiv.innerHTML = result;

          const scripts = tempDiv.querySelectorAll('script');
          const videos = tempDiv.querySelectorAll('video');
          videos.forEach(video => {
            observeVisibility(video);
          });
          scripts.forEach(script => {
            const newScript = document.createElement('script');
            if (script.src) {
              if (document.querySelector(`script[src="${script.src}"]`)) return;
              newScript.src = script.src;
              newScript.async = script.async; // Maintain async attribute if present
            } else {
              if (script.textContent && document.body.innerHTML.includes(script.textContent)) return;
              newScript.textContent = script.textContent;
            }
            document.body.appendChild(newScript);
            // loadedScripts.push({
            //   src: script.src || null,
            //   content: script.src ? null : script.textContent
            // });
            script.remove();
          });
          tempDiv.classList.add("posts");
          posts.appendChild(tempDiv);
          check_double_play();
          observed = false;
        }
      } catch (error) {
        document.getElementById('error').style.display = "flex";
        document.getElementById('error_content').innerText = error;
      }
    }


    function observeVisibility(targetDiv) {
      const observer = new IntersectionObserver(
        (entries) => {
          entries.forEach(entry => {
            if (!entry.isIntersecting) {
              if (targetDiv.play) {
                targetDiv.pause();
                const video_id = targetDiv.getAttribute('data-id');
                const playButton = document.getElementById(`play${video_id}_pause`);
                playButton.style.opacity = 1;
                playButton.classList.add('fa-circle-play');
                playButton.classList.remove('fa-circle-pause');
                const loading_container = document.getElementById(`loading${video_id}-container`)
                loading_container.style.display = 'none';
              }
            }
          });
        }, {
          threshold: 0.33
        } // Trigger when any part of the element is visible or not
      );

      // Start observing the target div
      observer.observe(targetDiv);
    }



    let loadedScripts = [];
    const nav = document.getElementById('nav');
    const textarea = document.getElementById('post_text');
    const notif_div = document.getElementById('notif_div');
    const submit_post_btn = document.getElementById('submit_post_btn');
    let submitted = false;
    let evaluated_form_data = {};
    document.querySelector('.container').style.paddingTop = `${(document.getElementById('header').clientHeight + 10)}px`;
    /////////////////////////////////////////////////////////

    document.getElementById('navigation').addEventListener('click', function() {
      const icon = document.getElementById('navigation');
      if (icon.classList.contains('fa-bars')) {
        // Switch from fa-bars to fa-xmark
        icon.classList.remove('fa-bars');
        icon.classList.add('fa-xmark');
        open_nav();
      } else {
        if (nav.style.width === "100dvw") {
          close_nav();
        } else if (notif_div.style.height === "100dvh") {
          close_notif();
        }
        // Switch back from fa-xmark to fa-bars
        icon.classList.remove('fa-xmark');
        icon.classList.add('fa-bars');
      }
      // Add animation effects
      icon.classList.add('rotate', 'fade');

      // Remove animation classes after the transition
      setTimeout(() => {
        icon.classList.remove('rotate', 'fade');
      }, 300); // Match transition duration
    });

    //////////////////////////////////////////////////////////////////////////////////////////////////////

    document.getElementById('notification').addEventListener('click', async function() {
      const icon = document.getElementById('navigation');
      if (icon.classList.contains('fa-bars')) {
        // Switch from fa-bars to fa-xmark
        icon.classList.remove('fa-bars');
        icon.classList.add('fa-xmark');
      }
      // Add animation effects
      icon.classList.add('rotate', 'fade');

      // Remove animation classes after the transition
      setTimeout(() => {
        icon.classList.remove('rotate', 'fade');
      }, 300);
      const notif_div = document.getElementById('notif_div');
      const notif_div_content = document.getElementById('notif_div_content');
      // notif_div.style.height = `${(window.innerHeight - menu.clientHeight)}px`;
      notif_div.style.height = "100dvh";
      try {
        // Send form data to the backend using POST
        const response = await fetch("<?= ROOT ?>notification_V2.php", {
          method: 'GET'
        });
        // Handle the response
        if (!response.ok) {
          document.getElementById('loader').style.display = "none";
          throw new Error("Network response was not ok");
        }


        const result = await response.text(); // Assuming backend sends JSON response
        if (!result) {
          notif_div.style.height = "0";
          loadedScripts.forEach(script => {
            document.body.removeChild(script);
          });
          loadedScripts = [];
        } else {

          notif_div_content.innerHTML = result;
          const scripts = notif_div_content.querySelectorAll('script');
          document.getElementById('loader').style.display = "none";
        }
      } catch (error) {
        document.getElementById('error').style.display = "flex";
        document.getElementById('error_content').innerText = error;
      }
    });

    //////////////////////////////////////////////////////////////////

    document.getElementById("cancel_error_alert").addEventListener('click', function() {
      document.getElementById('error').style.display = "none";
    });

    ////////////////////////////////////////////////////////////////////////////////////

    async function post() {
      const formData = new FormData();
      document.querySelectorAll('#post input').forEach(input => {
        formData.append(input.name, input.files[0]);
      });
      formData.append(textarea.name, textarea.value);

      // console.log(formData);

      try {
        // Send form data to the backend using POST
        const response = await fetch("<?= ROOT ?>Server_side/post_to_class.php", {
          method: 'POST',
          body: formData,
        });
        // Handle the response
        if (!response.ok) {
          document.getElementById('post_loader').style.display = "none";
          throw new Error("Network response was not ok");
        }


        const result = await response.text(); // Assuming backend sends JSON response
        if (!result) {
          document.getElementById('post_loader').style.display = "none";
          submitted = false;
          document.querySelectorAll('#post input').forEach(input => {
            input.value = "";
          });
          textarea.value = "";
        } else if (result === "Successful") {
          document.querySelectorAll('#post input').forEach(input => {
            input.value = "";
          });
          textarea.value = "";
          submitted = false;
          document.getElementById('post_loader').style.display = "none";
          alert("Successfully added post.");
        } else {
          document.querySelectorAll('#post input').forEach(input => {
            input.value = "";
          });
          textarea.value = "";
          document.getElementById('error').style.display = "flex";
          document.getElementById('error_content').innerText = result;
          document.getElementById('post_loader').style.display = "none";
          submitted = false;
        }
      } catch (error) {
        document.querySelectorAll('#post input').forEach(input => {
          input.value = "";
        });
        textarea.value = "";
        submitted = false;
        document.getElementById('post_loader').style.display = "none";
        document.getElementById('error').style.display = "flex";
        document.getElementById('error_content').innerText = error;
      }
    }

    function close_notif() {
      notif_div.style.height = "0";
    }

    function open_nav() {
      scrollPosition_nav = window.pageYOffset || document.documentElement.scrollTop;
      document.body.classList.add('no-scroll');
      document.body.style.top = `-${scrollPosition_nav}px`;
      nav.style.width = "100dvw";
      nav.style.right = "0";
    }

    function close_nav() {
      document.body.classList.remove('no-scroll');
      document.body.style.top = '';
      window.scrollTo(0, scrollPosition_nav);
      nav.style.width = "1px";
      nav.style.right = "-1px";
    }
    textarea.addEventListener('input', () => {
      textarea.style.height = 'auto';
      textarea.style.height = textarea.scrollHeight + 'px';
    });
    // Trigger resize on page load for pre-filled content
    textarea.dispatchEvent(new Event('input'));


    document.getElementById('post').addEventListener('submit', function(event) {
      event.preventDefault();
      submitted = true;
      const loader = document.getElementById('post_loader');
      submit_post_btn.style.opacity = 0.5;
      submit_post_btn.style.zIndex = -1;
      loader.style.display = "inline-block";
      post();
    })
    document.querySelectorAll('#post input').forEach(input => {
      switch (input.name) {
        case "story":
          input.addEventListener('change', () => {
            if (input.files.length > 0) {
              document.getElementById("image").style.zIndex = -1;
              document.getElementById("video").style.zIndex = -1;
            }
          });

          break;
        case "image":
          input.addEventListener('change', () => {
            if (input.files.length > 0) {
              document.getElementById("story").style.zIndex = -1;
              document.getElementById("video").style.zIndex = -1;
            }
          });
          break;
        case "video":
          input.addEventListener('change', () => {
            if (input.files.length > 0) {
              document.getElementById("story").style.zIndex = -1;
              document.getElementById("image").style.zIndex = -1;
            }
          });
          break;
        default:
          break;
      }
    });

    function update_btn() {
      const cover_btn = document.querySelector('.btn .cover');
      if (submitted === false) {
        let allEmpty = true;
        document.querySelectorAll('#post input').forEach(input => {
          if (input.files.length > 0) {
            allEmpty = false;
          }
        });
        if (textarea.value !== "" && allEmpty === true) {
          allEmpty = false;
        } else if (textarea.value === "" && allEmpty === true) {
          allEmpty = true;
        }
        if (allEmpty) {
          submit_post_btn.style.opacity = 0.5;
          cover_btn.style.display = "block";
        } else {
          submit_post_btn.style.opacity = 1;
          cover_btn.style.display = "none";
        }
      }
    }

    function close_like(e) {
      if (e.target.closest('.LIKES_BOX') || e.target.closest('.input_comment')) {} else {
        // alert('bad');
        document.getElementById('LIKES-CONTAINER').classList.remove("view_likes");
        document.getElementById('LIKES_BOX').classList.remove("view_likes_box");
        document.getElementById('LIKES_BOX').style.height = 0;
        document.body.classList.remove('no-scroll');
        document.body.style.top = '';
        if (scrollPosition > 0) {
          window.scrollTo(0, scrollPosition);
        }
        if (scrollPosition_nav > 0) {
          window.scrollTo(0, scrollPosition_nav);
        }
        if (scrollPosition_comments > 0) {
          window.scrollTo(0, scrollPosition_comments);
        }
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
      }
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
          // link = "<?= ROOT ?>User_profile"
          // history.pushState(null, "", link);
          newHeight = 50;
          setTimeout(() => {
            document.body.classList.remove('no-scroll');
            document.body.style.top = '';
            if (scrollPosition > 0) {
              window.scrollTo(0, scrollPosition);
            }
            if (scrollPosition_nav > 0) {
              window.scrollTo(0, scrollPosition_nav);
            }
            if (scrollPosition_comments > 0) {
              window.scrollTo(0, scrollPosition_comments);
            }
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

    async function fetchlikes(event, these) {
      event.preventDefault();
      scrollPosition = window.pageYOffset || document.documentElement.scrollTop;
      try {
        document.getElementById('loading-container').style.display = "flex";
        document.getElementById('LIKES-CONTAINER').classList.add("view_likes");
        document.getElementById('LIKES_BOX').classList.add("view_likes_box");
        document.getElementById('LIKES_BOX').style.height = "50dvh";
        document.getElementById('LIKES_BOX-BOTTOM').innerHTML = "";
        const resizableDiv = document.getElementById('LIKES_BOX');
        resizableDiv.addEventListener('mousedown', startResize);
        resizableDiv.addEventListener('touchstart', startResize);
        let link = these.getAttribute('href');
        document.body.classList.add('no-scroll');
        document.body.style.top = `-${scrollPosition}px`;
        // Send form data to the backend using POST
        const response = await fetch(link, {});
        // Handle the response
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        const result = await response.text(); // Assuming backend sends JSON response
        if (!result) {

        } else {
          const messageContainer = document.getElementById('LIKES_BOX-BOTTOM');
          messageContainer.innerHTML = result;
          document.getElementById('loading-container').style.display = "none";
        }
      } catch (error) {
        document.getElementById('LIKES_BOX-BOTTOM').innerHTML = error;
      }
      // window.scrollTo(0, document.body.scrollHeight);
    }

    async function fetchCommentsSecondFunction(event, these) {
      event.preventDefault();
      scrollPosition_comments = window.pageYOffset || document.documentElement.scrollTop;
      try {
        let link = these.getAttribute('href');
        const url = link.split("/");
        url.splice(0, 5)
        const new_url = url.join("/");
        // link2 = window.document.location.href + `/${new_url}`;
        // history.pushState(null, "", link2);
        document.getElementById('loading-container').style.display = "flex";
        document.getElementById('LIKES-CONTAINER').classList.add("view_likes");
        document.getElementById('LIKES_BOX').classList.add("view_likes_box");
        document.getElementById('LIKES_BOX').style.height = "50dvh";
        document.getElementById('LIKES_BOX-BOTTOM').innerHTML = "";
        document.body.classList.add('no-scroll');
        document.body.style.top = `-${scrollPosition_comments}px`;
        const resizableDiv = document.getElementById('LIKES_BOX');
        resizableDiv.addEventListener('mousedown', startResize, { passive: true });
        resizableDiv.addEventListener('touchstart', startResize, { passive: true });
        if (loadedScripts.forEach) {
          loadedScripts.forEach(script => {
            document.body.removeChild(script);
          });
        }
        loadedScripts = [];
        const response = await fetch(link, {});
        // Handle the response
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        const result = await response.text(); // Assuming backend sends JSON response
        if (!result) {

        } else {
          const messageContainer = document.getElementById('LIKES_BOX-BOTTOM');
          messageContainer.innerHTML = result;
          document.getElementById('loading-container').style.display = "none";
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
        }
      } catch (error) {
        document.getElementById('LIKES_BOX-BOTTOM').innerHTML = error;
      }
    }

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
    setInterval(() => {
      update_btn()
      resize_large_vid();
    }, 1000);
  </script>
</body>

</html>