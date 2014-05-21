<div class=entry>
	<h4>Ongoing games</h4>
	<table border="1" class="sortable">
		<thead><tr><th>Game</th><th>Press</th><th>Year</th><th>Season</th><th>Deadline</th><th>TTD</th><th>Info</th></thead>
		<tbody>
		<?php
			require_once("game/objects/Turn.php");
			while($curgame = $gamelist->fetch_assoc())
			{
				$gamename = "<a href='game/" . $curgame['id'] . "'>" .
					$curgame['name'] . "</a>";
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
				$turn = (new Turn($curgame['startyear'], $curgame['numturns']))->getnext();
				$year = $turn->getyear();
				$season = $turn->display("S P");
				$deadline = date("n/j G:i", strtotime($curgame['next_deadline']));
				$temp = max(0, strtotime($curgame['next_deadline']) - time());
				$ttd = sprintf("%02d:%02d", floor($temp/3600), floor($temp/60)%60);
				$infolink = "<a href='game/" . $curgame['id'] . "/info'>Info</a>";
				echo("<tr><td>$gamename</td><td>$press</td><td>$year</td>" .
					"<td>$season</td><td>$deadline</td><td>$ttd</td><td>$infolink</td></tr>");
			}
		?>
		</tbody>
	</table>
</div>
