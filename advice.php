<?php
    require_once("php/mainLogCheck.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petopia</title>
    <link href="css/advice.css" rel="stylesheet" type="text/css">
    <link href="css/navigation.css" rel="stylesheet" type="text/css">
    <link href="css/footer.css" rel="stylesheet" type="text/css">

    <!--[Google Fonts]-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!--Nunito Font-->
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,700;1,800&family=Work+Sans:wght@700;800&display=swap"
        rel="stylesheet">

    <!--Box Icons-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!--Flickity-->
    <!--CSS-->
    <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
    <!--JS-->
    <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>

</head>
<body>
    <header>
        <!--
        [NAVIGATION/HEADER]
    -->
    <header>
        <!--Logo-->
        <div class="logo-container"><a href="index.php"><img src="assets/logo.png" alt=""></a></div>

        <!--Middle Navigation-->
        <nav class="desktop-nav">
            <ul class="desktop-nav-ul">
                <li><a href="index.php">Home</a></li>
                <!--Dropdown-->
                <li class="dropdown">
                    <a href="#">Pets v</a>
                    <ul class="dropdown-menu">
                        <li class="dropdown-li"><a href="products.php">Cats</a></li>
                        <li class="dropdown-li"><a href="products.php">Dogs</a></li>
                    </ul>
                </li>
                <!--Dropdown-->
                <li class="dropdown">
                    <a href="#">Shop v</a>
                    <ul class="dropdown-menu">
                        <li class="dropdown-li"><a href="products.php">Toys</a></li>
                        <li class="dropdown-li"><a href="products.php">Grooming</a></li>
                        <li><a href="products.php">Treats</a></li>
                    </ul>
                </li>
                <li><a href="advice.php">Advice</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>

        <!--Right Navigation-->
        <div class="right-nav">
            <?php
                //Login Button
                if ($b==true) {
                    //Log out button
                    //Shoppiung Basket Button
                    echo '<a href="basket.php" class="basket-link"><div class="basket-button bx bx-basket"></div></a>';
                    echo '<div class="login-button"><a href="php/signOut.php"">Log Out</a></div>';
                }else{
                    //Login Button
                    echo '<div class="login-button"><a href="login.php">Login</a></div>';
                }       
            ?>
        </div>

        <!--Mobile-Background-->
        <div class="mobile-background"></div>
        <!--Mobile Navigation-->
        <nav class="mobile-nav">
            <div class="mobile-nav-top">
                <div class="mobile-logo"><img src="assets/logo.png" alt=""></div>
                <p class="close-menu-button" draggable="false">X</p>
            </div>
            <ul>
                <li><a href="index.php">Home</a></li>
                <!--Dropdown-->
                <li class="dropdown">
                    <a href="#">Pets v</a>
                    <ul class="dropdown-menu-mobile">
                        <li class="dropdown-li"><a href="products.php">Cats</a></li>
                        <li class="dropdown-li"><a href="products.php">Dogs</a></li>
                    </ul>
                </li>
                <!--Dropdown-->
                <li class="dropdown">
                    <a href="#">Shop v</a>
                    <ul class="dropdown-menu-mobile">
                        <li class="dropdown-li"><a href="products.php">Cats</a></li>
                        <li class="dropdown-li"><a href="products.php">Dogs</a></li>
                    </ul>
                </li>
                <li><a href="advice.php">Advice</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact</a></li>
                <div class="mobile-bottom-nav">
                    <?php
                        if ($b==true) {
                            //Log out button
                            //Shoppiung Basket Button
                            echo '<a href="basket.php" class="basket-link"><div class="basket-button bx bx-basket"></div></a>';
                            echo '<div class="login-button"><a href="php/signOut.php"">Log Out</a></div>';
                        }else{
                            //Login Button
                            echo '<div class="login-button"><a href="login.php">Login</a></div>';
                        } 
                    ?>
                </div>
            </ul>
        </nav>
        <!--Mobile Hamburger-->
        <div id="hamburger-button" class='bx bx-menu'></div>
    </header>
    <!--
        [HEADER/NAVIGATION END]
    -->

    </header>
    <section class="banner">
        <div class="banner-left">

    <div class="advice-image"><img src="assets/AdvicePage/vetwoman.jpg" alt=""></div>
    <div class="banner-content">
        <h2>Advice</h2>
    </div>
        </div>
    
</section>


    

    
<ul>
        <p>Getting a new family member is very exciting and being a responsible owner will surely feel rewarding! It is very important that when you get a new pet 
        you are aware that it is a commitment to another family member and they will need to be taken care of. 
        
        Fortunately, to ensure your pets heath and wellbeing, we have a guide that lists the necessities that pet owners need to provide.</p>
        
    <h1>Nutrition:</h1> 
        
        <li>It is important that pets are provided with a healthy and well balanced diet</li> 
        <li>Give your pets 24/7 access to clean and fresh drinking water</li>
        
    <h1>Regular Veterinary Care:</h1> 
        
        <li>Pets require doctor visits and healthy habits. To ensure your pet is healthy, schedule regular check-ups with a veterinarian to monitor your 
            pet's health and address any potential issues early.</li>
        
    <h1>Vaccinating: </h1>
        
        <li>Keeping up with vaccinations, parasite control, and dental care as recommended by your vet is also very important.</li>
        
    <h1>Exercise and Mental Stimulation:</h1>
        
        <li>Engage your pet in regular physical activities to maintain a healthy weight and prevent boredom.</li>
        <li>Provide toys, puzzles, and playtime to keep their minds stimulated.</li>
        
    <h1>Grooming:</h1>
        
        <li>Brush your pet's fur regularly to prevent matting and reduce shedding.</li>
        <li>Trim nails, clean ears, and maintain dental hygiene as recommended for your specific pet.</li>
        
    <h1>Safe Environment:</h1>  
        
        <li>Pet-proof your home to remove hazards and keep dangerous substances out of reach. Ensure your pet has a comfortable and safe place to rest.</li>
        
    <h1>Socialization:</h1> 
        
        <li>Introduce your pet to various environments, people, and other animals to promote positive social behavior. Spend quality time with your pet to build 
            a strong bond.</li>
        
    <h1>Identification and Microchipping:</h1>
        
        <li>Ensure your pet has proper identification, such as a collar with an ID tag. Consider microchipping as a reliable way to reunite with your pet if 
            they get lost.</li>
        
    <h1>Hygiene:</h1>
        
        <li>Keep your pet's living area clean and regularly clean their bedding and toys. Bathe your pet as needed, taking care not to overdo it, 
            as some animals don't require frequent baths.</li>
        
    <h1>Training:</h1>
        
        <li>Invest time in training your pet using positive reinforcement techniques. Teach basic commands and behaviors to ensure a well-behaved pet.</li>
        
    <h1>Monitoring Health Signs:</h1>
        
        <li>Be attentive to changes in behavior, appetite, and bathroom habits.</li>
        <li>Consult a veterinarian promptly if you notice any signs of illness or distress.</li>
        
    <h1>Respect Their Needs:</h1>
        
        <li>Understand the specific needs of your pet's species and breed.</li>
        <li>Respect their natural behaviors and instincts.</li>
        
    <h1>Love and Attention:</h1>
        
        <li>Spend quality time with your pet, offering affection and attention.</li>
        <li>Build a strong emotional connection to create a happy and trusting relationship.</li>
        
        <p>Remember, each pet is unique, and individual needs may vary. Regular veterinary advice and your personal observations will guide you in providing 
        the best care for your specific pet.
    </p>
        </ul>
    
    
</body>