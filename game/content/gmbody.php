<div class="entry">
	<h4>Players and orders</h4>
	<table border="1">
		<thead><tr><th>Country</th><th>Player</th><th>Orders</th><th>Replace</th></tr></thead>
		<?php foreach($powtable as $player)
		{
			$country = $player['name'];
			$pname = $player['user'];
			$pid = $player['player'];
			if($player['orders'] == "")
				$orders = "<font color='red'>None received</font>";
			else
				$orders = "<b>Click to show</b>" .
					"<div id='$country' style='display: none'>" .
					nl2br(htmlentities($player['orders'])) . "</div>";
			$kick = "<form action='http://hdwhite.org/dominate/game/$game/kick' method='post'>" .
				"<button type='submit' name='kick' value='$pid'>Replace</button></form>";
			echo("<tr onclick=\"toggle('$country')\">" .
				"<td style='vertical-align:top'>$country</td>" .
				"<td style='vertical-align:top'>$pname</td>" .
				"<td>$orders</td><td>$kick</td></tr>");
		}?>
	</table>
</div>
<?php if($gameinfo['status'] == 1) { ?>
<div class="entry">
	<h4>Start game</h4>
	<p>If it has been some time since this game was created, it is a good idea to make sure all players are ready to play before starting the game.</p>
    <form action="http://hdwhite.org/dominate/game/<?=$game ?>/gamestart" method="post">
    	<input type="submit" name="startgame" value="Start Game">
	</form>
</div>
<?php } elseif($gameinfo['status'] < 4) { ?>
<div class="entry">
	<h4>Change deadline</h4>
	<form action="http://hdwhite.org/dominate/game/<?=$game ?>/deadline" method="post">
		Set deadline to (yyyy-mm-dd hh:mm:ss):
		<input name="newdead" type="text" value="<?=$gameinfo['next_deadline'] ?>" size="15">
		<input type="submit" name="setdead" value="Set deadline"><br>
		Extend deadline by <input name="pushdead" type="text" value="24" size="1"> hours
		<input type="submit" name="extenddead" value="Extend deadline">
	</form>
</div>
<div class="entry">
	<h4>Adjudicate turn</h4>
	<p>Up to two images and one text file can be uploaded each turn. The uploaded files for retreats will be appended to the files used in the corresponding movement phase. Please upload all images in .png format.</p>
	<form enctype="multipart/form-data"
		action="http://hdwhite.org/dominate/game/<?=$game ?>/adjudicate" method="post">
		<input type="hidden" name="MAX_FILE_SIZE" value="1048576">
		<p>Upload first image: <input name="image1" type="file"></p>
		<p>Upload second image (optional): <input name="image2" type="file"></p>
		<p>Upload text results (optional): <input name="text" type="file"></p>
	    <input type="submit" name="adjudicate" value="Adjudicate">
	</form>
</div>
<div class="entry">
	<h4>End game</h4>
	<p>Note: Once the game has been ended, this action cannot be undone (without much annoyance from Harry). Only press this button if the game is actually over.</p>
    <form action="http://hdwhite.org/dominate/game/<?=$game ?>/gameend" method="post">
    	<input type="submit" name="endgame" value="End Game">
	</form>
</div>
<?php } ?>
