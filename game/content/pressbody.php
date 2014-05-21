<?php if($powername != "Observer") { ?>
<div class="entry">
	<form action="http://hdwhite.org/dominate/game/<?=$game ?>/presssend" method="post">
	<b>Send press to:</b><br>
	<?php
		if($gameinfo['press'] > 1)
			foreach($powerlist as $curpower)
				echo("<input type='checkbox' name='$curpower'> $curpower</input>&nbsp;&nbsp;");
	?>
	<input type="checkbox" name="GM"> GM</input><br>
	<?php if($gameinfo['press'] > 0) { ?>
	<input type="checkbox" name="broadcast"> Send press to all</input>&nbsp;
	<input type="checkbox" name="anonymous"> Send press anonymously</input><br>
	<?php } ?>
			<textarea name="message" id="message" cols="80" rows="15"></textarea>
			<br><input type="submit" name="action" value="Submit">
	</form>
</div>
<?php } ?>
<div class="entry">
	<h4>All Press</h4>
	<p>Click on a message header to view/hide the message</p>
	<table border="1" style="width:100%">
		<thead><tr><th>Season</th><th>Date</th><th>From</th><th>To</th></thead>
		<tbody>
		<?php
			foreach($presslist as $curmsg)
			{
				echo("<tr class='pointer" . ($curmsg['unread'] ? " unread" : "") . "' " .
					"onclick=\"toggle('" . $curmsg['id'] . "')\">" .
					"<td>" . $curmsg['season'] . "</td><td>" . $curmsg['date'] . "</td>" .
					"<td>" . $curmsg['from'] . "</td><td>" . $curmsg['to'] . "</td></tr>");
				echo("<tr id='" . $curmsg['id'] . "' style='display: none'>" .
					"<td colspan='4'>" . $curmsg['message'] . "</td></tr>");
			}
		?>
		</tbody>
	</table>
</div>
