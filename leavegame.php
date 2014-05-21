<?php
session_start();
include("dbnames.inc");
include($_dbconfig);
$id = $_GET['game'];
$mysqli->query("UPDATE $_powerdb SET player=NULL " .
	"WHERE game=$id AND player=" . $_SESSION['id']);
header("Location: .?message=40");
exit;
?>
