<?php
session_start();
include '../config.php'; // ensure DB connection
// include funtions
include "../functions.php";

// if driver not logged in redirect to login page
if (!isset($_SESSION['driver']) || !isset($_SESSION['email'])) {
    header("Location: ../");
    exit;
}

// email session
$email = $_SESSION['email'];

// Get profile
$profile = getUserProfile($conn, $email);
if (!$profile) {
    echo "User not found!";
    exit;
}

// Fetch all mechanics (filter by city in JS)
$sql = "SELECT id, fullname, email, phone_no, pay_rate, state, address, profile_pic, wallet 
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
							<img src="<?= $profile['profile_pic'] ?>" alt="profile pic">
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
										<li class="breadcrumb-item active" aria-current="page">Mechanics</li>
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

	<!-- Map pop up modal -->
	<div id="mapModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999;">
		<div style="position:relative; width:90%; height:80%; margin:5% auto; background:#fff; border-radius:10px;">
			<div id="mapid" style="width:100%; height:100%; border-radius:10px;"></div>
			<button onclick="closeMap()" style="position:absolute; top:10px; right:10px; z-index:1000; padding:5px 10px;">Close</button>
		</div>
	</div>


<!-- Js script to get mechanics -->
<script>
// PHP mechanics passed to JavaScript
let mechanics = <?php echo $mechanicsJSON; ?>;

// Geoapify Reverce Geocoding API Key
const geoapifyKey = "ff42e06657244517ac9eddc90644c5ba";

// Driver city
let driverCity = null;

// FUNCTION — Open map modal with mechanic pin only
function openMap(mechLat, mechLon, address) {
    if (!mechLat || !mechLon) {
        Swal.fire("Location Error", "Mechanic address could not be located.", "error");
        return;
    }

    document.getElementById("mapModal").style.display = "block";

    // Initialize map
    const map = L.map('mapid').setView([mechLat, mechLon], 14);

    // Add OSM tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    // Mechanic marker
    L.marker([mechLat, mechLon]).addTo(map)
        .bindPopup(address)
        .openPopup();
}

// Close map modal
function closeMap() {
    document.getElementById("mapModal").style.display = "none";
    document.getElementById("mapid").innerHTML = ""; // reset map
}


//****  INTEGRATE CARDANO (ADA) PAYMENT */
// Get driver email from PHP session
const DRIVER_EMAIL = "<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>";

// Debug: Check what we're getting
console.log("Driver Email:", DRIVER_EMAIL);
console.log("Driver Email Length:", DRIVER_EMAIL.length);
console.log("Driver Email Type:", typeof DRIVER_EMAIL);

// System Escrow Wallet (dummy/test wallet)
const SYSTEM_ESCROW_WALLET = "addr_test1vzpwq95z3xyum8vqndgdd9mdnmafh3djcxnc6jemlgdmswcve6tkw";

// Mock Cardano Payment Function
function mockCardanoPayment(fromWallet, toWallet, amountAda) {
    return new Promise(resolve => {
        console.log("===== CARDANO TEST PAYMENT =====");
        console.log("From:", fromWallet);
        console.log("To:", toWallet);
        console.log("Amount:", amountAda.toFixed(8), "ADA");
        console.log("Network: Cardano Testnet");
        console.log("Status: SUCCESS (SIMULATED)");
        console.log("================================");

        setTimeout(() => {
            resolve({
                status: "success",
                tx_hash: "tx_" + Math.random().toString(36).substring(2, 15)
            });
        }, 1500);
    });
}

