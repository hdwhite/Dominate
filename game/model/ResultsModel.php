<?php
require_once("model/PageModel.php");

//Displays maps and text for a given season
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
	//We have to do a different function here since there is also a turn number
	public function setparams($params)
	{
		$this->game = $params['game'];
		if(!$this->loadgame())
			return 1;
		$this->loadplayer($this->game);

		//The turn number has to be an integer
		//Otherwise it will attempt to turn it into an acceptable number
		if(!is_numeric($params[0]))
			$turnnum = $this->gameinfo['numturns'];
		else
			$turnnum = floor($params[0]);
		if($turnnum < 0) $turnnum = 0;
		if($turnnum > $this->gameinfo['numturns'])
			$turnnum = $this->gameinfo['numturns'];

		//Create a new Turn object with the requested season
		$this->turn = new Turn($this->gameinfo['startyear'], $turnnum);
		//If a retreat were requested, have it display the previous phase instead
		if($this->turn->isretreat())
			$this->turn = $this->turn->getprevious();
		$this->curturn = new Turn(
			$this->gameinfo['startyear'], $this->gameinfo['numturns']);
		return 0;
	}

	//Gets a list of Turns do display for navigation purposes
	protected function getturns()
	{
		$turnarray = array();
		//Start from the first Turn and go until the current turn
		$curturn = new Turn($this->gameinfo['startyear'], 0);
		while($curturn->getturn() <= $this->gameinfo['numturns'])
		{
			//We don't want to link to retreats, since they don't get their own page
			if($curturn->isretreat())
			{
				$curturn = $curturn->getnext();
				continue;
			}
			//Display the current turn, but we don't need a URL for it
			if($curturn->equals($this->turn))
			{
				$curturn->showlink(false);
				$turnarray[] = $curturn;
			}
			//All Winters should be displayed
			elseif($curturn->display('s') == 'W')
				$turnarray[] = $curturn;
			//Show all turns in the current year
			elseif($curturn->getyear() == $this->turn->getyear())
				$turnarray[] = $curturn;
			//Show the next and previous season (of which there will be exactly
			//one each in the two previous and two next turns)
			elseif(abs($curturn->getturn() - $this->turn->getturn()) == 2)
				$turnarray[] = $curturn;
			//Finally, show the latest turn
			//We can't compare the Turns themselves because the latest turn
			//might be a retreat
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
