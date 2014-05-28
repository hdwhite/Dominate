<?php
require_once("model/PageModel.php");

//Displays stats about the current game
class StatsModel extends PageModel
{
	public function __construct()
	{
		$this->init();
	}
	public function gettype()
	{
		return "stats";
	}

	//Gets the number of SCs owned by each player in each year
	protected function getstats()
	{
		$powerlist = $this->mysqli->query("SELECT id, name, points, rank " .
			"FROM $this->powerdb WHERE game=$this->game ORDER BY id ASC");
		$yearlist = $this->mysqli->query("SELECT player, sc, year " .
			"FROM $this->yeardb WHERE game=$this->game ORDER BY power ASC, year ASC");
		$stattable = array();
		//For each power
		while($curpower = $powerlist->fetch_assoc())
		{
			$powarray = array("name" => $curpower['name'],
							  "player" => array(),
							  "points" => $curpower['points'],
							  "rank" => $curpower['rank']);
			$playernames = array();
			//For each year
			for($i = 0; $i <= ($this->gameinfo['numturns'] + 1) / 5; $i++)
			{
				//Get the year info and insert it into the appropriate table
				$curyear = $yearlist->fetch_assoc();
				$playernames[] = $curyear['player'];
				$powarray[$curyear['year']] = $curyear['sc'];
			}
			//We only want one name at a time for the player list
			$powarray['players'] = array_unique($playernames);
			$stattable[] = $powarray;
		}
		return $stattable;
	}

	//Gets the owner of each SC in each year
	protected function getscowners()
	{
		//The list of SCs
		//Will be changed once variant support is added
		$scs = array("Edi", "Lvp", "Lon", "Bre", "Par", "Mar", "Por", "Spa",
			"Bel", "Hol", "Mun", "Kie", "Ber", "Den", "Swe", "Nor", "Stp",
			"War", "Mos", "Sev", "Con", "Ank", "Smy", "Bul", "Rum", "Gre",
			"Ser", "Bud", "Vie", "Tri", "Ven", "Rom", "Nap", "Tun");

		//Creates an array with keys equal to the IDs of each power and values
		//equal to the one-letter representation of each SC
		//Used to go from power ID to power name
		$powerid = array();
		$powerlist = $this->mysqli->query("SELECT id, name " .
			"FROM $this->powerdb WHERE game=$this->game");
		while($curpower = $powerlist->fetch_assoc())
			$powerid[$curpower['id']] = substr($curpower['name'], 0, 1);
		
		//Gets a two-dimentional table with each SC and year and populates it
		//with each SC owner
		$sclist = $this->mysqli->query("SELECT name, year, owner " .
			"FROM $this->scdb WHERE game=$this->game ORDER BY year, name");
		$sctable = array_fill_keys($scs, Array());
		while($cursc = $sclist->fetch_assoc())
			$sctable[$cursc['name']][$cursc['year']] = $powerid[$cursc['owner']];
		return $sctable;
	}
	public function getdata()
	{
		$stattable = $this->getstats();
		//SC ownership only works for standard games at the moment
		if($this->gameinfo['standard'] == 'y')
			$sctable = $this->getscowners();

		//Gets the maximum numbr of SCs owned in a given year
		for($i = $this->gameinfo['startyear']; $i <= $this->curturn->getyear(); $i++)
		{
			$tempmax = 0;
			foreach($stattable as $powtable)
				$tempmax = max($tempmax, $powtable[$i]);
			$maxsc[$i] = $tempmax;
		}
		return array("css"		=> "",
					 "game"		=> $this->game,
					 "curturn"	=> $this->curturn,
					 "stattable"=> $stattable,
					 "maxsc"	=> $maxsc,
					 "sctable"	=> $sctable,
					 "username"	=> $this->username,
					 "powername"=> $this->powername,
					 "orders"	=> $this->orders,
					 "gameinfo"	=> $this->gameinfo);
	}
}
?>
