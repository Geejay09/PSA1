<?php
function send_email($to, $subject, $body) {
    // For production, use a proper mailer like PHPMailer
    // This is a basic implementation using mail()
    
    $headers = "From: no-reply@yourdomain.com\r\n";
    $headers .= "Reply-To: no-reply@yourdomain.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    return mail($to, $subject, $body, $headers);
}
?>