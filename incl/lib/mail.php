<?php
include dirname(__FILE__)."/../../config/mail.php";
if($mail_type == 'ssl' || $mail_type == 'tls') {
    $mail_type = $mail_type;
} else {
    $mail_type = 'ssl';
}
if(!isset($smtp_port)) {
    $smtp_port = 465;
}
if(!isset($mail_server)) {
    exit("-1");
}
if(!isset($mail_server_password)) {
    exit("Mail password doesn't set");
}
if(!isset($smtp)) {
    exit("SMTP server doesn't set");
}
?>
