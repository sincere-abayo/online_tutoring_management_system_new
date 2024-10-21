<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'onlinetutoringmanagementsystem@gmail.com';
    $mail->Password   = 'echq pdvq pszu dhyf';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    //Recipients
    $mail->setFrom('onlinetutoringmanagementsystem@gmail.com', 'Online Tutoring management System');
    $mail->addAddress('sagemargo11@gmail.com', 'Test email');

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email from Online Tutoring Management System';
    $mail->Body    = 'This is a test email from the <b>Online Tutoring Management System</b>.';
    $mail->AltBody = 'This is a test email from the Online Tutoring Management System.';

    $mail->send();
    echo 'Test email has been sent successfully!';
} catch (Exception $e) {
    echo "Test email could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
