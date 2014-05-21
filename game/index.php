<?php
//URLs are of the form http://hdwhite.org/dominate/game/[GAME]/[PAGE]/[QUERY]
$urlarray = explode("/", $_SERVER['REQUEST_URI']);
$game = htmlentities($urlarray[3]);
$page = htmlentities($urlarray[4]);
$params = array_map('htmlentities', array_slice($urlarray, 5));
$params['game'] = $game;

//At the moment all the pages use the same View and Controller, though that might change
$view = "View";
$controller = "Controller";
switch($page)
{
	case "1":
	case "orders":
		$model = "OrdersModel";
		break;
	case "2":
	case "press":
		$model = "PressModel";
		break;
	case "3":
	case "stats":
		$model = "StatsModel";
		break;
	case "4":
	case "info":
		$model = "InfoModel";
		break;
	case "5":
	case "gm":
		$model = "GMModel";
		break;
	case "ordersubmit":
		$model = "OrderSubmitModel";
		break;
	case "presssend":
		$model = "PressSendModel";
		break;
	case "gamestart":
		$model = "GameStartModel";
		break;
	case "deadline":
		$model = "DeadlineModel";
		break;
	case "adjudicate":
		$model = "AdjudicateModel";
		break;
	case "gameend":
		$model = "GameEndModel";
		break;
	default:
		$model = "ResultsModel";
}

//Get the associated classes
//Variable names are limited to what is produced by the switch statement,
//so $_GET abuse won't happen here
require_once("objects/Turn.php");
require_once("model/$model.php");
require_once("view/$view.php");
require_once("controller/$controller.php");

//Initialise the classes
$model = new $model();
$controller = new $controller($model);
$view = new $view($controller, $model);

//Pass the parameters to the controller
$controller->params($params);

//Output the page
echo($view->output());
?>
