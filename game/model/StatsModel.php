<?php
require_once("model/PageModel.php");

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
	protected function getstats()
	{
		$powerlist = $this->mysqli->query("SELECT id, name, points, rank " .
			"FROM $this->powerdb WHERE game=$this->game ORDER BY id ASC");
		$yearlist = $this->mysqli->query("SELECT player, sc, year " .
			"FROM $this->yeardb WHERE game=$this->game ORDER BY power ASC, year ASC");
		$stattable = array();
		while($curpower = $powerlist->fetch_assoc())
		{
			$powarray = array("name" => $curpower['name'],
							  "player" => array(),
							  "points" => $curpower['points'],
							  "rank" => $curpower['rank']);
			$playernames = array();
			for($i = 0; $i <= ($this->gameinfo['numturns'] + 1) / 5; $i++)
			{
				$curyear = $yearlist->fetch_assoc();
				$playernames[] = $curyear['player'];
				$powarray[$curyear['year']] = $curyear['sc'];
			}
			$powarray['players'] = array_unique($playernames);
			$stattable[] = $powarray;
		}
		return $stattable;
	}
	protected function getscowners()
	{
		$scs = array("Edi", "Lvp", "Lon", "Bre", "Par", "Mar", "Por", "Spa",
			"Bel", "Hol", "Mun", "Kie", "Ber", "Den", "Swe", "Nor", "Stp",
			"War", "Mos", "Sev", "Con", "Ank", "Smy", "Bul", "Rum", "Gre",
			"Ser", "Bud", "Vie", "Tri", "Ven", "Rom", "Nap", "Tun");
		$powercolor = array("A" => "CD5C5C", "E" =>"7B68EE", "F" => "87CEEB",
			"G" => "696969", "I" => "6B8E23", "R" => "E600E3", "T" => "FFD700");
		$powerid = array();
		$powerlist = $this->mysqli->query("SELECT id, name " .
			"FROM $this->powerdb WHERE game=$this->game");
		while($curpower = $powerlist->fetch_assoc())
			$powerid[$curpower['id']] = substr($curpower['name'], 0, 1);
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
		if($this->gameinfo['standard'] == 'y')
			$sctable = $this->getscowners();
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
