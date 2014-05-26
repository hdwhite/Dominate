<?php
session_start();
if(!isset($_SESSION['loggedin']))
{
	header("Location: .?message=31");
	exit;
}
$id = $_GET['game'];
include("dbnames.inc");
include($_dbconfig);
if($game = $mysqli->query(
	"SELECT gm FROM $_gamedb WHERE id=$id AND status=0")->fetch_assoc())
{
	//GMs can't join games they're playing in
	if($game['gm'] == $_SESSION['user'])
	{
		header("Location: .?message=33");
		exit;
	}

	//You can't join a game if you're already in it
	if($mysqli->query("SELECT COUNT(*) FROM $_powerdb " .
		"WHERE game=$id AND player=" . $_SESSION['id'])->fetch_row()[0] > 0)
	{
		header("Location: .?message=32");
		exit;
	}

	//Picks a random empty spot for the new player
	$powerlist = $mysqli->query("SELECT id FROM $_powerdb " .
		"WHERE game=$id AND player IS NULL ORDER BY RAND()");
	$numempty = $powerlist->num_rows;
	$power = $powerlist->fetch_assoc()['id'];
	$mysqli->query(
		"UPDATE $_powerdb SET player=" . $_SESSION['id'] . " WHERE id=$power");

	//If there's one empty spot left, start the game
	if($numempty == 1)
		$mysqli->query("UPDATE $_gamedb SET status=1 WHERE id=$id");
}
header("Location: .?message=30");
exit;
?>
