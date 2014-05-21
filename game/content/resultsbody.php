<div class="entry">
	<h4><?=$gameinfo['name'] ?> - <?=$turn->display('S y F') ?></h4>
	<p>Choose a season: </p>
	<p style="text-align: center"><?php foreach($turnlist as $turnlink)
		echo($turnlink->printurl($game) . " "); ?></p>
</div>
<?php
$turndir = $turn->display("y/s");
$gamefile = str_replace(" ", "_", $gameinfo['name']);
for($w = 1;; $w++)
	if (file_exists($_SERVER['DOCUMENT_ROOT'] .
		"diplomacy/$gamefile/images/$turndir$w.png"))
		echo("<div class='entry'>" .
			"<img style='display:block; margin-left:auto; margin-right:auto;' " .
			"src=\"http://hdwhite.org/diplomacy/$gamefile/images/$turndir$w.png\">" .
			"</div>");
	else
		break;
if (file_exists($_SERVER['DOCUMENT_ROOT'] .
	"diplomacy/$gamefile/text/$turndir.html"))
{
	echo("<div class='entry'>" .
		"<div style='margin-top:0pt; font-family:helvetica, arial, sans-serif; " .
		"font-size:12pt; margin-bottom:0pt;'>");
	echo(file_get_contents($_SERVER['DOCUMENT_ROOT'] .
		"diplomacy/$gamefile/text/$turndir.html"));
	echo("</div></div>");
}
?>
<div class="entry">
	<p style="text-align: center"><?php foreach($turnlist as $turnlink)
		echo($turnlink->printurl($game) . " "); ?></p>
</div>
