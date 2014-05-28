<?php $numyears = floor(($gameinfo['numturns'] + 6)/ 5); ?>
<div class=entry>
	<h4>Statistics for <?=$gameinfo['name'] ?></h4>
	<?php
	//Because of width constraints, SC ownership tables are only 10 years long
	//and multiple tables will be used for longer games
	for($q = 0; $q < $numyears; $q += 10) { ?>
	<table border="1">
		<thead>
			<tr><th>Power</th>
			<?php
			//If the game is finished or not anonymous, show player names
			if($gameinfo['press'] == 3 || $_gameinfo['status'] > 3)
				echo("<th>Player</th>");
			$curyear = $gameinfo['startyear'] + $numyears - 2;

			for($i = $q + 1; $i < min($numyears, $q + 10); $i++)
			{
				$curyear = $gameinfo['startyear'] + $i - 1;
				echo("<th><a href='http://hdwhite.org/dominate/game/" .
					"$game/results/" . (($i - 1) * 5) ."'>$curyear</a></th>");
			}
			$curyear++;

			//If this is the last year and the game has finished, make the final
			//column labelled "Final"
			if ($gameinfo['status'] > 3 && $q + 10 >= $numyears)
			{
				echo("<th><a href='http://hdwhite.org/dominate/game/" .
					"$game'>Final</a></th>");
				if($gameinfo['status'] == 5)
					echo("<th>Pos</th><th>Points</th>");
			}
			//If Fall Retreats are done but not Winter Adjustments, link to the
			//former instead
			else
			{
				if($curyear > $curturn->getyear())
					echo("<th><a href='http://hdwhite.org/dominate/game/" .
						"$game'>$curyear</a></th>");
				else
					echo("<th><a href='http://hdwhite.org/dominate/game/" .
						"$game/results/" . (($i - 1) * 5) . "'>$curyear</a></th>");
			}
		echo("</tr></thead><tbody>");

		//Now for the table body
		//Iterate over each power
		foreach ($stattable as $curpower)
		{
			//Make the table colourful!
			echo("<tr><td class=\"" . substr($curpower['name'], 0, 1) . "\">" .
				$curpower['name'] . "</td>");

			//Show player name if we should
			if($gameinfo['press'] == 3 || $gameinfo['status'] > 3)
				echo("<td>" . implode(" / ", $curpower['players']) . "</td>");

			for ($j = $q; $j < min($numyears, $q + 10); $j++)
			{
				echo("<td>");
				$sc = $curpower[$gameinfo['startyear'] + $j];
				//Bold the entry if they were in the lead for that year
				//Italicise it if they were eliminated
				switch($sc)
				{
					case 0:
						echo("<i>0</i>");
						break;
					case $maxsc[$gameinfo['startyear'] + $j]:
						echo("<b>$sc</b>");
						break;
					default:
						echo($sc);
				}
				echo("</td>");
			}

			//If the game is over and was for stats, display their final rank
			//and points tally
			if ($gameinfo['status'] == 5 && $numyears <= $q + 10)
				echo("<td>" . $curpower['rank'] . "</td>" .
					"<td>" . $curpower['points'] . "</td>");
			echo("</tr>");
		}
		//Footer time
		echo("</tbody><tfoot><tr></tr><tr><td>Index</td>");
		if($gameinfo['press'] == 3 || $gameinfo['status'] > 3)
			echo("<td>N/A</td>");

		//The index is equal to the average of the sum of the squares of the
		//total SC count for each player in a given year. The higher the index,
		//the further along a game has progressed
		for ($i = $q; $i < min($numyears, $q + 10); $i++)
		{
			$index = 0;
			foreach ($stattable as $curpower)
				$index += pow($curpower[$gameinfo['startyear'] + $i], 2);
			echo("<td>" . round($index/count($stattable)) . "</td>");
		}
		if ($gameinfo['status'] == 5  && $numyears <= $q + 10)
		{
			echo("<td>N/A</td><td>N/A</td>");
		}
		?>
	</tfoot></table>
	<?php echo("<br>"); } ?>
</div>

<?php if($gameinfo['standard'] == 'y') { ?>
<div class="entry">
	<h4>SC Ownership by year</h4>
	<?php
	$scs = Array("Edi", "Lvp", "Lon", "Bre", "Par", "Mar", "Por", "Spa", "Bel",
		"Hol", "Mun", "Kie", "Ber", "Den", "Swe", "Nor", "Stp", "War", "Mos",
		"Sev", "Con", "Ank", "Smy", "Bul", "Rum", "Gre", "Ser", "Bud", "Vie",
		"Tri", "Ven", "Rom", "Nap", "Tun");
	?>
	<table border="1" class="sortable">
		<thead>
			<tr><th>SC</th>
			<?php
				for($i = 0; $i < $numyears; $i++)
					echo("<th>" . (1901 + $i) . "</th>");
			?>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach($scs as $cursc)
			{
				echo("<tr><td>$cursc</td>");
				for($i = 0; $i < $numyears; $i++)
				{
					$owner = $sctable[$cursc][1901 + $i];
					echo("<td class='$owner b c'>$owner</td>");
				}
				echo("</tr>");
			}
			?>
		</tbody>
	</table>
</div>
<?php } ?>
