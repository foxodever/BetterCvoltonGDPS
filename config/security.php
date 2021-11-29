<?php
$captchaType = 1; // 1 - classic, 2 - hcaptcha, 3 - reCaptcha
if($captchaType == 2) {
    $hcaptcha_key = ""; // key
    $hcaptcha_secret = ""; // starts with 0x
}
if($captchaType == 3) {
    $recaptcha_key = "";
    $recaptcha_secret = ""; // SECRET not PUBLIC
}

$cloudSaveEncryption = 0; //0 = password string replacement, 1 = cloud save encryption (password dependant)
$sessionGrants = 1; //0 = GJP check is done every time; 1 = GJP check is done once per hour; drastically improves performance, slightly descreases security
?>
