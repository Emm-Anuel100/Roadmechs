<?php

//********* */ FUNCTION TO FETCH USER'S DETAILS FROM DB START HERE**********//
function getUserProfile($conn, $email) {
    // Prepare statement
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if (!$user) {
        return null; // User not found
    }

    // Fallbacks
    $profile = [];
    $profile['profile_pic'] = !empty($user['profile_pic']) ? "../uploads/" . $user['profile_pic'] : "../uploads/default_img.png";
    $profile['fullname']    = !empty($user['fullname']) ? $user['fullname'] : "User";
    $profile['email']    = !empty($user['email']) ? $user['email'] : "No Email";
    $profile['bio']         = !empty($user['bio']) ? $user['bio'] : "No bio provided";
    $profile['phone_no']    = !empty($user['phone_no']) ? $user['phone_no'] : "N/A";
    $profile['state']       = !empty($user['state']) ? $user['state'] : "N/A";
    $profile['address']     = !empty($user['address']) ? nl2br($user['address']) : "N/A";
    $profile['pay_rate']    = !empty($user['pay_rate']) ? $user['pay_rate'] : 0;
    $profile['fb_uname']    = !empty($user['fb_uname']) ? $user['fb_uname'] : "";
    $profile['insta_uname'] = !empty($user['insta_uname']) ? $user['insta_uname'] : "";
    $profile['wa_no']       = !empty($user['wa_no']) ? $user['wa_no'] : "";


    // Prepare full URLs for social links
    $profile['facebook_url']  = !empty($profile['fb_uname']) ? "https://www.facebook.com/" . ltrim($profile['fb_uname'], "@") : "#";
    $profile['instagram_url'] = !empty($profile['insta_uname']) ? "https://www.instagram.com/" . ltrim($profile['insta_uname'], "@") : "#";
    $profile['whatsapp_url']  = !empty($profile['wa_no']) ? "https://wa.me/" . preg_replace("/[^0-9]/", "", $profile['wa_no']) : "#";

    return $profile;
}
//********* */ FUNCTION TO FETCH USER'S DETAILS FROM DB ENDS HERE**********//


