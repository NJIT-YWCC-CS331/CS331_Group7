<?php
// Start session to check login status
session_start();
include 'connect.php';

// Handle logout if requested
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_unset();
    session_destroy();

    // Start a new session for the logout message
    session_start();
    $_SESSION['logout_message'] = "You have been successfully signed out.";

    header("Location: index.php");
    exit();
}

// CUSTOMER login status
$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$customer_name = $is_logged_in ? $_SESSION['customer_name'] : '';

// STAFF login status
$is_staff_logged_in = isset($_SESSION['staff_logged_in']) && $_SESSION['staff_logged_in'] === true;
$staff_name = $is_staff_logged_in ? $_SESSION['employee_name'] : '';

// Check for logout message
$logout_message = isset($_SESSION['logout_message']) ? $_SESSION['logout_message'] : '';

// Clear logout message after displaying it
if (isset($_SESSION['logout_message'])) {
    unset($_SESSION['logout_message']);
}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title> TESTING</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
	</head>
	<body class="is-preload">
		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->
					<header id="header">
						<div class="inner">

							<!-- Logo -->
								<a href="index.php" class="logo">
									<span class="fa fa-car"></span> <span class="title">CAR RENTAL WEBSITE</span>
								</a>

							<!-- Nav -->
								<nav>
                                <ul>

                                    <!-- STAFF LOGGED IN -->
                                    <?php if ($is_staff_logged_in): ?>
                                        <li><span class="welcome-message" style="margin-left: 20px; font-weight: bold;">
                                            Staff: <?php echo htmlspecialchars($staff_name); ?>
                                        </span></li>
                                        <li><a href="AdminView.php" class="button small" style="margin-left: 10px;">Admin Panel</a></li>
                                        <li><a href="index.php?action=logout" class="button small" style="margin-left: 10px;">Logout</a></li>

                                    <!-- CUSTOMER LOGGED IN -->
                                    <?php elseif ($is_logged_in): ?>
                                        <li><span class="welcome-message" style="margin-left: 20px; font-weight: bold;">
                                            Welcome, <?php echo htmlspecialchars($customer_name); ?>!
                                        </span></li>
                                        <li><a href="profile.php" class="button small" style="margin-left: 10px;">Profile</a></li>
                                        <li><a href="index.php?action=logout" class="button small" style="margin-left: 10px;">Logout</a></li>

                                    <!-- NO ONE LOGGED IN -->
                                    <?php else: ?>
                                        <li><a href="create-account.php" class="button small" style="margin-left: 20px;">Create Account</a></li>
                                        <li><a href="login.php" class="button small" style="margin-left: 10px;">Login</a></li>
                                    <?php endif; ?>

									<li><a href="#menu">Menu</a></li>
                                </ul>
                            </nav>

						</div>
					</header>

				<!-- Menu -->
					<nav id="menu">
						<h2>Menu</h2>
						<ul>
							<li><a href="index.php" class="active">Home</a></li>

							<li><a href="offers.php">Offers</a></li>

							<li><a href="fleet.php">Fleet</a></li>

                            <!-- STAFF ONLY MENU ITEMS -->
                            <?php if ($is_staff_logged_in): ?>
                                <li><hr></li>
                                <li><a href="AdminView.php" style="color: #d9534f; font-weight: bold;">Admin View</a></li>
                                <li><a href="AdminReservations.php" style="color: #d9534f;">Admin Reservations</a></li>
                                <li><a href="index.php?action=logout" style="color: #d9534f;">Logout</a></li>
                            <?php endif; ?>

							<li>
								<a href="#" class="dropdown-toggle">About</a>

								<ul>
									<li><a href="about.html">About Us</a></li>
									<li><a href="team.html">Team</a></li>
									<li><a href="blog.html">Blog</a></li>
									<li><a href="testimonials.html">Testimonials</a></li>
									<li><a href="faq.html">FAQ</a></li>
									<li><a href="terms.html">Terms</a></li>
								</ul>
							</li>

							<li><a href="contact.html">Contact Us</a></li>

							<!-- CUSTOMER ONLY MENU -->
							<?php if ($is_logged_in): ?>
								<li><hr></li>
								<!--<li><a href="dashboard.php" style="color: #4CAF50; font-weight: bold;">My Dashboard</a></li> -->
								<li><a href="profile.php">My Profile</a></li>
								<li><a href="my-bookings.php">My Bookings</a></li>
							<?php endif; ?>
						</ul>
					</nav>

				<!-- Main -->
					<div id="main">
						<?php if (!empty($logout_message)): ?>
							<div class="alert alert-info" style="margin: 0 auto; text-align: center; max-width: 800px; border-radius: 0;">
								<?php echo htmlspecialchars($logout_message); ?>
							</div>
						<?php endif; ?>
						
						<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
						  <ol class="carousel-indicators">
						    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
						    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
						    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
						  </ol>
						  <div class="carousel-inner">
						    <div class="carousel-item active">
						      <img class="d-block w-100" src="images/slider-image-1-1920x700.jpg" alt="First slide">
						    </div>
						    <div class="carousel-item">
						      <img class="d-block w-100" src="images/slider-image-2-1920x700.jpg" alt="Second slide">
						    </div>
						    <div class="carousel-item">
						      <img class="d-block w-100" src="images/slider-image-3-1920x700.jpg" alt="Third slide">
						    </div>
						  </div>
						  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
						    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
						    <span class="sr-only">Previous</span>
						  </a>
						  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
						    <span class="carousel-control-next-icon" aria-hidden="true"></span>
						    <span class="sr-only">Next</span>
						  </a>
						</div>

						<br>
						<br>

						<div class="inner">

							<!-- CUSTOMER ONLY WELCOME -->
							<?php if ($is_logged_in): ?>
								<div class="alert alert-success" role="alert" style="max-width: 800px; margin: 0 auto 30px;">
									<h4 class="alert-heading">Welcome back, <?php echo htmlspecialchars($customer_name); ?>!</h4>
									<p>You're now logged in. Enjoy exclusive member benefits and faster booking!</p>
									<hr>
									<p class="mb-0">
										<a href="fleet.php" class="btn btn-primary">Book a Car Now</a>
										<a href="profile.php" class="btn btn-outline-primary">Go to Profile</a>
									</p>
								</div>
							<?php endif; ?>

							<!-- About Us -->
							<header id="inner">
								<h1>Rent a car at low prices</h1>
								<p>CS331 Final Project Web Application By Zuhair Munawar, Sttiwell Peguero</p>

                                <!-- Sign-up prompt for guests only -->
								<?php if (!$is_logged_in && !$is_staff_logged_in): ?>
									<p><a href="create-account.php" class="button primary">Create Free Account</a> to get special discounts!</p>
								<?php endif; ?>
							</header>

							<br>

							<!-- Offers Section -->
							<h2 class="h2">Offers</h2>

							<section class="tiles">
								<article class="style1">
									<span class="image">
										<img src="images/other-1-720x480.jpg" alt="" />
									</span>
									<a href="offers.php">
										<h2>Lorem ipsum dolor sit amet, consectetur</h2>
										<p>price from: <strong> $ 140.00</strong> per weekend</p>
										<div class="content">
											<p>Vestibulum id est eu felis vulputate hendrerit. Suspendisse dapibus turpis in dui pulvinar imperdiet. Nunc consectetur.</p>
										</div>
									</a>
								</article>

								<article class="style2">
									<span class="image">
										<img src="images/other-2-720x480.jpg" alt="" />
									</span>
									<a href="offers.php">
										<h2>Lorem ipsum dolor sit amet, consectetur</h2>
										<p>price from: <strong> $ 140.00</strong> per weekend</p>
										<div class="content">
											<p>Vestibulum id est eu felis vulputate hendrerit. Suspendisse dapibus turpis in dui pulvinar imperdiet. Nunc consectetur.</p>
										</div>
									</a>
								</article>

								<article class="style3">
									<span class="image">
										<img src="images/other-3-720x480.jpg" alt="" />
									</span>
									<a href="offers.php">
										<h2>Lorem ipsum dolor sit amet, consectetur</h2>
										<p>price from: <strong> $ 140.00</strong> per weekend</p>
										<div class="content">
											<p>Vestibulum id est eu felis vulputate hendrerit. Suspendisse dapibus turpis in dui pulvinar imperdiet. Nunc consectetur.</p>
										</div>
									</a>
								</article>
							</section>

							<p class="text-center"><a href="offer.html">View Offers &nbsp;<i class="fa fa-long-arrow-right"></i></a></p>

							<br>

							<!-- Fleet Section -->
							<h2 class="h2">Fleet</h2>

							<section class="tiles">
								<article class="style4">
									<span class="image">
										<img src="images/other-1-720x480.jpg" alt="" />
									</span>
									<a href="fleet.php">
										<h2>Lorem ipsum dolor sit amet, consectetur</h2>
										<p>price from: <strong> $ 140.00</strong> per weekend</p>
										<p>
											<i class="fa fa-user"></i> 5 &nbsp;&nbsp;
											<i class="fa fa-briefcase"></i> 4 &nbsp;&nbsp;
											<i class="fa fa-sign-out"></i> 4 &nbsp;&nbsp;
											<i class="fa fa-cog"></i> A
										</p>
									</a>
								</article>

								<article class="style5">
									<span class="image">
										<img src="images/other-2-720x480.jpg" alt="" />
									</span>
									<a href="fleet.php">
										<h2>Lorem ipsum dolor sit amet, consectetur</h2>
										<p>price from: <strong> $ 140.00</strong> per weekend</p>
										<p>
											<i class="fa fa-user"></i> 5 &nbsp;&nbsp;
											<i class="fa fa-briefcase"></i> 4 &nbsp;&nbsp;
											<i class="fa fa-sign-out"></i> 4 &nbsp;&nbsp;
											<i class="fa fa-cog"></i> A
										</p>
									</a>
								</article>

								<article class="style6">
									<span class="image">
										<img src="images/other-3-720x480.jpg" alt="" />
									</span>
									<a href="fleet.php">
										<h2>Lorem ipsum dolor sit amet, consectetur</h2>
										<p>price from: <strong> $ 140.00</strong> per weekend</p>
										<p>
											<i class="fa fa-user"></i> 5 &nbsp;&nbsp;
											<i class="fa fa-briefcase"></i> 4 &nbsp;&nbsp;
											<i class="fa fa-sign-out"></i> 4 &nbsp;&nbsp;
											<i class="fa fa-cog"></i> A
										</p>
									</a>
								</article>
							</section>

							<p class="text-center"><a href="fleet.php">View Fleet &nbsp;<i class="fa fa-long-arrow-right"></i></a></p>

							<br>

							<!-- Blog Section -->
							<h2 class="h2">Blog</h2>
							
							<div class="row">
								<div class="col-sm-4 text-center">
									<img src="images/blog-1-720x480.jpg" class="img-fluid" alt="" />
									<h2 class="m-n"><a href="#">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</a></h2>
									<p> John Doe &nbsp;|&nbsp; 12/06/2020 10:30</p>
								</div>

								<div class="col-sm-4 text-center">
									<img src="images/blog-2-720x480.jpg" class="img-fluid" alt="" />
									<h2 class="m-n"><a href="#">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</a></h2>
									<p> John Doe &nbsp;|&nbsp; 12/06/2020 10:30</p>
								</div>

								<div class="col-sm-4 text-center">
									<img src="images/blog-3-720x480.jpg" class="img-fluid" alt="" />
									<h2 class="m-n"><a href="#">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</a></h2>
									<p> John Doe &nbsp;|&nbsp; 12/06/2020 10:30</p>
								</div>
							</div>

							<p class="text-center"><a href="blog.html">Read More &nbsp;<i class="fa fa-long-arrow-right"></i></a></p>

							<br>

							<!-- Testimonials Section -->
							<h2 class="h2">Testimonials</h2>
							
							<div class="row">
								<div class="col-sm-6 text-center">
									<p class="m-n"><em>"Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sunt delectus mollitia, debitis architecto recusandae? Quidem ipsa, quo, labore minima enim similique, delectus ullam non laboriosam laborum distinctio repellat quas deserunt voluptas reprehenderit dignissimos voluptatum deleniti saepe. Facere expedita autem quos."</em></p>
									<p><strong> - John Doe</strong></p>
								</div>

								<div class="col-sm-6 text-center">
									<p class="m-n"><em>"Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sunt delectus mollitia, debitis architecto recusandae? Quidem ipsa, quo, labore minima enim similique, delectus ullam non laboriosam laborum distinctio repellat quas deserunt voluptas reprehenderit dignissimos voluptatum deleniti saepe. Facere expedita autem quos."</em></p>
									<p><strong>- John Doe</strong> </p>
								</div>
							</div>

							<p class="text-center"><a href="testimonials.html">Read More &nbsp;<i class="fa fa-long-arrow-right"></i></a></p>
						</div>
					</div>

				<!-- Footer -->
					<footer id="footer">
						<div class="inner">
							<section>
								<h2>Contact Us</h2>
								<form method="post" action="#">
									<div class="fields">
										<div class="field half">
											<input type="text" name="name" id="name" placeholder="Name" />
										</div>

										<div class="field half">
											<input type="text" name="email" id="email" placeholder="Email" />
										</div>

										<div class="field">
											<input type="text" name="subject" id="subject" placeholder="subject" />
										</div>

										<div class="field">
											<textarea name="message" id="message" rows="3" placeholder="Notes"></textarea>
										</div>

										<div class="field text-right">
											<label>&nbsp;</label>

											<ul class="actions">
												<li><input type="submit" value="Send Message" class="primary" /></li>
											</ul>
										</div>
									</div>
								</form>
							</section>

							<section>
								<h2>Contact Info</h2>

								<ul class="alt">
									<li><span class="fa fa-envelope-o"></span> <a href="#">contact@company.com</a></li>
									<li><span class="fa fa-phone"></span> +1 333 4040 5566 </li>
									<li><span class="fa fa-map-pin"></span> 212 Barrington Court New York, ABC 10001 United States of America</li>
								</ul>

								<h2>Follow Us</h2>

								<ul class="icons">
									<li><a href="#" class="icon style2 fa-twitter"><span class="label">Twitter</span></a></li>
									<li><a href="#" class="icon style2 fa-facebook"><span class="label">Facebook</span></a></li>
									<li><a href="#" class="icon style2 fa-instagram"><span class="label">Instagram</span></a></li>
									<li><a href="#" class="icon style2 fa-linkedin"><span class="label">LinkedIn</span></a></li>
								</ul>
							</section>

							<ul class="copyright">
								<li>Copyright © 2020 Company Name </li>
								<li>Template by: <a href="https://www.phpjabbers.com/">PHPJabbers.com</a></li>
							</ul>
						</div>
					</footer>

			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/jquery.scrollex.min.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>