// Show Payment Confirmation Modal
function showPaymentConfirmation(mechanicEmail, payRateNaira, walletAddress) {
    Swal.fire({
        title: 'Confirm Payment',
        html: `
            <div style="text-align: left; padding: 20px;">
                <p style="font-size: 16px; margin: 10px 0;">
                    <strong>Mechanic:</strong> ${mechanicEmail}
                </p>
                <p style="font-size: 20px; color: #0033AD; margin: 10px 0;">
                    <strong>Amount:</strong> ₦${parseFloat(payRateNaira).toLocaleString()}
                </p>
                <hr style="margin: 20px 0; border: 1px solid #eee;">
                <p style="font-size: 14px; color: #666; margin: 10px 0;">
                    <i class="fa fa-info-circle"></i> 
                     ADA amount to be paid to mechanic will be base on the current ADA/NAIRA value
                </p>
                <p style="font-size: 13px; color: #999; margin: 10px 0;">
                    <strong>Mechanic's Wallet Address:</strong> ${walletAddress.substring(0, 20)}...
                </p>
            </div>
        `,
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#0033AD',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fa fa-credit-card"></i> Proceed to Payment',
        cancelButtonText: '<i class="fa fa-times"></i> Cancel',
        reverseButtons: true,
        customClass: {
            confirmButton: 'btn btn-primary btn-lg',
            cancelButton: 'btn btn-secondary btn-lg'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // NOW call the actual payment function
            payMechanicInADA(mechanicEmail, payRateNaira, walletAddress);
        }
    });
}

// Pay mechanic in ADA (Actual payment function)
async function payMechanicInADA(mechanicEmail, payRateNaira, walletAddress) {
    try {
        // Debug inputs
        console.log("=== FUNCTION INPUTS ===");
        console.log("Mechanic's Email:", mechanicEmail);
        console.log("Pay Rate (Naira):", payRateNaira);
        console.log("Wallet Address:", walletAddress);
        console.log("=======================");

        // Check if wallet address exists
        if (!walletAddress || walletAddress === 'undefined' || walletAddress === '') {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Mechanic wallet address is missing. Please contact support.",
                confirmButtonText: 'OK'
            });
            return;
        }
        // Check if driver email exists
        if (!DRIVER_EMAIL) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "You must be logged in to make a payment",
                confirmButtonText: 'OK'
            });
            return;
        }

        // Show loading
        Swal.fire({
            title: 'Processing Payment...',
            html: 'Please wait while we process your ADA payment',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Fetch current market ADA / Naira rate
        const rateResp = await fetch('https://api.coingecko.com/api/v3/simple/price?ids=cardano&vs_currencies=ngn');
        
        if (!rateResp.ok) {
            throw new Error("Failed to fetch ADA rate from CoinGecko");
        }

        const rateData = await rateResp.json();
        const nairaToAdaRate = rateData.cardano?.ngn;

        if (!nairaToAdaRate) {
            throw new Error("ADA rate not available");
        }

        const payRateAda = payRateNaira / nairaToAdaRate;
        console.log("Pay rate in ADA:", payRateAda);
        console.log("ADA/NGN Rate:", nairaToAdaRate);

        // Simulate payment
        const paymentResult = await mockCardanoPayment(
            SYSTEM_ESCROW_WALLET, 
            walletAddress, 
            payRateAda
        );

        if (paymentResult.status === "success") {
            // Prepare transaction data (match DB Date format)
            const transactionDate = new Date().toISOString().split('T')[0];

            const transactionData = {
                email_m: mechanicEmail,
                email_d: DRIVER_EMAIL,
                amount_ada: payRateAda,
                transaction_date: transactionDate,
                tx_hash: paymentResult.tx_hash
            };

            console.log("=== TRANSACTION DATA ===");
            console.log("Mechanic's Email:", mechanicEmail);
            console.log("Driver Email:", DRIVER_EMAIL);
            console.log("Amount ADA:", payRateAda);
            console.log("Date:", transactionDate);
            console.log("TX Hash:", paymentResult.tx_hash);
            console.log("Full Data:", JSON.stringify(transactionData));
            console.log("========================");

            // Send to backend
            const resp = await fetch('../model/insert_payment.php', {
            
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(transactionData)
            });

            if (!resp.ok) {
                throw new Error(`HTTP error! status: ${resp.status}`);
            }

            const result = await resp.json();
            console.log("Backend response:", result);

            if (result.success) {
                Swal.fire({
                    icon: "success",
                    title: "Payment Successful!",
                    html: `
                        <p><strong>Amount:</strong> ${payRateAda.toFixed(8)} ADA (₦${payRateNaira.toLocaleString()})</p>
                        <p><strong>Mechanic's Email:</strong> ${mechanicEmail}</p>
                        <p><strong>TX Hash:</strong> ${paymentResult.tx_hash}</p>
                        <p><strong>Curent Rate:</strong> 1 ADA = ₦${nairaToAdaRate.toFixed(2)}</p>
                    `,
                    confirmButtonText: 'OK'
                }).then(() => {
                    // reload page
                    // location.reload();
                });
            } else {
                throw new Error(result.message || "Failed to record transaction in database");
            }

        } else {
            throw new Error("Payment processing failed");
        }

    } catch (err) {
        console.error("Payment Error:", err);
        Swal.fire({
            icon: "error",
            title: "Payment Failed",
            text: err.message || "Could not process payment. Please try again.",
            confirmButtonText: 'OK'
        });
    }
}



