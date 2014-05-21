<?php
class Turn
{
	protected $startyear, $turnnum, $displaylink;
	public function __construct($startyear, $turnnum)
	{
		$this->startyear = $startyear;
		$this->turnnum = $turnnum;
		$this->displaylink = true;
	}
	public function showlink($newdisplay)
	{
		$this->displaylink = $newdisplay;
	}
	public function getturn()
	{
		return $this->turnnum;
	}
	public function getyear()
	{
		return $this->startyear + floor($this->turnnum / 5);
	}
	public function getprevious()
	{
		return new Turn($this->startyear, $this->turnnum - 1);
	}
	public function getnext()
	{
		return new Turn($this->startyear, $this->turnnum + 1);
	}
	public function isretreat()
	{
		if($this->turnnum % 5 == 2 || $this->turnnum % 5 == 4)
			return true;
		return false;
	}
	public function display($format)
	{
		$shortseasons = array('W', 'S', 'S', 'F', 'F');
		$longseasons = array("Winter", "Spring", "Spring", "Fall", "Fall");
		$shortphases = array('A', 'M', 'R', 'M', 'R');
		$longphases = array("Adjustment", "Movement", "Retreat", "Movement", "Retreat");
		$seasonnum = $this->turnnum % 5;
		$farray = str_split($format);
		$outstring = "";
		foreach($farray as $fchar)
		{
			switch($fchar)
			{
				case 'y':
					$outstring .= $this->getyear();
					break;
				case 's':
					$outstring .= $shortseasons[$seasonnum];
					break;
				case 'S':
					$outstring .= $longseasons[$seasonnum];
					break;
				case 'p':
					$outstring .= $shortphases[$seasonnum];
					break;
				case 'P':
					$outstring .= $longphases[$seasonnum];
					break;
				case 'F':
					$outstring .= ($seasonnum ? "Movement and Retreat" : "Adjustment");
					break;
				default:
					$outstring .= $fchar;
			}
		}
		return $outstring;
	}
	public function printurl($game)
	{
		$url = $this->display('ys');
		if($this->displaylink)
			$url = "<a href='/dominate/game/$game/results/$this->turnnum'>$url</a>";
		return $url;
	}
	public function equals($turn)
	{
		if(!isset($turn))
			return false;
		if($turn->getturn() == $this->turnnum)
			return true;
		return false;
	}
}
?>
