<?php
session_start();
// include connection
include "../config.php";

// include funtions
include "../functions.php";

// set email sesion
$email = $_SESSION['email'];

// If driver or email sesssion is not set.. redirect to login page
if (!isset($_SESSION['driver']) || !isset($_SESSION['email'])) {
    header("Location: ../");
	exit;
}

// ===========================
// GET DRIVER'S INFO FROM FUNCTIONS.PHP WHICH WILL BE LOADED IN PROFILE
// ===========================

// Get profile
$profile = getUserProfile($conn, $email);
if (!$profile) {
    echo "User not found!";
    exit;
}

// ===========================
// GET DRIVER'S FUNDS PAID TO MECHANIC'S FOR CURRENT YEAR, MONTH, AND DAY
// ===========================
// Initialize variables
$ada_this_year = 0;
$ada_this_month = 0;
$ada_today = 0;

if ($email) {
    // Get current year, month, and today's date
    $current_year = date('Y');
    $current_month = date('Y-m');
    $today = date('Y-m-d');

    // Total ADA paid to mechanic THIS YEAR
    $stmt_year = $conn->prepare("
        SELECT SUM(amount_ada) as total 
        FROM payment 
        WHERE email_d = ? 
        AND YEAR(transaction_date) = ?
    ");
    $stmt_year->bind_param("si", $email, $current_year);
    $stmt_year->execute();
    $result_year = $stmt_year->get_result();
    $row_year = $result_year->fetch_assoc();
    $ada_this_year = $row_year['total'] ?? 0;
    $stmt_year->close();

    // Total ADA paid to mechanic THIS MONTH
    $stmt_month = $conn->prepare("
        SELECT SUM(amount_ada) as total 
        FROM payment
        WHERE email_d = ? 
        AND DATE_FORMAT(transaction_date, '%Y-%m') = ?
    ");
    $stmt_month->bind_param("ss", $email, $current_month);
    $stmt_month->execute();
    $result_month = $stmt_month->get_result();
    $row_month = $result_month->fetch_assoc();
    $ada_this_month = $row_month['total'] ?? 0;
    $stmt_month->close();

    // Total ADA paid to mechanic TODAY
    $stmt_today = $conn->prepare("
        SELECT SUM(amount_ada) as total 
        FROM payment 
        WHERE email_d = ? 
        AND transaction_date = ?
    ");
    $stmt_today->bind_param("ss", $email, $today);
    $stmt_today->execute();
    $result_today = $stmt_today->get_result();
    $row_today = $result_today->fetch_assoc();
    $ada_today = $row_today['total'] ?? 0;
    $stmt_today->close();
}

// Round to 2 decimal places for display
$ada_this_year = number_format($ada_this_year, 2, '.', '');
$ada_this_month = number_format($ada_this_month, 2, '.', '');
$ada_today = number_format($ada_today, 2, '.', '');
?>

<!DOCTYPE html>
<html>
<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title>Roadmechs - Dashboard</title>

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
	<link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/responsive.bootstrap4.min.css">
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
	<div class="pre-loader">
		<div class="pre-loader-box">
			<div class='loader-progress' id="progress_div">
				<div class='bar' id='bar1'></div>
			</div>
			<div class='percent' id='percent1'>0%</div>
			<div class="loading-text">
				Roadmechs
			</div>
		</div>
	</div>

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
		<div class="pd-ltr-20">
			<div class="card-box pd-20 height-100-p mb-30">
				<div class="row align-items-center">
					<div class="col-md-4">
						<img src="vendors/images/banner-img.png" alt="img">
					</div>
					<div class="col-md-8">
					<h4 class="font-20 weight-500 mb-10 text-capitalize">
						Welcome back 
						<div class="weight-600 font-30 text-blue">
							<?= !empty($profile['fullname']) ? htmlspecialchars($profile['fullname']) : "User"; ?>!
						</div>
					</h4>
						<p class="font-18 max-width-600">Stranded.. no worries, we got you.</p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xl-4 mb-30">
					<div class="card-box height-100-p widget-style1">
						<div class="d-flex flex-wrap align-items-center pd-20">
							<div class="progress-data">
								<!-- <div id="chart"></div> -->
								<img src="./vendors/images/icon-online-wallet.png" alt="online wallet icon">
							</div>
							<div class="widget-data">
							<div class="h4 mb-0"><span><?php echo $ada_this_year; ?></span>
							 <span class="h6">ADA</span>
							</div>
								<div class="weight-600 font-14">Spent this year</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-4 mb-30">
					<div class="card-box height-100-p widget-style1">
						<div class="d-flex flex-wrap align-items-center pd-20">
							<div class="progress-data">
								<!-- <div id="chart2"></div> -->
								<img src="./vendors/images/icon-online-wallet.png" alt="online wallet icon">
							</div>
							<div class="widget-data">
							<div class="h4 mb-0"><span><?php echo $ada_this_month; ?></span>
							<span class="h6">ADA</span>
							</div>
								<div class="weight-600 font-14">Spent this month</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-4 mb-30">
					<div class="card-box height-100-p widget-style1">
						<div class="d-flex flex-wrap align-items-center pd-20">
							<div class="progress-data">
								<!-- <div id="chart3"></div> -->
								<img src="./vendors/images/icon-online-wallet.png" alt="online wallet icon"> 
							</div>
							<div class="widget-data">
								<div class="h4 mb-0"><span><?php echo $ada_today; ?></span> 
								<span class="h6">ADA</span>
							    </div>
								<div class="weight-600 font-14">Spent Today</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- section todisplay current value of Naira to ADA -->
			<div class="ada-naira-chart">
    <div class="chart-header">
        <div class="chart-title">ADA/NGN Exchange Rate</div>
        <div class="live-indicator">
            <div class="pulse-dot"></div>
            <span>LIVE</span>
        </div>
    </div>
    
    <!-- Loading State -->
    <div class="loading-spinner" id="loadingState">
        <div class="spinner"></div>
        <span>Fetching live rate...</span>
    </div>
    
    <!-- Current Rate Display -->
    <div class="current-rate-box" id="rateDisplay" style="display: none;">
        <div class="rate-label" style="color: #f8f5f5;">Current Rate</div>
        <div class="rate-value" style="color: #f8f5f5;">
            <span id="currentRate">₦---</span>
        </div>
        <div class="rate-change" id="rateChange">--</div>
    </div>
    
    <!-- High and Low Display -->
    <div style="display: flex; gap: 20px; justify-content: center; margin-top: 20px;" id="highLowDisplay">
        <div class="current-rate-box" style="flex: 1; max-width: 250px; padding: 20px;">
            <div class="rate-label" style="font-size: 12px;color: #f8f5f5;">24h High</div>
            <div class="rate-value" style="font-size: 28px;color: #f8f5f5;" id="highRate">₦---</div>
        </div>
        <div class="current-rate-box" style="flex: 1; max-width: 250px; padding: 20px; color: #f8f5f5;">
            <div class="rate-label" style="font-size: 12px;color: #f8f5f5;">24h Low</div>
            <div class="rate-value" style="font-size: 28px;color: #f8f5f5;" id="lowRate">₦---</div>
        </div>
    </div>
    
    <div class="update-time" id="updateTime" style="display: none;">
        Last updated: --
    </div>
    </div>

	
			<div class="footer-wrap pd-20 mb-20 card-box">
				&copy;Roadmechs
			</div>
		</div>
	</div>

    <!-- style for ADA/NGN conversion section -->
	<style>
        .ada-naira-chart {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            margin: 20px 0;
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            color: white;
        }
        
        .chart-title {
            font-size: 24px;
            font-weight: bold;
        }
        
        .live-indicator {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }
        
        .pulse-dot {
            width: 10px;
            height: 10px;
            background: #4ade80;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.2); }
        }
        
        .current-rate-box {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .rate-item {
            text-align: center;
            color: white;
        }
        
        .rate-label {
            font-size: 12px;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .rate-value {
            font-size: 28px;
            font-weight: bold;
            margin-top: 5px;
        }
        
        .rate-change {
            font-size: 14px;
            margin-top: 5px;
        }
        
        .rate-change.positive {
            color: #4ade80;
        }
        
        .rate-change.negative {
            color: #f87171;
        }
        
        .chart-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            position: relative;
            height: 400px;
        }
        
        .loading-spinner {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 400px;
            color: white;
            font-size: 18px;
        }
        
        .spinner {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top: 4px solid white;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin-right: 15px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .update-time {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            font-size: 12px;
            margin-top: 10px;
        }
    </style>
	
	<!-- Fetch current ADA / NAIRA value -->
	<script>
let currentAdaRate = 0;

// Fetch ADA price from CoinGecko
async function fetchAdaPrice() {
    try {
        const response = await fetch('https://api.coingecko.com/api/v3/simple/price?ids=cardano&vs_currencies=ngn&include_24hr_change=true&include_24hr_high_low=true');
        
        if (!response.ok) {
            throw new Error('Failed to fetch price');
        }
        
        const data = await response.json();
        
        if (!data.cardano || !data.cardano.ngn) {
            throw new Error('Invalid data received');
        }
        
        currentAdaRate = data.cardano.ngn;
        const change24h = data.cardano.ngn_24h_change || 0;
        const high24h = data.cardano.ngn_24h_high || currentAdaRate;
        const low24h = data.cardano.ngn_24h_low || currentAdaRate;
        
        // Hide loading, show content
        document.getElementById('loadingState').style.display = 'none';
        document.getElementById('rateDisplay').style.display = 'block';
        document.getElementById('highLowDisplay').style.display = 'flex';
        document.getElementById('updateTime').style.display = 'block';
        
        // Update current rate
        document.getElementById('currentRate').textContent = '₦' + currentAdaRate.toFixed(2);
        
        // Update 24h high and low
        document.getElementById('highRate').textContent = '₦' + high24h.toFixed(2);
        document.getElementById('lowRate').textContent = '₦' + low24h.toFixed(2);
        
        // Update 24h change
        const changeEl = document.getElementById('rateChange');
        const changeText = (change24h >= 0 ? '↑ +' : '↓ ') + change24h.toFixed(2) + '% (24h)';
        changeEl.textContent = changeText;
        changeEl.className = 'rate-change ' + (change24h >= 0 ? 'positive' : 'negative');
        
        // Update timestamp
        const now = new Date();
        document.getElementById('updateTime').textContent = 
            'Last updated: ' + now.toLocaleString('en-US', { 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit',
                hour12: true
            });
        
        console.log('ADA Rate updated:', currentAdaRate, 'High:', high24h, 'Low:', low24h);
        
    } catch (error) {
        console.error('Error fetching ADA price:', error);
        
        // Show error state
        document.getElementById('loadingState').innerHTML = `
            <span style="color: #f87171;">⚠️ Unable to fetch live rate. Retrying...</span>
        `;
    }
}

// Initialize
async function init() {
    // Fetch immediately
    await fetchAdaPrice();
    
    // Update every 30 seconds
    setInterval(fetchAdaPrice, 30000);
}

// Start when page loads
window.addEventListener('load', init);
</script>
	<!-- js -->
	<script src="vendors/scripts/core.js"></script>
	<script src="vendors/scripts/script.min.js"></script>
	<script src="vendors/scripts/process.js"></script>
	<script src="vendors/scripts/layout-settings.js"></script>
	<script src="src/plugins/apexcharts/apexcharts.min.js"></script>
	<script src="src/plugins/datatables/js/jquery.dataTables.min.js"></script>
	<script src="src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
	<script src="src/plugins/datatables/js/dataTables.responsive.min.js"></script>
	<script src="src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
	<script src="vendors/scripts/dashboard.js"></script>
</body>
</html>