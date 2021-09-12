<?php
include "../../incl/lib/connection.php";

$query = $db->prepare("UPDATE levels SET starDemon = 0, starEpic = 0, starFeatured = 0, starCoins = 0 WHERE starStars < 1");
$query->execute();
?>
1
