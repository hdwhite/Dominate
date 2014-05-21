<div class=entry>
	<h4>Games awaiting replacement players</h4>
	<table border="1" class="sortable">
		<thead><tr><th>Game</th><th>Press</th><th>Year</th><th>Season</th><th>Deadlines</th><th>Info</th><th>Replace</th></thead>
		<tbody>
		<?php
			require_once("game/objects/Turn.php");
			while($curgame = $gamelist->fetch_assoc())
			{
				$gamename = "<a href='game/" . $curgame['id'] . "'>" .
					$curgame['game'] . "</a>";
				switch ($curgame['press'])
				{
					case 0:
						$press = "None";
						break;
					case 1:
						$press = "Broadcast";
						break;
					case 2:
						$press = "Anonymous";
						break;
					case 3:
						$press = "All";
						break;
				}
				$turn = new Turn($curgame['startyear'], $curgame['numturns'])->getnext();
				$year = $turn->getyear();
				$season = $turn->display("S P");
				$deadlines = explode(':', $curgame['move_deadlines'])[0] . " h/" .
					explode(':', $curgame['retreat_deadlines'])[0] . " h";
				$infolink = "<a href='game/" . $curgame['id'] . "/info'>Info</a>";
				$rlist = array();
				foreach($mysqli->query("SELECT id, name FROM $_powerdb " .
					"WHERE game=" . $curgame['id'] . " AND player IS NULL")
					as $rpower);
				while($rpower = mysql_fetch_array($replacelist))
					$rlist[] = "<a href='replacegame.php?power=" . $rpower['id'] . "'>" .
						$rpower['name'] . "</a>";
				$replace = implode(" ", $rlist);
				echo("<tr><td>$gamename</td><td>$press</td><td>$year</td>" .
					"<td>$season</td><td>$deadlines</td><td>$infolink</td>" .
					"<td>$replace</td></tr>");
			}
		?>
		</tbody>
	</table>
</div>
