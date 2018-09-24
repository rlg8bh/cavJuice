<!DOCTYPE html>
<html>
<head>
	<title>Cav Juice</title>
	<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="stylesheet" href="assets/css/register.css"/>
		<!-- <noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript> -->
</head>
<body>

	<div id="page-wrapper">

			<!-- Header -->
				<div id="header">

					<!-- Inner -->
						<div class="inner">
							<header>
								<h1><a href="index.html" id="logo">Log in or Register Now!!</a></h1>
							</header>
						</div>

					<!-- Nav -->
						<nav id="nav">
							<ul>
								<img src="images/logo.png" width="17px">
								<li><a href="index.html">Home</a></li>
								<li><a href="about-us.html">About Us</a></li>
								<li><a href="register.php">Sign Up</a></li>
								<li><a href="contact.html">Contact Us</a></li>
							</ul>
						</nav>

				</div>


							<?php 
	session_start();
	$con = mysqli_connect("localhost", "root", "", "social"); //Connectionvariable

	if(mysqli_connect_errno()) {
		echo "Failed to connect: " . mysqli_connect_errno();
	}

	//Declaring variables to prevent errors
	$fname = ""; //first name
	$lname = ""; //last name
	$em = ""; //email 
	$em = ""; //email2
	$password = ""; //password
	$password2 = ""; //password2
	$date = ""; //Sign up date
	$error_array = array();

	if(isset($_POST['register_button'])){

	//Register form values

	//First Name
	$fname = strip_tags($_POST['reg_fname']); //remove html tages
	$fname = str_replace(' ', '', $fname); //remove spaces
	$fname = ucfirst(strtolower($fname)); //Uppercase first letter
	$_SESSION['reg_fname'] = $fname; // stores first name into session variable

	//Last Name
	$lname = strip_tags($_POST['reg_lname']); //remove html tages
	$lname = str_replace(' ', '', $lname); //remove spaces
	$lname = ucfirst(strtolower($lname)); //Uppercase first letter
	$_SESSION['reg_lname'] = $lname; // stores last name into session variable

	//email
	$em = strip_tags($_POST['reg_email']); //remove html tages
	$em = str_replace(' ', '', $em); //remove spaces
	
	$_SESSION['reg_email'] = $em; // stores email into session variable

	//email2
	$em2 = strip_tags($_POST['reg_email2']); //remove html tages
	$em2 = str_replace(' ', '', $em2); //remove spaces
	
	$_SESSION['reg_email2'] = $em2; // stores email2 into session variable

	//Password
	$password = strip_tags($_POST['reg_password']); //remove html tages
	$password2 = strip_tags($_POST['reg_password2']); //remove html tages

	$date= date("Y-m-d"); //gets current date

	if($em == $em2){
		//check if email is in valid format
		if(filter_var($em, FILTER_VALIDATE_EMAIL)){
			$em = filter_var($em, FILTER_VALIDATE_EMAIL);

			//check if email already exists
			$e_check = mysqli_query($con, "SELECT email FROM users WHERE email='$em'");

			//COunt number of rows returned, should be zero if no count
			$num_rows = mysqli_num_rows($e_check);

			if($num_rows > 0){
				array_push($error_array, "Email already in use<br>");
			}
		}else{
			array_push($error_array, "Invalid formate<br>");
		}
	}else {
		array_push($error_array, "Emails don't match<br>");
	}

	if(strlen($fname) > 25 || strlen($fname) < 2){
		array_push($error_array, "Your first name must be between 2 and 25 characters");
	}

	if(strlen($lname) > 25 || strlen($lname) < 2){
		array_push($error_array,"Your last name must be between 2 and 25 characters");
	}

	if($password != $password2) {
		array_push($error_array,"Your passwords do not match");
	}
	else {
		if(preg_match('/[^A-Za-z0-9]/', $password)){
			array_push($error_array,"Your password can only have english characters or numbers");
		}
	}

	if(strlen($password > 30 || strlen($password) < 5)){
		array_push($error_array,"Your password must be between 5 and 30 characters long");
	}

	if(empty($error_array)){
		$password = md5($password); //encrypt password before sending to database

		//generate username by concatanating first name and last name
		$username = strtolower($fname . "_" . $lname);
		$check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");

		$i = 0;
		//if username exists add number to username
		while(mysqli_num_rows($check_username_query) != 0){
			$i++;
			$username = $username . "_" . $i;
			$check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");
		}

		//progile picture
		$rand = rand(1,2); //random number bw 1 and 2
		$profile_pic = "assets/images/profile_pics/defaults/head_nephritis.png";

		if($rand = 1){
			$profile_pic = "assets/images/profile_pics/defaults/head_nephritis.png";
		}else {
			$profile_pic = "assets/images/profile_pics/defaults/head_pete_river.png";
		}

		$query = mysqli_query($con, "INSERT INTO users VALUES ('', '$fname', '$lname', '$username', '$em', '$password', '$date', '$profile_pic', '0', '0', 'no', ',')");

		array_push($error_array, "<span style='color: #14C800';>Your all set! GO ahead and log in</span><br>");

		//clear sessiopn variables
		$_SESSION['$fname'] = "";
		$_SESSION['$lname'] = "";
		$_SESSION['$em'] = "";
		$_SESSION['$em2'] = "";

	}

}
 ?>

