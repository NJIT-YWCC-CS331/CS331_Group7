<?php
// Start session and check login
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'connect.php';

// Get customer ID from session
$customer_id = $_SESSION['customer_id'];
$customer_name = $_SESSION['customer_name'];

// Get customer details - using mysqli_query instead of prepared statements
$customer_sql = "SELECT * FROM Customer WHERE CustomerID = $customer_id";
$customer_result = mysqli_query($conn, $customer_sql);

if (!$customer_result) {
    die("Error getting customer info: " . mysqli_error($conn));
}

$customer = mysqli_fetch_assoc($customer_result);

// If customer not found
if (!$customer) {
    $customer = array(
        'Name' => $customer_name,
        'Email' => 'Not available',
        'Phone' => 'Not available',
        'DateOfBirth' => 'Not available',
        'CustomerID' => $customer_id,
        'Address' => 'Not available'
    );
}

// Get rental information - using mysqli_query instead of prepared statements
$rental_sql = "SELECT * FROM Rental WHERE CustomerID = $customer_id ORDER BY StartDate DESC";
$rental_result = mysqli_query($conn, $rental_sql);

if (!$rental_result) {
    die("Error getting rental info: " . mysqli_error($conn));
}

$rental_count = mysqli_num_rows($rental_result);
?>

<!DOCTYPE HTML>
<html>
    <head>
        <title>My Profile - Car Rental</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" href="assets/css/main.css" />
        <noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
        <style>
            .profile-container {
                max-width: 1200px;
                margin: 30px auto;
                padding: 20px;
            }
            .profile-header {
                background-color: #f8f9fa;
                padding: 30px;
                border-radius: 10px;
                margin-bottom: 30px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            .profile-info {
                margin-bottom: 30px;
            }
            .rental-card {
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 20px;
                margin-bottom: 20px;
                background-color: #fff;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            }
            .rental-card h4 {
                color: #333;
                margin-bottom: 15px;
            }
            .rental-details {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 15px;
                margin-bottom: 15px;
            }
            .detail-item {
                padding: 10px;
                background-color: #f8f9fa;
                border-radius: 5px;
            }
            .detail-label {
                font-weight: bold;
                color: #666;
                font-size: 0.9em;
            }
            .detail-value {
                font-size: 1.1em;
                color: #333;
            }
            .no-rentals {
                text-align: center;
                padding: 40px;
                background-color: #f8f9fa;
                border-radius: 10px;
                color: #666;
            }
            .action-buttons {
                margin-top: 20px;
                text-align: center;
            }
        </style>
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
                            <li><a href="#menu">Menu</a></li>
                            <li><span class="welcome-message" style="margin-left: 20px; font-weight: bold;">
                                Welcome, <?php echo htmlspecialchars($customer_name); ?>!
                            </span></li>
                            <li><a href="profile.php" class="button small" style="margin-left: 10px;">Profile</a></li>
                            <li><a href="index.php?action=logout" class="button small" style="margin-left: 10px;">Logout</a></li>
                        </ul>
                    </nav>
                </div>
            </header>

            <!-- Menu -->
            <nav id="menu">
                <h2>Menu</h2>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="offers.php">Offers</a></li>
                    <li><a href="fleet.php">Fleet</a></li>
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
                    <li><hr></li>
                    <li><a href="dashboard.php" style="color: #4CAF50; font-weight: bold;">My Dashboard</a></li>
                    <li><a href="profile.php" class="active">My Profile</a></li>
                    <li><a href="my-bookings.php">My Bookings</a></li>
                </ul>
            </nav>

            <!-- Main -->
            <div id="main">
                <div class="inner">
                    <div class="profile-container">
                        <!-- Profile Header -->
                        <div class="profile-header">
                            <h1>My Profile</h1>
                            <div class="profile-info">
                                <h3>Personal Information</h3>
                                <div class="rental-details">
                                    <div class="detail-item">
                                        <div class="detail-label">Name</div>
                                        <div class="detail-value"><?php echo htmlspecialchars($customer['Name']); ?></div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Email</div>
                                        <div class="detail-value"><?php echo htmlspecialchars($customer['Email']); ?></div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Phone</div>
                                        <div class="detail-value"><?php echo htmlspecialchars($customer['Phone']); ?></div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Date of Birth</div>
                                        <div class="detail-value"><?php echo htmlspecialchars($customer['DateOfBirth']); ?></div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Customer ID</div>
                                        <div class="detail-value"><?php echo $customer['CustomerID']; ?></div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Address</div>
                                        <div class="detail-value"><?php echo htmlspecialchars($customer['Address']); ?></div>
                                    </div>
                                </div>
                            </div>
                            
                            <h3>My Rental History (<?php echo $rental_count; ?> rentals)</h3>
                        </div>

                        <!-- Rental History -->
                        <?php if ($rental_count > 0): ?>
                            <?php while ($rental = mysqli_fetch_assoc($rental_result)): ?>
                                <div class="rental-card">
                                    <h4>Rental #<?php echo $rental['RentalID']; ?> - Car ID: <?php echo $rental['CarID']; ?></h4>
                                    <div class="rental-details">
                                        <div class="detail-item">
                                            <div class="detail-label">Rental ID</div>
                                            <div class="detail-value"><?php echo $rental['RentalID']; ?></div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="detail-label">Car ID</div>
                                            <div class="detail-value"><?php echo $rental['CarID']; ?></div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="detail-label">Start Date</div>
                                            <div class="detail-value"><?php echo htmlspecialchars($rental['StartDate']); ?></div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="detail-label">End Date</div>
                                            <div class="detail-value"><?php echo htmlspecialchars($rental['EndDate']); ?></div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="detail-label">Daily Rate</div>
                                            <div class="detail-value">$<?php echo number_format($rental['DailyRate'], 2); ?></div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="detail-label">Total Cost</div>
                                            <div class="detail-value">$<?php echo number_format($rental['TotalCost'], 2); ?></div>
                                        </div>
                                        
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="no-rentals">
                                <h3>No Rental History Found</h3>
                                <p>You haven't rented any cars yet.</p>
                                <a href="fleet.php" class="button primary">Browse Available Cars</a>
                            </div>
                        <?php endif; ?>

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <a href="index.php" class="button">Back to Home</a>
                            <a href="fleet.php" class="button primary">Book Another Car</a>
                        </div>
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
                                <div class="field half">
                                    <input type="text" name="name" id="name" placeholder="Name" />
                                </div>
                                <div class="field half">
                                    <input type="text" name="email" id="email" placeholder="Email" />
                                </div>
                                <div class="field">
                                    <input type="text" name="subject" id="subject" placeholder="Subject" />
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

<?php
// Close database connection
if (isset($conn)) mysqli_close($conn);
?>