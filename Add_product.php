<?php
include("autoload.php");
// $_GET['']
$login = new Login();
$first_visit = $login->check_new_user();
if ($first_visit === true) {
    header("Location: Welcome");
    die;
}

if (isset($_SESSION['entreefox_userid']) && is_numeric($_SESSION['entreefox_userid'])) {

    $id = $_SESSION['entreefox_userid'];
    $login = new Login();
    $result = $login->check_login($id);
    // $answer_id = "";
    // $post_ID = "";
    // $Error = "";
    $shopping = new Shopping();
    //   $Phones = $shopping->get_specific_products("phone");
    //   if (isset($_GET['Search'])) {
    //     $DB = new Database();
    //     $find = addslashes($_GET['Search']);
    //     $sql2 = "select * from products where product_name like '%$find%' || product_type like '%$find%'";
    //     $PROduct = $DB->read($sql2);
    //   }
    // if (isset($URL[1]) && isset($URL[2]) && is_numeric($URL[1]) && is_numeric($URL[2])) {
    //     $PROduct = $shopping->get_user_products($URL[2], $URL[1]);
    //     $shop_info = $shopping->get_shop_info($URL[1]);
    //     if (isset($URL[3]) && !isset($_GET['Search']) && is_numeric($URL[3])) {
    //         $DB = new Database();
    //         $product_info = $shopping->get_product($URL[3], $URL[1]);
    //         $find = $product_info[0]['product_name'];
    //         $find2 = $product_info[0]['product_type'];
    //         $sql2 = "select * from products where userid = '$URL[2]' && product_name like '%$find%' || product_type like '%$find2%' && productid != '$URL[3]'";
    //         $PROduct = $DB->read($sql2);
    //     } else {
    //         if (isset($_GET['Search'])) {
    //             $DB = new Database();
    //             $find = addslashes($_GET['Search']);
    //             $sql2 = "select * from products where product_name like '%$find%' || product_type like '%$find%' && userid = '$URL[2]'";
    //             $PROduct = $DB->read($sql2);
    //         }
    //     }
    // }

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
    <!-- <link rel="stylesheet" href="<?= ROOT ?>CSS/Orders_stylesheet.css" /> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            border: 0;
            margin: 0;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            scrollbar-width: 0;
        }

        ::-webkit-scrollbar {
            display: none;
        }

        html {
            height: -webkit-fill-available;
            background-color: rgb(240, 240, 240);
        }

        body {
            background-color: rgb(240, 240, 240);
        }

        header {
            display: flex;
            position: fixed;
            /* flex-direction: column; */
            /* justify-content: space-between; */
            align-items: center;
            width: 90dvw;
            /* height: 50px; */
            padding: 1rem 5dvw;
            top: 0;
            background-color: white;
            z-index: 50;
            transition: 0.3s;
        }

        header i {
            font-size: 40px;
        }

        header .left a {
            color: black;
        }

        header h1 {
            /* padding-left: 15px; */
            line-height: 1;
        }

        .no-scroll {
            overflow: hidden;
        }

        .left {
            display: flex;
            /* align-items: center; */
            gap: 5dvw;
        }

        form {
            width: 90dvw;
            padding: 2rem 5dvw;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2rem;
        }

        section {
            position: relative;
            /* padding-bottom: 5%; */
            display: flex;
            flex-direction: column;
            /* transition: height 0.25s ease-in; */
            height: auto;
            width: 100%;
            border-radius: 20px;
            /* overflow-x: hidden;/ */
        }

        .input {
            border-radius: 20px;
            display: flex;
            align-items: center;
            border: 1px solid black;
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            padding: 0.5rem 5vw;
            background-color: white;
            gap: 1dvw;
            position: static;
            z-index: 2;
        }

        .textarea {
            align-items: start;
        }

        .input input {
            font-size: 1.2rem;
            background-color: white;
            height: fit-content;
            /* width: 70vw; */
            flex-grow: 1;
            outline: none;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }

        .input textarea {
            font-size: 1.2rem;
            background-color: white;
            height: fit-content;
            /* width: 70vw; */
            flex-grow: 1;
            outline: none;
            resize: none;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }

        .input select {
            font-size: 1.2rem;
            background-color: white;
            /* height: fit-content; */
            width: fit-content;
            width: 100%;
            flex-grow: 1;
            outline: none;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            color: black;
            overflow-wrap: break-word;
            /* background-color: bisque; */
        }

        .input select option {
            color: black;
            overflow-wrap: break-word;
        }

        /* .input select {
            height: fit-content;
            width: 70vw;
            outline: none;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        } */

        .input i {
            /* scale: 1.5; */
            padding: 0 0.4rem;
            font-size: 1.5rem;
            /* background-color: blue; */
            border-right: 2px solid black;
        }

        /* .input select {
            font-size: 1.2rem;
            background-color: white;
            border-left: 2px solid black;
        } */

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

        section .input_error {
            border-radius: 20px;
            overflow: hidden;
            position: absolute;
            height: 100%;
            width: 80dvw;
            padding: 0 5dvw;
            z-index: 1;
            /* background-color: bisque; */
            background-color: bisque;
            /* opacity: 0.5; */
        }

        section .input_error p {
            transition: padding-top 0.25s ease-in-out;
            /* padding-top: 50%; */
            color: red;
            /* padding: 0.1rem 1rem; */
            font-size: 0.8rem;
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }

        .pictures {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 1rem;
        }

        .pictures .parent {
            position: relative;
        }

        .pictures i {
            font-size: 2rem;
            background-color: white;
            padding: 1rem;
            border-radius: 50%;
            border: 1px solid black;
        }

        .pictures input {
            position: absolute;
            opacity: 0;
            right: 0;
            left: 0;
            margin: auto;
            width: 100%;
            height: 100%;
            border-radius: 50%;
        }

        .pictures p {
            color: red;
            font-weight: bold;

        }

        .page_loader {
            height: 100dvh;
            width: 100dvw;
            background-color: white;
            opacity: 0.3;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 60;
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
            width: 80%;
        }

        .button {
            transition: all 0.2s;
            position: relative;
            /* background-color: aqua; */
        }

        #sign_in_btn .loader {
            background-image: conic-gradient(black, white);
            padding: 1rem;
            border-radius: 50%;
            position: relative;
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

        .images img {
            width: 75vw;
            padding-left: 5vw;
            flex-shrink: 0;
            user-select: none;
        }

        .images img:nth-of-type(1) {
            padding-left: 5vw;
        }

        .images_container {
            overflow: hidden;
            /* Hide overflow */
            position: relative;
            cursor: grab;
            width: 90vw;
            background-color: rgb(231, 231, 231);
            padding: 3vh 0;
        }

        .images {
            /* background-color: aqua; */
            /* overflow-x: scroll; */
            display: flex;
            gap: 3dvw;
            transition: transform 0.3s ease;
            /* width: 100vw; */
            /* padding-right: 10vw; */
        }

        .img_parent {
            position: relative;
        }

        .img_parent .img {
            width: 85dvw;
            height: 85dvw;
            /* padding-left: 5vw; */
            flex-shrink: 0;
            user-select: none;
            background-repeat: no-repeat;
            background-position: center;
            background-clip: border-box;
            background-size: cover;
        }

        .image-description {
            position: absolute;
            display: flex;
            justify-content: center;
            align-items: center;
            bottom: 0;
            /* Adjust based on your preference */
            right: 0;
            transform: translateX(-10%);
            background: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 10px;
            height: 25px;
            width: 25px;
            border-radius: 50%;
            font-size: 10px;
        }

        .cancel_img {
            font-size: 2rem;
            color: white;
        }

        .cancel_img_div {
            height: 3rem;
            width: 3rem;
            display: flex;
            justify-content: center;
            align-items: center;
            position: absolute;
            top: 1rem;
            right: 0;
            background-color: rgba(0, 0, 0, 0.3);
            border-radius: 50%;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            cursor: pointer;
        }

        .invlid_img {
            height: 100%;
            width: 100%;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .invlid_img p {
            color: red;
            font-size: 2rem;
            font-weight: bold;
            text-align: center;
        }

        .error_container {
            height: 100dvh;
            width: 100dvw;
            -webkit-backdrop-filter: blur(5px);
            backdrop-filter: blur(5px);
            display: none;
            align-items: center;
            justify-content: center;
            background-color: rgba(0, 0, 0, 0.308);
            position: fixed;
            top: 0;
            right: 0;
            z-index: 10;
        }

        .error_div {
            width: 80dvw;
            padding: 1rem 5dvw;
            background-color: white;
            border-radius: 20px;
            height: auto;
            max-height: 80dvw;
            overflow: scroll;
        }
    </style>
</head>

<body>
    <header id="header">
        <div class="left">
            <a href="<?= ROOT ?>Home"><i class="fa-classic fa-solid fa-arrow-left fa-fw"></i></a>
            <h1>Add New Product</h1>
        </div>
    </header>
    <section class="page_loader" id="page_loader">
    </section>
    <div class="error_container" id="error">
        <div class="error_div">
            <p></p>
        </div>
    </div>
    <div class="container">
        <form id="new_product" enctype="multipart/form-data">
            <section>
                <div class="input" id="name">
                    <i class="fa-solid fa-user"></i>
                    <input required autocomplete="cc-family-name" type="text" placeholder="Product name" id="Product_Name" name="Product_name" />
                </div>
                <div class="input_error" id="Product_name_error">
                    <p id="Product_name_error_p" class="error"></p>
                </div>
            </section>
            <section>
                <div class="input" id="name">
                    <i class="fa-solid fa-user"></i>
                    <select required name="Product_category" id="Product_category">
                        <option value="Product Category" selected disabled>Product Category</option>
                        <option data-category="Appliances" value="Home Appliances">Home Appliances</option>
                        <option data-category="Kitchen" value="Home & Kitchen">Home & Kitchen</option>
                        <option data-category="Home" value="Home">Home</option>
                        <option data-category="Office" value="Office Products">Office Products</option>
                        <option data-category="Phones" value="Mobile Phones">Mobile Phones</option>
                        <option data-category="Tablets" value="Tablets">Tablets</option>
                        <option data-category="Phone_accessories" value="Mobile Phone Accessories">Mobile Phone Accessories</option>
                        <option data-category="Men_fashion" value="Men's Fashion">Men's Fashion</option>
                        <option data-category="Women_fashion" value="Women's Fashion">Women's Fashion</option>
                        <option data-category="Kid_fashion" value="Kid's Fashion">Kid's Fashion</option>
                        <option data-category="Watches" value="Watches">Watches</option>
                        <option data-category="Luggage" value="Luggage & Travel Gear">Luggage & Travel Gear</option>
                        <option data-category="Makeup" value="Makeup">Makeup</option>
                        <option data-category="Fragrances" value="Fragrances">Fragrances</option>
                        <option data-category="Hair_care" value="Hair Care">Hair Care</option>
                        <option data-category="Personal_care" value="Personal Care">Personal Care</option>
                        <option data-category="Oral_care" value="Oral Care">Oral Care</option>
                        <option data-category="Health_care" value="Health Care">Health Care</option>
                        <option data-category="Television" value="Television & Video">Television & Video</option>
                        <option data-category="Home_audio" value="Home Audio">Home Audio</option>
                        <option data-category="Camera" value="Camera & Photo">Camera & Photo</option>
                        <option data-category="Generators" value="Generators & Portable Power">Generators & Portable Power</option>
                        <option data-category="Computers" value="Computers">Computers</option>
                        <option data-category="Data_storage" value="Data Storage">Data Storage</option>
                        <option data-category="Antivirus" value="Antivirus & Security">Antivirus & Security</option>
                        <option data-category="Printers" value="Printers">Printers</option>
                        <option data-category="Computer_accessories" value="Computer Accessories">Computer Accessories</option>
                        <option data-category="Beer" value="Beer, Wine & Sprits">Beer, Wine & Sprits</option>
                        <option data-category="Food" value="Food Cupboard">Food Cupboard</option>
                        <option data-category="Household" value="Household Cleaning">Household Cleaning</option>
                        <option data-category="Car_care" value="Car Care">Car Care</option>
                        <option data-category="Car_electronics" value="Car Electronics & Accessories">Car Electronics & Accessories</option>
                        <option data-category="Light" value="Light & Lightning Accessories">Light & Lightning Accessories</option>
                        <option data-category="Exterior" value="Exterior Accessories">Exterior Accessories</option>
                        <option data-category="Oil_fluids" value="Oil & Fluids">Oil & Fluids</option>
                        <option data-category="Interior" value="Interior Accessories">Interior Accessories</option>
                        <option data-category="Tyre" value="Tyre & Rim">Tyre & Rim</option>
                        <option data-category="Cardio" value="Cardio Training">Cardio Training</option>
                        <option data-category="Strength" value="Strength Training">Strength Training</option>
                        <option data-category="Sporting" value="Sporting Accessories">Sporting Accessories</option>
                        <option data-category="Team" value="Team Sports">Team Sports</option>
                        <option data-category="Outdoor" value="Outdoor & Adventure">Outdoor & Adventure</option>
                        <option data-category="Playstation" value="Playstation">Playstation</option>
                        <option data-category="Xbox" value="Xbox">Xbox</option>
                        <option data-category="Nintendo" value="Nintendo">Nintendo</option>
                        <option data-category="Apperel" value="Apperel & Accessories">Apperel & Accessories</option>
                        <option data-category="Diapering" value="Diapering">Diapering</option>
                        <option data-category="Feeding" value="Feeding">Feeding</option>
                        <option data-category="Toddler_toys" value="Baby & Toddler Toys">Baby & Toddler Toys</option>
                        <option data-category="Baby_gear" value="Baby Gear">Baby Gear</option>
                        <option data-category="Baby_care" value="Baby Bathing & Skin Care">Baby Bathing & Skin Care</option>
                        <option data-category="Potty" value="Potty Training">Potty Training</option>
                        <option data-category="Baby_safety" value="Baby Safety Products">Baby Safety Products</option>
                    </select>
                </div>
                <div class="input_error" id="Product_category_error">
                    <p id="Product_category_error_p" class="error"></p>
                </div>
            </section>
            <section>
                <div class="input" id="name">
                    <i class="fa-solid fa-user"></i>
                    <select required name="Type" id="Type">
                        <option style="color: bisque;" value="Product Type" selected disabled>Product Type</option>
                    </select>
                </div>
                <div class="input_error" id="Type_error">
                    <p id="Type_error_p" class="error"></p>
                </div>
            </section>
            <section>
                <div class="input" id="name">
                    <i class="fa-solid fa-user"></i>
                    <input required value="1" autocomplete="cc-family-name" type="number" min="1" placeholder="Product Quantity" name="Product_quantity" />
                </div>
                <div class="input_error" id="Product_quantity_error">
                    <p id="Product_quantity_error_p" class="error"></p>
                </div>
            </section>
            <section>
                <div class="input" id="name">
                    <i class="fa-solid fa-user"></i>
                    <input required value="500" autocomplete="cc-family-name" type="number" min="500" placeholder="Product Price" name="Product_price" />
                </div>
                <div class="input_error" id="Product_price_error">
                    <p id="Product_price_error_p" class="error"></p>
                </div>
            </section>
            <section>
                <div class="input textarea" id="name">
                    <i class="fa-solid fa-user"></i>
                    <textarea required autocomplete="cc-family-name" name="Description" id="description" placeholder="Product Description" rows="5" maxlength="350"></textarea>
                    <!-- <input required autocomplete="cc-family-name" type="number" min="1" placeholder="Product Quantity" id="Product_quantity" name="Product_quantity" /> -->
                </div>
                <div class="input_error" id="Description_error">
                    <p id="Description_error_p" class="error"></p>
                </div>
            </section>
            <section>
                <div class="pictures">
                    <div class="parent">
                        <i class="fa-solid fa-camera"></i>
                        <input required id="file" type="file" name="Product_pic[]" accept=".jpg, .jpeg, .png" multiple data-max-files="3">
                    </div>
                    <p id="file_error" class="error"></p>
                </div>
                <!-- <div class="input_error" id="name_error">
                    <p></p>
                </div> -->
            </section>
            <section id="preview">

            </section>
            <button type="submit" class="button" id="sign_in_btn">
                <span class="button_text">Add</span>
                <div class="loader"></div>
            </button>

        </form>
        </form>
    </div>
    <script>
        const textarea = document.getElementById('description');

        textarea.addEventListener('input', () => {
            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight + 'px';
        });
        // Trigger resize on page load for pre-filled content
        textarea.dispatchEvent(new Event('input'));
        const productCategory = document.getElementById("Product_category");
        const productType = document.getElementById("Type");
        document.addEventListener("DOMContentLoaded", function() {

            // Store all product types in a JavaScript object
            const typeOptions = {
                Appliances: ["Large Appliances", "Small Appliances"],
                Kitchen: ["Cookware", "Small Appliances", "Bakeware", "Cutlery & Knife Accessories"],
                Home: ["Bedding", "Home Decorations", "Kitching & Dining", "Wall Art", "Vacuums & Floor Care", "Home Furniture", "Arts, Crafts & Sewing", "Bath", "Storage & Organization", "Lighting", "Stationary"],
                Office: ["Office & School Supplies", "Office Electronics", "Office Furniture", "Packaging Materials"],
                Phones: ["Smartphones", "Basic Phones"],
                Tablets: ["Android Tablets", "Educational Tablets", "iPads", "Tablet Accessories"],
                Phone_accessories: ["Accessory Kits", "Adapters", "Batteries", "Bluetooth Headsets", "Cables", "Car Accessories", "Earphones & Headsets", "Selfie Sticks & Tripods", "Cases", "Chargers", "Screen Protectors", "Smart Watches"],
                Men_fashion: ["Clothing", "Shoes", "Watches", "Jewelry", "Bags", "Men's Accessories", "Underwear & Sleepwear", "Traditional & Cultural Wear"],
                Women_fashion: ["Clothing", "Shoes", "Jewelry", "Handbags", "Women's Accessories", "Maternity"],
                Kid_fashion: ["Boy's Fashion", "Girl's Fashion", "Boy's Accessories", "Girl's Accessories"],
                Watches: ["Men's Watches", "Women's Watches", "Unisex Watches"],
                Luggage: ["Backpacks", "Briefcases", "Luggages", "Umbrellas", "Gym Bags", "Laptop Bags", "Luggage Sets", "Messenger bags", "Travel Accessories", "Travel Duffels", "Travel Totes", "Waist Packs"],
                Makeup: ["Concealers & Color Corrector", "Foundation", "Powder", "Lip Gloss", "Lip Liner", "Lipstick", "Eyeliner & Kajal", "Eyeshsdow", "Mascara"],
                Fragrances: ["Men's", "Women's"],
                Hair_care: ["Hair & Scalp Care", "Hair Accessories", "Hair Cutting Tools", "Shampoo & Conditional", "Wigs & Accessories"],
                Personal_care: ["Feminine Care", "Contraceptives & Lubricants", "Body"],
                Oral_care: ["Teeth Whitening", "Toothbrushes", "Toothpaste", "Breath Frsheners"],
                Health_care: ["Face Protection", "Thermometers", "Hand Sanitizers", "Lab, Safety & Work Gloves"],
                Television: ["Televisions", "QLED & OLED TVs", "LED & LCD TVs", "Smart TVs", "DVD Players & Recorders"],
                Home_audio: ["Home & Theater Systems", "Receivers & Amplifiers", "Sound Bars"],
                Camera: ["Video Survillance", "Camcorders"],
                Generators: ["Generators", "Power Inverters", "Solar & Wind Power", "Stabilizers"],
                Computers: ["Desktops", "Laptops", "Monitors"],
                Data_storage: ["External Hard Drive", "USB Flash Drives", "External Solid State Drivers"],
                Antivirus: ["Antivirus", "Internet Security"],
                Printers: ["Inkjet Printers", "Laser Printers", "Printer Ink & Toner"],
                Computer_accessories: ["Keyboards, Mice & Accessories", "Uninterrupted Power Supply (UPS)", "Memory Cards", "Battries", "Scanners", "Video Projectors"],
                Beer: ["Beers", "Champage & Sparkling Wine", "Spirits & Liquors", "Wine"],
                Food: ["Rice & Grains", "Breakfast Food", "Herbs, Spices & Seasoning", "Flours & Meals", "Coocking Oil", "Canned, Jarred & Packaged Foods", "Candy & Chocolate", "Pasta & Noodles", "Beverages"],
                Household: ["Laundry", "Toilet Paper, Wipes & Sprays", "Disinfectant Wipes", "Dishwashing", "Bathroom Cleaners", "Air Fresheners"],
                Car_care: ["Cleaning Kits", "Exterior Care", "Interior Care"],
                Car_electronics: ["Car Electronics", "Car Electronics Accessories"],
                Light: ["Bulbs", "Accent & Off Road Lighting"],
                Exterior: ["Bumber Stickers, Decals & Magnets", "Covers", "Mirrors"],
                Oil_fluids: ["Brake Fluids", "Greases& Lubricants", "Oils"],
                Interior: ["Air Freasheners", "Consoles& Organizers", "Cup Holders", "Floor Mats & Cargo Liners", "Key Chains", "Seat Covers & Accessories", "Sun Protection"],
                Tyre: ["Tyre", "Inflator & Guages"],
                Cardio: ["Exercise Bikes", "Treadmills", "Elliptical Trainers"],
                Strength: ["Core & Abdominal Trainers", "Dumbbells", "Bars"],
                Sporting: ["Exercise Bands", "Exercise Mats", "Jump Ropes", "Sport Clothing"],
                Team: ["Basketball", "Team Sport Accessories", "Tennis & Racquet Sports", "Swimming"],
                Outdoor: ["Cycling", "Running"],
                Playstation: ["PlayStation 4", "PlayStation 3", "PlayStation 2", "PlayStation", "PlayStation Vita"],
                Xbox: ["Xbox One", "Xbox 360", "Xbox", "Accessories"],
                Nintendo: ["Nintendo 3DS", "Nintendo DS", "Nintendo Switch", "Wii"],
                Apperel: ["Baby Boy's Clothing", "Baby Girl's Clothing"],
                Diapering: ["Disposable Dipers", "Diaper Bags", "Wipes & Holders", "Cloth Diapers", "Changing tables"],
                Feeding: ["Bibs & Burp Cloths", "Bottle-Feeding", "Breastfeeding", "Food Storage", "Highchairs & Booster Seats", "Pacifiers & Accessories", "Solid Feeding"],
                Toddler_toys: ["Bath toys", "Music & Sound", "Learning & Education"],
                Baby_gear: ["Backpacks & Carriers", "Swings, Jumpers & Bouncers", "Walkers"],
                Baby_care: ["Skin Care", "Washcloths & Towels", "Bathing Tubs & Seats", "Grooming & Healthcare Kits"],
                Potty: ["Potties & Seats", "Seat Covers", "Training Pants"],
                Baby_safety: ["Monitors", "Rails & Rail Guards", "Edge & Corner Guards"]
            };

            productCategory.addEventListener("change", function() {
                const selectedCategory = this.options[this.selectedIndex].dataset.category;

                // Clear the Product Type dropdown (except the first option)
                productType.innerHTML = '<option value="Product Type" selected disabled>Product Type</option>';

                // If the category has associated types, add them dynamically
                if (typeOptions[selectedCategory]) {
                    typeOptions[selectedCategory].forEach(type => {
                        let option = document.createElement("option");
                        option.value = type;
                        option.textContent = type;
                        productType.appendChild(option);
                    });
                }
            });
        });


        let evaluated = false
        const allowedTypes = ["image/png", "image/jpeg", "image/jpg"];
        const maxSize = 3 * 1024 * 1024; // 3MB in bytes
        const maxFiles = 10;
        let tracker = 0;
        let called_error_display = false;

        function evaluate(e) {
            load_content();
            document.querySelectorAll("input").forEach(input => {
                let id = `${input.name}_error`;
                switch (input.type) {
                    case "text":
                        if (input.value.length > 1) {
                            called_error_display = false;
                            close_error(id);
                        } else {
                            called_error_display = true;
                            let err_name = "Product name can't be empty";
                            display_error(err_name, id);
                            // remove_load_content();
                            // console.log(e.target.value);
                            // break;
                        }
                    case "number":
                        if (/^\d+$/.test(input.value)) {
                            called_error_display = false;
                            close_error(id)
                        } else {
                            let err_num = "";
                            if (input.name === "Product_quantity") {
                                err_num = "Invalid product quantity.";
                            } else if (input.name === "Product_price") {
                                err_num = "Invalid product price.";
                            }
                            if (err_num !== "") {
                                // remove_load_content();
                                called_error_display = true;
                                display_error(err_num, id);
                            }
                        }
                    case "file":
                        if (input.files && input.files.length > 0) {
                            called_error_display = false;
                            document.getElementById("file_error").innerText = "";
                            tracker = 0;
                            preview_imgs(input);
                        } else {
                            called_error_display = true;
                            document.getElementById("file_error").innerText = "Please input an image";
                        }
                    default:
                        break;
                }
            });
            document.querySelectorAll("select").forEach(select => {
                let id = `${select.name}_error`;
                // console.log(select.value);
                let err = `${select.value} can't be empty`;
                switch (select.name) {
                    case "Product_category":
                        if (select.value == "Product Category") {
                            called_error_display = true;
                            display_error(err, id);
                            break;
                        } else {
                            called_error_display = false;
                            close_error(id);
                        }
                    case "Type":
                        if (select.value == "Product Type") {
                            called_error_display = true;
                            display_error(err, id);
                            break;
                        } else {
                            called_error_display = false;
                            close_error(id);
                        }
                    default:
                        break;
                }

            });
            let Text_id = `${textarea.name}_error`;
            if (textarea.value.length > 10) {
                called_error_display = false;
                close_error(Text_id, "textarea");
            } else {
                called_error_display = true;
                display_error("Product description can't be empty", Text_id, "textarea")
            }
            if (document.querySelector(".images_container")) {
                check_parameters(e);
            }else{
                if(evaluated === false){
                    remove_load_content();
                }
            }

        }
        const file_input = document.getElementById("file");
        file_input.addEventListener('change', (e) => {
            tracker = 0;
            preview_imgs(e.target)
        });

        function preview_imgs(input) {
            document.getElementById("file_error").innerText = "";
            const dt = new DataTransfer();
            // console.log(input.files);

            if (input.files && input.files.length > 0) {
                const files = Array.from(input.files);
                // console.log(files);

                let preview_div = document.getElementById("preview");
                let content = `<div class="images_container">
                                        <div class="images">`;

                for (const file of input.files) {
                    tracker++;
                    if (tracker > maxFiles) {
                        files.slice(0, maxFiles).forEach(file => dt.items.add(file));

                        input.files = dt.files;
                        tracker = 0;
                        alert("The maximum amount of files that can be uploaded is 10");
                        break;
                    }
                    if (file) {


                        if (allowedTypes.includes(file.type) && file.size < maxSize) {
                            content += `<div class="img_parent">
                                                                            <div class="img" style="background-image: url(${URL.createObjectURL(file)});"></div>
                                                                <button class="cancel_img_div" onclick="remove_img(event, this)"><i class="fa-solid fa-xmark cancel_img"></i></button>
                                                                </div>`;
                        } else if (allowedTypes.includes(file.type) && file.size > maxSize) {
                            content += `<div class="img_parent">
                                                                    <div class="invlid_img">
                                                                        <p class="error">File must be below 3MB. File size: ${(file.size / 1024 / 1024).toFixed(2)}MB</p>
                                                                    </div>
                                                                    <div class="img" style="background-image: url(${URL.createObjectURL(file)});"></div>
                                                                    <button class="cancel_img_div" onclick="remove_img(event, this)"><i class="fa-solid fa-xmark cancel_img"></i></button>
                                                                </div>`;
                            // display_error("Inval")
                        } else if (!allowedTypes.includes(file.type) && file.size < maxSize) {
                            content += `<div class="img_parent">
                                                                    <div class="invlid_img">
                                                                        <p class="error">File must be of type jpg/png. File type: ${file.type}</p>
                                                                    </div>
                                                                    <div class="img" style="background-image: url(<?= ROOT ?>Images/IMG_7431.JPG);"></div>
                                                                    <button class="cancel_img_div" onclick="remove_img(event, this)"><i class="fa-solid fa-xmark cancel_img"></i></button>
                                                                </div>`;
                        }

                    }
                };
                content += `</div>
                                                 <div class="image-description">
                                                  <span id="current-image">1</span>/<span id="total-images">3</span>
                                             </div>
                                               </div>`;
                preview_div.innerHTML = content;
                slide_images()
            }
        }

        function remove_img(e, these) {
            let file_input2 = document.querySelector('input[type="file"]');
            e.preventDefault();

            clickedDiv = e.target.closest(".img_parent");
            let children = Array.from(clickedDiv.parentElement.children); // Convert HTMLCollection to array
            let index = children.indexOf(clickedDiv);
            console.log(index);
            console.log(file_input2.files);



            const files = Array.from(file_input2.files);
            files.splice(index, 1);
            const dataTransfer = new DataTransfer();
            files.forEach(file => dataTransfer.items.add(file));
            file_input2.files = dataTransfer.files;


            const section = document.getElementById("preview");
            const images = section.querySelectorAll(".images_container .images .img_parent");
            // if(images.length)
            console.log(images.length);
            if (clickedDiv.parentElement && clickedDiv.parentElement.lastElementChild === clickedDiv) {
                reset_position_last();
            }
            if (images.length === 1) {
                section.removeChild(document.querySelector(".images_container"));
                document.getElementById("file_error").innerText = "Please input an image";
            } else {
                document.querySelector(".images").removeChild(clickedDiv);
                touchEnd();
                slide_images()
            }
            console.log(file_input2.files);

            // console.log(e.target.closest(".img_parent"));
        }

        function display_error(Error, id, input_type = "normal") {
            // console.log(Error);

            // const parent = document.querySelector("section");
            let height = document.querySelector(".input").clientHeight;
            if (input_type === "textarea") {
                height = document.querySelector(".textarea").clientHeight;
            } else {
                height = document.querySelector(".input").clientHeight;
            }
            const ERR = document.getElementById(id);
            const ERR_P = document.getElementById(`${id}_p`)
            ERR_P.innerHTML = Error;
            // console.log(error.querySelector("p").innerHTML);

            ERR_P.style.paddingTop = `${height + 5}px`;
            setTimeout(() => {
                ERR.style.height = "auto";
            }, 150)
        }

        function close_error(id, input_type = "normal") {
            let height = document.querySelector(".input").clientHeight;
            if (input_type === "textarea") {
                height = document.querySelector(".textarea").clientHeight;
            } else {
                height = document.querySelector(".input").clientHeight;
            } // console.log(height);

            const ERR = document.getElementById(id);
            const ERR_P = document.getElementById(`${id}_p`)
            // console.log(ERR_P.innerHTML);
            if ((ERR_P.clientHeight - height) > height) {
                ERR_P.style.paddingTop = `${Math.abs((ERR_P.clientHeight - ERR_P.style.paddingTop.replace("px", "")) - height)}px`;
            } else {
                ERR_P.style.paddingTop = `${(ERR_P.clientHeight + 2) - height}px`;
                // console.log(Math.abs((error.querySelector("p").clientHeight - error.querySelector("p").style.paddingTop.replace("px", "")) - height));
            }

            setTimeout(() => {
                ERR.style.height = "100%";
                ERR_P.innerHTML = "";
            }, 250)
        }
        document.getElementById("new_product").addEventListener("submit", (e) => {
            e.preventDefault();
            load_content();
            evaluate(e)
        })

        function load_content() {
            const button = document.querySelector('.button');
            const page_loader = document.getElementById('page_loader');
            page_loader.style.display = "block"
            // button.style.zIndex = ;
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
        window.addEventListener("resize", () => {
            document.querySelector(".container").style.marginTop = `${document.getElementById("header").clientHeight + 10}px`;
            if (document.querySelector(".images_container")) {
                touchEnd();
            }
        })
        window.addEventListener("load", () => {
            document.querySelector(".container").style.marginTop = `${document.getElementById("header").clientHeight + 10}px`
        })
        let container = document.querySelector('.images_container') ? document.querySelector('.images_container') : "";
        let imageWrapper = document.querySelector('.images') ? document.querySelector('.images_container') : "";
        let images = document.querySelectorAll('.images .img_parent') ? document.querySelectorAll('.images .img_parent') : "";
        let currentImageElement = document.getElementById('current-image') ? document.getElementById('current-image') : "";
        let totalImagesElement = document.getElementById('total-images') ? document.getElementById('total-images') : "";

        function slide_images() {
            container = document.querySelector('.images_container');
            imageWrapper = document.querySelector('.images');
            images = document.querySelectorAll('.images .img_parent');
            currentImageElement = document.getElementById('current-image');
            totalImagesElement = document.getElementById('total-images');
            totalImagesElement.textContent = images.length; // Set the total number of images

            images.forEach((image, index) => {
                const imageWidth = image.clientWidth;

                image.addEventListener('dragstart', (e) => e.preventDefault());

                // Touch events
                image.addEventListener('touchstart', touchStart(index));
                image.addEventListener('touchend', touchEnd);
                image.addEventListener('touchmove', touchMove);

                // Mouse events
                image.addEventListener('mousedown', touchStart(index));
                image.addEventListener('mouseup', touchEnd);
                image.addEventListener('mousemove', touchMove);
                image.addEventListener('mouseleave', touchEnd);
            });

        }

        let isDragging = false;
        let startPos = 0;
        let currentTranslate = 0;
        let prevTranslate = 0;
        let animationID;
        let currentIndex = 0;
        let startY = 0; // Store the starting Y position to differentiate vertical scrolling


        function touchStart(index) {
            return function(event) {
                isDragging = true;
                currentIndex = index;
                startPos = getPositionX(event);
                startY = getPositionY(event); // Record the starting Y position
                animationID = requestAnimationFrame(animation);
                container.classList.add('grabbing');
            };
        }

        function touchEnd() {
            isDragging = false;
            cancelAnimationFrame(animationID);

            const movedBy = currentTranslate - prevTranslate;

            if (movedBy < -100 && currentIndex < images.length - 1) currentIndex += 1;
            if (movedBy > 100 && currentIndex > 0) currentIndex -= 1;

            setPositionByIndex();
            container.classList.remove('grabbing');
        }

        function touchMove(event) {
            // console.log("good");
            if (isDragging) {
                const currentPosition = getPositionX(event);
                currentTranslate = prevTranslate + currentPosition - startPos + dvwToPx(3);

                // Prevent vertical scrolling
                const currentY = getPositionY(event);
                const diffY = Math.abs(currentY - startY);
                if (diffY > 0) {
                    event.preventDefault();
                }
            }
        }

        function getPositionX(event) {
            return event.type.includes('mouse') ? event.pageX : event.touches[0].clientX;
        }

        function getPositionY(event) {
            return event.type.includes('mouse') ? event.pageY : event.touches[0].clientY;
        }

        function animation() {
            setSliderPosition();
            if (isDragging) requestAnimationFrame(animation);
        }

        function setSliderPosition() {
            imageWrapper.style.transform = `translateX(${currentTranslate}px)`;
        }

        function reset_position_last() {

            currentTranslate = currentTranslate + images[0].clientWidth;
            imageWrapper.style.transform = `translateX(${currentTranslate}px)`;
        }

        function setPositionByIndex() {
            currentTranslate = currentIndex * -(images[0].clientWidth + dvwToPx(3));
            prevTranslate = currentTranslate;
            setSliderPosition();
            updateDescription(); // Update the description after position change
        }

        function dvwToPx(dvw) {
            return (dvw * window.innerWidth) / 100;
        }

        function updateDescription() {
            currentImageElement.textContent = currentIndex + 1;
        }

        function check_parameters(event) {
            load_content();
            if(called_error_display === false){
                setTimeout(()=>{
                    let p_err = document.querySelectorAll(".error");
                    console.log(p_err.length);
        
                    // let p_err_img = document.querySelectorAll(".images_container .images .error");
                    for (let ER of p_err) {
                        // console.log(ER.textContent.trim());
                        if (ER.textContent.trim() === "") {
                            evaluated = true;
                        } else {
                            evaluated = false;
                            break;
                        }
                    }
                    if (evaluated === true) {
                        const form = event.target;
                        const formData = new FormData(form);
                        add(formData);
                    }else{
                        remove_load_content();
                    }
                }, 260)
            }
        }
        async function add(form) {

            try {
                // Send form data to the backend using POST
                const response = await fetch("<?= ROOT ?>Server_side/add_product.php", {
                    method: 'POST',
                    body: form,
                });
                // Handle the response
                if (!response.ok) {
                    remove_load_content();
                    throw new Error("Network response was not ok");
                }


                const result = await response.text(); // Assuming backend sends JSON response
                if (!result) {
                    remove_load_content();
                } else if (result === "Successful") {
                    window.location.href = "<?= ROOT ?>Profile";
                } else {
                    remove_load_content();
                    display_error2(result);
                }
            } catch (error) {
                display_error2(error);
            }
        };

        function display_error2(error) {
            const error_div = document.getElementById('error');
            error_div.style.display = "flex";
            error_div.querySelector(".error_div p").innerText = error;
            let timeout = error.length * 100;
            alert(timeout);
            setTimeout(()=>{
                error_div.style.display = "none";
            }, timeout)
        }
    </script>
</body>

</html>