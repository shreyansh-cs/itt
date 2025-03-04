<?php 
include 'showerror.php';
use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\Exception; 
require 'vendor/autoload.php'; 

//Include PHPMailer 
$mail = new PHPMailer(true); 
try 
    {
         $mail->isSMTP(); 
         $mail->Host = 'smtp.hostinger.com'; 
         $mail->SMTPAuth = true; 
         $mail->Username = 'support@itticon.site'; 
         $mail->Password = 'Normaxin@321'; 
         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
         $mail->Port = 587; 
         $mail->setFrom('support@itticon.site', 'ITT Team'); 
         $mail->addAddress('shreyansh.cs@gmail.com'); 
         $mail->isHTML(true); $mail->Subject = 'Welcome to ITT Icon'; 
         $mail->Body = '<p>Email body in HTML format</p>'; 
         $mail->send(); 
         echo 'Email sent successfully!'; 
    } 
    catch (Exception $e) 
    { 
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"; 
    } 
?>