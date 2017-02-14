<?php
	//The page that you are currently in gets a border around it
	//This page is called only in the game pages
	function geturl($page, $model)
	{
		$game = $model->game;
		$clink = "class='currentLink'";
		$url = "href=\"/dominate/game/$game/$page\"";
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
		<a href="/logout.php">Log Out</a>
	<?php } else { ?>
		<a href="/login.php">Log In/Register</a>
	<?php } ?>
	<a href="/">hdwhite.org</a>
</div>
