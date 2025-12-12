<?php
// start session
session_start();

// include connection
include "../config.php";

// include funtions
include "../functions.php";

// If mechanic or email sesssion is not set.. redirect to login page
if (!isset($_SESSION['mechanic']) || !isset($_SESSION['email'])) {
    header("Location: ../");
	exit;
}

// set email sesion
$email = $_SESSION['email'];

// ===========================
// MECHANIC'S PROFILE UPDATE STARTS HERE
// ==========================
$getUser = $conn->prepare("SELECT * FROM users WHERE email=?");
$getUser->bind_param("s", $email);
$getUser->execute();
$currentUser = $getUser->get_result()->fetch_assoc();


// Initialize alert buffer
$alertScripts = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {

    // Prepare update array
    $updates = [];
    $params  = [];
    $types   = "";

    // Helper function to add fields only when not empty
	 function addField(&$updates, &$params, &$types, $column, $value) {
        if (isset($value) && trim($value) !== "") {
            $updates[] = "$column=?";
            $params[]  = $value;
            $types    .= "s";
        }
    }

    // Add user fields only if provided
    addField($updates, $params, $types, "fullname", $_POST['fullname']);
    addField($updates, $params, $types, "bio", $_POST['bio']);
    addField($updates, $params, $types, "state", $_POST['state']);
    addField($updates, $params, $types, "phone_no", $_POST['phone']);
    addField($updates, $params, $types, "address", $_POST['address']);
	addField($updates, $params, $types, "pay_rate", $_POST['payrate']);
    addField($updates, $params, $types, "fb_uname", $_POST['facebook']);
    addField($updates, $params, $types, "wa_no", $_POST['whatsapp']);
    addField($updates, $params, $types, "insta_uname", $_POST['instagram']);

    // ===========================
    // OPTIONAL IMAGE UPLOAD
    // ===========================
    $uploadDir = __DIR__ . "/../uploads";

    // Create folder if it does not exist
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            $alertScripts .= "
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Failed to create upload folder!',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2500
                });
            </script>";
            goto outputAlerts; // jump to alert output
        }
    }

    if (!empty($_FILES['profile']['name'])) {
        $ext = strtolower(pathinfo($_FILES['profile']['name'], PATHINFO_EXTENSION));
        $imageName = uniqid("IMG_", true) . "." . $ext;
        $uploadPath = $uploadDir . "/" . $imageName;

        if (move_uploaded_file($_FILES['profile']['tmp_name'], $uploadPath)) {
            addField($updates, $params, $types, "profile_pic", $imageName);
        } else {
            $alertScripts .= "
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Failed to upload profile image!',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2500
                });
            </script>";
        }
    }

    // ===========================
    // RUN UPDATE ONLY IF NEEDED
    // ===========================
    if (!empty($updates)) {
        $sql = "UPDATE users SET " . implode(", ", $updates) . " WHERE email=?";
        $params[] = $email;
        $types   .= "s";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        if ($stmt->execute()) {
            $alertScripts .= "
            <script>
                setTimeout(function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Profile updated successfully!',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2500
                    });
                }, 300);
            </script>";
        } else {
            $alertScripts .= "
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Failed to update profile!',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2500
                });
            </script>";
        }
    }
}

outputAlerts:
// Output all buffered alerts after SweetAlert2 library is loaded
echo $alertScripts;


// ===========================
// GET MECHANIC'S INFO FROM FUNCTIONS.PHP WHICH WILL BE LOADED IN PROFILE
// ===========================

// Get profile
$profile = getUserProfile($conn, $email);
if (!$profile) {
    echo "User not found!";
    exit;
}

// ===========================
// MECHANIC'S SOCIAL LINK
// ===========================
$facebookUrl  = !empty($facebook)  ? "https://www.facebook.com/" . ltrim($facebook, "@") : "#";
$instagramUrl = !empty($instagram) ? "https://www.instagram.com/" . ltrim($instagram, "@") : "#";
$whatsappUrl  = !empty($whatsapp)  ? "https://wa.me/" . preg_replace("/[^0-9]/", "", $whatsapp) : "#";

?>



<!DOCTYPE html>
<html>
<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title>Roadmechs - Update Profile</title>

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
	<link rel="stylesheet" type="text/css" href="src/plugins/cropperjs/dist/cropper.css">
	<link rel="stylesheet" type="text/css" href="vendors/styles/style.css">

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-119386393-1"></script>
	<!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-119386393-1');
	</script>
