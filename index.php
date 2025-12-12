<?php
// start session
session_start();

// include connection file
include "config.php";

$login_error = ""; // for SweetAlert display

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {

    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Validate fields
    if (empty($email) || empty($password)) {
        $login_error = "Email and password are required";
    } else {

        // Check user in DB
        $stmt = $conn->prepare("SELECT id, role, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {
            $login_error = "Account not found!";
        } else {

            $stmt->bind_result($id, $role, $hashedPassword);
            $stmt->fetch();

            // Verify password
            if (!password_verify($password, $hashedPassword)) {
                $login_error = "Incorrect password!";
            } else {

				// Create general session
				$_SESSION['email'] = $email;

                // SUCCESS: Create session by role
                if ($role === "driver") {
                    $_SESSION['driver'] = "driver";
                } else {
                    $_SESSION['mechanic'] = "mechanic";
                }

                // REMEMBER ME
                if (isset($_POST['remember'])) {
                    setcookie("remember_email", $email, time() + (86400 * 7), "/"); // 7 days
                } else {
                    setcookie("remember_email", "", time() - 3600, "/");
                }

                // Redirect to router
                header("Location: router.php");
                exit;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title>Roadmechs - Login</title>

	<!-- Site favicon -->
	<link rel="apple-touch-icon" sizes="180x180" href="./view/vendors/images/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="./view/vendors/images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="./view/vendors/images/favicon-16x16.png">

	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- Google Font -->
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="./view/vendors/styles/core.css">
	<link rel="stylesheet" type="text/css" href="./view/vendors/styles/icon-font.min.css">
	<link rel="stylesheet" type="text/css" href="./view/vendors/styles/style.css">

	<!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-119386393-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-119386393-1');
	</script>
</head>
<body class="login-page">
        <!-- Preloader starts here -->
		<!-- <div class="pre-loader">
		<div class="pre-loader-box">
			<div class='loader-progress' id="progress_div">
				<div class='bar' id='bar1'></div>
			</div>
			<div class='percent' id='percent1'>0%</div>
			<div class="loading-text">
				Roadmechs
			</div>
		</div>
	</div> -->
	<!-- Preloader ends here -->

	<div class="login-header box-shadow">
		<div class="container-fluid d-flex justify-content-between align-items-center">
			<div class="brand-logo">
				<a href="./">
					<img src="./view/vendors/images/deskapp-logo.png" alt="">
				</a>
			</div>
			
		</div>
	</div>
	<div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-md-6 col-lg-7">
					<img src="./view/vendors/images/login-page-img.png" alt="">
				</div>
				<div class="col-md-6 col-lg-5">
					<div class="login-box bg-white box-shadow border-radius-10">
						<div class="login-title">
							<h2 class="text-center text-primary">Login</h2>
						</div>

						<!-- Login form starts here -->
						<form action="index.php" method="post">
							<div class="input-group custom">
								<input type="text" class="form-control form-control-lg" placeholder="Email" name="email"
								value="<?php echo isset($_COOKIE['remember_email']) ? $_COOKIE['remember_email'] : ''; ?>">
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
								</div>
							</div>
							<div class="input-group custom">
								<input type="password" class="form-control form-control-lg" placeholder="Password" name="password">
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="dw dw-padlock1"></i></span>
								</div>
							</div>
							<div class="row pb-30">
								<div class="col-6">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="custom-control-input" id="customCheck1" name="remember"
										 <?php if (isset($_COOKIE['remember_email'])) echo 'checked'; ?>>
										<label class="custom-control-label" for="customCheck1">Remember</label>
									</div>
								</div>
								<div class="col-6">
									<div class="forgot-password"><a href="javascript:void();">Forgot Password</a></div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="input-group mb-0">
										<button type="submit" class="btn btn-primary btn-lg btn-block">Login</button>
									</div>
									<div class="font-16 weight-600 pt-10 pb-10 text-center" data-color="#707373">OR</div>
									<div class="input-group mb-0">
										<a class="btn btn-outline-primary btn-lg btn-block" href="./view/signup.php">Register</a>
									</div>
								</div>
							</div>
						</form>
						<!-- Login form ends here -->
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- ------------------ SWEET ALERT ERROR HANDLER ------------------ -->
	<?php if (!empty($login_error)) : ?>
	<script>
	Swal.fire({
		icon: "error",
		title: "<?php echo $login_error; ?>",
		toast: true,
		position: "top-end",
		timer: 3000,
		showConfirmButton: false
	});
	</script>
	<?php endif; ?>


	<!-- js -->
	<script src="./view/vendors/scripts/core.js"></script>
	<script src="./view/vendors/scripts/script.min.js"></script>
	<script src="./view/vendors/scripts/process.js"></script>
	<script src="./view/vendors/scripts/layout-settings.js"></script>
</body>
</html>