<?php
namespace app\controllers;

class CSearchController implements IPageController
{
	public function setPermissions($permissions) { //разрешения
		
	}
	public function render($pdo) { 
		$request = new \app\request\CRequestGoods($pdo);
		$arr_goods = $request->getArray();

		// получить массивы совпадений по запросу пользователя
		if(isset($_POST['search_data']) && $_POST['search_data'] !== '') {
			//сохранить запрос пользователя
			$request_user = $_POST['search_data'];
			//массив совпадений в названии товара
			$search_by_name = self::searchByName($pdo);
			//массив совпадений в описании товара
			$search_by_descrip = self::searchByDescription($pdo);
		}
		else {
			$request_user = '';
			$search_by_name = [];
			$search_by_descrip = [];
		}
		//подключить страницу, на который будет вывод
		$path_to_template = "../app/views/search.php";
		include($path_to_template);
	}
	//ф-ция поиска
	private function searchByName($pdo) {
		//запрос в таблицу goods на совпадение по имени
		$query = "SELECT * FROM goods WHERE name like '%".$_POST['search_data']."%';";
		return $pdo->query($query)->fetchAll();
	}
	private function searchByDescription($pdo) {
		//запрс на совпадение в описании
		$query = "SELECT * FROM goods WHERE description like '%".$_POST['search_data']."%';";
		return $pdo->query($query)->fetchAll();
	}
}