<?php session_start();
require_once("dbnames.inc");
require_once($_dbconfig);
?>
<html>
	<head>
		<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/analytics.php"); ?>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		<STYLE TYPE="text/css">
			@import url("../harry.css");
			th, td { padding:1px 2px; }
		</STYLE>
		<script src="sorttable.js"></script>
		<title>Game Stats</title>
	</head>
	<body>
		<div id=container>
			<div id=header>
				<h2>Global Game Stats</h2>
				<?php $sel=6; include("../header.php"); ?>
			</div>
			<div id=content>
				<div class=entry>
					<p>Note: Only completed standard games are used for determining statistics. Click on a header to sort by column.</p><br>
					<?php
						include("dipstats/powerrank.php");
						include("dipstats/gamestats.php");
					?>
				</div>
			</div>
			<?php include("../footer.php"); ?>
		</div>
	</body>
</html>
