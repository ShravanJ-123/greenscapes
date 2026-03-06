<?php
require 'PHPMailer/PHPMailerAutoload.php';

// Function to get client IP
function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Collect POST data correctly (case-sensitive keys!)
    $name = isset($_POST['name']) ? $_POST['name'] : 'N/A';
    $email = isset($_POST['email']) ? $_POST['email'] : 'N/A';
    $phone = isset($_POST['number']) ? $_POST['number'] : 'N/A';
    $message = isset($_POST['message']) ? $_POST['message'] : 'N/A';
    
    $ip = get_client_ip(); // Keeps your IP function
    
    // Create the email body content (NO COUNTRY CODE ANYMORE)
    $bodyContent = "<h1>Enquiry Form - Prestige Nautilus</h1>";
    $bodyContent .= "
        Name: <strong>$name</strong><br>
        Email: $email<br>
        Phone Number: $phone<br>
        Message: $message<br>
        IP Address: $ip<br>
    ";

    
    // Set up PHPMailer
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'info@prestige-mumbai.in';
    $mail->Password = 'Infoprestige@1029';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('info@prestige-mumbai.in', 'Prestige Nautilus');
    $mail->addAddress('dsbhisekar@gmail.com');
    $mail->addAddress('abhijitsarvade39@gmail.com');



    // Set email format to HTML
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';  // Ensure proper encoding

    $mail->Subject = "$name ($email) Sent Email From Enquiry Form For - Prestige Nautilus";
    $mail->Body = $bodyContent;

    // Send email and handle result
    if (!$mail->send()) {
        // Send a valid JSON response with error message
        echo json_encode(['status' => 'error', 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
        exit;
    } else {
        // Send a valid JSON response with success message
        echo json_encode(['status' => 'success', 'message' => 'Email sent successfully']);
    }
} else {
    // Handle invalid request method
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>