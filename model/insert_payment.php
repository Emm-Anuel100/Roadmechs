<?php
// ini_set('display_errors', 0);
// ini_set('log_errors', 1);
// error_log("Payment insert called");

include '../config.php'; // DB connection

header('Content-Type: application/json');

try {
    $input = file_get_contents('php://input');
    error_log("Received data: " . $input);
    
    $data = json_decode($input, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON: " . json_last_error_msg());
    }

    $email_m = $data['email_m'] ?? '';
    $email_d = $data['email_d'] ?? '';
    $amount_ada = $data['amount_ada'] ?? 0;
    $transaction_date = $data['transaction_date'] ?? '';
    $tx_hash = $data['tx_hash'] ?? '';

    $response = ['success' => false];

    // Validate required fields
    if (empty($email_m) || empty($email_d) || empty($amount_ada)) {
        throw new Exception("Missing required fields");
    }

    // Validate email formats
    if (!filter_var($email_m, FILTER_VALIDATE_EMAIL) || !filter_var($email_d, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format");
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO payment (email_m, email_d, amount_ada, transaction_date, tx_hash) VALUES (?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssdss", $email_m, $email_d, $amount_ada, $transaction_date, $tx_hash);
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Payment recorded successfully";
        $response['payment_id'] = $stmt->insert_id;
        error_log("Payment made successfully. ID: " . $stmt->insert_id);
    } else {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    $stmt->close();

} catch (Exception $e) {
    error_log("Payment error: " . $e->getMessage());
    $response['success'] = false;
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit;
?>