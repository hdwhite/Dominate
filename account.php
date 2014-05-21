<?php
session_start();
include("dbnames.inc");
include("$_dbconfig");
if(!isset($_SESSION['loggedin']))
{
	header('Location: ../login.php');
	exit;
}
if(isset($_POST['action']))
{
	$mysqli->query("UPDATE $_userdb " .
				   "SET press='" . (isset($_POST['press']) ? 'y' : 'n') . "', " .
				   "adjudicate='" . (isset($_POST['adjudicate']) ? 'y' : 'n') . "' " .
				   "WHERE id=" . $_SESSION['id']);
	$message = "Settings updated.";
}
$userinfo = $mysqli->query("SELECT press, adjudicate FROM $_userdb " .
	" WHERE id=" . $_SESSION['id'])->fetch_assoc();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		<STYLE TYPE="text/css">
			@import url("../harry.css");
		</STYLE>
		<title>Account settings</title>
	</head>
	<body>
		<div id=container>
			<div id="header">
				<h2>DOMINATE account settings</h2>
				<?php $sel=6; include("../header.php"); ?>
			</div>
			<div id=content>
				<?php if(isset($message))
					echo("<div class='entry'><font color='#990000'>" .
						"<b>$message</b></font></div>"); ?>
		    	<div class="entry">
					<h4>Email notifications</h4>
					<form action="account.php" method="post">
					<input type="checkbox" name="press"<?php if($userinfo['press'] == 'y') echo(" checked"); ?>> Receive an e-mail whenever someone sends you a message.<br>
					<input type="checkbox" name="adjudicate"<?php if($userinfo['adjudicate'] == 'y') echo(" checked"); ?>> Receive an e-mail whenever a game you're in is adjudicated.<br>
					<input type="submit" name="action" value="Update settings">
					</form>
			</div>
			<?php include("../footer.php"); ?>
		</div>
	</body>
</html>
