<?php
//The controller used for all the pages so far
class Controller
{
	private $model;
	//Your basic initialisation
	public function __construct($model)
	{
		$this->model = $model;
	}

	//Passes the page parameters into the Model
	public function params($params)
	{
		$error = $this->model->setparams($params);
		//Error 0: Ok
		//Error 1: Invalid game
		//Error 2: Not enough permissions
		if($error === 1)
		{
			header("Location: /dominate/");
			exit;
		}
		if($error === 2)
		{
			header("Location: /dominate/game/" . $params['game']);
			exit;
		}
		
		//If the model simply processes a form, we will want to redirect to the
		//results page
		if($this->model->redirect())
		{
			$newparams = implode("/", $this->model->getquery());
			header("Location: ../$newparams");
			exit;
		}
	}
}
?>
