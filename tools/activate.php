<?php
include "../incl/lib/connection.php";
require_once "../incl/lib/mainLib.php";
$gs = new mainLib();
$tk = $_GET["token"];
if(!empty($tk)) {
    $query = $db->prepare("SELECT * FROM register WHERE token = :tk");
    $query->execute([':tk' => $tk]);
    if ($query->rowCount() > 0) {
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
        echo "1";
    } else {
        echo "-1";
    }
} else {
    echo "1";
}
?>