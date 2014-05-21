<?php
$longest = 0;
$totalgames = array_fill(0, 50, 0);
$gamesthrough = array_fill(0, 50, 0);
$countries = array("Austria", "England", "France", "Germany", "Italy", "Russia", "Turkey");
$gametable = array_fill_keys($countries, array_fill(0, 50, 0));
foreach($mysqli->query("SELECT status, $_powerdb.name AS power, year, sc " .
	"FROM $_gamedb, $_powerdb, $_yeardb WHERE $_powerdb.game=$_gamedb.id AND " .
	"$_yeardb.power=$_powerdb.id AND for_stats='y' AND standard='y'") as $curyear)
{
	$year = $curyear['year'] - 1901;
	if($curyear['power'] == "Austria")
	{
		$longest = max($longest, $year + 1);
		$totalgames[$year]++;
		if($curyear['status'] == 5)
			$gamesthrough[$year]++;
	}
	$gametable[$curyear['power']][$year] += $curyear['sc'];
}
?>

<h4>Number of games lasting through:</h4><br>
<table border="1"><tr>
<?php
for ($i = 0; $i < $longest; $i++)
	echo("<th>" . ($i + 1901) . "</th>");
echo("</tr><tr>");
for ($i = 0; $i < $longest; $i++)
	echo("<td>" . $gamesthrough[$i] . "</td>");
?>
</tr></table><br>
<h4>Average Supply Center count by year</h4><br>
<table border="1" class="sortable"><tr><th>Power</th>
<?php
for ($i = 0; $i < 10; $i++)
	echo("<th>" . ($i + 1901) . "</th>");
echo("</tr>");
for ($i = 0; $i < 7; $i++)
{
	echo("<tr><td>" . $countries[$i] . "</td>");
	for ($j = 0; $j < 10; $j++)
		echo("<td>" . round($gametable[$countries[$i]][$j]/$totalgames[$j], 2) . "</td>");
}
?>
</tr></table>