</head>
<body>
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

		<div class="header">
		<div class="header-left">
			<div class="menu-icon dw dw-menu"></div>
			<div class="search-toggle-icon dw dw-search2" data-toggle="header_search"></div>
			
			<!--*** search widget starts here ***-->
			<div class="header-search">
				<form method="post" action="#" enctype="multipart/form-data">
					<div class="form-group mb-0">
						<i class="dw dw-search2 search-icon"></i>
						<input type="text" class="form-control search-input" placeholder="Search Here ...">
					</div>
				</form>
			</div>
			<!--*** search widget ends here ***-->

		</div>
		<div class="header-right">

			<!--*** Notification section starts here ***-->
			<div class="user-notification">
				<div class="dropdown">
					<a class="dropdown-toggle no-arrow" href="#" role="button" data-toggle="dropdown">
						<i class="icon-copy dw dw-notification"></i>
						<span class="badge notification-active"></span>
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<div class="notification-list mx-h-350 customscroll">
							<ul>
								<li>
									<a href="#">
										<img src="vendors/images/img.jpg" alt="">
										<h3>John Doe</h3>
										<p>Viewed your profile</p>
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<!--*** Notification section starts here ***-->
			
			<div class="user-info-dropdown">
				<div class="dropdown">
					<a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
						<span class="user-icon">
							<img src="<?= $profile['profile_pic'] ?>" alt="profile pic">
						</span>
						<span class="user-name">
							<?php echo($_SESSION['email']);?>
						</span>
					</a>
					<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
						<a class="dropdown-item" href="./update_profile_m.php"><i class="dw dw-user1"></i>Update Profile</a>
						<a class="dropdown-item" href="../logout.php"><i class="dw dw-logout"></i> Log Out</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!--*** Nav. menu starts here ***-->
	<div class="left-side-bar">
		<div class="brand-logo">
			<a href="./dashboard_d.php">
				<img src="vendors/images/deskapp-logo.png" alt="" class="light-logo">
			</a>
			<div class="close-sidebar" data-toggle="left-sidebar-close">
				<i class="ion-close-round"></i>
			</div>
		</div>
		<div class="menu-block customscroll">
			<div class="sidebar-menu">
				<ul id="accordion-menu">
					<li>
						<a href="./dashboard_m.php" class="dropdown-toggle no-arrow">
							<span class="micon dw dw-home"></span><span class="mtext">Home</span>
						</a>
					</li>
					<li>
						<a href="./myprofile_m.php" class="dropdown-toggle no-arrow">
							<span class="micon dw dw-user1"></span><span class="mtext">Profile</span>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="mobile-menu-overlay"></div>
	<!--*** Nav. menu ends here ***-->

	<div class="main-container">
		<div class="pd-ltr-20 xs-pd-20-10">
			<div class="min-height-200px">
				<div class="page-header">
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="title">
								<h4>Profile</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="index.php">Home</a></li>
									<li class="breadcrumb-item active" aria-current="page">Update Profile</li>
								</ol>
							</nav>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
    <div class="pd-20 card-box height-100-p">
        <div class="profile-photo">
            <img src="<?php echo $profile['profile_pic']; ?>" alt="profile image" class="avatar-photo">
        </div>
        <h5 class="text-center h5 mb-0"><?php echo $profile['fullname']; ?></h5>
        <p class="text-center text-muted font-14"><?php echo $profile['bio']; ?></p>

        <div class="profile-info">
            <h5 class="mb-20 h5 text-blue">My Information</h5>
            <ul>
                <li>
                    <span>Email Address:</span>
                    <?php echo $profile['email']; ?>
                </li>
                <li>
                    <span>Phone Number(s):</span>
                    <?php echo $profile['phone_no']; ?>
                </li>
                <li>
                    <span>State:</span>
                    <?php echo $profile['state']; ?>
                </li>
				<li>
                    <span>Pay Rate:</span>
                    &#8358;<?php echo $profile['pay_rate']; ?>
                </li>
                <li>
                    <span>Address:</span>
                    <?php echo $profile['address']; ?>
                </li>
            </ul>
        </div>

          <div class="profile-social">
				<h5 class="mb-20 h5 text-blue">Social Links</h5>
				<ul class="clearfix">
					<li><a href="<?php echo $profile['facebook_url'] ?>" target="_blank" class="btn" data-bgcolor="#3b5998" data-color="#ffffff"><i class="fa fa-facebook"></i></a></li>
					<li><a href="<?php echo $profile['instagram_url']; ?>" target="_blank" class="btn" data-bgcolor="#f46f30" data-color="#ffffff"><i class="fa fa-instagram"></i></a></li>
					<li><a href="<?php echo $profile['whatsapp_url']; ?>" target="_blank" class="btn" data-bgcolor="#00b489" data-color="#ffffff"><i class="fa fa-whatsapp"></i></a></li>
				</ul>
				</div>
				</div>
			</div>

					<div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30">
						<div class="card-box height-100-p overflow-hidden">
							<div class="profile-tab height-100-p">
								<div class="tab height-100-p">
									<ul class="nav nav-tabs customtab" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" data-toggle="tab" href="#setting" role="tab">Settings</a>
										</li>
									</ul>
									<div class="tab-content">
										<!-- Setting Tab start -->
										<div class="tab-pane show active fade height-100-p" id="setting" role="tabpanel">
											<div class="profile-setting">

											<!-- Form to update user profile details -->
												<form action="update_profile_m.php" method="post" enctype="multipart/form-data">
											<ul class="profile-edit-list row">
												<li class="weight-500 col-md-6">
													<h4 class="text-blue h5 mb-20">Edit Personal Settings</h4>

													<div class="form-group">
														<label>Full Name</label>
														<input class="form-control form-control-lg" type="text" name="fullname">
													</div>

													<div class="form-group">
														<label>Email</label>
														<input class="form-control form-control-lg" name="email" type="email" value="<?php echo $email; ?>" readonly>
													</div>

													<div class="form-group">
														<label>Bio</label>
														<input class="form-control form-control-lg" type="text" name="bio">
													</div>

													<div class="form-group">
														<label>State *</label>
														<input class="form-control form-control-lg" type="text" name="state">
													</div>

													<div class="form-group">
														<label>Profile picture</label>
														<input class="form-control form-control-lg" type="file" name="profile" accept=".png,.jpg,.jpeg">
													</div>

													<div class="form-group">
														<label>Phone Number(s) *</label>
														<input class="form-control form-control-lg" type="text" name="phone">
													</div>

													<div class="form-group">
														<label>Pay Rate *</label>
														<input class="form-control form-control-lg" type="text" name="payrate">
													</div>

													<div class="form-group">
														<label>Address *</label>
														<textarea class="form-control" name="address"></textarea>
													</div>
													<div class="form-group">
														Input fields marked (*) are required
													</div>

												</li>

												<li class="weight-500 col-md-6">

													<h4 class="text-blue h5 mb-20">Edit Social Media Links</h4>

													<div class="form-group">
														<label>Facebook Username:</label>
														<input class="form-control form-control-lg" type="text" name="facebook">
													</div>

													<div class="form-group">
														<label>Whatsapp No:</label>
														<input class="form-control form-control-lg" type="text" name="whatsapp">
													</div>

													<div class="form-group">
														<label>Instagram Username:</label>
														<input class="form-control form-control-lg" type="text" name="instagram">
													</div>

													<div class="form-group mb-0">
														<input type="submit" class="btn btn-primary" value="Save & Update">
													</div>
												</li>
											</ul>
										</form>

											</div>
										</div>
										<!-- Setting Tab End -->
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="footer-wrap pd-20 mb-20 card-box">
				&copy;Roadmechs
			</div>
		</div>
	</div>
	<!-- js -->
	<script src="vendors/scripts/core.js"></script>
	<script src="vendors/scripts/script.min.js"></script>
	<script src="vendors/scripts/process.js"></script>
	<script src="vendors/scripts/layout-settings.js"></script>
	<script src="src/plugins/cropperjs/dist/cropper.js"></script>
	<script>
		window.addEventListener('DOMContentLoaded', function () {
			var image = document.getElementById('image');
			var cropBoxData;
			var canvasData;
			var cropper;

			$('#modal').on('shown.bs.modal', function () {
				cropper = new Cropper(image, {
					autoCropArea: 0.5,
					dragMode: 'move',
					aspectRatio: 3 / 3,
					restore: false,
					guides: false,
					center: false,
					highlight: false,
					cropBoxMovable: false,
					cropBoxResizable: false,
					toggleDragModeOnDblclick: false,
					ready: function () {
						cropper.setCropBoxData(cropBoxData).setCanvasData(canvasData);
					}
				});
			}).on('hidden.bs.modal', function () {
				cropBoxData = cropper.getCropBoxData();
				canvasData = cropper.getCanvasData();
				cropper.destroy();
			});
		});
	</script>
</body>
</html>