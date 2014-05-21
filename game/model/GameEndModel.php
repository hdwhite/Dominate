<?php
require_once("model/RedirectModel.php");

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
		if($this->powername != "GM")
			return array($this->game);
		$this->mysqli->query("UPDATE $this->gamedb SET status=4 WHERE id=$this->game");
		return array($this->game, "gm");
	}
}
?>
