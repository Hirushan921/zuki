<?php

use PHPMailer\PHPMailer\PHPMailer;

require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';


require "connection.php";

if (isset($_GET["e"])) {
    $e = $_GET["e"];

    if (empty($e)) {
        echo "Please enter your email address";
    } else {
        $rs = Database::search("SELECT * FROM `user` WHERE `email`='" . $e . "'");
        if ($rs->num_rows == 1) {

            $code = uniqid();
            Database::iud("UPDATE `user` SET `verification_code`='" . $code . "' WHERE `email`='" . $e . "'");

            $mail = new PHPMailer;
            $mail->IsSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'restaurantzuki@gmail.com';
            $mail->Password = 'himashazuki';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
            $mail->setFrom('restaurantzuki@gmail.com', 'zuki');
            $mail->addReplyTo('restaurantzuki@gmail.com', 'zuki');
            $mail->addAddress($e);
            $mail->isHTML(true);
            $mail->Subject = 'ZUKI forgot password verification code';
            $bodyContent = '<h1>Your verification code is '.$code.'</h1>';
            $mail->Body    = $bodyContent;

            if (!$mail->send()) {
                echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
            } else {
                echo 'success';
            }
        } else {
            echo "Email address not found";
        }
    }
} else {
    echo "Please enter your email address";
}