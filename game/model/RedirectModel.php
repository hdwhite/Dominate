<?php
require_once("model/Model.php");

//The abstract class for pages that execute some logic and then redirect the
//user to another page
abstract class RedirectModel extends Model
{
	public function redirect()
	{
		return true;
	}
	//Does the logic for the page
	//Returns an array of where to redirect people
	abstract protected function getquery();
}
?>
