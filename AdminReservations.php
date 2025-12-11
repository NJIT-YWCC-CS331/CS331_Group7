<?php
session_start();
include 'connect.php';

// Handle staff logout
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_unset();
    session_destroy();
    session_start();
    $_SESSION['logout_message'] = "You have been successfully signed out.";
    header("Location: AdminReservations.php");
    exit();
}

// Detect if staff is logged in
$is_staff_logged_in = isset($_SESSION['staff_logged_in']) && $_SESSION['staff_logged_in'] === true;
$staff_name = $is_staff_logged_in ? $_SESSION['employee_name'] : '';

// Logout message
$logout_message = isset($_SESSION['logout_message']) ? $_SESSION['logout_message'] : '';
if (isset($_SESSION['logout_message'])) {
    unset($_SESSION['logout_message']);
}

// Main query
$query = "
    SELECT 
        r.RentalID,
        c.CustomerID,
        c.Name AS CustomerName,
        c.Email,
        c.Phone,
        car.Make,
        car.Model,
        car.Year,
        r.StartDate,
        r.EndDate,
        r.DailyRate,
        r.TotalCost
    FROM Rental r
    INNER JOIN Customer c ON r.CustomerID = c.CustomerID
    INNER JOIN Car car ON r.CarID = car.CarID
    ORDER BY r.RentalID ASC
";

$result = mysqli_query($conn, $query);
if (!$result) die("Query failed: " . mysqli_error($conn));
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Admin Reservations</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
</head>
<body class="is-preload">
<div id="wrapper">

    <!-- Header -->
    <header id="header">
        <div class="inner">
            <a href="index.php" class="logo"><span class="fa fa-car"></span> CAR RENTAL WEBSITE</a>

            <nav>
                <ul>

                    <?php if ($is_staff_logged_in): ?>
                        <li>
                            <span class="welcome-message" style="margin-left: 20px; font-weight: bold;">
                                Welcome, <?= htmlspecialchars($staff_name); ?>!
                            </span>
                        </li>
                        <li>
                            <a href="AdminReservations.php?action=logout" class="button small" style="margin-left: 10px;">
                                Logout
                            </a>
                        </li>
                    <?php else: ?>
                        <li><a href="login.php" class="button small" style="margin-left: 20px;">Login</a></li>
                        <li><a href="create-account.php" class="button small" style="margin-left: 10px;">Create Account</a></li>
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
            <li><a href="offers.php">Offers</a></li>
            <li><a href="fleet.php">Fleet</a></li>
            <li><a href="AdminView.php">AdminView</a></li>
            <li><a href="AdminReservations.php">Admin Reservations</a></li>
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

            <!-- Logout Message -->
            <?php if (!empty($logout_message)): ?>
                <div class="alert alert-info" style="margin-bottom: 20px;">
                    <?= htmlspecialchars($logout_message); ?>
                </div>
            <?php endif; ?>

            <h1>Rental Reservations</h1>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Rental ID</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Car</th>
                        <th>Year</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Daily Rate</th>
                        <th>Total Cost</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row['RentalID']) ?></td>
                        <td><?= htmlspecialchars($row['CustomerName']) ?></td>
                        <td><?= htmlspecialchars($row['Email']) ?></td>
                        <td><?= htmlspecialchars($row['Phone']) ?></td>
                        <td><?= htmlspecialchars($row['Make'] . ' ' . $row['Model']) ?></td>
                        <td><?= htmlspecialchars($row['Year']) ?></td>
                        <td><?= htmlspecialchars($row['StartDate']) ?></td>
                        <td><?= htmlspecialchars($row['EndDate']) ?></td>
                        <td>$<?= htmlspecialchars(number_format((float)$row['DailyRate'], 2)) ?></td>
                        <td>$<?= htmlspecialchars(number_format((float)$row['TotalCost'], 2)) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        </div>
    </div>

</div>

<!-- Scripts -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/jquery.scrolly.min.js"></script>
<script src="assets/js/jquery.scrollex.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
