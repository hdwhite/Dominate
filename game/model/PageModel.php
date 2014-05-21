<?php
require_once("model/Model.php");

abstract class PageModel extends Model
{
	public function redirect()
	{
		return false;
	}
	abstract protected function getdata();
}
?>
