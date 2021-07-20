<?php
include "../incl/lib/connection.php";
require_once "../incl/lib/mainLib.php";
$gs = new mainLib();
$tk = $_GET["token"];
if(!empty($tk)) {
    $query = $db->prepare("SELECT * FROM reset WHERE token = :tk");
    $query->execute([':tk' => $tk]);
    if ($query->rowCount() > 0) {
        $query = $db->prepare("SELECT password FROM reset WHERE token = :tk");
        $query->execute([':tk' => $tk]);
        $pass = $query->fetchColumn();
        $query = $db->prepare("SELECT acc FROM reset WHERE token = :tk");
        $query->execute([':tk' => $tk]);
        $acc = $query->fetchColumn();
        $query = $db->prepare("UPDATE accounts SET password = :password WHERE accountID = :accID");
        $query->execute([':accID' => $acc, ':password' => $pass]);
        $query = $db->prepare("DELETE FROM reset WHERE token = :tk");
        $query->execute([':tk' => $tk]);
        echo "1";
    } else {
        echo "-1";
    }
} else {
    echo "1";
}
?>
