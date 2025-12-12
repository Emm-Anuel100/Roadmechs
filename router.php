<?php
session_start();

if (isset($_SESSION['driver']) && $_SESSION['driver'] === "driver") {
    header("Location: ./view/update_profile_d.php");
    exit;
}

if (isset($_SESSION['mechanic']) && $_SESSION['mechanic'] === "mechanic") {
    header("Location: ./view/update_profile_m.php");
    exit;
}

header("Location: index.php");
exit;
