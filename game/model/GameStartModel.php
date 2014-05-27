<?php
require_once("model/RedirectModel.php");

//Starts the game
class GameStartModel extends RedirectModel
{
	public function __construct()
	{
		$this->init();
	}
	public function gettype()
	{
		return "gamestart";
	}
	public function getquery()
	{
		if($this->powername != "GM")
			return array($this->game);

		//Updates the game's status
		$this->mysqli->query("UPDATE $this->gamedb SET status=2 WHERE id=$this->game");

		//Sets the first deadline
		$deadtime = $this->mysqli->query("SELECT TIME_TO_SEC('" .
			$this->gameinfo['move_deadlines'] . "')")->fetch_row()[0];
		$nextdead = date("Y-m-d H:i:s", time() + $deadtime);
		$this->mysqli->query("UPDATE $this->gamedb " .
			"SET next_deadline='$nextdead' WHERE id=$this->game");

		//Does the first round of SC ownership updating
		//For more comments, check out Adjudicate.php
		//This will probably be redone once variant support is added
		$year = $this->curturn->getyear();
		$powerids = array("Unowned" => 0);
		foreach($ownerquery = $this->mysqli->query("SELECT id, name " .
			"FROM $this->powerdb WHERE game=$this->game")
			as $curpower)
			$powerids[$curpower['name']] = $curpower['id'];
		$xval = array(237, 232, 236, 224, 243, 287, 104, 159, 267, 307, 352,
			358, 377, 355, 405, 357, 649, 479, 665, 670, 596, 634, 660, 539,
			556, 488, 483, 498, 427, 422, 353, 367, 413, 308);
		$yval = Array(251, 305, 351, 408, 413, 491, 498, 498, 376, 351, 403,
			371, 371, 309, 260, 227, 181, 361, 305, 401, 567, 553, 609, 526,
			502, 588, 557, 475, 449, 487, 492, 535, 612, 639);
		$scs = Array("Edi", "Lvp", "Lon", "Bre", "Par", "Mar", "Por", "Spa",
			"Bel", "Hol", "Mun", "Kie", "Ber", "Den", "Swe", "Nor", "Stp",
			"War", "Mos", "Sev", "Con", "Ank", "Smy", "Bul", "Rum", "Gre",
			"Ser", "Bud", "Vie", "Tri", "Ven", "Rom", "Nap", "Tun");
		$powers = Array(hexdec("CD5C5C") => "Austria",
						hexdec("7B68EE") => "England",
						hexdec("87CEEB") => "France",
						hexdec("696969") => "Germany",
						hexdec("6B8E23") => "Italy",
						hexdec("F5F5F5") => "Russia",
						hexdec("D3D3D3") => "Russia",
						hexdec("FFD700") => "Turkey",
						hexdec("DAA520") => "Turkey",
						hexdec("FAEBD7") => "Unowned",
						hexdec("F5DEB3") => "Unowned");
		$numscs = Array("Austria" => 0, "England" => 0, "France" => 0,
			"Germany" => 0, "Italy" => 0, "Russia" => 0, "Turkey" => 0, "Unowned" => 0);
		$powernames = array_keys($numscs);
		$scstmt = $this->mysqli->prepare("INSERT INTO $this->scdb" .
			"(name, game, year, owner) VALUES(?, ?, ?, ?)");
		$scstmt->bind_param("siii", $cursc, $this->game, $year, $ownernum);
		$image = imagecreatefrompng("http://hdwhite.org/diplomacy/" .
			str_replace(" ", "_", $this->gameinfo['name']) . "/images/" .
			$this->gameinfo['startyear'] . "/W1.png");
		for($i = 0; $i < count($scs); $i++)
		{
			$cursc = $scs[$i];
			$owner = $powers[imagecolorat($image, $xval[$i], $yval[$i])];
			$numscs[$owner]++;
			$ownernum = $powerids[$owner];
			$scstmt->execute();
		}
		$yearstmt = $this->mysqli->prepare("INSERT INTO $this->yeardb(" .
			"game, power, player, year, sc) VALUES(?, ?, ?, ?, ?)");
		$yearstmt->bind_param("iisii", $this->game, $powerid,
			$powplayer, $this->gameinfo['startyear'], $sccount);
		foreach($this->mysqli->query("SELECT name, player, user, $this->powerdb.id AS pid " .
			"FROM $this->powerdb, $this->userdb " .
			"WHERE $this->powerdb.player=$this->userdb.id AND game=$this->game " .
			"ORDER BY name") as $powinfo)
		{
			$powplayer = $powinfo['user'];
			$powerid = $powinfo['pid'];
			$sccount = $numscs[$powinfo['name']];
			$yearstmt->execute();
		}
		$yearstmt->close();
		return array($this->game, "gm");
	}
}
?>
