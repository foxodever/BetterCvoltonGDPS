<?php
include dirname(__FILE__)."/../../config/mail.php";
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