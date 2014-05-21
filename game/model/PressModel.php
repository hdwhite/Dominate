<?php
require_once("model/PageModel.php");

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
	public function getpress()
	{
		$presslist = array();
		$mquery = "SELECT $this->pressdb.id AS mid, sender, turn, date, anonymous, " .
			"broadcast, message, GROUP_CONCAT(receiver) AS rlist, unread " .
			"FROM $this->pressdb, $this->pressreceiverdb " .
			"WHERE $this->pressdb.id=$this->pressreceiverdb.messageid " .
			"AND game=$this->game";
		$grouping = "GROUP BY $this->pressdb.id";
		$ordering = "ORDER BY date DESC";
		$powerid = 0;
		if($this->powername == "Observer")
			$messagelist = $this->mysqli->query("$mquery AND broadcast='y' " .
				"$grouping $ordering");
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
		while($curmsg = $messagelist->fetch_assoc())
		{
			$season = (new Turn($this->gameinfo['startyear'], $curmsg['turn']))
				->display('ysp');
			$date = date("n/j/Y H:i:s", strtotime($curmsg['date']));
			if($curmsg['sender'] == 0)
				$from = "GM";
			else if($curmsg['anonymous'] == 'y')
			{
				if($curmsg['sender'] == $powerid)
					$from = "($this->powername)";
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
				$rlist = explode(",", $curmsg['rlist']);
				sort($rlist);
				$rnames = Array();
				if($rlist[0] == 0)
					$rnames[0] = "GM";
				$namelist = $this->mysqli->query("SELECT name FROM $this->powerdb " .
					"WHERE id IN(" . $curmsg['rlist'] . ")");
				while($newto = $namelist->fetch_assoc()['name'])
					$rnames[] = "<span class=\"" . substr($newto, 0, 1) . "\">$newto</span>";
				$to = implode(" ", $rnames);
			}
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
		if($this->username == "Guest")
			return 2;
		return 0;
	}
	public function getdata()
	{
		$presslist = $this->getpress();
		$powerlist = array();
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
