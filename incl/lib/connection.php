<?php
//error_reporting(0);
include dirname(__FILE__)."/../../config/connection.php";
@header('Content-Type: text/html; charset=utf-8');
if(!isset($port))
	$port = 3306;
try {
    $db = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password, array(
    PDO::ATTR_PERSISTENT => true
));
    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	if(file_get_contents("https://api.foxodever.com/bcver/1.0") != "good") exit("Core is outdated. Ask your server owner for upgrade.");
    	$query = $db->prepare("DELETE FROM register WHERE registerDate + 1800 < ".time());	
    	$query->execute();
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
?>
