<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		<STYLE TYPE="text/css">
			@import url("http://hdwhite.org/dominate/dominate<?=$css ?>.css");
			.A { color:#CD5C5C; }
			.E { color:#0000FF; }
			.F { color:#00BFFF; }
			.G { color:#000000; }
			.I { color:#60A000; }
			.R { color:#666666; }
			.T { color:#FFAA00; }
			.b { font-weight:bold; }
			td.c { text-align:center; }
			.pointer td { cursor:pointer; }
		</STYLE>
		<title><?=$gameinfo['name'] ?> - <?=$powername ?> - <?=$username ?></title>
		<script language="javascript">
		function toggle(targetId) {
		target = document.getElementById(targetId);
		if (target.style.display == "none"){
		target.style.display="";
		} else {
		target.style.display="none";
		}
		}
		//-->
		</script>
		<script src="sorttable.js"></script>
	</head>
	<body>
		<div id="container">
			<div id="header">
				<h2><?=$gameinfo['name'] ?> - <?=$powername ?></h2>
				<?php include("../../dominate/header.php"); ?>
			</div>
			<div id="content">
				<div class="entry">
					<h4>
<?php
switch($gameinfo['status'])
{
	case 0:
		if ($powername == "Observer")
			echo("<a href='joingame.php?game=$game'>Join this game.</a>");
		else
			echo("This game is awaiting players.");
		break;
	case 1:
		echo("This game is paused until started by the GM.");
		break;
	case 2:
		echo("It is currently the " . $curturn->getnext()->display('S y P') . ".<br>");
		if (strtotime($gameinfo['next_deadline']) - time() > 0)
			echo("Orders are due " .
				 date("g:i A, F j, Y", strtotime($gameinfo['next_deadline'])) . ".");
		else
			echo("The deadline has passed.");
		if ($powername != "Observer" && $powername != "GM")
		{
			if ($orders === NULL || $orders == "")
				echo(" You have not sent in orders.");
			else
				echo(" You have sent in orders.");
		}
		break;
	case 3:
		echo("This game is paused until a replacement player can be found.");
		break;
	case 4:
	case 5:
		echo("This game has finished.");
		break;
}
?>
					</h4>
				</div>
