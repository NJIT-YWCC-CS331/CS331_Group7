<?php
// Start session for user authentication
session_start();

// Include the database connection
include 'connect.php';

// Initialize variables
$email = $password = "";
$error_message = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Basic validation
    if (empty($email) || empty($password)) {
        $error_message = "Email and Password are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } else {

        /* ---------------------------------------------------------
           STAFF LOGIN CHECK (new section added, rest unchanged)
        ----------------------------------------------------------- */

        $sql_staff = "
            SELECT Staff.EmployeeID, Staff.Name, Staff.Email, EmployeePass.Password 
            FROM Staff
            JOIN EmployeePass ON Staff.EmployeeID = EmployeePass.EmployeeID
            WHERE Staff.Email = '$email'
            LIMIT 1;
        ";
        $result_staff = mysqli_query($conn, $sql_staff);

        if ($result_staff && mysqli_num_rows($result_staff) == 1) {
            $staff = mysqli_fetch_assoc($result_staff);

            if ($password === $staff['Password']) {
                // Successful staff login
                $_SESSION['employee_id'] = $staff['EmployeeID'];
                $_SESSION['employee_name'] = $staff['Name'];
                $_SESSION['employee_email'] = $staff['Email'];
                $_SESSION['staff_logged_in'] = true;

                header("Location: AdminView.php");
                exit();
            }
        }

        /* ---------------------------------------------------------
           CUSTOMER LOGIN CHECK (your original code kept exactly)
        ----------------------------------------------------------- */

        $sql = "SELECT CustomerID, Name, Email, password FROM Customer WHERE Email = '$email'";
        $result = mysqli_query($conn, $sql);
        
        if ($result && mysqli_num_rows($result) == 1) {
            $customer = mysqli_fetch_assoc($result);
            
            // Check password (plain text comparison based on your database structure)
            if ($password == $customer['password']) {
                // Login successful - set session variables
                $_SESSION['customer_id'] = $customer['CustomerID'];
                $_SESSION['customer_name'] = $customer['Name'];
                $_SESSION['customer_email'] = $customer['Email'];
                $_SESSION['logged_in'] = true;
                
                // Redirect to home page or dashboard
                header("Location: index.php");
                exit();
            } else {
                $error_message = "Invalid email or password.";
            }
        } else {
            $error_message = "Invalid email or password.";
        }
    }
}

// If user is already logged in, redirect appropriately
if (isset($_SESSION['staff_logged_in']) && $_SESSION['staff_logged_in'] === true) {
    header("Location: AdminView.php");
    exit();
}

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Car Rental</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <style>
        .login-container {
            max-width: 400px;
            margin: 80px auto;
            padding: 30px;
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h2 {
            color: #333;
            margin-bottom: 10px;
        }
        .login-header p {
            color: #666;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
        .required::after {
            content: " *";
            color: red;
        }
        .alert {
            margin-bottom: 20px;
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            margin-bottom: 15px;
        }
        .links {
            text-align: center;
            margin-top: 20px;
        }
        .links a {
            display: block;
            margin: 5px 0;
            color: #007bff;
        }
        .links a:hover {
            text-decoration: underline;
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
                        <li><a href="create-account.php" class="button primary small" style="margin-left: 20px;">Create Account</a></li>
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
            </ul>
        </nav>

        <!-- Main Content -->
        <div id="main">
            <div class="inner">
                <div class="login-container">
                    <div class="login-header">
                        <h2>Customer Login</h2>
                        <p>Welcome back! Please sign in to your account.</p>
                    </div>

                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_GET['success']) && $_GET['success'] == 'registered'): ?>
                        <div class="alert alert-success">
                            Account created successfully! Please login with your credentials.
                        </div>
                    <?php endif; ?>

                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="form-group">
                            <label for="email" class="required">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($email); ?>" 
                                   placeholder="Enter your email" required autofocus>
                        </div>

                        <div class="form-group">
                            <label for="password" class="required">Password</label>
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Enter your password" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-login">Login</button>
                    </form>

                    <div class="links">
                        <a href="create-account.php">Don't have an account? Sign up</a>
                        <a href="index.php">← Back to Home</a>
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
