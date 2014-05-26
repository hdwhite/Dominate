<?php
session_start();
if(!isset($_SESSION['loggedin']))
{
	header("Location: http://hdwhite.org/login.php");
	exit;
}
include("dbnames.inc");
include($_dbconfig);

//Logic for game creation
if(isset($_POST['action']))
{
	$name = trim($_POST['name']);
	$lowername = strtolower($name);
	$mdeadline = $_POST['mdeadline'] . ":00:00";
	$rdeadline = $_POST['rdeadline'] . ":00:00";
	$press = $_POST['press'];
	$stats = isset($_POST['stats']) ? 'y' : 'n';
	$user = $_SESSION['user'];
	$info = nl2br(htmlentities($_POST['info']));

	//Make sure that a game of the same name doesn't already exist
	$dupstmt = $mysqli->prepare("SELECT COUNT(*) FROM $_gamedb WHERE name=?");
	$dupstmt->bind_param('s', $name);
	$dupstmt->execute();
	$dupstmt->bind_result($samename);
	$dupstmt->fetch();
	$dupstmt->close();
	//"template" and "dipstats" are reserved names, and game names can have a
	//limited range of accepted characters
	if($samename == 0 && preg_match("/^[A-Za-z0-9_- ]+$/", $name) &&
		$lowername != "template" && $lowername != "dipstats")
	{
		//Insert game info into the game table
		$gamestmt = $mysqli->prepare("INSERT INTO $_gamedb(name, for_stats, press, " .
			"move_deadlines, retreat_deadlines, gm, info) " .
			"VALUES(?, ?, ?, ?, ?, ?, ?)");
		$gamestmt->bind_param('ssissss',
			$name, $stats, $press, $mdeadline, $rdeadline, $user, $info);
		$gamestmt->execute();
		$gamestmt->close();
		$gameid = $mysqli->insert_id;
		//Now insert the power information
		$powerstmt = $mysqli->prepare("INSERT INTO $_powerdb(name, game) VALUES(?, ?)");
		$powerstmt->bind_param('si', $power, $gameid);
		$powerlist = array("Austria", "England", "France", "Germany",
						   "Italy", "Russia", "Turkey");
		foreach($powerlist as $power)
			$powerstmt->execute();
		$powerstmt->close();
		//Create the directories and initial game files
		//Permissions might not need to be so generous now that safe mode is
		//no longer in place
		mkdir("../diplomacy/$name/images/1901", 0775, true);
		mkdir("../diplomacy/$name/text/1901", 0775, true);
		copy("../diplomacy/template/images/1901/W1.png", "../diplomacy/$name/images/1901/W1.png");
		copy("../diplomacy/template/text/1901/W.php", "../diplomacy/$name/text/1901/W.html");
		header("Location: http://hdwhite.org/dominate/?message=10");
		exit;
	}
	else
		$message = "A game with this name already exists.";
}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		<STYLE TYPE="text/css">
			@import url("../harry.css");
		</STYLE>
		<title>Create game</title>
	</head>
	<body>
		<div id=container>
			<div id=header>
				<h2>Create a game</h2>
				<?php $sel=6; include("../header.php"); ?>
			</div>
			<div id=content>
				<?php if(isset($message))
					echo("<div class='entry'><font color='#990000'><b>$message</b></font></div>"); ?>
				<div class=entry>
					<h4>Game information</h4>
					<form action="creategame.php" method="post">
					<p>Name: <input type="text" name="name" size="32"></p>
					<p>Move deadlines (in hours): <input type="text" name="mdeadline" size="3"></p> 
					<p>Retreat and adjustment deadlines (in hours): <input type="text" name="rdeadline" size="3"></p>
					<p>Press options: <select name="press">
						<option value="0">No press allowed</option>
						<option value="1">Broadcast press only</option>
						<option value="3" selected="selected">All press allowed</option>
					</select></p>
					<p>Is the game for stats? <input type="checkbox" name="stats"></p>
					<p>Game information:</p>
					<textarea name="info" cols="80" rows="25"></textarea><br>
					<input type="submit" name="action" value="Create game">
					</form>
				</div>
			</div>
		<?php include("../footer.php"); ?>
		</div>
	</body>
</html>
