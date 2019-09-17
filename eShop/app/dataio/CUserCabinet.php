<?php
namespace app\dataio;

class CUserCabinet {
	private $pdo;
	private $user_id = 0;
	private $history = [];
	private const HISTORY = "SELECT goods.id as 'goods_id', 
									goods.cost, 
									goods.name,
									goods.weight, 
									cart.goods_count as 'count_in_cart', 
									goods.description,
									`user`.id as 'user_id', 
									cart.order_data,
									goods.img
							FROM goods
							LEFT JOIN cart ON cart.goods_id = goods.id
							LEFT JOIN `user` ON `user`.id = cart.user_id
							WHERE cart.user_id = :user_id AND cart.order_data = :order_date;";

	public function __construct($pdo, $user_id) {
		$this->pdo = $pdo;
		if(!empty($user_id))
			$this->user_id = $user_id;
		//заполнить массив из базы истории заказов пользователя
		$this->toArrHistory();
	}
	//заполняет массив истории заказов
	private function toArrHistory() {
		if($this->user_id > 0) {
			//запрос на количество заказов пользователя(из уникальных значений даты и времени заказов)
			$query = "SELECT  DISTINCT order_data, user_id
					  FROM cart WHERE user_id =".$this->user_id." AND status = 2;";
			$order_dates = $this->pdo->query($query)->fetchAll();
			//если длина массива ответа > 0, значит история заказов есть и их кол-во = длине массива
			if(count($order_dates) > 0) {
				for($i = 0; $i < count($order_dates); $i++) {
					$query = self::HISTORY;
					$resalt = $this->pdo->prepare($query);
					$resalt->bindValue('user_id', $this->user_id);
					$resalt->bindValue('order_date', $order_dates[$i]['order_data']);
					$resalt->execute();
					$resalt = $resalt->fetchAll();
					//добавить в массив истории заказов
					$this->history[$i] = $resalt;
				}
			}
		}
	}
	public function getHistory() {
		return $this->history;
	}
}