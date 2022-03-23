<?php
error_reporting(0);
include "../incl/lib/connection.php";
include "../incl/lib/mail.php";
require_once "../incl/lib/exploitPatch.php";
$ep = new exploitPatch();
if($_POST["userName"] != "" && filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) && $_POST["password"] != ""){
	//here im getting all the data
	$userName = $ep->remove($_POST["userName"]);
	$password = $ep->remove($_POST["password"]);
	$email = $ep->remove($_POST["email"]);
	$domain = explode('@', $email)[1];
	$secret = "";
	//checking if name is taken
	$query2 = $db->prepare("SELECT count(*) FROM accounts WHERE userName LIKE :userName");
	$query2->execute([':userName' => $userName]);
	$regusrs = $query2->fetchColumn();
	if ($regusrs > 0) {
		echo "-2";
	}else{
		//checking if name is taken in not registered accounts
		$query2 = $db->prepare("SELECT count(*) FROM register WHERE userName LIKE :userName");
		$query2->execute([':userName' => $userName]);
		$regusrs = $query2->fetchColumn();
		if ($regusrs > 0) {
			echo "-2";
		}else{
			//checking if email is taken
			$query2 = $db->prepare("SELECT count(*) FROM accounts WHERE email LIKE :email");
			$query2->execute([':email' => $email]);
			$regusrs = $query2->fetchColumn();
			if ($regusrs > 0) {
				exit("-3");
			}else{
				$query2 = $db->prepare("SELECT count(*) FROM register WHERE email LIKE :email");
				$query2->execute([':email' => $email]);
				$regusrs = $query2->fetchColumn();
				if ($regusrs > 0) {
					exit("-3");
				}else{
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
					$hashpass = password_hash($password, PASSWORD_DEFAULT);
					$token = generate(10);
					$query = $db->prepare("INSERT INTO register (userName, password, email, secret, saveData, registerDate, saveKey, token)
					VALUES (:userName, :password, :email, :secret, '', :time, '', :token)");
					$query->execute([':userName' => $userName, ':password' => $hashpass, ':email' => $email, ':secret' => $secret, ':token' => $token, ':time' => time()]);
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
					$mail->Subject = 'GDPS account activation';
					$mail->Body    = "<h1 align=center>Hello $userName</h1><p align=center>activate your GDPS account by going to link down:</p>
					<p align=center><a href='$url_register?token=$token' 'style=color:blue;text-decoration:none'>Activate your account</a></p>
					<p align=center>Can not open link? $url_register?token=$token</p>";
					$mail->AltBody = '';
					if($mail->send()) {
						echo "1";
					} else {
						echo "-1";
					}
				}
			}
		}
	}
}
?>
