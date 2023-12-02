<?php
//if the form has been submitted
if (isset($_POST['submitted'])) {
	#prepare the form input

	// connect to the database
	require_once('connectdb.php');

	$email = isset($_POST['email']) ? $_POST['email'] : false;
	$username = isset($_POST['username']) ? $_POST['username'] : false;
	$password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : false;

	if (!($email)) {
		echo "Email wrong!";
		exit;
	}
	if (!($username)) {
		echo "Username wrong!";
		exit;
	}
	if (!($password)) {
		exit("Password wrong!");
	}
	try {

		#register user by inserting the users info 
		$stat = $db->prepare("insert into users values(default,?,?,?)");
		$stat->execute(array($username, $password, $email));

		$id = $db->lastInsertId();
		echo "Congratulations! You are now registered. Your ID is: $id  ";
	} catch (PDOexception $ex) {
		echo "Sorry, a database error occurred! <br>";
		echo "Error details: <em>" . $ex->getMessage() . "</em>";
	}
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Petopia</title>
        <link href="css/login.css" rel="stylesheet" type="text/css" href="CSS/login.css" />
    
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
        <!--Logo-->
        <div class="logo-container"><img src="assets/logo.png" alt=""></div>

        <!--Middle Navigation-->
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="cats.html">Cats</a></li>
                <li><a href="dogs.html">Dogs</a></li>
                <li><a href="advice.html">Advice</a></li>
                <li><a href="about.html">About Us</a></li>
                <li><a href="contact.html">Contact</a></li>
            </ul>
        </nav>

        <!--Right Navigation-->
        <div class="right-nav">
            <!--Shoppiung Basket Button-->
            <div class='basket-button bx bx-basket'><a href="basket.html"></a></div>
            <!--Login Button-->
            <div class="login-button"><a href="login.html">Login</a></div>
        </div>

    </header>
    
    <section class="main-content">
        <h2></h2>
        <p>login</p>
    </section>
    <div class="flex h-screen">
        <!-- Left Pane -->
        <div class="hidden lg:flex items-center justify-center flex-1 bg-white text-black">
            <div class="max-w-md text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="524.67004" height="531.39694" class="w-full" alt="https://undraw.co/illustrations" title="https://undraw.co/illustrations" viewBox="0 0 524.67004 531.39694" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <!-- ... (SVG code from the original code) ... -->
                </svg>
            </div>
        </div>

        <!-- Right Pane (Login Form) -->
        <div class="flex-1 flex items-center justify-center bg-gray-200">
            <div class="max-w-md p-8">
                <h2 class="text-2xl font-semibold text-gray-700 mb-6">Login</h2>
                <form id="loginForm" name="loginForm" method="post" action="login.php">
                    <div class="mb-4">
                        <label for="username" class="block text-gray-600 text-sm font-medium mb-2">Username</label>
                        <input type="text" id="username" name="username" class="w-full border border-gray-300 px-3 py-2 rounded-md" required>
                    </div>
                    <div class="mb-6">
                        <label for="password" class="block text-gray-600 text-sm font-medium mb-2">Password</label>
                        <input type="password" id="password" name="password" class="w-full border border-gray-300 px-3 py-2 rounded-md" required>
                    </div>
                    <input type="submit" value ="login" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600"></input>
                    <input type="hidden" name="submitted" value="TRUE" />
                </form>
            </div>
		</div>
        <!--php
                if($b==True){
                    echo '<a href="signout.php"><Button type="button">Sign out</Button></a>';
                }else{
                    echo '<form id="Form" name="signInForm" action="index.php" method="post">
                            <label>Username</label>
                            <input type="text" name="username" size="15" maxlength="25" />
                            <label>Password:</label>
                            <input type="password" name="password" size="15" maxlength="25" />

                            <input type="submit" value="Login" />
                            <input type="hidden" name="submitted" value="TRUE" />
                        </form>';
                }
            -->
    </div>

    <script src="script.js"></script>
    <form target="paypal" action="https://www.paypal.com/cgi-bin/webscr"
    method="post">
  <!-- Identify your business so that you can collect the payments. -->
  <input type="hidden" name="business" value="test@band.com">

  <!-- Specify a PayPal shopping cart View Cart button. -->
  <input type="hidden" name="cmd" value="_cart">
  <input type="hidden" name="display" value="1">

  <!-- Display the View Cart button. -->
  <input type="image" name="submit"
         src="https://www.paypalobjects.com/en_US/i/btn/btn_viewcart_LG.gif"
         alt="Add to Cart">
  <img alt="" width="1" height="1"
       src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif">
</form>
    <footer>
        &copy; 2023 Petopia
    </footer>
</body>
</html>