<?php
session_start();
require_once("dbnames.inc");
require_once($_dbconfig);
$id = $_GET['game'];
$user = $_SESSION['user'];
$stmt = $mysqli->prepare("SELECT COUNT(*) FROM $_gamedb " .
	"WHERE id=? AND status=0 AND gm=?");
$stmt->bind_param("is", $id, $user);
$stmt->execute();
$stmt->bind_result($isgm);
$stmt->fetch();
if($isgm)
{
	$mysqli->query("DELETE FROM $_gamedb WHERE id=$id");
	$mysqli->query("DELETE FROM $_powerdb WHERE game=$id");
	header("Location: .?message=20");
	exit;
}
header("Location: .?message=21");
exit;
?>
