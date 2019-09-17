<?php
namespace app\controllers;

class CFilterController implements IPageController
{
	public function setPermissions($permissions) { //разрешения
	}
	public function render($pdo) { 
		$request = new \app\request\CRequestGoods($pdo);
		$arr_goods = $request->getArray();
		//производим фильтрацию 
		$type = $_GET['filtertype'];	//тип фильтра(равно, больше, меньше)
		$value = $_GET['filtervalue'];	//значение с которым сравнивать(конкретная категория, стоимость от/до 1000)
		//здесь декодировать обратно в кириллицу
		$value = urldecode($value);
		$data = $_GET['datafilter'];	//значение по которому сравнивать(категория, стоимость)
		$new_goods = self::filtration($arr_goods, $data, $value, $type);
		$path_to_template = "../app/views/filter.php"; 
		include($path_to_template); 
	}
	//ф-ция фильтрации. 
	//в массиве $_GET у нас:
	// datafilter - значение по которому сравнивать(категория, стоимость)
	// filtervalue - значение с которым сравнивать(конкретная категория, стоимость от/до 1000)
	// filtertype - тип фильтра(равно, больше, меньше)
	private function filtration($arr, $dataf, $valuef, $typef) {
		$rule = self::getRule($typef);
		$rez = [];
		foreach ($arr as $key => $item) {
			if(self::$rule($item[$dataf], $valuef)) { 
				$rez[$key] = $item;
			}
		}
		return $rez;
		
	}
	//ф-ция возвращает правило фильтрации
	private function getRule($t) {
		if($t === 'equal') {
			return 'isEqual';
		}
		elseif ($t === 'more') {
			return 'isMore';
		}
		elseif ($t === 'less') {
			return 'isLess';
		}
	}
	private function isEqual($a, $b) {
		return ($a == $b);
	}
	private function isMore($a, $b) {
		return ($a > $b);
	}
	private function isLess($a, $b) {
		return ($a <= $b);
	}
}