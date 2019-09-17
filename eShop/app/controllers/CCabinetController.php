<?php
namespace app\controllers;

class CCabinetController implements IPageController
{
	public function setPermissions($permissions) { //разрешения
		
	}
	public function render($pdo) { //формирование страницы
		//подключить класс запросов, выполнить запрос и получить массив-ответ
		$request = new \app\request\CRequestGoods($pdo);
		//массив всех товаров
		$arr_goods = $request->getArray();
		//подключить страницу вывода
		$path_to_template = "../app/views/cabinet.php";
		include($path_to_template);
	}
}