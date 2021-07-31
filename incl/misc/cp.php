<?php
error_reporting(E_ALL);
include "../../incl/lib/connection.php";
$query = $db->prepare("update users 
set creatorPoints = (
     select COUNT(*)
     from levels 
     where levels.userID = users.userID AND starStars != 0
) + (
     select COUNT(*)
     from levels 
     where levels.userID = users.userID AND levels.starFeatured != 0 AND levels.starEpic = 0 
) + (
    select COUNT(*)
    from levels 
    where levels.userID = users.userID AND levels.starEpic = 1 AND levels.starFeatured = 0
) + (
    select COUNT(*)
    from levels 
    where levels.userID = users.userID AND levels.starEpic = 1 AND levels.starFeatured = 0
) + (
    select COUNT(*)
    from levels 
    where levels.userID = users.userID AND levels.starEpic = 1 AND levels.starFeatured = 1
) + (
     select COUNT(*)
     from levels 
     where levels.userID = users.userID AND levels.starEpic = 1 AND levels.starFeatured = 1
)");
$query->execute();
?>
