<div class="entry">
	<h4>Submit orders</h4>
	<form action="<?=$game ?>/ordersubmit" method="post">
		<textarea name="orders" id="orders" cols="25" rows="10"
			<?php
			if($powername == "Observer" || $powername == "GM" ||
				$gameinfo['status'] == 0 || $gameinfo['status'] > 3)
				echo("DISABLED");
			elseif(strtotime($gameinfo['next_deadline']) - time() < 0 &&
				$gameinfo['status'] == 2)
				echo("READONLY");
			?>
		><?php
		if($powername == "Observer")
			echo("You are an observer and cannot submit orders.");
		elseif($powername == "GM")
			echo("As the GM, you cannot submit orders.");
		else
			echo(htmlentities($orders));
		?></textarea>
		<br><input type="submit" name="setorders" value="Submit">
	</form>
</div>
