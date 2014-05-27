<?php
require_once("model/PageModel.php");

//The Model for viewing press
class PressModel extends PageModel
{
	public function __construct()
	{
		$this->init();
	}
	public function gettype()
	{
		return "press";
	}

	//Basic formatting
	//Used so people can make text fancy safely
	protected function bbformat($text)
	{
		$text = preg_replace("/\[b\](.*)\[\/b\]/Ui",
			"<b>$1</b>", $text);
		$text = preg_replace("/\[i\](.*)\[\/i\]/Ui",
			"<i>$1</i>", $text);
		$text = preg_replace("/\[u\](.*)\[\/u\]/Ui",
			"<u>$1</u>", $text);
		$text = preg_replace("/\[url\]http(.*)\[\/url\]/Ui",
			"<a href=\"http$1\">http$1</a>", $text);
		$text = preg_replace("/\[url=http(.*)\](.*)\[\/url\]/Ui",
			"<a href=\"http$1\">$2</a>", $text);
		$text = preg_replace("/\[img\](.*)\[\/img\]/Ui",
			"<img src=\"$1\">", $text);
		return $text;
	}

	//Gets the list of messages for the player
	public function getpress()
	{
		$presslist = array();

		//Selects message details and a comma-separated list of recipients
		$mquery = "SELECT $this->pressdb.id AS mid, sender, turn, date, anonymous, " .
			"broadcast, message, GROUP_CONCAT(receiver) AS rlist, unread " .
			"FROM $this->pressdb, $this->pressreceiverdb " .
			"WHERE $this->pressdb.id=$this->pressreceiverdb.messageid " .
			"AND game=$this->game";
		$grouping = "GROUP BY $this->pressdb.id";
		$ordering = "ORDER BY date DESC";
		$powerid = 0;

		//Observers can only see broadcast press
		if($this->powername == "Observer")
			$messagelist = $this->mysqli->query("$mquery AND broadcast='y' " .
				"$grouping $ordering");
		//The GMs id for a message is '0'
		elseif($this->powername == "GM")
			$messagelist = $this->mysqli->query("$mquery $grouping " .
				"HAVING sender=0 OR rlist REGEXP '(^|,)0(,|$)' " . $ordering);
		else
		{
			$powerid = $this->mysqli->query("SELECT id FROM $this->powerdb " .
				"WHERE game=$this->game AND name='$this->powername'")
				->fetch_assoc()['id'];
			$messagelist = $this->mysqli->query("$mquery $grouping " .
				"HAVING sender=$powerid OR rlist REGEXP '(^|,)$powerid(,|$)' $ordering");
		}

		//Iterates through each message
		while($curmsg = $messagelist->fetch_assoc())
		{
			//Gets the year and season of the message
			$season = (new Turn($this->gameinfo['startyear'], $curmsg['turn']))
				->display('ysp');
			$date = date("n/j/Y H:i:s", strtotime($curmsg['date']));

			//Sender of ID 0 implies it was the GM
			if($curmsg['sender'] == 0)
				$from = "GM";

			else if($curmsg['anonymous'] == 'y')
			{
				//You can tell if you sent an anonymous message
				if($curmsg['sender'] == $powerid)
					$from = "($this->powername)";
				//So does the GM
				else if($powername == "GM")
				{
					$from = $this->mysqli->query("SELECT name FROM $this->powerdb " .
						"WHERE id=" . $curmsg['sender'])->fetch_assoc()['name'];
					if($from != "GM")
						$from = "<span class=\"" . substr($from, 0, 1) . "\">$from</span>";
					$from = "($from)";
				}
				else
					$from = "Anonymous";
			}
			//Otherwise, get the name and style it appropriately
			else
			{
				$from = $this->mysqli->query("SELECT name FROM $this->powerdb " .
					"WHERE id=" . $curmsg['sender'])->fetch_assoc()['name'];
				if($from != "GM")
					$from = "<span class=\"" . substr($from, 0, 1) . "\">$from</span>";
			}
			if($curmsg['broadcast'] == 'y')
				$to = "Broadcast";
			else
			{
				//Creates an array of recipients
				$rlist = explode(",", $curmsg['rlist']);
				sort($rlist);
				$rnames = Array();
				//Since it's sorted, we know ID 0 has to come first
				if($rlist[0] == 0)
					$rnames[0] = "GM";

				//For each ID, find the appropriate power name and add it to the list
				$namelist = $this->mysqli->query("SELECT name FROM $this->powerdb " .
					"WHERE id IN(" . $curmsg['rlist'] . ")");
				while($newto = $namelist->fetch_assoc()['name'])
					$rnames[] = "<span class=\"" . substr($newto, 0, 1) . "\">$newto</span>";
				$to = implode(" ", $rnames);
			}

			//Sanitises and formats the message and adds the message info to the
			//master array
			$message = $this->bbformat(nl2br(htmlentities($curmsg['message'])));
			$presslist[] = array("id" => $curmsg['mid'],
								 "season" => $season,
								 "date" => $date,
								 "from" => $from,
								 "to" => $to,
								 "unread" => 0,
								 "message" => $message);
		}
		return $presslist;
	}

	public function setparams($params)
	{
		$this->game = $params['game'];
		if($this->loadgame() == false)
			return 1;
		$this->loadplayer($this->game);
		//Only logged-in people can see messages
		//That way people can broadcast email addresses and the like without
		//being seen by the outside world
		if($this->username == "Guest")
			return 2;
		return 0;
	}
	public function getdata()
	{
		$presslist = $this->getpress();
		$powerlist = array();
		//Gets an array of all the power names
		$powerquery = $this->mysqli->query("SELECT name FROM $this->powerdb " .
			"WHERE game=$this->game ORDER BY name");
		while($powerlist[] = $powerquery->fetch_assoc()['name']);
		array_pop($powerlist);
		return array("css"		=> "",
					 "game"		=> $this->game,
					 "curturn"	=> $this->curturn,
					 "presslist"=> $presslist,
					 "powerlist"=> $powerlist,
					 "username"	=> $this->username,
					 "powername"=> $this->powername,
					 "orders"	=> $this->orders,
					 "gameinfo"	=> $this->gameinfo);
	}
}
?>
