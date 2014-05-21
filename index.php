<?php
session_start();
if(isset($_SESSION['loggedin']))
{
	$username = $_SESSION['user'];
	$uid = $_SESSION['id'];
}
else
{
	$username = "Guest";
	$uid = -1;
}
require_once("dbnames.inc");
require_once($_dbconfig);
switch($_GET['message'])
{
	case 10:
		$message = "Game successfully created.";
		break;
	case 20:
		$message = "Game deleted.";
		break;
	case 21:
		$message = "You do not have permission to delete this game.";
		break;
	case 30:
		$message = "Game successfully joined.";
		break;
	case 31:
		$message = "You must be logged in to be able to join a game.";
		break;
	case 32:
		$message = "You have already joined this game.";
		break;
	case 33:
		$message = "You cannot join a game you are GMing.";
		break;
	case 40:
		$message = "Game left.";
		break;
	case 50:
		$message = "Game successfully joined.";
		break;
	case 51:
		$message = "Power does not require a replacement.";
		break;
	case 52:
		$message = "You cannot replace a power if you are already playing.";
		break;
	case 53:
		$message = "Replaced players may not re-join a game.";
		break;
	case 54:
		$message = "You must be logged in to be able to join a game.";
		break;
	case 61:
		$message = "Invalid game ID.";
		break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		<STYLE TYPE="text/css">
			@import url("../harry.css");
		</STYLE>
		<title>Game Listing</title>
	</head>
	<body>
		<div id=container>
			<div id=header>
				<h2>DOMINATE</h2>
				<?php $sel=6; include("../header.php"); ?>
			</div>
			<div id=content>
				<?php
				if(isset($message))
					echo("<div class='entry'><font color='#990000'><b>$message</b></font></div>");
				?>
		    	<div class="entry">
					<h4>What this is</h4>
					<p>Welcome to the Diplomacy Online Management Interface Normally Accessible Through Electronics (DOMINATE)! Yes, I know that name doesn't really make sense. This site allows for a more central management of Diplomacy games, allowing one to easily create and manage a game of Diplomacy, and for players to join, negotiate, and send orders all in one convenient place. To start playing, all you need to do is register an account, and you'll be ready to jump in!</p><br>
					<p><b>Important note:</b> This site is still very much in beta. There are still some features lacking, and bugs still probably exist. If you have any issues with this site, please let me know as soon as possible.</p>
				</div>
				<?php
				$gamelist = $mysqli->query("SELECT $_gamedb.id AS gid, " .
					"$_gamedb.name AS gname, next_deadline, startyear, numturns, " .
					"$_powerdb.name AS pname, orders " .
					"FROM $_gamedb, $_powerdb WHERE $_gamedb.id=$_powerdb.game " .
					"AND $_powerdb.player=$uid AND status>0 AND status<4");
				if($gamelist->num_rows > 0)
					include("gamesplaying.php");
				$gamelist = $mysqli->query("SELECT id, name, startyear, numturns, " .
					"move_deadlines, retreat_deadlines, press FROM $_gamedb " .
					"WHERE status=3");
				if($gamelist->num_rows > 0)
					include("gamesreplacing.php");
				$gamelist = $mysqli->query("SELECT id, name, gm, move_deadlines, " .
					"retreat_deadlines, press FROM $_gamedb WHERE status=0");
				if($gamelist->num_rows > 0)
					include("gamesnew.php");
				$gamelist = $mysqli->query("SELECT id, name, startyear, numturns, " .
					"next_deadline, press FROM $_gamedb WHERE status>0 AND status<3");
				if($gamelist->num_rows > 0)
					include("gamesongoing.php");
				$gamelist = $mysqli->query("SELECT id, name, press " .
					"FROM $_gamedb WHERE status>3 ORDER BY start_date DESC");
				if($gamelist->num_rows > 0)
					include("gamesfinished.php");
				?>
				<div class="entry">
					<h4>Box of Links</h4>
					<p><a href="account.php">Email settings</a>
					<br><a href="creategame.php">Create new game</a>
					<br><a href="stats.php">Global Game Stats</a>
					<br>View blank map <a href="StartingNames.gif">with full region names</a> or <a href="StartingAbbr.png">with region abbreviations</a>
					<br><a href="syntax.php">A guide for writing orders</a>
					<br><a href="http://www.diplomacy-archive.com/resources/rulebooks/2000AH4th.pdf">The latest Diplomacy rules (pdf)</a>
					<br><a href="http://en.wikipedia.org/wiki/Diplomacy_(game)">The Wikipedia page for Diplomacy</a>
					<br><a href="http://diplomacy-archive.com">The Diplomacy Archive, a repository of strategy articles and such</a></p>
				</div>
 			</div>
		<?php include("../footer.php"); ?>
		</div>
	</body>
</html>
