<?php
namespace app\controllers;

class CSortController implements IPageController
{
	public function setPermissions($permissions) { //разрешения
	}
	public function render($pdo) { 
		$request = new \app\request\CRequestGoods($pdo);
		$arr_goods = $request->getArray();
		
		//произвести сортировку
		$new_goods = self::sorting($arr_goods, self::getSortRule());
		$path_to_template = "../app/views/sortby.php";
		include($path_to_template);

	}
	//ф-ция, определяющая правило сортировки по GET-параметрам.
	//(для определения по какому полю(вес/цена...) сортируем и по возраст. или убыв.)
	private function getSortRule() {
		$rule = "";	
		if(isset($_GET['actionsort']) && ($_GET['actionsort'] === "sort_up") ) {
			if( isset($_GET['sort_field']) ) {
				$k = $_GET['sort_field'];
				switch ($k) {
					case 'weight':
						$rule = 'ruleSortWeightUp';
						break;
					case 'cost':
						$rule = 'ruleSortCostUp'; 
						break;
					case 'vogue':
						$rule = 'ruleSortVogueUp'; 
						break;
				}
			}
		} elseif(isset($_GET['actionsort']) && ($_GET['actionsort'] === "sort_down") ) {
			if( isset($_GET['sort_field']) ) {
				$k = $_GET['sort_field'];
				switch ($k) {
					case 'weight':
						$rule = 'ruleSortWeightDown';
						break;
					case 'cost':
						$rule = 'ruleSortCostDown'; 
						break;
					case 'vogue':
						$rule = 'ruleSortVogueDown'; 
						break;
				}
			}
		}
		return $rule;
	}

	//ф-ция сортировки. принимает массив и правило сортировки
	private function sorting($arr,$myrule) {
		$n = count($arr);
		for($i=0;$i<$n;$i++) {
			for($j=$i+1;$j<$n;$j++) {
				if(!self::$myrule($arr[$i],$arr[$j])) {
					$k=$arr[$i];
					$arr[$i] = $arr[$j];
					$arr[$j] = $k;
				}
			}
		}
		return $arr;
	}
	private function ruleSortWeightUp($a,$b) {
		return $a['weight']<=$b['weight'];
	}
	private function ruleSortCostUp($a,$b) {
		return $a['cost']<=$b['cost'];
	}
	private function ruleSortVogueUp($a,$b) {
		return $a['vogue']<=$b['vogue'];
	}
	private function ruleSortWeightDown($a,$b) {
		return $a['weight']>=$b['weight'];
	}
	private function ruleSortCostDown($a,$b) {
		return $a['cost']>=$b['cost'];
	}
	private function ruleSortVogueDown($a,$b) {
		return $a['vogue']>=$b['vogue'];
	}
}