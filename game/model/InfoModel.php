<?php
require_once("model/PageModel.php");

//Gets the game info
//It's a very simple class
class InfoModel extends PageModel
{
	public function __construct()
	{
		$this->init();
	}
	public function gettype()
	{
		return "info";
	}
	public function getdata()
	{
		return array("css"		=> "",
					 "game"		=> $this->game,
					 "curturn"	=> $this->curturn,
					 "username"	=> $this->username,
					 "powername"=> $this->powername,
					 "orders"	=> $this->orders,
					 "gameinfo"	=> $this->gameinfo);
	}
}
?>
