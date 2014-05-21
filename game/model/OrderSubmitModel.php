<?php
require_once("model/RedirectModel.php");

class OrderSubmitModel extends RedirectModel
{
	public function __construct()
	{
		$this->init();
	}
	public function gettype()
	{
		return "ordersubmit";
	}
	public function getquery()
	{
		if($this->powername == "Observer" || $this->powername == "GM")
			return array($this->game, "orders");
		$stmt = $this->mysqli->prepare("UPDATE $this->powerdb SET orders=? " .
			"WHERE game=? AND name=?");
		$stmt->bind_param("sis", $_POST['orders'], $this->game, $this->powername);
		$stmt->execute();
		$stmt->close();
		return array($this->game, "orders");
	}
}
?>
