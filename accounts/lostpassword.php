Enter your account email<br><form action="lostpassword.php" method="post">Email: <input type="text" name="email"><br><input type="submit" value="Reset"></form>
<?php
include "../incl/lib/connection.php";
include "../incl/lib/mail.php";
$email = $_POST["email"];
if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $query = $db->prepare("SELECT * FROM accounts WHERE email = :email");
    $query->execute([':email' => $email]);
    if ($query->rowCount() > 0) {
        $query = $db->prepare("SELECT accountID FROM accounts WHERE email = :email");
        $query->execute([':email' => $email]);
        $accountID = $query->fetchColumn();
        function generate($how_long) {
            $length = $how_long;
            $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
            $numChars = strlen($chars);
            $string = '';
            for ($i = 0; $i < $length; $i++) {
                $string .= substr($chars, rand(1, $numChars) - 1, 1);
            }
            return $string;
        }
        $pass1 = generate(8);
        $token = generate(8);
        $pass = password_hash($pass1, PASSWORD_DEFAULT);
        $query = $db->prepare("INSERT INTO reset (acc, password, token)
        VALUES (:acc, :password, :token)");
        $query->execute([':acc' => $accountID, ':password' => $pass, ':token' => $token]);
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
        $mail->Subject = 'GDPS password reseting';
        $mail->Body    = "<h1 align=center>Hello $userName</h1><p align=center>Update your GDPS account password to $pass1 by going to link down:</p>
        <p align=center><a href='$url_reset?token=$token' 'style=color:blue;text-decoration:none'>Update your account password</a></p>
        <p align=center>Can not open link? $url_reset?token=$token</p>";
        $mail->AltBody = '';
        if($mail->send()) {
            echo "1";
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
