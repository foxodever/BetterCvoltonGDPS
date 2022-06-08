<?php
include "../incl/lib/connection.php";
require "../incl/lib/generatePass.php";
require_once "../incl/lib/exploitPatch.php";
$ep = new exploitPatch();
require_once "../incl/lib/mainLib.php";
$gs = new mainLib();
if(!empty($_POST["userName"]) AND !empty($_POST["password"]) AND !empty($_POST["needUserName"])){
	$userName = $ep->remove($_POST["userName"]);
	$password = $ep->remove($_POST["password"]);
	$needUserName = $ep->remove($_POST["needUserName"]);
	$generatePass = new generatePass();
	$pass = $generatePass->isValidUsrname($userName, $password);
	if ($pass == 1) {
		$query = $db->prepare("SELECT accountID FROM accounts WHERE userName=:userName");	
		$query->execute([':userName' => $userName]);
		$accountID = $query->fetchColumn();
		if($gs->checkPermission($accountID, "toolLeaderboardsban")){
			$query = $db->prepare("UPDATE users SET isBanned = 1 WHERE userName = :need");
			$query->execute([':need' => $needUserName]);
			if($query->rowCount() != 0){
				echo "Banned succesfully.";
			}else{
				echo "Ban failed.";
			}
			$query = $db->prepare("INSERT INTO modactions  (type, value, value2, timestamp, account) 
													VALUES ('15',:userName, '1',  :timestamp,:account)");
			$query->execute([':userName' => $needUserName, ':timestamp' => time(), ':account' => $accountID]);
		}else{
			exit("You do not have the permission to do this action. <a href='leaderboardsBan.php'>Try again</a>");
		}
	}else{
		echo "Invalid password or nonexistant account. <a href='leaderboardsBan.php'>Try again</a>";
	}
}else{
	echo '<form action="leaderboardsBan.php" method="post">Your Username: <input type="text" name="userName">
		<br>Your Password: <input type="password" name="password">
		<br>Target Username: <input type="text" name="needUserName">
		<br><input type="submit" value="Ban"></form>';
}
?>
