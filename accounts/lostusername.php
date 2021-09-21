Enter your account email<br><form action="lostusername.php" method="post">Email: <input type="text" name="email"><br><input type="submit" value="Reset"></form>
<?php
include "../incl/lib/connection.php";
include "../incl/lib/mail.php";
$email = $_POST["email"];
if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $query = $db->prepare("SELECT * FROM accounts WHERE email = :email");
    $query->execute([':email' => $email]);
    if ($query->rowCount() > 0) {
        $query = $db->prepare("SELECT userName FROM accounts WHERE email = :email");
        $query->execute([':email' => $email]);
        $userName = $query->fetchColumn();
        require("mail/PHPMailerAutoload.php");
        $mail = new PHPMailer;
        $mail->CharSet = 'utf-8';

        $mail->isSMTP();
        $mail->Host = $smtp;
        $mail->SMTPAuth = true;
        $mail->Username = $mail_server;
        $mail->Password = $mail_server_password; 
        $mail->SMTPSecure = $mail_type;
        $mail->Port = $smtp_port;

        $mail->setFrom($mail_server);
        $mail->addAddress("$email"); 
        $mail->isHTML(true);
        $mail->Subject = 'Your username';
        $mail->Body    = "<h1 align=center>Hello</h1><p align=center>Your username is: $userName</p>";
        $mail->AltBody = '';
        if($mail->send()) {
            echo "Email sent";
        } else {
            echo "-1";
        }
    } else {
        echo "Email not found";
    }
} else {
    if(empty($_POST["email"])) {

    } else {
        echo "Email invalid";
    }
}

?>
