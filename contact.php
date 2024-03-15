<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petopia</title>
    <link href="css/contact.css" rel="stylesheet" type="text/css">

    <!--[Google Fonts]-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!--Nunito Font-->
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,700;1,800&family=Work+Sans:wght@700;800&display=swap"
        rel="stylesheet">

    <!--Box Icons-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!--
        [Navigation & Footer]
    -->
    <script src="templates/navigationTemplate.js"></script>
    <link href="css/navigation.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/footer.css">

    <!--Flickity-->
    <!--CSS Templates-->
    <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
    <link rel="stylesheet" href="templates/hero-banner.css">
    <!--JS-->
    <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
    <script>"contact.js/scripts"</script>

</head>

<body>
    
    <header></header>

    <?php
    require_once('php/connectdb.php');
    require_once('php/mainLogCheck.php');

    $userFirstName = '';
    $userEmail = '';

    /*If user is logged in, grab their data*/
    if ($b) {
        $username = $_SESSION['username'];
        $stmt = $db->prepare("SELECT First_Name, Contact_Email FROM customer WHERE Username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($user) {
            $userFirstName = $user['First_Name'];
            $userEmail = $user['Contact_Email'];
        }
    }

    if (isset($_POST['submit-contact'])) {
        //Form data
        $name = $_POST['contact-name'];
        $email = isset($_POST['contact-hide-email']) ? '' : $_POST['contact-email'];
        $message = $_POST['contact-message'];
        $contactDate = date('Y-m-d');
    
        //Preparing the SQL statement
        $stmt = $db->prepare("INSERT INTO contactforms (`Name`, `Contact_Email`, `Contact_Date`, `Contact_Text`) VALUES (?, ?, ?, ?)");
        $stmt->bindValue(1, $name);
        $stmt->bindValue(2, $email);
        $stmt->bindValue(3, $contactDate);
        $stmt->bindValue(4, $message);
    
        //Executes the form
        require_once('php/alerts.php');
        jsAlert('Form successfully sent', true, 4000);
        if ($stmt->execute()) {
            jsAlert('Form successfully sent', true, 4000);
        } else {
            jsAlert('Error: Form was not sent', false, 4000);
        }
    }
?>

    <main>

        <section class="hero-banner">
            <div class="hero-banner-image"><img src="assets/Homepage/hero-banner2.jpg" alt=""></div>

            <div class="hero-banner-left">

                <div class="hero-banner-content">
                    <h2>Contact us</h2>
                    <p>Get in touch with our team</p>
                </div>
            </div>
        </section>

        <section class="contact-section">
            <div class="contact-bottom">
                <!--Left Box-->
                <div class="left-contact">

                    <!--Title Etc...-->
                    <h2 class="contact-h2">Contact Us</h2>
                    <h3 class="contact-h3">Ask anything you wish!</h3>
                    <h4 class="contact-h4">Have questions, comments, or just want to say hello? Drop us a message below
                        and let's start the conversation!
                    </h4>

                    <!--Contact Form-->
                    <form id="contact-form" action="contact.php" method="post" name="contact-form">
                        <!--Name Input Container-->
                        <div class="input-container">
                            <label for="contact-name">Your Name<span style="color: red;">*</span></label>
                            <input type="text" id="contact-name" name="contact-name" class="name-input" placeholder="Name" required value="<?php echo htmlspecialchars($userFirstName); ?>" />
                            <i class='bx bxs-user'></i>
                        </div>

                        <!--Email Input Container-->
                        <div class="input-container">
                            <label for="contact-email">Your Email<span style="color: red;">*</span></label>
                            <input type="email" id="contact-email" name="contact-email" class="email-input" placeholder="Email" required value="<?php echo htmlspecialchars($userEmail); ?>" />
                            <i class='bx bxs-envelope'></i>
                        </div>

                        <!--Message Input Container-->
                        <div class="input-container-textarea">
                            <label for="contact-message">Your Message<span style="color: red;">*</span></label>
                            <textarea id="contact-message" name="contact-message" class="message-input"
                                placeholder="Enter message here..." required></textarea>
                        </div>

                        <!--Checkbox Input Container-->
                        <div class="input-container-checkbox">
                            <input type="checkbox" id="contact-agree" name="contact-hide-email">
                            <label for="contact-agree">Don't show your email address</label>
                        </div>

                        <!--Contact Button-->
                        <button type="submit" class="contact-btn">Send Now</button>
                        <input type="hidden" name="submit-contact" value="TRUE" />
                    </form>

                </div>

                <!--Right Box-->
                <div class="right-contact">
                    <div class="contact-image-container">
                        <img src="assets/Contactpage/Envelopes.png" alt="">
                    </div>
                    <div class="contact-information-container">
                        <!--Contact Information Row (Location)-->
                        <div class="contact-row">
                            <!--Contact Icon-->
                            <div class="circle-contact">
                                <i class='bx bx-current-location'></i>
                            </div>
                            <!--Contact Text-->
                            <p class="contact-text">Aston St, Birmingham B4 7ET</p>
                        </div>

                        <!--Contact Information Row (Phone Number)-->
                        <div class="contact-row">
                            <!--Contact Icon-->
                            <div class="circle-contact">
                                <i class='bx bxs-phone'></i>
                            </div>
                            <!--Contact Text-->
                            <p class="contact-text">+44 71 2345 6789 </p>
                        </div>

                        <!--Contact Information Row (Email)-->
                        <div class="contact-row">
                            <!--Contact Icon-->
                            <div class="circle-contact">
                                <i class='bx bxs-envelope'></i>
                            </div>
                            <!--Contact Text-->
                            <p class="contact-text">contact-us@petopia.co.uk</p>
                        </div>
                    </div>

                    <!--Social Media-->
                    <div class="contact-social-media-container">
                        <!--Social Media (LinkedIn)-->
                        <div class="contact-social-background linkedin-color">
                            <a href="#" class='bx bxl-linkedin'></a>
                        </div>

                        <!--Social Media (twitter)-->
                        <div class="contact-social-background twitter-color">
                            <a href="#" class='bx bxl-twitter'></a>
                        </div>

                        <!--Social Media (facebook)-->
                        <div class="contact-social-background facebook-color">
                            <a href="#" class='bx bxl-facebook'></a>
                        </div>

                    </div>


                </div>
            </div>

        </section>
    </main>

    <footer>
        <p>Copyright © Petopia 2023</p>
    </footer>


</body>