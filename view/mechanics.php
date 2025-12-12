<?php
session_start();
include '../config.php'; // ensure DB connection

// if driver not logged in redirect to login page
if (!isset($_SESSION['driver']) || !isset($_SESSION['email'])) {
    header("Location: ../");
    exit;
}

// email session
$email = $_SESSION['email'];

// Fetch all mechanics (we'll filter by city in JS)
$sql = "SELECT id, fullname, phone_no, pay_rate, state, address, profile_pic 
        FROM users WHERE role='mechanic'";

$result = $conn->query($sql);

$mechanics = [];
while ($row = $result->fetch_assoc()) {
    $mechanics[] = $row;
}

// Pass to JS
$mechanicsJSON = json_encode($mechanics);

?>


<!DOCTYPE html>
<html>
<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title>Roadmechs - Mechanics </title>

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

	<!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Leadflet JS -->
	<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-119386393-1"></script>
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
							<img src="vendors/images/photo1.jpg" alt="">
						</span>
						<span class="user-name">
							<?php echo($_SESSION['email']);?>
						</span>
					</a>
					<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
						<a class="dropdown-item" href="./update_profile_d.php"><i class="dw dw-user1"></i>Update Profile</a>
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
						<a href="./dashboard_d.php" class="dropdown-toggle no-arrow">
							<span class="micon dw dw-home"></span><span class="mtext">Home</span>
						</a>
					</li>
					<li>
						<a href="./myprofile_d.php" class="dropdown-toggle no-arrow">
							<span class="micon dw dw-user1"></span><span class="mtext">Profile</span>
						</a>
					</li>
					<li class="dropdown">
						<a href="./mechanics.php" class="dropdown-toggle no-arrow">
							<span class="micon dw dw-user-2"></span><span class="mtext">Mechanics</span>
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
				<div class="container pd-0">
					<div class="page-header">
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<div class="title">
									<h4>Mechanics</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item"><a href="./dashboard_d.php">Home</a></li>
										<li class="breadcrumb-item active" aria-current="page">Mechanics near me</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
					<!--- section to dynamically load mechanics -->
					<div class="contact-directory-list">
						<ul class="row" id="mechanicsList"></ul>
					</div>
				</div>

				<div class="blog-pagination mb-30">
					<div class="btn-toolbar justify-content-center mb-15">
						<div class="btn-group">
							<a href="javascript:void();" class="btn btn-outline-primary prev"><i class="fa fa-angle-double-left"></i></a>
							<span class="btn btn-primary current">1</span>
							<a href="#" class="btn btn-outline-primary">2</a>
							<a href="#" class="btn btn-outline-primary">3</a>
							<a href="#" class="btn btn-outline-primary next"><i class="fa fa-angle-double-right"></i></a>
						</div>
					</div>
				</div>
			</div>
			<div class="footer-wrap pd-20 mb-20 card-box">
				&copy;Roadmechs
			</div>
		</div>
	</div>


<!-- Js script to get nearby mechanics -->
<script>
let mechanics = <?php echo $mechanicsJSON; ?>;

// Geoapify Api key for Reverce Geocoding
const geoapifyKey = "ff42e06657244517ac9eddc90644c5ba"; 
let driverCity = null;

// Open map modal for a mechanic
function openMap(mechLat, mechLon, address) {
    if (!mechLat || !mechLon) {
        Swal.fire("Location Error", "Mechanic address could not be located.", "error");
        return;
    }

    document.getElementById("mapModal").style.display = "block";

    const map = L.map('mapid').setView([mechLat, mechLon], 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    L.marker([mechLat, mechLon]).addTo(map)
        .bindPopup("Mechanic: " + address)
        .openPopup();
}

function closeMap() {
    document.getElementById("mapModal").style.display = "none";
    document.getElementById("mapid").innerHTML = ""; // reset map
}

// Render mechanics cards filtered by city
function renderMechanics() {
    if (!driverCity) return;

    const container = document.getElementById("mechanicsList");
    let html = "";

    const filtered = mechanics.filter(m => {
        return m.state.toLowerCase() === driverCity.toLowerCase();
    });

    if (filtered.length === 0) {
        container.innerHTML = "<p class='text-center'>No mechanics found in your city.</p>";
        return;
    }

    filtered.forEach((m, index) => {
        const address = m.address + ", " + m.state;
        html += `
        <li class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
            <div class="contact-directory-box">
                <div class="contact-dire-info text-center">
                    <div class="contact-avatar">
                        <span>
                            <img src="../uploads/${m.profile_pic || '../uploads/default_img.png'}" alt="profile pic">
                        </span>
                    </div>
                    <div class="contact-name">
                        <h4>${m.fullname}</h4>
                        <p>Pay Rate: <span>&#8358;${m.pay_rate || 'N/A'}</span></p>
                        <div class="work " style="cursor:pointer; font-weight:600; color: green"
                             onclick="openMap(${m.lat || 'null'}, ${m.lon || 'null'}, '${address}')">
                            <i class="icon-copy dw dw-map"></i> view on map
                        </div>
                        <br>
                        <p><i class="icon-copy dw dw-smartphone"></i> <span>${m.phone_no}</span></p>
                    </div>
                    <div class="profile-sort-desc">
                        ${address}
                    </div>
                </div>
				    <div class="view-contact">
                        <a href="javascript:void();">PAY MECHANIC</a>
                    </div>
            </li>`;
    });

    container.innerHTML = html;
}

// Get driver city via Geoapify reverse geocoding
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(async pos => {
        const lat = pos.coords.latitude;
        const lon = pos.coords.longitude;

        try {
            const res = await fetch(`https://api.geoapify.com/v1/geocode/reverse?lat=${lat}&lon=${lon}&apiKey=${geoapifyKey}`);
            const data = await res.json();
            const props = data.features[0].properties;

            // City fallback chain
            driverCity = props.city || props.town || props.village || props.county;

            if (!driverCity) {
                Swal.fire("Location Error", "Could not detect your city.", "error");
                return;
            }

            console.log("Driver city detected:", driverCity);

            // Filter mechanics by this city
            renderMechanics();

        } catch (err) {
            console.error(err);
            Swal.fire("Location Error", "Could not detect your city.", "error");
        }
    }, err => {
        Swal.fire("Location Error", "Cannot get GPS coordinates.", "error");
    });
} else {
    Swal.fire("Error", "Your device cannot fetch GPS location.", "error");
}
</script>

	
<!-- js -->
<script src="vendors/scripts/core.js"></script>
<script src="vendors/scripts/script.min.js"></script>
<script src="vendors/scripts/process.js"></script>
<script src="vendors/scripts/layout-settings.js"></script>
</body>
</html>