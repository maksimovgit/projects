<?php
namespace app\request;

class CRequestGoods {
	protected const SELECT = "SELECT 
						goods.id,
						goods.name as name,
						goods.cost as cost,
						goods.weight as weight,
						goods.`count` as count,
						goods.vogue as vogue,
						goods.category as category,
						goods.description as description,
						goods.img as img,
						goods.receipt_data as receipt_data ";

	protected const FROM = "FROM goods ";

	protected const CROUPBY = "GROUP BY goods.id ";

	protected $limit = []; 
	protected $pdo;

	public function __construct($pdo) { 
		$this->pdo = $pdo;
	}
	//будет возвращать массив массивов
	public function getArray() {
		//запрос(пока только с лиммитом)
		$query = self::SELECT . self::FROM
		.$this->getWhere()
		.self::CROUPBY
		.$this->getHaving()
		.$this->getOrder()
		.$this->getLimit() //пока только этот метод реализован
		.";";
		return $this->pdo->query($query)->fetchAll();
	}

	//получить WHERE
	protected function getWhere() {
		return " ";
	}
	//получить HAVING
	protected function getHaving() {
		return " ";
	}
	//ф-ция для формирования сортировки
	protected function getOrder() {
		return " ";
	}
	//получить LIMIT
	protected function getLimit() {
		if(count($this->limit) > 0) {
			return "LIMIT  ". implode(',' , $this->limit);
		} else {
			return " ";
		}
	}
	//ф-ция устанавливающая лимит
	public function setLimit($limit, $ofset = null) {
		$this->limit = []; //обнуляем лимит
		if(is_numeric($ofset)) $this->limit[] = $ofset;
		if(is_numeric($limit)) $this->limit[] = $limit;
		return $this;

	}
}