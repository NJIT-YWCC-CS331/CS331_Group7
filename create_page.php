<!DOCTYPE HTML>
<html>
	<head>
		<title>Create New Page | Car Rental Admin</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
		<style>
			.admin-container {
				max-width: 800px;
				margin: 50px auto;
				padding: 30px;
				background: white;
				border-radius: 10px;
				box-shadow: 0 0 20px rgba(0,0,0,0.1);
			}
			.admin-header {
				text-align: center;
				margin-bottom: 30px;
				border-bottom: 2px solid #f0f0f0;
				padding-bottom: 20px;
			}
			.form-group {
				margin-bottom: 25px;
			}
			label {
				font-weight: bold;
				margin-bottom: 8px;
				display: block;
			}
			.alert {
				padding: 15px;
				border-radius: 5px;
				margin-bottom: 20px;
			}
			.alert-success {
				background-color: #d4edda;
				border-color: #c3e6cb;
				color: #155724;
			}
			.alert-danger {
				background-color: #f8d7da;
				border-color: #f5c6cb;
				color: #721c24;
			}
			.btn-admin {
				background: #2a9fd6;
				color: white;
				padding: 12px 30px;
				border: none;
				border-radius: 5px;
				font-size: 16px;
				cursor: pointer;
				transition: background 0.3s;
			}
			.btn-admin:hover {
				background: #2185c5;
			}
			.preview-area {
				background: #f8f9fa;
				border: 1px solid #dee2e6;
				border-radius: 5px;
				padding: 20px;
				margin-top: 20px;
				max-height: 300px;
				overflow-y: auto;
			}
			.back-link {
				display: inline-block;
				margin-top: 20px;
				color: #2a9fd6;
				text-decoration: none;
			}
			.back-link:hover {
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
							<a href="index.html" class="logo">
								<span class="fa fa-car"></span> <span class="title">CAR RENTAL ADMIN</span>
							</a>

						<!-- Nav -->
							<nav>
								<ul>
									<li><a href="#menu">Menu</a></li>
								</ul>
							</nav>

					</div>
				</header>

			<!-- Menu -->
				<nav id="menu">
					<h2>Menu</h2>
					<ul>
						<li><a href="index.html">Home</a></li>
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
						<li><a href="create_page.php">Create Page (Admin)</a></li>
					</ul>
				</nav>

				<!-- Main -->
					<div id="main">
						<div class="inner">
							<div class="admin-container">
								<div class="admin-header">
									<h1><span class="fa fa-file-text"></span> Create New Page</h1>
									<p>Add new HTML/PHP pages to your website</p>
								</div>

								<?php
								// Initialize variables
								$message = '';
								$message_type = '';
								$preview_content = '';
								
								// Check if form is submitted
								if ($_SERVER['REQUEST_METHOD'] === 'POST') {
									// Sanitize inputs
									$page_title = htmlspecialchars(trim($_POST['page_title'] ?? ''));
									$page_slug = trim($_POST['page_slug'] ?? '');
									$page_content = $_POST['page_content'] ?? '';
									$page_type = $_POST['page_type'] ?? 'html';
									
									// Basic validation
									if (empty($page_title) || empty($page_slug) || empty($page_content)) {
										$message = 'Please fill in all required fields.';
										$message_type = 'danger';
									} else {
										// Create filename
										$filename = $page_slug . '.' . $page_type;
										
										// Create the HTML/PHP template
										if ($page_type === 'php') {
											$file_content = <<<HTML
<!DOCTYPE HTML>
<html>
	<head>
		<title>$page_title | Car Rental Website</title>
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
							<a href="index.html" class="logo">
								<span class="fa fa-car"></span> <span class="title">CAR RENTAL WEBSITE</span>
							</a>

						<!-- Nav -->
							<nav>
								<ul>
									<li><a href="#menu">Menu</a></li>
								</ul>
							</nav>

					</div>
				</header>

			<!-- Menu -->
				<nav id="menu">
					<h2>Menu</h2>
					<ul>
						<li><a href="index.html">Home</a></li>
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
						<li><a href="create_page.php">Create Page (Admin)</a></li>
					</ul>
				</nav>

				<!-- Main -->
					<div id="main">
						<div class="inner">
							<h1>$page_title</h1>
							
							<div class="content">
								$page_content
							</div>
						</div>
					</div>

				<!-- Footer -->
					<footer id="footer">
						<div class="inner">
							<section>
								<ul class="icons">
									<li><a href="#" class="icon style2 fa-twitter"><span class="label">Twitter</span></a></li>
									<li><a href="#" class="icon style2 fa-facebook"><span class="label">Facebook</span></a></li>
									<li><a href="#" class="icon style2 fa-instagram"><span class="label">Instagram</span></a></li>
									<li><a href="#" class="icon style2 fa-linkedin"><span class="label">LinkedIn</span></a></li>
								</ul>
								&nbsp;
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
HTML;
										} else {
											// For HTML files, remove PHP tags
											$file_content = <<<HTML
<!DOCTYPE HTML>
<html>
	<head>
		<title>$page_title | Car Rental Website</title>
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
							<a href="index.html" class="logo">
								<span class="fa fa-car"></span> <span class="title">CAR RENTAL WEBSITE</span>
							</a>

						<!-- Nav -->
							<nav>
								<ul>
									<li><a href="#menu">Menu</a></li>
								</ul>
							</nav>

					</div>
				</header>

			<!-- Menu -->
				<nav id="menu">
					<h2>Menu</h2>
					<ul>
						<li><a href="index.html">Home</a></li>
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

				<!-- Main -->
					<div id="main">
						<div class="inner">
							<h1>$page_title</h1>
							
							<div class="content">
								$page_content
							</div>
						</div>
					</div>

				<!-- Footer -->
					<footer id="footer">
						<div class="inner">
							<section>
								<ul class="icons">
									<li><a href="#" class="icon style2 fa-twitter"><span class="label">Twitter</span></a></li>
									<li><a href="#" class="icon style2 fa-facebook"><span class="label">Facebook</span></a></li>
									<li><a href="#" class="icon style2 fa-instagram"><span class="label">Instagram</span></a></li>
									<li><a href="#" class="icon style2 fa-linkedin"><span class="label">LinkedIn</span></a></li>
								</ul>
								&nbsp;
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
HTML;
										}
										
										// Try to write the file
										try {
											if (file_put_contents($filename, $file_content) !== false) {
												$message = "Page created successfully! File saved as: <strong>$filename</strong>";
												$message_type = 'success';
												
												// Clear form
												$page_title = $page_slug = $page_content = '';
												$page_type = 'html';
											} else {
												$message = 'Error creating file. Please check directory permissions.';
												$message_type = 'danger';
											}
										} catch (Exception $e) {
											$message = 'Error: ' . $e->getMessage();
											$message_type = 'danger';
										}
									}
								}
								
								// Display message if any
								if ($message) {
									echo "<div class='alert alert-$message_type'>$message</div>";
								}
								?>
								
								<form method="POST" action="">
									<div class="form-group">
										<label for="page_title">Page Title *</label>
										<input type="text" id="page_title" name="page_title" 
											   class="form-control" required 
											   value="<?php echo htmlspecialchars($page_title ?? ''); ?>"
											   placeholder="e.g., Services, Pricing, Locations">
									</div>
									
									<div class="form-group">
										<label for="page_slug">Page Slug/URL *</label>
										<div class="input-group">
											<input type="text" id="page_slug" name="page_slug" 
												   class="form-control" required 
												   value="<?php echo htmlspecialchars($page_slug ?? ''); ?>"
												   placeholder="e.g., services, pricing, locations">
											<div class="input-group-append">
												<select id="page_type" name="page_type" class="form-control" style="max-width: 100px;">
													<option value="html" <?php echo ($page_type ?? 'html') === 'html' ? 'selected' : ''; ?>>.html</option>
													<option value="php" <?php echo ($page_type ?? 'html') === 'php' ? 'selected' : ''; ?>>.php</option>
												</select>
											</div>
										</div>
										<small class="form-text text-muted">This will be the filename (e.g., "services.html")</small>
									</div>
									
									<div class="form-group">
										<label for="page_content">Page Content *</label>
										<textarea id="page_content" name="page_content" 
												  class="form-control" rows="10" required
												  placeholder="Enter your page content here (HTML allowed)..."><?php echo htmlspecialchars($page_content ?? ''); ?></textarea>
										<small class="form-text text-muted">You can use HTML tags for formatting</small>
									</div>
									
									<div class="form-group">
										<button type="submit" class="btn-admin">
											<span class="fa fa-plus"></span> Create Page
										</button>
										<button type="button" class="btn-admin" onclick="previewContent()" style="background: #6c757d;">
											<span class="fa fa-eye"></span> Preview
										</button>
									</div>
								</form>
								
								<?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($page_content)): ?>
								<div class="preview-area">
									<h4>Content Preview:</h4>
									<hr>
									<?php echo nl2br(htmlspecialchars($page_content)); ?>
								</div>
								<?php endif; ?>
								
								<div class="existing-pages">
									<h4>Existing Pages:</h4>
									<ul>
										<?php
										// List existing HTML/PHP files
										$files = glob('*.{html,php}', GLOB_BRACE);
										foreach ($files as $file) {
											$filename = basename($file);
											echo "<li><a href='$filename' target='_blank'>$filename</a></li>";
										}
										?>
									</ul>
								</div>
								
								<a href="index.html" class="back-link">
									<span class="fa fa-arrow-left"></span> Back to Home
								</a>
							</div>
						</div>
					</div>

				<!-- Footer -->
					<footer id="footer">
						<div class="inner">
							<section>
								<ul class="icons">
									<li><a href="#" class="icon style2 fa-twitter"><span class="label">Twitter</span></a></li>
									<li><a href="#" class="icon style2 fa-facebook"><span class="label">Facebook</span></a></li>
									<li><a href="#" class="icon style2 fa-instagram"><span class="label">Instagram</span></a></li>
									<li><a href="#" class="icon style2 fa-linkedin"><span class="label">LinkedIn</span></a></li>
								</ul>
								&nbsp;
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
			
			<script>
				function previewContent() {
					const content = document.getElementById('page_content').value;
					if (content.trim() === '') {
						alert('Please enter some content to preview.');
						return;
					}
					
					// Create a preview window
					const previewWindow = window.open('', '_blank');
					previewWindow.document.write(`
						<!DOCTYPE html>
						<html>
						<head>
							<title>Content Preview</title>
							<style>
								body { font-family: Arial, sans-serif; padding: 20px; }
								.preview-container { max-width: 800px; margin: 0 auto; }
							</style>
						</head>
						<body>
							<div class="preview-container">
								<h2>Content Preview</h2>
								<hr>
								<div>${content.replace(/\n/g, '<br>')}</div>
							</div>
						</body>
						</html>
					`);
					previewWindow.document.close();
				}
				
				// Auto-generate slug from title
				document.getElementById('page_title').addEventListener('input', function() {
					const titleInput = this;
					const slugInput = document.getElementById('page_slug');
					
					if (!slugInput.value || slugInput.value === slugInput.defaultValue) {
						const slug = titleInput.value
							.toLowerCase()
							.replace(/[^\w\s]/gi, '')
							.replace(/\s+/g, '-')
							.replace(/^-+|-+$/g, '');
						slugInput.value = slug;
					}
				});
			</script>
	</body>
</html>
