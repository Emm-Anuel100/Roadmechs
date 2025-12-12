<?php
// start session
session_start();  

// Include connection file
include_once "../config.php";

// Message holder
$alert = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['option'])) {

    $role     = mysqli_real_escape_string($conn, $_POST['option']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // VALIDATION
    if (empty($role) || empty($email) || empty($password)) {
        $alert = "all_required";
    } else {

        // CHECK EMAIL
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $alert = "email_exists";
        } else {

            // CREATE ACCOUNT
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (role, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $role, $email, $hashed);

            if ($stmt->execute()) {

				// create general email session
                $_SESSION['email'] = $email;

                if ($role == "driver") {
                    $_SESSION['driver'] = $role;
                    header("Location: update_profile_d.php");
                } else {
                    $_SESSION['mechanic'] = $role;
                    header("Location: update_profile_m.php");
                }

            } else {
                $alert = "create_error";
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
	<title>Roadmechs - SignUp</title>

	<!-- Site favicon -->
	<link rel="apple-touch-icon" sizes="180x180" href="vendors/images/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="vendors/images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="vendors/images/favicon-16x16.png">

	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- Google Font -->
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="vendors/styles/core.css">
	<link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css">
	<link rel="stylesheet" type="text/css" href="vendors/styles/style.css">

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-119386393-1"></script>

    <!-- Sweet Alert -->
	 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
				<a href="./signup.php">
					<img src="vendors/images/deskapp-logo.png" alt="">
				</a>
			</div>
		</div>
	</div>
	<div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-md-6 col-lg-7">
					<img src="vendors/images/login-page-img.png" alt="">
				</div>
				<div class="col-md-6 col-lg-5">
					<div class="login-box bg-white box-shadow border-radius-10">
						<div class="login-title">
							<h2 class="text-center text-primary">SignUp</h2>
						</div>

						<!-- Signup form starts here -->
						<form action="./signup.php" method="post">
							<div class="select-role">
								<div class="btn-group btn-group-toggle" data-toggle="buttons">
									<label class="btn active">
										<input type="radio" name="option" id="admin" value="mechanic">
										<div class="icon"><img src="vendors/images/briefcase.svg" class="svg" alt=""></div>
										<span>I'm</span>
										Mechanic
									</label>
									<label class="btn">
										<input type="radio" name="option" id="user" value="driver">
										<div class="icon"><img src="vendors/images/person.svg" class="svg" alt=""></div>
										<span>I'm</span>
										Driver
									</label>
								</div>
							</div>
							<div class="input-group custom">
								<input type="text" class="form-control form-control-lg" name="email" placeholder="Email">
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
								</div>
							</div>
							<div class="input-group custom">
								<input type="password" class="form-control form-control-lg" name="password" placeholder="Password">
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="dw dw-padlock1"></i></span>
								</div>
							</div>
							<div class="row pb-30">
								<div class="col-12">
									<div class="forgot-password"><a href="javascript:void();">Forgot Password</a></div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="input-group mb-0">
										<button type="submit" class="btn btn-primary btn-lg btn-block" >Sign Up</button>
									</div>
									<div class="font-16 weight-600 pt-10 pb-10 text-center" data-color="#707373">OR</div>
									<div class="input-group mb-0">
										<a class="btn btn-outline-primary btn-lg btn-block" href="../">Login</a>
									</div>
								</div>
							</div>
						</form>
						<!-- Signup form ends here -->
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php if (!empty($alert)): ?>
	<script>
	document.addEventListener("DOMContentLoaded", function() {

    <?php if ($alert == "all_required"): ?>
        Swal.fire({
            icon: "error",
            title: "All fields are required",
            toast: true,
            position: "top-end",
            timer: 3000,
            showConfirmButton: false
        });
    <?php endif; ?>
	<?php if ($alert == "email_exists"): ?>
        Swal.fire({
            icon: "error",
            title: "Email already exists!",
            toast: true,
            position: "top-end",
            timer: 3000,
            showConfirmButton: false
        });
    <?php endif; ?>

    <?php if ($alert == "create_error"): ?>
        Swal.fire({
            icon: "error",
            title: "Error creating account",
            toast: true,
            position: "top-end",
            timer: 3000,
            showConfirmButton: false
        });
    <?php endif; ?>

	});
	</script>
	<?php endif; ?>

	<!-- js -->
	<script src="vendors/scripts/core.js"></script>
	<script src="vendors/scripts/script.min.js"></script>
	<script src="vendors/scripts/process.js"></script>
	<script src="vendors/scripts/layout-settings.js"></script>
</body>
</html>