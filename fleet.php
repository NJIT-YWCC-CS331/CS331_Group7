<?php
session_start();
include 'connect.php';

// User must be logged in to reserve
$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$customer_id = $is_logged_in ? $_SESSION['customer_id'] : null;

$error_message = ""; // <-- NEW

// Handle reservation submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!$is_logged_in) {
        header("Location: login.php?error=login_required");
        exit();
    }

    $carID = intval($_POST['vehicle']);
    $start = $_POST['date_from'];
    $end = $_POST['date_to'];

    // Get daily rate for selected car
    $rate_query = $conn->prepare("SELECT DailyRate FROM Car WHERE CarID = ?");
    $rate_query->bind_param("i", $carID);
    $rate_query->execute();
    $rate_query->bind_result($dailyRate);
    $rate_query->fetch();
    $rate_query->close();

    if (!$dailyRate) {
        $error_message = "Invalid car selected.";
    }

    // Calculate days
    $days = (strtotime($end) - strtotime($start)) / 86400;

    // Validation: invalid range
    if ($days <= 0) {
        $error_message = "Invalid date range.";
    }

    if (empty($error_message)) {

        $totalCost = $days * $dailyRate;

        // Get next RentalID
        $max_id_result = $conn->query("SELECT MAX(RentalID) AS max_id FROM Rental");
        $row = $max_id_result->fetch_assoc();
        $next_rental_id = $row['max_id'] + 1;

        // Insert new rental
        $insert = $conn->prepare("
            INSERT INTO Rental (RentalID, CustomerID, CarID, StartDate, EndDate, DailyRate, TotalCost)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $insert->bind_param("iiissdd", $next_rental_id, $customer_id, $carID, $start, $end, $dailyRate, $totalCost);
        $insert->execute();
        $insert->close();

        header("Location: fleet.php?success=1");
        exit();
    }
}

// Handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>PHPJabbers.com | Free Car Rental Website Template</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
</head>
<body class="is-preload">
<div id="wrapper">

    <!-- Header -->
    <header id="header">
        <div class="inner">

            <a href="index.php" class="logo">
                <span class="fa fa-car"></span> <span class="title">CAR RENTAL WEBSITE</span>
            </a>

            <nav>
                <ul>
                    <?php if ($is_logged_in): ?>
                        <li><b>Welcome, <?= htmlspecialchars($_SESSION['customer_name']) ?>! </b></li>
                        <li><a href="fleet.php?action=logout" class="button small">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php" class="button small">Login</a></li>
                        <li><a href="create-account.php" class="button small">Create Account</a></li>
                    <?php endif; ?>
                    <li><a href="#menu">Menu</a></li>
                </ul>
            </nav>

        </div>
    </header>

    <!-- Sidebar Menu -->
    <nav id="menu">
        <h2>Menu</h2>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="offers.php">Offers</a></li>
            <li><a href="fleet.php" class="active">Fleet</a></li>
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
        </ul>
    </nav>

    <!-- Main Content -->
    <div id="main">
        <div class="inner">
            <h1>Fleet</h1>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    Reservation successfully created!
                </div>
            <?php endif; ?>

            <div class="image main">
                <img src="images/banner-image-7-1920x500.jpg" class="img-fluid" alt="" />
            </div>

            <!-- Fleet Tiles -->
            <section class="tiles">
                <?php
                $cars = $conn->query("SELECT CarID, Make, Model, Year, DailyRate FROM Car");
                $styles = ["style1","style2","style3","style4","style5","style6"];
                $i = 0;
                while ($row = $cars->fetch_assoc()):
                    $style = $styles[$i % count($styles)];
                    $img_index = ($i % 6) + 1;
                ?>
                <article class="<?= $style ?>">
                    <span class="image">
                        <img src="images/product-<?= $img_index ?>-720x480.jpg" alt="" />
                    </span>
                    <a href="#footer" class="scrolly">
                        <h2><?= htmlspecialchars($row['Make'] . " " . $row['Model']) ?></h2>
                        <p>price from: <strong>$<?= $row['DailyRate'] ?></strong> per day</p>
                        <p>
                            <i class="fa fa-user"></i> 5 &nbsp;&nbsp;
                            <i class="fa fa-briefcase"></i> 4 &nbsp;&nbsp;
                            <i class="fa fa-sign-out"></i> 4 &nbsp;&nbsp;
                            <i class="fa fa-cog"></i> A
                        </p>
                    </a>
                </article>
                <?php $i++; endwhile; ?>
            </section>
        </div>
    </div>

    <!-- Footer / Booking Form -->
    <footer id="footer">
        <div class="inner">
            <section>
                <h2>Book now</h2>

                <!-- ERROR MESSAGE DISPLAY HERE -->
                <?php if (!empty($error_message)): ?>
                    <p style="color:red; font-weight:bold;"><?= $error_message ?></p>
                <?php endif; ?>

                <?php if (!$is_logged_in): ?>
                    <p style="color:red; font-weight:bold;">You must log in before making a reservation.</p>
                <?php endif; ?>

                <form method="post" action="fleet.php">
                    <div class="fields">

                        <div class="field half">
                            <select name="vehicle" required>
                                <option value="">Select Car</option>
                                <?php
                                $cars_list = $conn->query("SELECT CarID, Make, Model, Year, DailyRate FROM Car");
                                while ($row = $cars_list->fetch_assoc()):
                                ?>
                                <option value="<?= $row['CarID'] ?>">
                                    <?= $row['Make'] . " " . $row['Model'] . " (" . $row['Year'] . ") — $" . $row['DailyRate'] ?>/day
                                </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="field half">
                            <input type="date" name="date_from" required>
                        </div>

                        <div class="field half">
                            <input type="date" name="date_to" required>
                        </div>

                        <div class="field text-right">
                            <ul class="actions">
                                <li><input type="submit" value="Book now" class="primary" <?= $is_logged_in ? "" : "disabled" ?>></li>
                            </ul>
                        </div>

                    </div>
                </form>
            </section>

            <section>
                <h2>Contact Info</h2>
                <ul class="alt">
                    <li><span class="fa fa-envelope-o"></span> <a href="#">contact@company.com</a></li>
                    <li><span class="fa fa-phone"></span> +1 333 4040 5566</li>
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
