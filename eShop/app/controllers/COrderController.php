<?php
namespace app\controllers;

class COrderController implements IPageController
{
	public function setPermissions($permissions) { //разрешения 
	}
	public function render($pdo) {
		$request = new \app\request\CRequestGoods($pdo);
		$arr_goods = $request->getArray();
		$path_to_template = "../app/views/order.php";
		include($path_to_template);
	}
}