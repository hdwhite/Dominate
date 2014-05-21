<?php
require_once("model/RedirectModel.php");

class DeadlineModel extends RedirectModel
{
	public function __construct()
	{
		$this->init();
	}
	public function gettype()
	{
		return "deadline";
	}
	public function getquery()
	{
		if($this->powername != "GM")
			return array($this->game);
		$stmt = $this->mysqli->prepare("UPDATE $this->gamedb SET next_deadline=? " .
			"WHERE id=?");
		$stmt->bind_param("si", $newdead, $this->game);
		if(isset($_POST['setdead']))
		{
			$newdead = trim($_POST['newdead']);
			if(!preg_match("/^20[1-9][0-9]-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01]) " .
				"([01][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/", $newdead))
				return array($this->game, "gm");
		}
		elseif(isset($_POST['extenddead']))
		{
			$pushdead = trim($_POST['pushdead']);
			if(!preg_match("/^[0-9]+$/", $pushdead))
				return array($this->game, "gm");
			$newdead = date("Y-m-d H:i:s",
				strtotime($this->gameinfo['next_deadline']) + 60*60*$pushdead);
		}
		else
			return array($this->game, "gm");
		$stmt->execute();
		$stmt->close();
		return array($this->game, "gm");
	}
}
?>
