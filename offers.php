<?php
// Enable error reporting at the top
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session to check login status
session_start();
include 'connect.php';

// Handle logout if requested
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_unset();
    session_destroy();
    session_start();
    $_SESSION['logout_message'] = "You have been successfully signed out.";
    header("Location: offers.php");
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
if (isset($_SESSION['logout_message'])) {
    unset($_SESSION['logout_message']);
}

// Check database connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Get search parameters
$make = isset($_GET['make']) ? trim($_GET['make']) : '';
$model = isset($_GET['model']) ? trim($_GET['model']) : '';
$year = isset($_GET['year']) ? trim($_GET['year']) : '';

?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Car Offers & Search | Car Rental Website</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>

		<style>
			.data-table {
				width: 100%;
				border-collapse: collapse;
				margin: 20px 0;
			}
			.data-table th, .data-table td {
				border: 1px solid #ddd;
				padding: 12px;
				text-align: left;
			}
			.data-table th {
				background-color: #f2f2f2;
				font-weight: bold;
			}
			.data-table tr:nth-child(even) {
				background-color: #f9f9f9;
			}
			.data-table tr:hover {
				background-color: #f5f5f5;
			}
			.search-container {
				background: #f8f9fa;
				padding: 20px;
				border-radius: 5px;
				margin-bottom: 20px;
			}
		</style>
	</head>

	<body class="is-preload">
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
								<li><a href="offers.php?action=logout" class="button small" style="margin-left: 10px;">Logout</a></li>

							<!-- CUSTOMER LOGGED IN -->
							<?php elseif ($is_logged_in): ?>
								<li><span class="welcome-message" style="margin-left: 20px; font-weight: bold;">
									Welcome, <?php echo htmlspecialchars($customer_name); ?>!
								</span></li>
								<li><a href="profile.php" class="button small" style="margin-left: 10px;">Profile</a></li>
                                <li><a href="offers.php?action=logout" class="button small" style="margin-left: 10px;">Logout</a></li>

							<!-- GUEST -->
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
					<li><a href="index.php">Home</a></li>
					<li><a href="offers.php" class="active">Offers</a></li>
					<li><a href="fleet.php">Fleet</a></li>

					<!-- STAFF-ONLY MENU -->
					<?php if ($is_staff_logged_in): ?>
						<li><hr></li>
						<li><a href="AdminView.php" style="color: #d9534f; font-weight: bold;">Admin View</a></li>
						<li><a href="AdminReservations.php" style="color: #d9534f;">Admin Reservations</a></li>
						<li><a href="offers.php?action=logout" style="color: #d9534f;">Logout</a></li>
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

					<!-- CUSTOMER MENU -->
					<?php if ($is_logged_in): ?>
						<li><hr></li>
						<li><a href="dashboard.php" style="color: #4CAF50; font-weight: bold;">My Dashboard</a></li>
						<li><a href="profile.php">My Profile</a></li>
						<li><a href="my-bookings.php">My Bookings</a></li>
					<?php endif; ?>
				</ul>
			</nav>

			<!-- Main Content -->
			<div id="main">

				<?php if (!empty($logout_message)): ?>
					<div class="alert alert-info" style="margin: 0 auto; text-align: center; max-width: 800px; border-radius: 0;">
						<?php echo htmlspecialchars($logout_message); ?>
					</div>
				<?php endif; ?>

				<div class="inner">
					<h1>Car Database</h1>

					<!-- SEARCH FORM -->
					<div class="search-container">
						<h2>Search Cars</h2>
						<form method="GET" action="offers.php">
							<div class="row">
								<div class="col-md-4">
									<input type="text" name="make" placeholder="Make" class="form-control" value="<?php echo htmlspecialchars($make); ?>">
								</div>
								<div class="col-md-4">
									<input type="text" name="model" placeholder="Model" class="form-control" value="<?php echo htmlspecialchars($model); ?>">
								</div>
								<div class="col-md-2">
									<input type="number" name="year" placeholder="Year" class="form-control" value="<?php echo htmlspecialchars($year); ?>">
								</div>
								<div class="col-md-2">
									<button type="submit" class="btn btn-primary btn-block">Search</button>
								</div>
							</div>
						</form>
					</div>

					<!-- DATABASE RESULTS -->
					<div class="database-results">
						<?php
						// Build SQL query based on search parameters
						$sql = "SELECT * FROM Car WHERE 1=1";
						$params = [];
						$types = "";
						
						if (!empty($make)) { $sql .= " AND Make LIKE ?"; $params[] = "%$make%"; $types .= "s"; }
						if (!empty($model)) { $sql .= " AND Model LIKE ?"; $params[] = "%$model%"; $types .= "s"; }
						if (!empty($year)) { $sql .= " AND Year = ?"; $params[] = $year; $types .= "i"; }

						$sql .= " ORDER BY CarID";

						$rows = [];

						if (count($params) > 0) {
							$stmt = $conn->prepare($sql);
							$stmt->bind_param($types, ...$params);
							$stmt->execute();
							$result = $stmt->get_result();
						} else {
							$result = $conn->query($sql);
						}

						if ($result && $result->num_rows > 0) {
							echo '<h3>' . (!empty($make) || !empty($model) || !empty($year) ? 'Search Results' : 'All Car Data') . '</h3>';
							echo '<p>Found ' . $result->num_rows . ' car(s)</p>';

							echo '<table class="data-table"><thead><tr>
								<th>CarID</th><th>Make</th><th>Model</th>
								<th>Year</th><th>Daily Rate</th><th>BranchID</th>
							</tr></thead><tbody>';

							while ($row = $result->fetch_assoc()) {
								echo "<tr>
									<td>{$row['CarID']}</td>
									<td>" . htmlspecialchars($row['Make']) . "</td>
									<td>" . htmlspecialchars($row['Model']) . "</td>
									<td>{$row['Year']}</td>
									<td>$" . number_format($row['DailyRate'], 2) . "</td>
									<td>{$row['BranchID']}</td>
								</tr>";
							}

							echo '</tbody></table>';

							if (!empty($make) || !empty($model) || !empty($year)) {
								echo '<p><a href="offers.php" class="btn btn-secondary">Show All Cars</a></p>';
							}

						} else {
							echo '<div class="alert alert-warning">
								<h3>No cars found</h3>
								<p>No cars match your search criteria.</p>
								<a href="offers.php" class="btn btn-primary">Reset Search</a>
							</div>';
						}
						
						$conn->close();
						?>
					</div>

				</div>
			</div>

			<!-- Footer -->
			<footer id="footer">
				<div class="inner">
					<section>
						<h2>Contact Us</h2>
						<form method="post" action="#">
							<div class="fields">
								<div class="field half"><input type="text" name="name" id="name" placeholder="Name" /></div>
								<div class="field half"><input type="text" name="email" id="email" placeholder="Email" /></div>
								<div class="field"><input type="text" name="subject" id="subject" placeholder="Subject" /></div>
								<div class="field"><textarea name="message" id="message" rows="3" placeholder="Notes"></textarea></div>

								<div class="field text-right">
									<ul class="actions"><li><input type="submit" value="Send Message" class="primary" /></li></ul>
								</div>
							</div>
						</form>
					</section>

					<section>
						<h2>Contact Info</h2>
						<ul class="alt">
							<li><span class="fa fa-envelope-o"></span> contact@company.com</li>
							<li><span class="fa fa-phone"></span> +1 333 4040 5566</li>
							<li><span class="fa fa-map-pin"></span> 212 Barrington Court, New York, ABC 10001</li>
						</ul>

						<h2>Follow Us</h2>
						<ul class="icons">
							<li><a href="#" class="icon style2 fa-twitter"></a></li>
							<li><a href="#" class="icon style2 fa-facebook"></a></li>
							<li><a href="#" class="icon style2 fa-instagram"></a></li>
							<li><a href="#" class="icon style2 fa-linkedin"></a></li>
						</ul>
					</section>

					<ul class="copyright">
						<li>Copyright © 2020 Company Name</li>
						<li>Template by: PHPJabbers.com</li>
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
