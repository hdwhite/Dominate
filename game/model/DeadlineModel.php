<?php
require_once("model/RedirectModel.php");

//Used to update the deadline
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
		//Only the GM can chsnge the deadline
		if($this->powername != "GM")
			return array($this->game);
		$stmt = $this->mysqli->prepare("UPDATE $this->gamedb SET next_deadline=? " .
			"WHERE id=?");
		$stmt->bind_param("si", $newdead, $this->game);

		//If the set deadline button was pressed, set the deadline to the new time
		if(isset($_POST['setdead']))
		{
			$newdead = trim($_POST['newdead']);
			if(!preg_match("/^20[1-9][0-9]-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01]) " .
				"([01][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/", $newdead))
				return array($this->game, "gm");
		}
		//Extend the deadline otherwise
		elseif(isset($_POST['extenddead']))
		{
			$pushdead = trim($_POST['pushdead']);
			if(!preg_match("/^[0-9]+$/", $pushdead))
				return array($this->game, "gm");
			$newdead = date("Y-m-d H:i:s",
				strtotime($this->gameinfo['next_deadline']) + 60*60*$pushdead);
		}
		//If nether button was pressed, something's off and don't do anything
		else
			return array($this->game, "gm");
		$stmt->execute();
		$stmt->close();
		return array($this->game, "gm");
	}
}
?>
