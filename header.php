<?php
	function geturl($page, $model)
	{
		$game = $model->game;
		$clink = "class='currentLink'";
		$url = "href=\"http://hdwhite.org/dominate/game/$game/$page\"";
		if($page == $model->gettype())
			return "$clink $url";
		return $url;
	}
?>
<div id="menu">
	<a href="http://hdwhite.org/dominate">Dominate Home</a>
	<a <?=geturl("results", $this->model) ?>>Results</a>
	<?php if($gameinfo['status'] > 0 && $powername != "Observer")
	{
		if($powername == "GM")
			{ ?><a <?=geturl("gm", $this->model) ?>>GM Panel</a><?php }
		else
			{ ?><a <?=geturl("orders", $this->model) ?>>Orders</a><?php } ?>
		<a <?=geturl("press", $this->model) ?>>Press</a>
	<?php } ?>
	<a <?=geturl("stats", $this->model) ?>>Stats</a>
	<a <?=geturl("info", $this->model) ?>>Info</a>
	<?php if(isset($_SESSION['loggedin'])) { ?>
		<a href="http://hdwhite.org/logout.php">Log Out</a>
	<?php } else { ?>
		<a href="http://hdwhite.org/login.php">Log In/Register</a>
	<?php } ?>
	<a href="http://hdwhite.org/">hdwhite.org</a>
</div>
