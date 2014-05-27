<?php
require_once("model/RedirectModel.php");

//Kicks a player from the game
class KickModel extends RedirectModel
{
	public function __construct()
	{
		$this->init();
	}
	public function gettype()
	{
		return "kick";
	}
	public function getquery()
	{
		//Only GMs can kick players
		if($this->powername != "GM")
			return array($this->game);

		//Removes the player from the game
		$stmt = $this->mysqli->prepare("UPDATE $this->powerdb SET curplayer=NULL" .
			"WHERE game=? AND player=?");
		$stmt->bind_params("ii", $this->game, $_POST['kick']);
		$stmt->execute();
		$stmt->close();

		//Updates the game's status to indicate that it needs a replacement
		$this->mysqli->query("UPDATE $this->gamedb SET status=3 WHERE id=$this->game");
		return array($this->game, "gm");
	}
}
?>
