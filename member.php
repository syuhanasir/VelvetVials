<?php
session_start(); 

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: login.php"); 
    exit();
}

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "velvetvials"; 
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['userEmail']) && isset($_COOKIE['userEmail'])) {
    $_SESSION['userEmail'] = $_COOKIE['userEmail'];
}

if (!isset($_SESSION['userEmail'])) {
    header("Location: login.php"); 
    exit();
}

$userEmail = $_SESSION['userEmail'];

$query = "SELECT loyaltyPoint, userPrefStatus FROM users WHERE userEmail = ?";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    die('MySQL prepare error: ' . $conn->error);
}

$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
    $loyaltyPoints = $userData['loyaltyPoint'];
} else {
    $loyaltyPoints = 0; 
    $preferredScents = []; 
}

$stmt->close();
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Velvet Vials - Member</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <nav class="nav-bar">
            <a id="logo" href="member.php">Velvet Vials</a>
            <div class="nav-bar-links">
                <ul>
                    <li><a href="shopPublic.php">Shop</a></li>
                    <li><a href="membership.php">Membership</a></li>
                    <li><a href="loyalty.php">Loyalty</a></li>
                    <li><a href="account.php">Account</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
            
            <div id="cart">
                <a href="cartPage.php">Your Cart</a>
            </div>
        </nav>
        
        <div class="container-5">
            <div class="container-text">
                <h1 class="large-text">Welcome to Velvet Vials!</h1>
                <p>Where Every Scent Tells a Story</p>
                <a href="shopPublic.php">Shop Now</a>
            </div>

           
            <div id="loyalty-points-container">
                <h1 class="large-text">Loyalty Points</h1>
                <div class="progress-pie" style="--points: <?php echo $loyaltyPoints; ?>" data-points="<?php echo $loyaltyPoints; ?>"></div>
                <p class="loyalty-text">You have collected <?php echo $loyaltyPoints; ?> loyalty points!</p>
                <a href="loyalty.php"><button class="button-style">View Points</button></a>
            </div>
        </div>

        <div class="container-3">
    <h1 class="big-text">Featured Products</h1>
    <div class="container-4-collection">
       
        <div class="product-item">
            <img src="images/roomspray.png" alt="Room Sprays">
            <p>Room Sprays</p><br>
            <a href="shopPublic.php?category=room spray"><button class="shop-button">Shop Now</button></a>
        </div>
       
        <div class="product-item">
            <img src="images/limitededition.png" alt="Scented Candles">
            <p>Scented Candles</p><br>
            <a href="shopPublic.php?category=scented candle"><button class="shop-button">Shop Now</button></a>
        </div>
        
        <div class="product-item">
            <img src="images/loyaltyprogram.png" alt="Aromatherapy">
            <p>Aromatherapy</p><br>
            <a href="shopPublic.php?category=aromatherapy"><button class="shop-button">Shop Now</button></a>
        </div>
        
        <div class="product-item">
            <img src="images/social selection.png" alt="Perfume">
            <p>Perfumes</p><br>
            <a href="shopPublic.php?category=perfume"><button class="shop-button">Shop Now</button></a>
        </div>
        
        <div class="product-item">
            <img src="images/trendingnow.png" alt="Fragrance Diffuser">
            <p>Fragrance Diffuser</p><br>
            <a href="shopPublic.php?category=diffuser"><button class="shop-button">Shop Now</button></a>
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

        <script>
            
            document.querySelectorAll('.progress-pie').forEach(function(pie) {
                var points = pie.getAttribute('data-points');
                
                var maxPoints = 1000; 
                var percentage = (points / maxPoints) * 100;
                pie.style.setProperty('--percentage', percentage + '%');
            });

            
            let currentProductIndex = 0;
            const products = document.querySelectorAll('.product-item');
            const prevArrow = document.getElementById('prev-arrow');
            const nextArrow = document.getElementById('next-arrow');

            function updateProductVisibility() {
                products.forEach((product, index) => {
                    product.style.display = index === currentProductIndex ? 'block' : 'none';
                });
            }

            prevArrow.addEventListener('click', function() {
                currentProductIndex = (currentProductIndex === 0) ? products.length - 1 : currentProductIndex - 1;
                updateProductVisibility();
            });

            nextArrow.addEventListener('click', function() {
                currentProductIndex = (currentProductIndex === products.length - 1) ? 0 : currentProductIndex + 1;
                updateProductVisibility();
            });

            updateProductVisibility();
        </script>
    </body>
</html>
