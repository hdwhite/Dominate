<?php
require_once("model/PageModel.php");

//Displays a player's orders
//It's a fairly simple Model
class OrdersModel extends PageModel
{
	public function __construct()
	{
		$this->init();
	}
	public function gettype()
	{
		return "orders";
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
