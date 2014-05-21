<?php
require_once("model/Model.php");

abstract class RedirectModel extends Model
{
	public function redirect()
	{
		return true;
	}
	abstract protected function getquery();
}
?>
