<?php
//Abstract Model class
abstract class Model
{
	protected $mysqli, $gamedb, $powerdb, $pressdb, $pressreceiverdb, $scdb, $variantdb, $yeardb;
	protected $gameinfo, $username, $powername, $orders, $curturn;
	public $game;

	//All models have to be initialised
	abstract protected function __construct();

	//Initialises the MySQL connection
	public function init()
	{
		session_start();
		require_once("../dbnames.inc");
		require_once($_dbconfig);
		$this->userdb = $_userdb;
		$this->gamedb = $_gamedb;
		$this->powerdb = $_powerdb;
		$this->pressdb = $_pressdb;
		$this->pressreceiverdb = $_pressreceiverdb;
		$this->scdb = $_scdb;
		$this->variantdb = $_variantdb;
		$this->yeardb = $_yeardb;
		$this->mysqli = $mysqli;
	}
	
	protected function loadgame()
	{
		if(!is_numeric($this->game))
			return false;
		if(!($gameinfo = $this->mysqli->query(
			"SELECT * FROM $this->gamedb WHERE id=$this->game")
			->fetch_assoc()))
			return false;
		$this->gameinfo = $gameinfo;
		$this->curturn = new Turn($gameinfo['startyear'], $gameinfo['numturns']);
		return true;
	}

	protected function loadplayer($game)
	{
		if(!isset($_SESSION['loggedin']))
		{
			$this->username = "Guest";
			$this->powername = "Observer";
		}
		else
		{
			$this->username = $_SESSION['user'];
			if($curpower = $this->mysqli->query(
				"SELECT name, orders FROM $this->powerdb " .
				"WHERE game=$game AND player=" . $_SESSION['id'])
				->fetch_assoc())
			{
				if($this->gameinfo['status'] == 0)
					$this->powername = "TBD";
				else
					$this->powername = $curpwoer['name'];
				$this->orders = $curpower['orders'];
			}
			elseif($this->username == $this->gameinfo['gm'])
				$this->powername = "GM";
			else
				$this->powername = "Observer";
		}
			
	}
	//Used so the Controller can know what Model we're using
	abstract protected function gettype();

	abstract protected function redirect();

	public function setparams($params)
	{
		$this->game = $params['game'];
		if($this->loadgame() == false)
			return 1;
		$this->loadplayer($this->game);
		return true;
	}
}
?>
