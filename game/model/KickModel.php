<?php
require_once("model/RedirectModel.php");

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
		if($this->powername != "GM")
			return array($this->game);
		$stmt = $this->mysqli->prepare("UPDATE $this->powerdb SET curplayer=NULL" .
			"WHERE game=? AND player=?");
		$stmt->bind_params("ii", $this->game, $_POST['kick']);
		$stmt->execute();
		$stmt->close();
		return array($this->game, "gm");
	}
}
?>