// Geocode mechanics addresses via Geoapify
async function geocodeMechanics() {
    for (let m of mechanics) {
        if (!m.lat || !m.lon) {
            const query = encodeURIComponent(`${m.address}, ${m.state}, Nigeria`);
            try {
                const res = await fetch(`https://api.geoapify.com/v1/geocode/search?text=${query}&apiKey=${geoapifyKey}`);
                const data = await res.json();
                if (data.features && data.features.length > 0) {
                    m.lat = data.features[0].properties.lat;
                    m.lon = data.features[0].properties.lon;
                } else {
                    m.lat = null;
                    m.lon = null;
                }
            } catch (err) {
                console.error("Geocoding mechanic failed:", err);
                m.lat = null;
                m.lon = null;
            }
        }
    }
}

// Render mechanics cards
function renderMechanics() {
    const container = document.getElementById("mechanicsList");
    let html = "";

    // Filter mechanics by driver city
    const filteredMechanics = mechanics.filter(m => m.state.toLowerCase() === driverCity.toLowerCase());

    if (filteredMechanics.length === 0) {
        container.innerHTML = "<p class='text-center'>Sorry, no mechanic available.</p>";
        return;
    }

    filteredMechanics.forEach((m, index) => {
        const fullAddress = m.address + ", " + m.state + ", Nigeria";
        html += `
        <li class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
            <div class="contact-directory-box">
                <div class="contact-dire-info text-center">
                    <div class="contact-avatar">
                        <span>
                            <img src="../uploads/${m.profile_pic || '../uploads/default_img.png'}">
                        </span>
                    </div>
                    <div class="contact-name">
                        <h4>${m.fullname}</h4>
                        <p>Pay Rate: <span>&#8358;${m.pay_rate || 'N/A'}</span></p>
                        <div class="work" style="cursor:pointer; font-weight:600; color: green"
                             onclick="openMap(${m.lat}, ${m.lon}, '${fullAddress}')">
                            <i class="icon-copy dw dw-map"></i> view on map
                        </div>
                        <br>
                        <p><i class="icon-copy dw dw-smartphone"></i> <span>${m.phone_no}</span></p>
                    </div>
                    <div class="profile-sort-desc">
                        ${fullAddress}
                    </div>
                </div>
				<div class="view-contact">
					<a href="javascript:void(0);"
					onclick="showPaymentConfirmation('${m.email}', ${m.pay_rate}, '${m.wallet}')">
					Pay Mechanic In (ADA)
					</a>
				</div>
            </div>
        </li>`;
    });
    container.innerHTML = html;
}


// Get driver's GPS and city
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(async pos => {
        const lat = pos.coords.latitude;
        const lon = pos.coords.longitude;

        try {
            // Reverse geocode to get city
            const res = await fetch(`https://api.geoapify.com/v1/geocode/reverse?lat=${lat}&lon=${lon}&apiKey=${geoapifyKey}`);
            const data = await res.json();
            const props = data.features[0].properties;

            driverCity = props.city || props.town || props.village || props.county;

            if (!driverCity) {
                Swal.fire("Location Error", "Could not detect your city.", "error");
                return;
            }

            // Geocode mechanics before rendering
            await geocodeMechanics();

            // Render mechanics
            renderMechanics();

        } catch (err) {
            console.error(err);
            Swal.fire("Location Error", "Could not detect your city.", "error");
        }

    }, err => {
        Swal.fire("Location Error", "We need your location to find nearby mechanics.", "error");
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