<?php
require_once("model/RedirectModel.php");

class PressSendModel extends RedirectModel
{
	public function __construct()
	{
		$this->init();
	}
	public function gettype()
	{
		return "presssend";
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
	public function getquery()
	{
		if($this->powername == "Observer")
			return array($this->game, "press");
		$mtitle = "DOMINATE - New message in game " . $this->gameinfo['name'];
		$mstart = "Hello,<br><br>" .
				  "You have received a message in the Diplomacy game " .$this->gameinfo['name'] . ".<br>" .
				  "To read and send messages, go to http://hdwhite.org/dominate/game/$this->game/press.<br>" .
				  "-----------------------------------------<br>";
		$mfooter = "<br><br>" . 
				   "-----------------------------------------<br>" .
				   "This e-mail has been sent via DOMINATE.<br>" .
				   "http://hdwhite.org/dominate<br>" .
				   "To stop receiving these e-mails, please change your settings at http://hdwhite.org/dominate/account.php";
		if($this->powername == "GM")
		{
			$mfrom = "GM (" . $this->gameinfo['gm'] . ")";
			$powerid = 0;
		}
		else
		{
			$mfrom = "$this->powername ($this->username)";
			$powerid = $this->mysqli->query("SELECT id FROM $this->powerdb " .
				"WHERE game=$this->game AND name='$this->powername'")
				->fetch_assoc()['id'];
		}
		if(isset($_POST['anonymous']))
		{
			$mfrom = "Anonymous";
			$anonymous = 'y';
		}
		else
			$anonymous = 'n';
		$turn = $this->curturn->getturn();
		$date = date("Y-m-d H:i:s");
		$htmlmessage = $this->bbformat(nl2br(htmlentities($_POST['message'])));
		$dbmessage = $_POST['message'];
		$tolist = array();
		$pressstmt = $this->mysqli->prepare("INSERT INTO $this->pressdb(" .
			"game, sender, turn, date, anonymous, broadcast, message)" .
			"VALUES(?, ?, ?, ?, ?, ?, ?)");
		$pressstmt->bind_param("iiissss", $this->game, $powerid, $turn, $date,
			$anonymous, $broadcast, $dbmessage);
		$receiverstmt = $this->mysqli->prepare("INSERT INTO $this->pressreceiverdb(" .
			"messageid, receiver) VALUES(?, ?)");
		$receiverstmt->bind_param("ii", $messageid, $powerid);
		if(isset($_POST['broadcast']))
		{
			$mto = "Broadcast";
			$broadcast = 'y';
			foreach($this->mysqli->query("SELECT name, user, $this->powerdb.id AS pid " .
				"FROM $this->powerdb, $this->userdb " .
				"WHERE $this->powerdb.player=$this->userdb.id AND game=$this->game " .
				"ORDER BY name") as $curpower)
				if($curpower['name'] != $this->powername)
					$tolist[] = array("powerid" => $curpower['pid'],
									  "powername" => $curpower['name'],
									  "username" => $curpower['user']);
			if($this->powername != "GM")
				$tolist[] = array("powerid" => 0,
								  "powername" => "GM",
								  "username" => $this->gameinfo['gm']);
		}
		else
		{
			$broadcast = 'n';
			$mto = "";
			foreach($this->mysqli->query("SELECT name, user, $this->powerdb.id AS pid " .
				"FROM $this->powerdb, $this->userdb " .
				"WHERE $this->powerdb.player=$this->userdb.id AND game=$this->game " .
				"ORDER BY name") as $curpower)
				if(isset($_POST[$curpower['name']]))
				{
					$tolist[] = array("powerid" => $curpower['pid'],
									  "powername" => $curpower['name'],
									  "username" => $curpower['user']);
					$mto .= $curpower['name'] . " (" . $curpower['user'] . ") ";
				}
			if(isset($_POST['GM']))
			{
				$tolist[] = array("powerid" => 0,
								  "powername" => "GM",
								  "username" => $this->gameinfo['gm']);
				$mto .= "GM (" . $this->gameinfo['gm'] . ")";
			}
		}
		$pressstmt->execute();
		$messageid = $this->mysqli->insert_id;
		foreach($tolist as $sendpower)
		{
			$powerid = $sendpower['powerid'];
			$receiverstmt->execute() or die($this->mysqli->error);
		}
		$userstmt = $this->mysqli->prepare("SELECT user, email FROM $this->userdb " .
			"WHERE user=? AND press='y'");
		$userstmt->bind_param('s', $curuser['username']);
		$mailto = array();
		foreach($tolist as $curuser)
		{
			$userstmt->execute();
			$userstmt->bind_result($uname, $email);
			if($userstmt->fetch())
				$mailto[] = "'$uname' <$email>";
		}
		$mheader = "MIME-Version: 1.0\r\n" .
				   "Content-type: text/html; charset=iso-8859-1\r\n" .
				   "From: DOMINATE <dominate@hdwhite.org>\r\n" .
				   "Reply-to: hdwhite.contact@gmail.com\r\n" .
				   "Bcc: " . implode(", ", $mailto) . "\r\n";
		$mtext = $mstart . "From: $mfrom<br>To: $mto<br>Message:<br> $htmlmessage$mfooter";
		mail("", $mtitle, $mtext, $mheader);
		$message = "Message sent";
		$pressstmt->close();
		$receiverstmt->close();
		$userstmt->close();
		return array($this->game, "press");
	}
}
?>
