<?php
require_once("model/PageModel.php");

//The model for the GM page
class GMModel extends PageModel
{
	public function __construct()
	{
		$this->init();
	}
	public function gettype()
	{
		return "gm";
	}
	public function setparams($params)
	{
		$this->game = $params['game'];
		if($this->loadgame() == false)
			return 1;
		$this->loadplayer($this->game);
		if($this->powername == "GM")
			return 0;
		return 2;
	}
	public function getdata()
	{
		//Gets names, ids, and orders for each player
		$powtable = array();
		foreach($this->mysqli->query("SELECT name, player, user, orders " .
			"FROM $this->powerdb, $this->userdb " .
			"WHERE $this->powerdb.player=$this->userdb.id AND game=$this->game " .
			"ORDER BY name") as $powinfo)
			$powtable[] = $powinfo;
		return array("css"		=> "",
					 "game"		=> $this->game,
					 "curturn"	=> $this->curturn,
					 "powtable"	=> $powtable,
					 "username"	=> $this->username,
					 "powername"=> $this->powername,
					 "orders"	=> $this->orders,
					 "gameinfo"	=> $this->gameinfo);
	}
}
?>
