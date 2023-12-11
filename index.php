
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    session_start();
    $error = ''; // IF LOGIN.PHP RETURNS A LOGIN ERROR, THIS WILL DISPLAY A MESSAGE
	
	// REGISTRATION FORM
if (isset($_POST['submit2'])) {
    $connection = mysqli_connect("localhost", "ayanamala1", "ayanamala1", "ayanamala1");
	
	// Creating necessary tables if they don't exist
    $sqlCreateTables = "
        CREATE TABLE IF NOT EXISTS `county` (
            `BoroughID` smallint(6) NOT NULL,
            `bname` tinytext NOT NULL,
            `longitude` tinyint(3) unsigned NOT NULL,
            `latitude` tinyint(3) unsigned NOT NULL,
            PRIMARY KEY (`BoroughID`)
        );
    ";

    // Execute the table creation query
    mysqli_query($connection, $sqlCreateTables);

    $sqlCreateTables = "
        CREATE TABLE IF NOT EXISTS `sellersearch` (
            `MemberID` smallint(5) unsigned NOT NULL,
            `agemin` tinyint(3) unsigned NOT NULL,
            `agemax` tinyint(3) unsigned NOT NULL,
            `occupation` tinyint(3) NOT NULL,
            `income` tinyint(3) NOT NULL,
            `pet` varchar(3) NOT NULL,
            `smoke` varchar(3) NOT NULL,
            PRIMARY KEY (`MemberID`)
        );
    ";

    // Execute the table creation query
    mysqli_query($connection, $sqlCreateTables);

    $sqlCreateTables = "
CREATE TABLE IF NOT EXISTS `property` (
  `MemberID` smallint(5) unsigned NOT NULL,
  `PostID` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` tinytext NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `borough` tinyint(3) unsigned NOT NULL,
  `address` varchar(40) NOT NULL,
  `image` text NOT NULL,
  `description` text NOT NULL,
  
  -- Add new columns here
  `location` varchar(50) NOT NULL,
  `age` smallint(5) unsigned NOT NULL,
  `floor_plan_square_footage` decimal(10,2) NOT NULL,
  `number_of_bedrooms` tinyint(3) unsigned NOT NULL,
  `additional_facilities` varchar(255) NOT NULL,
  `garden_presence` tinyint(1) NOT NULL,
  `parking_availability` tinyint(1) NOT NULL,
  `proximity_to_facilities` varchar(255) NOT NULL,
  `proximity_to_main_roads` tinyint(1) NOT NULL,
  `property_tax_records` decimal(10,2) NOT NULL,
  
  PRIMARY KEY (`PostID`)
);
";

    // Execute the table creation query
    mysqli_query($connection, $sqlCreateTables);

    $sqlCreateTables = "
        CREATE TABLE IF NOT EXISTS `buyerinfo` (
            `MemberID` smallint(5) unsigned NOT NULL,
            `age` tinyint(4) NOT NULL,
            `occupation` tinyint(4) NOT NULL,
            `income` tinyint(4) NOT NULL,
            `pet` varchar(3) NOT NULL,
            `smoker` varchar(3) NOT NULL,
            PRIMARY KEY (`MemberID`)
        );
    ";

    // Execute the table creation query
    mysqli_query($connection, $sqlCreateTables);

    $sqlCreateTables = "
        CREATE TABLE IF NOT EXISTS `buyersearch` (
            `MemberID` smallint(5) unsigned NOT NULL,
            `borough` tinyint(4) NOT NULL,
            `price` smallint(6) unsigned NOT NULL,
            PRIMARY KEY (`MemberID`)
        );
    ";

    // Execute the table creation query
    mysqli_query($connection, $sqlCreateTables);

    $sqlCreateTables = "
        CREATE TABLE IF NOT EXISTS `user` (
            `MemberID` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
            `uname` varchar(20) NOT NULL,
            `pword` varchar(32) NOT NULL,
            `fname` varchar(20) NOT NULL,
            `lname` varchar(20) NOT NULL,
            `usertype` varchar(10) NOT NULL,
            `email` tinytext NOT NULL,
            `phonenum` varchar(15) NOT NULL,
            PRIMARY KEY (`MemberID`)
        );
    ";

    // Execute the table creation query
    mysqli_query($connection, $sqlCreateTables);

    if (mysqli_connect_errno()) {
        print "Connect failed:". mysqli_connect_error();
        exit();
    }

    $FIRST_NAME = trim(htmlspecialchars($_POST['FIRST_NAME']));
    $LAST_NAME = trim(htmlspecialchars($_POST['LAST_NAME']));
    $USERTYPE = htmlspecialchars($_POST['USERTYPE']);
    $PHONE_NUMBER = htmlspecialchars($_POST['PHONE_NUMBER']);
    $EMAIL = htmlspecialchars(trim($_POST['EMAIL']));
    $USERNAME = htmlspecialchars($_POST['USERNAME']);
    $PASSWORD = htmlspecialchars($_POST['PASSWORD']);

    // CHECK FOR DUPLICATE USERNAME
    $SQL = "SELECT * FROM user WHERE uname = '$USERNAME'";
    $result = mysqli_query($connection, $SQL);

    $num_rows = mysqli_num_rows($result);

    // IF USERNAME IS NOT UNIQUE, SET $duplicateUSERNAME TO TRUE
    if ($num_rows > 0) {
        $duplicateUSERNAME = true;
    } else {
        // INSERT USER INTO DATABASE
        $SQL = "INSERT INTO user (fname, lname, usertype, phonenum, email, uname, pword) VALUES ('$FIRST_NAME','$LAST_NAME','$USERTYPE','$PHONE_NUMBER','$EMAIL','$USERNAME', md5('$PASSWORD'))";
        mysqli_query($connection, $SQL);

        // RETRIEVE USER DATA AFTER INSERT
        $result = mysqli_query($connection, "SELECT * FROM user WHERE BINARY uname = '$USERNAME'");
        $row = mysqli_fetch_assoc($result);

        $_SESSION['login_MEMBERID'] = $row['MemberID'];
        $_SESSION['login_FIRSTNAME'] = $row['fname'];
        $_SESSION['login_LASTNAME'] = $row['lname'];
        $_SESSION['login_USERTYPE'] = $row['usertype'];

        header("location: ../4/index.php");
        exit();
    }

    mysqli_close($connection);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Home - property Hub</title>
    <link href="css/bootstrap.min.css" type="text/css" rel="stylesheet">
    <link href="css/stylesheet.css" type="text/css" rel="stylesheet">
    <link rel="shortcut icon" href="newlogo.ico">
    <script src="jquery-3.7.1.slim.min.js" ></script>
    <style>
    </style>
</head>

<body>
    <div class="navbar-default">
        <div class="container">
            <div class="navbar-header nabar-left">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#nav-bar-links"> <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="">
                    <img src="logo.png" width="50" style="margin-top: -10px">
                </a>
                <div class="navbar-heading ml-2" style="padding: 10px; font-weight: bold; margin-top : -20px; margin-left:350px;"><h2>AMD HOME VISIONARIES</h2></div>
            </div>

            <div class="collapse navbar-collapse" id="nav-bar-links">
                <?php
                if (isset($_SESSION['login_MEMBERID'])) {
                    // If the user is logged in, show the welcome message and dropdown
                    print '
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="true">Welcome! Signed in as ' . $_SESSION['login_FIRSTNAME'] . ' ' . $_SESSION['login_LASTNAME'] . '<span class="caret"></span> </a>
                                <ul class="dropdown-menu" role="menu">
                    ';

                    // Display menu items based on user type
                    if ($_SESSION['login_USERTYPE'] == 'owner')
                        print '
                                        <li><a href="users/sellerpost">View Your Listed Properties</a></li>
                                        <li><a href="users/sellerpost/newpost">List Your property</a></li>
                                        <li><a href="users/sellersearch/index.php">Find a Buyer</a></li>
                                        <li><a href="users/sellerprofile/index.php">My Account</a></li>
                                        <li class="divider"></li>
                            ';
                    if ($_SESSION['login_USERTYPE'] == 'tenant')
                        print '
                                        <li><a href="users/buyerprofile/">View or Update Profile</a></li>
                                        <li><a href="#">Search for Properties</a></li>
                                        <li class="divider"></li>
                            ';

                    // Logout option
                    print '
                                        <li><a href="logout.php">Sign Out</a></li>
                                    </ul>
                                </li>
                            </ul>
                        ';
                } else {
                    // If not logged in, show login and registration options
                  print '
    <form class="navbar-form navbar-right" style="border: none" onSubmit="return Validate()" method="post" action="loginPage.php">
        <div class="form-group" style="border: none;">
            <input type="submit" class="btn btn-default" value="Sign In" name="submit1">
        </div>
    </form>
';
				 print '
					<form class="navbar-form navbar-right" style="border: none" onSubmit="return Validate()" method="post" action="https://codd.cs.gsu.edu/~ayanamala1/WP/PW/4/registration/index.php">
				 <div class="form-group" style="border: none;">
					<input type="submit" class="btn btn-default" value="Register" name="submit1">
				</div>
				</form>
				';

                }
                ?>
            </div>
        </div>
    </div>
    <div class="jumbotron">
		<div class="container">
			<div class="transbox">
				<h1><?php if(isset($_SESSION['login_MEMBERID'])) print 'Welcome '.$_SESSION['login_FIRSTNAME'].'  '.$_SESSION['login_LASTNAME'].". " ;?>Crafting Memories - One Home At A Time.</h1>
				<p ><h3>Empowering Homes, Enriching Lives: Where Every property Thrives!</h3></p>
			</div>
		</div>
	</div>
    	<div class="container">
        <!-- First Row -->
        <div class="row">
            <div class="col-12">
                <h3><b>Welcome to our Website</b></h3>
                <p style="opacity:"><h4>Discover the ideal buyer for your home. Connect with potential homebuyers who are actively seeking properties like yours, or proactively search for those looking to make a purchase. With millions of users subscribed to our platform, at adm home visionaries, we are dedicated to bringing joy to everyone's face by facilitating successful home-selling experiences.</h4></p>
            </div>
        </div>

        <!-- Second Row -->
        <div class="row">
            <div class="col-12">
                <?php
                if(isset($_SESSION['login_MEMBERID'])){
                    print '
                        <h3><b>Begin now!</b></h3>
                        <ul style="list-style-type:square">
                    ';

                    if ($_SESSION['login_USERTYPE'] == 'owner')
                        print'
                            <li><h4><a href="users/sellerpost">View Your Listed Properties</a></h4></li>
                            <li><h4><a href="users/sellerpost/newpost">List Your property</a></h4></li>
                            <li><h4><a href="users/sellersearch">Find a Buyer</a></h4></li>
                            <li><h4><a href="users/sellerprofile">My Account</a></h4></li>
                        ';
                    if ($_SESSION['login_USERTYPE'] == 'tenant')
                        print'
                            <li><h4><a href="users/buyerprofile">Click to view or update your profile.</a></h4></li>
                            <li><h4><a href="#">Click to search for Properties.</a></h4></li>
                        ';

                    print '
                        </ul>
                    ';
                }
                else{
                    print '
                    <h3><b>What is AMD Home Visionaries?</b></h3>
                    <p><h4>At AMD Home Visionaries, we serve as a holistic real estate platform dedicated to facilitating the buying and selling of properties. Whether you are a seller looking to list your property effortlessly or a buyer in search of the perfect home, our platform provides an intuitive space to navigate the complexities of real estate transactions.
                   </h4> ';
                }
                ?>
            </div>
        </div>

        <!-- Third Row -->
        <div class="row">
            <div class="col-12">
                <h3><b>How does it work?</b></h3>
                <dl><h4>
                    <dt>For Buyers:</dt>
                    <dd>Prospective homebuyers have the convenience of quickly establishing a concise profile that allows them to explore a variety of houses listed by sellers. This process enables them to actively engage in searching for houses that align with their specific preferences and requirements.
                    <br/>In essence, individuals in search of a new home can navigate through available listings, considering various factors that cater to their unique needs and desires. 
                    <br/>This approach provides a comprehensive and personalized experience for homebuyers as they navigate through the diverse array of housing options presented by sellers.
                    <dt>For Sellers:</dt>
                    <dd>Property owners can effortlessly create listings for houses or apartments available for rent, offering the flexibility to manage their posts. Whether attracting potential tenants or actively seeking suitable occupants, sellers have the autonomy to post and delete listings at their convenience.
                    <br/>
                </dl>
            </div>
        </div>
        <!-- Fourth Row - Carousel with Images -->
        <div class="carousel-inner" role="listbox">

          <div class="item active">
            <img src="image1.jpg" alt="interior" width="1200" height="300">
            <div class="carousel-caption">
              <h3>Do you need</h3>
              <p>Buying and selling properties needs a lot of research and can be exhausting exhausting sometimes. What if we could bring the property buyers and property sellers under a single platform?</p>
            </div>
          </div>

          <div class="item">
            <img src="image2.jpg" alt="BlueBliss Crew" width="600" height="400">
            <div class="carousel-caption">
              <h3>Who we are</h3>
              <p>Sell Your Home With An Expert Who Knows Your Neighborhood And Can Get You The Best Price. Redfin Agents Sell Homes 5 Days Faster On Average Than Agents From Other Brokerages</p>
            </div>
          </div>
        
          <div class="item">
            <img src="image3.jpg" alt="Service" width="600" height="400">
            <div class="carousel-caption">
              <h3>Motive</h3>
              <p>We believe that everything boils down to the human connection, and every part of my business is a direct reflection of this conviction. Whether buying or selling, what matters most to use is honest communication, extreme loyalty to my clients, and a direct, hands-on approach.</p>
            </div>
          </div>
        </div>


        <!-- Fifth Row -->
        <div class="row">
            <div class="col-12">
                <h3><b>Why Choose Us Over Competitors?</b></h3><h4>
                <p style="opacity:"> User - Centric Approach: Enjoy a smooth and intuitive experience designed with you in mind. <br/> Comprehensive Listings: Access an extensive database for a wide array of property choices. <br/>Expert Guidance: Rely on the expertise of our seasoned real estate professionals throughout your journey.   <br/> Innovative Technology: Leverage cutting-edge technology for an enhanced real estate experience. <br/>Transparent Transactions: Experience transparency in every transaction, from listing to closing.</p>
            </h4>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <h3><b>What Kind of Services Do We Provide?</b></h3><h4>
                <p style="opacity:">Property Listings: Discover diverse property listings to suit your preferences.
                <br/>Seller Assistance:  Easily list your property using our user-friendly tools.
                <br/>Buyer Resources:  Access detailed property information and connect with sellers seamlessly.
                <br/>Professional Guidance:  Benefit from expert advice and support from our seasoned real estate professionals.
                <br/>Financial Tools:  Explore financing options and calculate mortgage details directly on our platform.
                </h4>
            </div>
        </div>

        <div class="carousel-inner" role="listbox">
          <div class="item active">
            <img src="image2.jpg" alt="BlueBliss Crew" width="1200" height="300">
            <div class="carousel-caption">
              <h3>Who we are</h3>
              <p>Sell Your Home With An Expert Who Knows Your Neighborhood And Can Get You The Best Price. Redfin Agents Sell Homes 5 Days Faster On Average Than Agents From Other Brokerages</p>
            </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h3><b>Attraction Strategies</b></h3><h4>
                <p style="opacity:">Exclusive Deals:  Access special offers available only on our platform.
                <br/>Customer Loyalty Programs:  Enjoy perks and benefits through our customer loyalty programs.
                <br/>Educational Content:  Stay informed with our blog and educational resources on the latest real estate trends.
             </h4>
            </div>
        </div>

        <hr>
    </div>

    <footer>
    <div class="container">
        <div class="row text-center">
        <h3><b>Contact Details </b></h3><br/>
            <div class="col-md-4 text-center">
             
                <p>
                        <strong>Abheesht Reddy Yanamala</strong><br>
                        740 Sidney Marcus Blvd NE <br>
                        Atlanta, Georgia, USA <br>
                        Mobile Number : (400)500-6000
                </p>
            </div>

            <div class="col-md-4 text-center">
                <p>
                    <strong>Meenakshi Kolishetty</strong><br>
                        740 Sidney Marcus Blvd NE <br>
                        Atlanta, Georgia, USA <br>
                        Mobile Number : (444)555-6666
                </p>
            </div>

            <div class="col-md-4 text-center">
                <p>
                    <strong>Devaki Bolleneni</strong><br>
                        740 Sidney Marcus Blvd NE <br>
                        Atlanta, Georgia, USA <br>
                        Mobile Number : (555) 777-8888
                </p>
            </div>
        </div>
        <p class="text-center col-md-12"r style="padding-top: 30px">
					<small></small>
		</p>
    </div>
</footer>


    <script src="script/validateform.js"></script>
    <script src="script/jquery-1.11.2.min.js"></script>
    <script src="script/bootstrap.min.js"></script>
</body>

</html>