<div id="background">
<div class="signuploginContainer">
<div class="signupContainer">
	<form action="register.php" method="POST">
		<h2>Register for an Account</h2>
		<input type="text" name="reg_fname" placeholder="First Name" value="<?php if(isset($_SESSION['reg_fname'])){
			echo $_SESSION['reg_fname'];
		} 
		?>" required>	
		<br>
		<?php if(in_array("Your first name must be between 2 and 25 characters", $error_array)) echo "Your first name must be between 2 and 25 characters<br>";?>


		<input type="text" name="reg_lname" placeholder="Last Name" value="<?php if(isset($_SESSION['reg_lname'])){
			echo $_SESSION['reg_lname'];
		} 
		?>" required>
		<br>
		<?php if(in_array("Your last name must be between 2 and 25 characters", $error_array)) echo "Your last name must be between 2 and 25 characters<br>";?>

		<input type="email" name="reg_email" placeholder="Email" value="<?php if(isset($_SESSION['reg_email'])){
			echo $_SESSION['reg_email'];
		} 
		?>" required>	
		<br>


		<input type="email" name="reg_email2" placeholder="Confirm Email" value="<?php if(isset($_SESSION['reg_email2'])){
			echo $_SESSION['reg_email2'];
		} 
		?>" required>	
		<br>
		<?php if(in_array("Email already in use<br>", $error_array)) echo "Email already in use<br>";
		else if(in_array("Invalid formate<br>", $error_array)) echo "Invalid formate<br>";
		else if(in_array("Emails don't match<br>", $error_array)) echo "Emails don't match<br>";?>

		<input type="password" name="reg_password" placeholder="Password" required>	
		<br>
		<input type="password" name="reg_password2" placeholder="Confirm Password" required>	
		<br>
		<?php if(in_array("Your passwords do not match", $error_array)) echo "Your passwords do not match<br>";
		else if(in_array("Your password can only have english characters or numbers", $error_array)) echo "Your password can only have english characters or numbers<br>";
		else if(in_array("Your password must be between 5 and 30 characters long", $error_array)) echo "Your password must be between 5 and 30 characters long<br>";?>

		<input type="submit" name="register_button" placeholder="Register" required>
		<br>

		<?php if(in_array("<span style='color: #14C800';>Your all set! GO ahead and log in</span><br>", $error_array)) echo "<span style='color: #14C800';>Your all set! GO ahead and log in</span><br>"; ?>

		<br>	

		<div style="float:right; font-size: 85%; position: relative; top:-10px"><a id="signinlink" href="#" onclick="$('.signupContainer').hide(); $('.loginContainer').show()">Sign In</a></div>

	</form>
</div>



<div class="loginContainer">
	<form id="loginForm" action="register.php" method="POST">
		<h2>Login to your account</h2>
		<p>
			
			<label for="loginUsername">Username</label>
			<input id="loginUsername" name="loginUsername" type="text" placeholder="e.g. bartSimpson" required>
		</p>
		<p>
			<label for="loginPassword">Password</label>
			<input id="loginPassword" name="loginPassword" type="password" placeholder="Your password" required>
		</p>

		<button type="submit" name="loginButton">LOG IN</button>

		<div style="float:right; font-size: 85%; position: relative; top:-10px"><a id="signinlink" href="#" onclick="$('.signupContainer').show(); $('.loginContainer').hide()">Log In</a></div>
				
	</form>
</div>
</div>

</div>

		<div class="row">
							<div class="col-12">

								<!-- Contact -->
									<section class="contact">
										<header>
											<h3>Nisl turpis nascetur interdum?</h3>
										</header>
										<p>Urna nisl non quis interdum mus ornare ridiculus egestas ridiculus lobortis vivamus tempor aliquet.</p>
										<ul class="icons">
											<li><a href="#" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
											<li><a href="#" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
											<li><a href="#" class="icon fa-instagram"><span class="label">Instagram</span></a></li>
											<li><a href="#" class="icon fa-pinterest"><span class="label">Pinterest</span></a></li>
											<li><a href="#" class="icon fa-dribbble"><span class="label">Dribbble</span></a></li>
											<li><a href="#" class="icon fa-linkedin"><span class="label">Linkedin</span></a></li>
										</ul>
									</section>

								<!-- Copyright -->
									<div class="copyright">
										<ul class="menu">
											<li>&copy; Untitled. All rights reserved.</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
										</ul>
									</div>

							</div>

						</div>
					</div>
				</div>

		</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.dropotron.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/jquery.scrollex.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>
					

</body>
</html>
