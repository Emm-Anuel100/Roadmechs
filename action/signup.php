<?php
session_start();

// If driver is logged in.. redirect to driver dashboard
if (isset($_SESSION['driver']) && isset($_SESSION['email'])) {
    header("Location: dashboard_d.php");
}

// If mechanic is logged in..  redirect to mechanic dashboard
if (isset($_SESSION['mechanic']) && isset($_SESSION['email'])) {
    header("Location: dashboard_m.php");
}

// If no session.. redirect to login page
header("Location: login.php");
// exit;

