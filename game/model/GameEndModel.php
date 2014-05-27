<?php
require_once("model/RedirectModel.php");

//Ends the game
class GameEndModel extends RedirectModel
{
	public function __construct()
	{
		$this->init();
	}
	public function gettype()
	{
		return "gameend";
	}
	public function getquery()
	{
		//Only GMs can end the game
		if($this->powername != "GM")
			return array($this->game);

		//You can only end games that are ongoing
		if($this->gameinfo['status'] != 2 && $this->gameinfo['status'] != 3)
			return array($this->game);

		//Changes the status of the game
		$this->mysqli->query("UPDATE $this->gamedb SET status=4 WHERE id=$this->game");
		return array($this->game, "gm");
	}
}
?>
