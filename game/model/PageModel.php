<?php
require_once("model/Model.php");

//The abstract class for Models for non-redirecting pages
abstract class PageModel extends Model
{
	public function redirect()
	{
		return false;
	}

	//All such Models have information that need to get passed to the View
	abstract protected function getdata();
}
?>
