<?php
namespace app\router;

// класс для определения по строке запроса, какой раздел сайта необходимо отобразить
class CRouter
{
	public static function getController() {
		$params = $_GET; //
		$routes = [
			'catalog' =>"CCatalogController",
			'cart' => "CCartController",
			'product' => "CProductController",
			'sortby' => "CSortController",
			'filter' => "CFilterController",
			'cabinet' => "CCabinetController",
			'order' => "COrderController",
			'search_page' =>"CSearchController"
		]; 
		
		if(isset($params['q']) && isset($routes[$params['q']])) {
		    $route = '\\app\\controllers\\'. $routes[$params['q']];
		    $controller = new $route();
		} else {
		    $route = '\\app\\controllers\\'. $routes['catalog'];
		    $controller = new $route(); 
		}
		return $controller;
	}
}