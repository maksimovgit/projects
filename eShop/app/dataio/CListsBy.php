<?php
namespace app\dataio;

class CListsBy {
	public static $categories=[]; //ассоц массив(ключ - категория, значение - кол-во товара)
	public static $count_cost_less=0; // кол-во товара до 10000
	public static $count_cost_more=0; // кол-во товара дороже 10000
	public static $count_weight_less = 0;
	public static $count_weight_more = 0;
	public static $count_vogue_less = 0;
	public static $count_vogue_more = 0;
	public static function getListCategory($arr) {
		//пройти массив товаров и создать новый массив по категориям
		foreach ($arr as $key=> $item) {
			if( isset(self::$categories[$item['category']]) ) {
				self::$categories[$item['category']]++;
			} else {
				self::$categories[$item['category']] = 1;
			}
		}
	}
	public static function getListCost($arr) {
		foreach ($arr as $key => $item) {
			if($item['cost'] <= 5000)
				self::$count_cost_less += 1;
		}
		self::$count_cost_more = count($arr) - self::$count_cost_less;
	}
	public static function getListWeight($arr) {
		foreach ($arr as $key => $item) {
			if($item['weight'] <= 5000)
				self::$count_weight_less += 1;
		}
		self::$count_weight_more = count($arr) - self::$count_weight_less;
	}
	public static function getListVogue($arr) {
		foreach ($arr as $key => $item) {
			if($item['vogue'] <= 5)
				self::$count_vogue_less += 1;
		}
		self::$count_vogue_more = count($arr) - self::$count_vogue_less;
	}
	public static function getFilterName() {
		$answer = '';
		if(isset($_GET['q']) && $_GET['q']=='filter') {
			if(isset($_GET['filtertype']) && $_GET['filtertype']=='less')
				$ot = " до ";
			elseif(isset($_GET['filtertype']) && $_GET['filtertype']=='more')
				$ot = " от ";

			if(isset($_GET['datafilter']) && $_GET['datafilter']=='cost')
				$answer = 'стоимости : <i>'.$ot .$_GET['filtervalue'].' руб.</i>';
			elseif(isset($_GET['datafilter']) && $_GET['datafilter']=='weight')
				$answer = 'весу : <i>'.$ot .($_GET['filtervalue']/1000).' кг.</i>';
			elseif(isset($_GET['datafilter']) && $_GET['datafilter']=='vogue')
				$answer = 'популярности : <i>'.$ot .$_GET['filtervalue'].'</i>';
			elseif(isset($_GET['datafilter']) && $_GET['datafilter']=='category')
				$answer = 'категории : <i>'.$_GET['filtervalue'].'</i>';
		}
		return $answer;
	}
	public static function getSortName() {
		$answer = '';
		if(isset($_GET['q']) && $_GET['q']=='sortby') {
			if(isset($_GET['actionsort']) && $_GET['actionsort'] == 'sort_up')
				$sort = ' возрастанию ';
			elseif(isset($_GET['actionsort']) && $_GET['actionsort'] == 'sort_down')
				$sort = ' убыванию ';

			if(isset($_GET['sort_field']) && $_GET['sort_field']=='cost')
				$answer = 'цене по : <i>'.$sort.'</i>';
			if(isset($_GET['sort_field']) && $_GET['sort_field']=='weight')
				$answer = 'весу по : <i>'.$sort.'</i>';
			if(isset($_GET['sort_field']) && $_GET['sort_field']=='vogue')
				$answer = 'популярности по : <i>'.$sort.'</i>';
		}
		return $answer;
	}
}