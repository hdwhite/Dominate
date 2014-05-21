<div class=entry>
	<h4>Games you are in</h4>
	<table border="1" class="sortable">
		<thead><tr><th>Game</th><th>Country</th><th>Year</th><th>Season</th><th>Info</th><th>Deadline</th><th>TTD</th><th>Unread</th><th>Orders</th></tr></thead>
		<tbody>
		<?php
			require_once("game/objects/Turn.php");
			while($curgame = $gamelist->fetch_assoc())
			{
				$gamename = "<a href='game/" . $curgame['gid'] . "'>" .
					$curgame['gname'] . "</a>";
				$power = $curgame['pname'];
				$turn = new Turn($curgame['startyear'], $curgame['numturns'])->getnext();
				$year = $turn->getyear();
				$season = $turn->display("S P");
				$infolink = "<a href='game/" . $curgame['gid'] . "/info'>Info</a>";
				$deadline = date("n/j G:i", strtotime($curgame['next_deadline']));
				$temp = max(0, strtotime($curgame['next_deadline']) - time());
				$ttd = sprintf("%02d:%02d", floor($temp/3600), floor($temp/60)%60);
				$unread = 0;
				if($curgame['orders'] == '' || $curgame['orders'] == NULL)
					$orders = "None";
				else
					$orders = "Sent";
				echo("<tr><td>$gamename</td><td>$power</td><td>$year</td>" .
					"<td>$season</td><td>$infolink</td><td>$deadline</td>" .
					"<td>$ttd</td><td>$unread</td><td>$orders</td></tr>");
			}
		?>
		</tbody>
	</table>
</div>
