<?php

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Velvet Vials</title>
        <link rel="stylesheet" href="css/style.css">
        
    </head>
    <body>
        <nav class="nav-bar">
            <a id="logo" href="index.html">Velvet Vials</a>
            <div class="nav-bar-links">
                <ul>
                    <li><a href="shop.php">Shop</a></li>
                    <li><a href="login.php">Log In</a></li>
                    <li><a href="signUp.php">Sign Up</a></li>
                </ul>
            </div>
        </nav>


<div class="slider">
    <img class="slides" src="images/banner1.png" alt="Image 1" style="width: 100%;">
    <img class="slides" src="images/banner2.png" alt="Image 2" style="width: 100%;">
    <img class="slides" src="images/banner3.png" alt="Image 3" style="width: 100%;">

    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
    <a class="next" onclick="plusSlides(1)">&#10095;</a>
</div>

        <div class="container-2">
            <div class="container-text">
                <h1 class="large-text">BECOME A MEMBER</h1><br>
                <p>Keep updated about new launches, as well as receiving member's benefits</p><br>
                <a href="signUp.php"><button class="button-style">Sign Up Now!</button></a>
                <p>Already a member? <a href="login.php">Log in</a>.</p>
            </div>
            <div id="image-container-1">
                <img src="images/member.png" alt="member Poster">
            </div>
        </div>

        <div class="container-3">
    <h1 class="big-text">Featured Products</h1>
    <div class="container-4-collection">
        
        <div class="product-item">
            <img src="images/roomspray.png" alt="Room Sprays">
            <p>Room Sprays</p><br>
            <a href="shop.php?category=room spray"><button class="shop-button">Shop Now</button></a>
        </div>
        
        <div class="product-item">
            <img src="images/limitededition.png" alt="Scented Candles">
            <p>Scented Candles</p><br>
            <a href="shop.php?category=scented candle"><button class="shop-button">Shop Now</button></a>
        </div>
        
        <div class="product-item">
            <img src="images/loyaltyprogram.png" alt="Aromatherapy">
            <p>Aromatherapy</p><br>
            <a href="shop.php?category=aromatherapy"><button class="shop-button">Shop Now</button></a>
        </div>
       
        <div class="product-item">
            <img src="images/social selection.png" alt="Perfume">
            <p>Perfumes</p><br>
            <a href="shop.php?category=perfume"><button class="shop-button">Shop Now</button></a>
        </div>
        
        <div class="product-item">
            <img src="images/trendingnow.png" alt="Fragrance Diffuser">
            <p>Fragrance Diffuser</p><br>
            <a href="shop.php?category=diffuser"><button class="shop-button">Shop Now</button></a>
        </div>
    </div>
</div>


        <div class="container-4">
            <div id="image-container-2">
                <img src="images/about1.png" alt="About Velvet Vials">
            </div>
            <div class="container-text">
                <h1>About Velvet Vials</h1>
                <p>At Velvet Vials, we craft unforgettable fragrance experiences, blending luxury and artistry in every bottle. Our curated collection ranges from delicate florals to deep musks, designed to evoke emotion and elegance. 
                    With our exclusive membership tiers, you unlock early access to new scents, exclusive discounts, and VIP perks like complimentary samples and invitations to special events. Discover the world of luxury fragrances at Velvet Vials—where every scent tells a story.</p>
                <h1>Velvet Vials Frontman</h1>
                <p>1) AIZATUL SUFI BINTI SULAIMAN SHAH</p>
                <p>2022862416</p>
                <p>2) NUR AIN SYUHADA BINTI MOHD NASIR</p>
                <p>2022887518</p>
                <p>3) ANIS YASMIN BINTI MUHAMAD SHAHARUDDIN</p>
                <p>2022862876</p>
                <p>4) DANIA NURFAREESHA YUSREENA BINTI ROZAIMAN</p>
                <p>2022601618</p>

            </div>
        </div>

        <footer id="footer">
            <p>&copy; 2025 Velvet Vials. All rights reserved.</p>
        </footer>

        <script src="js/slider.js"></script>
    </body>
</html>