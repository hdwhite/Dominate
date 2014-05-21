<?php
$countries = array("Austria", "England", "France", "Germany", "Italy", "Russia", "Turkey");
$rankTable = array_fill_keys($countries, array_fill(0, 7, 0));
$pointsTable = array_fill_keys($countries, 0);
$numFinished = $mysqli->query("SELECT COUNT(*) FROM $_gamedb " .
	"WHERE for_stats='y' AND status=5 AND standard='y'")->fetch_row()[0];
foreach($mysqli->query("SELECT $_powerdb.name AS power, rank, points " .
	"FROM $_gamedb, $_powerdb WHERE $_powerdb.game=$_gamedb.id AND " .
	"for_stats='y' AND status=5 AND standard='y'") as $curpower)
{
	$rankTable[$curpower['power']][$curpower['rank'] - 1]++;
	$pointsTable[$curpower['power']] += $curpower['points'];
}
?>
<h4>Rank by power</h4><br>
<table border="1" class="sortable">
<thead><tr><th>Power</th><th>1st</th><th>2nd</th><th>3rd</th><th>4th</th><th>5th</th><th>6th</th><th>7th</th><th>Average</th></tr></thead>
<tbody>
<?php
	for($i = 0; $i < 7; $i++)
	{
		$tally = 0;
		$power = $countries[$i];
		echo("<tr><td>$power</td>");
		for($j = 0; $j < 7; $j++)
		{
			$tally += $rankTable[$power][$j] * ($j + 1);
			echo("<td>" . $rankTable[$power][$j] . "</td>");
		}
		echo("<td>" . round($tally / $numFinished, 2) . "</td></tr>");
	}
?>
</tbody></table><br>
<h4>Average points by power</h4><br>
<table border="1"><tr>
<?php
	for($i = 0; $i < 7; $i++)
	{
		echo("<th>" . $countries[$i] . "</th>");
	}
	echo("</tr><tr>");
	for($i = 0; $i < 7; $i++)
	{
		echo("<td>" . round($pointsTable[$countries[$i]] / $numFinished, 2) . "</td>");
	}
?>
</tr></table><br>
