<?php
require_once("model/PageModel.php");

class ResultsModel extends PageModel
{
	protected $turn;
	public function __construct()
	{
		$this->init();
	}
	public function gettype()
	{
		return "results";
	}
	public function setparams($params)
	{
		$this->game = $params['game'];
		if(!$this->loadgame())
			return 1;
		$this->loadplayer($this->game);
		if(!is_numeric($params[0]))
			$turnnum = $this->gameinfo['numturns'];
		else
			$turnnum = floor($params[0]);
		if($turnnum < 0) $turnnum = 0;
		if($turnnum > $this->gameinfo['numturns'])
			$turnnum = $this->gameinfo['numturns'];
		$this->turn = new Turn($this->gameinfo['startyear'], $turnnum);
		if($this->turn->isretreat())
			$this->turn = $this->turn->getprevious();
		$this->curturn = new Turn(
			$this->gameinfo['startyear'], $this->gameinfo['numturns']);
		return 0;
	}
	protected function getturns()
	{
		$turnarray = array();
		$curturn = new Turn($this->gameinfo['startyear'], 0);
		while($curturn->getturn() <= $this->gameinfo['numturns'])
		{
			if($curturn->isretreat())
			{
				$curturn = $curturn->getnext();
				continue;
			}
			if($curturn->equals($this->turn))
			{
				$curturn->showlink(false);
				$turnarray[] = $curturn;
			}
			elseif($curturn->display('s') == 'W')
				$turnarray[] = $curturn;
			elseif($curturn->getyear() == $this->turn->getyear())
				$turnarray[] = $curturn;
			elseif(abs($curturn->getturn() - $this->turn->getturn()) == 2)
				$turnarray[] = $curturn;
			elseif($curturn->display('ys') == $this->curturn->display('ys'))
				$turnarray[] = $curturn;
			$curturn = $curturn->getnext();
		}
		return $turnarray;
	}
	public function getdata()
	{
		$turnlist = $this->getturns();
		return array("css"		=> "map",
					 "game"		=> $this->game,
					 "curturn"	=> $this->curturn,
					 "turn"		=> $this->turn,
					 "turnlist"	=> $turnlist,
					 "username"	=> $this->username,
					 "powername"=> $this->powername,
					 "orders"	=> $this->orders,
					 "gameinfo"	=> $this->gameinfo);
	}
}
?>
