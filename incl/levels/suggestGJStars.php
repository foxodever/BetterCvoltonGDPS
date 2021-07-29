<?php
//error_reporting(0);
chdir(dirname(__FILE__));
include "../lib/connection.php";
require_once "../lib/GJPCheck.php";
require_once "../lib/exploitPatch.php";
$ep = new exploitPatch();
require_once "../lib/mainLib.php";
$gs = new mainLib();
$gjp = $ep->remove($_POST["gjp"]);
$stars = $ep->remove($_POST["stars"]);
$feature = $ep->remove($_POST["feature"]);
$levelID = $ep->remove($_POST["levelID"]);
$accountID = $ep->remove($_POST["accountID"]);
if($accountID != "" AND $gjp != ""){
	$status = $gs->getRateStatus($levelID);
	$cp = 0;
	if($status == 0) {
		if($feature == 1) {
			$cp = 2;
		} else {
			$cp = 1;
		}
	} else if($status == 1) {
		if($feature == 1) {
			$cp = 1;
		} else {
			$cp = 0;
		}
	} else if($status == 2) {
		if($feature == 1) {
			$cp = 0;
		} else {
			$cp = -1;
		}
	} else if($status == 3) {
		exit("-1");
	}
	$GJPCheck = new GJPCheck();
	$gjpresult = $GJPCheck->check($gjp,$accountID);
	if($gjpresult == 1){
		$difficulty = $gs->getDiffFromStars($stars);
		if($gs->checkPermission($accountID, "actionRateStars")){
			$gs->rateLevel($accountID, $levelID, $stars, $difficulty["diff"], $difficulty["auto"], $difficulty["demon"]);
			$gs->featureLevel($accountID, $levelID, $feature);
			$gs->verifyCoinsLevel($accountID, $levelID, 1);
			if($cp != 0) {
				$a = $gs->addCreatorPoints($cp, $levelID);
			} else {
				$a = -1;
			}
			echo $a;
		}else if($gs->checkPermission($accountID, "actionSuggestRating")){
			$gs->suggestLevel($accountID, $levelID, $difficulty["diff"], $stars, $feature, $difficulty["auto"], $difficulty["demon"]);
			echo 1;
		}else{
			echo -2;
		}
	}else{
		echo -2;
	}
}else{
	echo -2;
}
include "cp.php";
?>
