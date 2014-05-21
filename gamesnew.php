<div class=entry>
	<h4>New games</h4>
	<table border="1" class="sortable">
		<thead><tr><th>Name</th><th>Press</th><th>Deadlines</th><th>Slots</th><th>Info</th><th>Join</th></thead>
		<tbody>
		<?php
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
				$deadlines = explode(':', $curgame['move_deadlines'])[0] . " h/" .
					explode(':', $curgame['retreat_deadlines'])[0] . " h";
				$numslots = $mysqli->query("SELECT COUNT(player), COUNT(*) " .
					"FROM $_powerdb WHERE game=" . $curgame['id'])->fetch_row();
				$slots = implode("/", $numslots);
				$infolink = "<a href='game/" . $curgame['id'] . "/info'>Info</a>";
				if($curgame['gm'] == $_SESSION['user'])
					$joinlink = "<a href='delgame.php?game=" . $curgame['id'] . "'>Delete</a>";
				elseif($mysqli->query("SELECT COUNT(*) FROM $_powerdb " .
					"WHERE game=" . $curgame['id'] . " AND player=" . $_SESSION['id'])
					->fetch_row()[0] > 0)
					$joinlink = "<a href='leavegame.php?game=" . $curgame['id'] . "'>Leave</a>";
				else
					$joinlink = "<a href='joingame.php?game=" . $curgame['id'] . "'>Join</a>";
				echo("<tr><td>$gamename</td><td>$press</td><td>$deadlines</td><td>$slots</td><td>$infolink</td><td>$joinlink</td></tr>");
			}
		?>
		</tbody>
	</table>
</div>
