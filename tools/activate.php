<?php
include "../incl/lib/connection.php";
include "../config/security.php";
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

if($cap == "" && $captchaType == 1) {

} else {
    if ($captchaType == 2) {
        $data = array(
            'secret' => $hcaptcha_secret,
            'response' => $_POST['h-captcha-response']
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://hcaptcha.com/siteverify");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $responseData = json_decode($response);
        if($responseData->success) {
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
            exit;
        }
    }
    if($cap == $captcha && $captchaType == 1) {
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
        if($captchaType == 1) {
            $err = "Invalid captcha";
        }
    }
}

// if(!empty($tk)) {
//     $query = $db->prepare("SELECT * FROM register WHERE token = :tk");
//     $query->execute([':tk' => $tk]);
//     if ($query->rowCount() > 0) {
        
//     } else {
//         echo "-1";
//     }
// } else {
//     echo "1";
// }
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
    <?php
    if($captchaType == 1) {
    ?>
    <img style="border: 1px solid black" src="cap.php" method="post">
    <form action="activate.php?token=<?=$tk?>" method="post">
        <input style="padding-bottom: 5px" name="cap" placeholder="Enter here..." /><br />
        <input type="submit" value="verify" />
    </form>
    <?php
    } else {
        ?>
        <form action="activate.php?token=<?=$tk?>" method="post">
            <div class="h-captcha" data-sitekey="<?=$hcaptcha_key?>"></div><br />
            <input type="submit" value="verify" />
        </form>
        <?php
    }
    ?>
</div>
<?php
if($captchaType == 2) {
    echo '<script src="https://hcaptcha.com/1/api.js" async defer></script>';
}
?>
