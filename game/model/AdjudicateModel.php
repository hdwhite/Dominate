<?php
require_once("model/RedirectModel.php");

class AdjudicateModel extends RedirectModel
{
	public function __construct()
	{
		$this->init();
	}
	public function gettype()
	{
		return "adjudicate";
	}
	public function getquery()
	{
		if($this->powername != "GM")
			return array($this->game);
		if($_FILES['image1']['error'] == 4)
			return array($this->game, "gm");
		if($_FILES['image1']['error'] > 0)
			return array($this->game, "gm");
		if($_FILES['image1']['type'] != "image/png")
			return array($this->game, "gm");
		if($_FILES['image2']['error'] > 0 && $_FILES['image2']['error'] != 4)
			return array($this->game, "gm");
		if($_FILES['image2']['type'] != "image/png" && $_FILES['image2']['error'] == 0)
			return array($this->game, "gm");
		if($_FILES['text']['error'] > 0 && $_FILES['image2']['error'] != 4)
			return array($this->game, "gm");
		$imgpath = $_SERVER['DOCUMENT_ROOT'] . "/diplomacy/" .
			str_replace(" ", "_", $this->gameinfo['name']) .
			"/images/" . $this->curturn->getyear();
		$textpath = $_SERVER['DOCUMENT_ROOT'] . "/diplomacy/" .
			str_replace(" ", "_", $this->gameinfo['name']) .
			"/text/" . $this->curturn->getyear();
		switch($this->curturn->display("sp"))
		{
			case "FR":
				mkdir("../diplomacy/" . str_replace(" ", "_", $this->gameinfo['name']) .
					"/images/" . ($this->curturn->getyear() + 1));
				mkdir("../diplomacy/" . str_replace(" ", "_", $this->gameinfo['name']) .
					"/text/" . $this->curturn->getyear() + 1);
				move_uploaded_file($_FILES['image1']['tmp_name'], "$imgpath/W1.png");
				if($_FILES['image2']['error'] == 0)
					move_uploaded_file($_FILES['image1']['tmp_name'], "$imgpath/W2.png");
				if($_FILES['text']['error'] == 0)
					move_uploaded_file($_FILES['text']['tmp_name'], "$textpath/W.html");
				break;
			case "WA":
				move_uploaded_file($_FILES['image1']['tmp_name'], "$imgpath/S1.png");
				if($_FILES['image2']['error'] == 0)
					move_uploaded_file($_FILES['image1']['tmp_name'], "$imgpath/S2.png");
				if($_FILES['text']['error'] == 0)
					move_uploaded_file($_FILES['text']['tmp_name'], "$textpath/S.html");
				break;
			case "SM":
				if(file_exists("$imgpath/S2.png"))
				{
					move_uploaded_file($_FILES['image1']['tmp_name'], "$imgpath/S3.png");
					if($_FILES['image2']['error'] == 0)
						move_uploaded_file($_FILES['image1']['tmp_name'], "$imgpath/S4.png");
				}
				else
				{
					move_uploaded_file($_FILES['image1']['tmp_name'], "$imgpath/S2.png");
					if($_FILES['image2']['error'] == 0)
						move_uploaded_file($_FILES['image1']['tmp_name'], "$imgpath/S3.png");
				}
				if($_FILES['text']['error'] == 0)
				{
					if(file_exists("$textpath/S.html"))
					{
						$textfile = fopen("$textpath/S.html", "a");
						fwrite($textfile, file_get_contents($_FILES['text']['tmp_name']));
						fclose($textfile);
					}
					else
						move_uploaded_file($_FILES['text']['tmp_name'], "$textpath/S.html");
				}
				break;
			case "SR":
				move_uploaded_file($_FILES['image1']['tmp_name'], "$imgpath/F1.png");
				if($_FILES['image2']['error'] == 0)
					move_uploaded_file($_FILES['image1']['tmp_name'], "$imgpath/F2.png");
				if($_FILES['text']['error'] == 0)
					move_uploaded_file($_FILES['text']['tmp_name'], "$textpath/F.html");
				break;
			case "FM":
				if($_FILES['image2']['error'] == 0)
					$image = imagecreatefrompng($_FILES['image2']['tmp_name']);
				else
					$image = imagecreatefrompng($_FILES['image1']['tmp_name']);
				if(file_exists("$imgpath/F2.png"))
				{
					move_uploaded_file($_FILES['image1']['tmp_name'], "$imgpath/F3.png");
					if($_FILES['image2']['error'] == 0)
						move_uploaded_file($_FILES['image2']['tmp_name'], "$imgpath/F4.png");
				}
				else
				{
					move_uploaded_file($_FILES['image1']['tmp_name'], "$imgpath/F2.png");
					if($_FILES['image2']['error'] == 0)
						move_uploaded_file($_FILES['image2']['tmp_name'], "$imgpath/F3.png");
				}
				if($_FILES['text']['error'] == 0)
				{
					if(file_exists("$textpath/F.html"))
					{
						$textfile = fopen("$textpath/F.html", "a");
						fwrite($textfile, file_get_contents($_FILES['text']['tmp_name']));
						fclose($textfile);
					}
					else
					{
						move_uploaded_file($_FILES['text']['tmp_name'], "$textpath/F.html");
					}
				}
				$year = $this->curturn->getyear() + 1;
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
				foreach($this->mysqli->query("SELECT name, player, user, power.id AS pid " .
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
		}
		$this->mysqli->query("UPDATE $this->gamedb SET numturns = numturns + 1 " .
			"WHERE id=" . $this->gameinfo['id']);
		if($this->curturn->getnext()->getnext()->display('p') == "M")
			$deadtime = $this->mysqli->query("SELECT TIME_TO_SEC('" .
				$this->gameinfo['move_deadlines'] . "')")->fetch_row()[0];
		else
			$deadtime = $this->mysqli->query("SELECT TIME_TO_SEC('" .
				$this->gameinfo['retreat_deadlines'] . "')")->fetch_row()[0];
		$nextdead = date("Y-m-d H:i:s",
			strtotime($this->gameinfo['next_deadline']) + $deadtime);
		$this->mysqli->query("UPDATE $this->gamedb " .
			"SET next_deadline='$nextdead' WHERE id=$this->game");
		$this->mysqli->query("UPDATE $this->powerdb SET orders=NULL " .
			"WHERE game=$this->game");
		$userto = array();
		$mailto = array();
		foreach($this->mysqli->query("SELECT user, email " .
			"FROM $this->powerdb, $this->userdb " .
			"WHERE $this->powerdb.player=$this->userdb.id AND game=$this->game " .
			"AND adjudicate='y' ORDER BY name") as $userinfo)
		{
			$userto[] = $userinfo['user'];
			$mailto[] = $userinfo['user'] . " <" . $userinfo['email'] . ">";
		}
		$mheader = "MIME-Version: 1.0\r\n" .
				   "Content-type: text/html; charset=iso-8859-1\r\n" .
				   "From: DOMINATE <dominate@hdwhite.org>\r\n" .
				   "Reply-to: hdwhite.contact@gmail.com\r\n" .
				   "Bcc: " . implode(", ", $mailto) . "\r\n";
		$mtitle = "DOMINATE - Game " . $this->gameinfo['name'] . " has changed turns";
		$mstart = "Hello,<br><br>" . 
				  "A game you are in, " . $this->gameinfo['name'] . ", has changed turns.<br>" .
				  "To see the results of the turn or to plan next turn's orders, " .
				  "go to http://hdwhite.org/dominate/game/$_gameid.<br>";
		$mfooter = "<br><br>" . 
				   "-----------------------------------------<br>" .
				   "This e-mail has been sent via DOMINATE.<br>" .
				   "http://hdwhite.org/dominate<br>" .
				   "To stop receiving these e-mails, please change your settings " .
				   "at http://hdwhite.org/dominate/account.php";
		$mtext = $mstart . $mfooter;
		mail("", $mtitle, $mtext, $mheader);
		return(array($this->game, "gm"));
	}
}
?>
