<?php
session_start();
if(isset($_SESSION['loggedin']))
{
	header("Location: .?message=54");
	exit;
}
$id = $_GET['power'];
include("dbnames.inc");
include($_dbconfig);
if($game = $mysqli->query("SELECT game FROM $_powerdb " .
	"WHERE id=$id AND player IS NULL")->fetch_assoc()['game'])
{
	if($gm = $mysqli->query("SELECT gm FROM $_gamedb " .
		"WHERE game=$game AND status=3")->fetch_assoc()['gm'])
	{
		//GMs can't join their own games
		if($gm == $_SESSION['user'])
		{
			header("Location: .?message=51");
			exit;
		}

		//You can't join games if you're already playing in it
		if($mysqli->query("SELECT COUNT(*) FROM $_powerdb " .
			"WHERE game=$game AND player=" . $_SESSION['id'])->fetch_row()[0] > 0)
		{
			header("Location: .?message=52");
			exit;
		}
		$mysqli->query("UPDATE $_powerdb SET player=" . $_SESSION['id'] . " WHERE id=$id");

		//Resume the game if there are no empty spots left
		if($mysqli->query("SELECT COUNT(*) FROM $_powerdb " .
			"WHERE game=$game AND player IS NULL")->fetch_row()[0] == 0)
			$mysqli->query("UPDATE $_gamedb SET status=1 WHERE id=$game"]);
		header("Location: .?message=50");
		exit;
	}
}
header("Location: .?message=51");
exit;
?>
