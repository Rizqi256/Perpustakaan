<?php

use PHPMailer\PHPMailer\PHPMailer;

function kirim_email($to = '', $subject = '', $body = '')
{
    include_once('PHPMailer/src/Exception.php');
    include_once('PHPMailer/src/PHPMailer.php');
    include_once('PHPMailer/src/SMTP.php');

    $mail = new PHPMailer(true);

    // $mail->SMTPDebug =SMTP::DEBUG_SERVER;

    $mail->isSMTP();
    $mail->SMTPAuth = true;

    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Port = 587;
    $mail->Username = 'rizqi.27560@gmail.com';
    $mail->Password = 'acsvvlvybpcctxdr';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

    $mail->addAddress($to);

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $body;

    $mail->send();
    return $mail;
}
