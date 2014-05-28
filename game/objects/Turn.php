<?php

//The Turn class
//Each Turn represerts a year, a season, and a phase
class Turn
{
	protected $startyear, $turnnum, $displaylink;
	//The games database keeps track of the starting year and the number of
	//elapsed turns, so that is what we will use as well
	public function __construct($startyear, $turnnum)
	{
		$this->startyear = $startyear;
		$this->turnnum = $turnnum;
		$this->displaylink = true;
	}

	//When printing the Turn, do we want it to show up as a link?
	public function showlink($newdisplay)
	{
		$this->displaylink = $newdisplay;
	}
	public function getturn()
	{
		return $this->turnnum;
	}

	//Five turns in a year
	public function getyear()
	{
		return $this->startyear + floor($this->turnnum / 5);
	}

	//There's nothing inherently wrong with a negative turn number, just as long
	//as it's not used as a link
	public function getprevious()
	{
		return new Turn($this->startyear, $this->turnnum - 1);
	}
	public function getnext()
	{
		return new Turn($this->startyear, $this->turnnum + 1);
	}

	//Probably could just use display() instead of creating another function,
	//but oh well
	public function isretreat()
	{
		if($this->turnnum % 5 == 2 || $this->turnnum % 5 == 4)
			return true;
		return false;
	}

	//Prints Turn information in the given format
	//Think of this like the date() function
	//Options:
	//  'y': Prints the year
	//  's': Prints a one-letter abbreviation of the season
	//  'S': Prints the full name of the season
	//  'p': Prints a one-letter abbreviation of the phase
	//  'P': Prints the full name of the phase
	//  'F': Prints a description of the season
	//  All else: Print as written
	//
	// For example, display('S y F') might return
	//  "Spring 1901 Movement and Retreat",
	// while display('ysp') would return "1901SM"
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

	//Given a game number, prints the Turn's year and season and links to it if
	//displaylink is set to true
	public function printurl($game)
	{
		$url = $this->display('ys');
		if($this->displaylink)
			$url = "<a href='/dominate/game/$game/results/$this->turnnum'>$url</a>";
		return $url;
	}

	//Returns true if compared turns refer to the same year, season, and phase
	public function equals($turn)
	{
		if(!isset($turn))
			return false;
		if($turn->display('ysp') == $this->display('ysp'))
			return true;
		return false;
	}
}
?>
