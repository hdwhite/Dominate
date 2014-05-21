<div class=entry>
	<h4>Finished games</h4>
	<?php
		$gamename = Array();
		$press = Array();
		$infolink = Array();
		while($curgame = $gamelist->fetch_assoc())
		{
			$gamename[] = "<a href='game/" . $curgame['id'] . "'>" . $curgame['name'] . "</a>";
			switch ($curgame['press'])
			{
				case 0:
					$press[] = "None";
					break;
				case 1:
					$press[] = "Broadcast";
					break;
				case 2:
					$press[] = "Anonymous";
					break;
				case 3:
					$press[] = "All";
					break;
			}
			$infolink[] = "<a href='game/" . $curgame['id'] . "/info'>Info</a>";
		}
	?>
	<table width="800">
		<tr>
			<td><table border="1" class="sortable">
				<thead><tr><th>Game</th><th>Press</th><th>Info</th></thead>
				<tbody>
				<?php
					for($i = 0; $i < $gamelist->num_rows; $i += 3)
						echo("<tr><td>" . $gamename[$i] . "</td>" .
							"<td>" . $press[$i] . "</td>" .
							"<td>" . $infolink[$i] . "</td></tr>");
				?>
				</tbody>
			</table></td>
			<td style="vertical-align:top"><table border="1" class="sortable">
				<thead><tr><th>Game</th><th>Press</th><th>Info</th></thead>
				<tbody>
				<?php
					for($i = 1; $i < $gamelist->num_rows; $i += 3)
						echo("<tr><td>" . $gamename[$i] . "</td>" .
						"<td>" . $press[$i] . "</td>" .
						"<td>" . $infolink[$i] . "</td></tr>");
				?>
				</tbody>
			</table></td>
			<td style="vertical-align:top"><table border="1" class="sortable">
				<thead><tr><th>Game</th><th>Press</th><th>Info</th></thead>
				<tbody>
				<?php
					for($i = 2; $i < $gamelist->num_rows; $i += 3)
						echo("<tr><td>" . $gamename[$i] . "</td>" .
						"<td>" . $press[$i] . "</td>" .
						"<td>" . $infolink[$i] . "</td></tr>");
				?>
				</tbody>
			</table></td>
		</tr>
	</table>
</div>
