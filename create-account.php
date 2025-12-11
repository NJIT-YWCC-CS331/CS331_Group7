<?php
include 'connect.php';

// Initialize variables
$name = $dob = $phone = $email = $password = $address = "";
$error_message = "";
$success_message = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    // Basic validation
    if (empty($name) || empty($email) || empty($password)) {
        $error_message = "Name, Email, and Password are required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } else {
        // Check if email already exists
        $check_sql = "SELECT CustomerID FROM Customer WHERE Email = '$email'";
        $check_result = $conn->query($check_sql);
        
        if ($check_result->num_rows > 0) {
            $error_message = "This email is already registered. Please use a different email.";
        } else {
            // Get the next CustomerID
            $max_id_sql = "SELECT MAX(CustomerID) as max_id FROM Customer";
            $max_id_result = $conn->query($max_id_sql);
            $row = $max_id_result->fetch_assoc();
            $next_id = $row['max_id'] + 1;
            
            // Insert new customer
            $sql = "INSERT INTO Customer (CustomerID, Name, DateOfBirth, Phone, Email, password, Address) 
                    VALUES ('$next_id', '$name', '$dob', '$phone', '$email', '$password', '$address')";
            
            if ($conn->query($sql) === TRUE) {
                $success_message = "Account created successfully! Your Customer ID is: " . $next_id;
                // Clear form fields
                $name = $dob = $phone = $email = $password = $address = "";
            } else {
                $error_message = "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Car Rental</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <style>
        .account-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .account-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .account-header h2 {
            color: #333;
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
        .btn-submit {
            width: 100%;
            padding: 12px;
            font-size: 16px;
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
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
                       <a href="login.php"  class="button small" >Login</a></p>
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
                <li><a href="offers.html">Offers</a></li>
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
                <div class="account-container">
                    <div class="account-header">
                        <h2>Create New Account</h2>
                        <p>Join our car rental service today!</p>
                    </div>

                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success">
                            <?php echo $success_message; ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="form-group">
                            <label for="name" class="required">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?php echo htmlspecialchars($name); ?>" 
                                   placeholder="Enter your full name" required>
                        </div>

                        <div class="form-group">
                            <label for="email" class="required">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($email); ?>" 
                                   placeholder="Enter your email" required>
                        </div>

                        <div class="form-group">
                            <label for="password" class="required">Password</label>
                            <input type="password" class="form-control" id="password" name="password" 
                                   value="<?php echo htmlspecialchars($password); ?>" 
                                   placeholder="Enter a password" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?php echo htmlspecialchars($phone); ?>" 
                                   placeholder="Enter your phone number">
                        </div>

                        <div class="form-group">
                            <label for="dob">Date of Birth</label>
                            <input type="date" class="form-control" id="dob" name="dob" 
                                   value="<?php echo htmlspecialchars($dob); ?>">
                        </div>

                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea class="form-control" id="address" name="address" 
                                      rows="3" placeholder="Enter your address"><?php echo htmlspecialchars($address); ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-submit">Create Account</button>
                    </form>

                    <div class="back-link">
                        <p>Already have an account? <a href="login.php">Login here</a></p>
                        <p><a href="index.php">← Back to Home</a></p>
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
$conn->close();
?>
