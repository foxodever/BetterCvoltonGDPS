<?php
include "../incl/lib/connection.php";
require_once "../incl/lib/mainLib.php";
$gs = new mainLib();
session_start();
function invalid() {
    exit("-1");
}
function success() {
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * {
            font-family: Geneva, Arial, Helvetica, sans-serif;
            margin: 0;
        }
        .main {
            margin-top: 100px;
        }
        form {
            margin: 5px;
        }
    </style>
    <div class="main" align="center">
        <h1>Account activated!</h1>
        <p>You can login now</p>
    </div>';
    exit;
}
$captcha = $_SESSION["captcha"];
$tk = htmlspecialchars($_GET["token"]);
if($tk != "") {
    $_SESSION["tk"] = $tk;
    echo "<script>location.href='activate.php'</script>";
    exit;
} else {
    if($_SESSION["tk"]) {
        $query = $db->prepare("SELECT * FROM register WHERE token = :tk");
        $query->execute([':tk' => $_SESSION["tk"]]);
        if ($query->rowCount() == 0) {
            invalid();
        }
    } else {
        invalid();
    }
}
$cap = htmlspecialchars($_POST["cap"]);

if($cap == "") {
} else {
    if($cap == $captcha) {
        $tk = $_SESSION["tk"];
        $query = $db->prepare("SELECT userName FROM register WHERE token = :tk");
        $query->execute([':tk' => $tk]);
        $userName = $query->fetchColumn();
        $query = $db->prepare("SELECT password FROM register WHERE token = :tk");
        $query->execute([':tk' => $tk]);
        $password = $query->fetchColumn();
        $query = $db->prepare("SELECT email FROM register WHERE token = :tk");
        $query->execute([':tk' => $tk]);
        $email = $query->fetchColumn();
        $query = $db->prepare("SELECT registerDate FROM register WHERE token = :tk");
        $query->execute([':tk' => $tk]);
        $registerDate = $query->fetchColumn();
        $query = $db->prepare("INSERT INTO accounts (userName, password, registerDate, email) VALUES (:userName, :password, :registerDate, :email)");
        $query->execute([':userName' => $userName, ':password' => $password, ':email' => $email, ':registerDate' => $registerDate]);
        $query = $db->prepare("DELETE FROM register WHERE token = :tk");
        $query->execute([':tk' => $tk]);
        success();
    } else {
        $err = "Invalid captcha";
    }
}
?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
    * {
        font-family: Geneva, Arial, Helvetica, sans-serif;
        margin: 0;
    }
    .main {
        margin-top: 100px;
    }
    form {
        margin: 5px;
    }
</style>
<div class="main" align="center">
    <h1>Almsot here!</h1>
    <p style="padding-bottom: 10px"><?php
    if(!$err) {
        echo "Please verify what you are not a robot!";
    } else {
        echo $err;  
    }
    ?></p>
    <img style="border: 1px solid black" src="cap.php" method="post">
    <form action="activate.php?token=<?=$tk?>" method="post">
        <input style="padding-bottom: 5px" name="cap" placeholder="Enter here..." /><br />
        <input type="submit" value="verify" />
    </form>
</div>
