<?php
namespace app\dataio;

class CUserCart {
	protected $pdo;
	private $user_id;
	private $login;
	private $cart = [];
	private $total_cost = 0;
	private $total_weight = 0;
	private $total_count = 0;
	//для получения корзины пользователя
	private const CARTREQUEST = "SELECT
								goods.id, 
								goods.name, 
								goods.cost,
								goods.weight,
								goods.vogue,
								goods.category,
								goods.description,
								goods.img,
								goods.receipt_data,
								cart.goods_id,
								cart.goods_count as 'count_in_cart',
								(goods.cost * cart.goods_count) as 'summ_cost',
								cart.user_id ";
	private const FROM = "FROM goods ";
	private const JOIN = "LEFT JOIN cart ON goods.id = cart.goods_id
						LEFT JOIN `user` ON `user`.id = cart.user_id ";
	//для подсчета общей суммы, веса и кол-ва
	private const SUMMINFO = "SELECT
								SUM(goods.cost * cart.goods_count) as 'total_cost',
								SUM(cart.goods_count) as 'total_count',
								SUM(goods.weight * cart.goods_count) as 'total_weight' ";
	//для проверки есть ли товар в корзине(нужно при добавлении в корзину)
	private const CHECKCART = "SELECT
								goods.id,
								goods.name,
								`cart`.goods_count ";
	//при добавлении товара в корзину если он уже есть в корзине
	private const UPDATEPLUS = "UPDATE `cart` SET goods_count = goods_count +1 ";
	//при уменьшении кол-ва товара в корзину если он уже есть в корзине
	private const UPDATEMINUS = "UPDATE `cart` SET goods_count = goods_count -1 ";

	public function __construct($pdo, $login, $user_id) {
		$this->pdo = $pdo;
		$this->login = $login;
		if($login !== 'anonim') {
			$this->user_id = $user_id;
			$this->cart = $this->arrToCart();
			if(isset($_SESSION['cart'])) {
				$this->combineCart();
				unset($_SESSION['cart']);
			}
		}
		else {//если аноним, то
			//формировать корзину из сессии
			if(isset($_SESSION['cart']) && $this->login === 'anonim') {
				$this->cart = $this->arrToCartAnonim();
			}
		}
		$this->calcTotalInfo();
	}
	//ф-ция соединяет корзину анонима и корзину пользователя(если она есть) при его логине
	private function combineCart() {
		//если корзина пользователя пуста, то заполняем ее из массива $_SESSION['cart']
		if(count($this->cart) == 0 ) {
			$this->cart = $this->arrToCartAnonim();
			if(count($this->cart) > 0) {
				foreach ($this->cart as $key => $value) {
					$query = "INSERT INTO cart VALUES(NULL, ".$value['id'].", ".$value['count'].",
														".$this->user_id.", 1, NULL);";
					$this->pdo->query($query);
				}
			}
		}
		//иначе нужно добавить 
		else {
			//обойти массив $this->cart и массив полученный $this->arrToCartAnonim() на соотв-ия id
			$newcart = $this->arrToCartAnonim();
			foreach ($this->cart as $key => $value) {
				foreach ($newcart as $n => $item) {
					//если есть совпадения то запросом поменять кол-во в базе корзины
					if($value['id'] == $item['id']) {
						$query = "UPDATE `cart` SET goods_count = goods_count + ".$item['count']
								." WHERE user_id = ".$this->user_id." AND goods_id = ".$item['id']
								." AND status = 1;";
						$this->pdo->query($query);
						// и удалить элемент, так как далее его не будем обрабатывать
						unset($newcart[$n]);
					}
				}
			}
			// оставшиеся элементы корзины полученные из сессии добавляем в базу корзины
			sort($newcart);
			foreach ($newcart as $key => $value) {
				$query = "INSERT INTO cart VALUES (NULL,".$value['id'].",".$value['count']
								.",".$this->user_id.", 1, NULL);";
				$this->pdo->query($query);
			}
			
		}
		//сделать обновление $this->cart из базы
		$this->cart = $this->arrToCart();
	}
	private function getWhere() {
		return "WHERE user_id = ".$this->user_id;
	}
	//ф-ция по запросу формирует массив корзины
	private function arrToCart() {
		$query = self::CARTREQUEST. self::FROM. self::JOIN .$this->getWhere()." AND cart.status =1;";
		$resalt = $this->pdo->query($query)->fetchAll();
		return $resalt;
	}
	//ф-ция формирует массив корзины анонима
	private function arrToCartAnonim() {
		//брать $_SESSION['cart'], по нему искать совпадения в полном каталоге(запросить из базы)
		$cart_anonim = [];
		$query = "SELECT * FROM goods;";
		$goods = $this->pdo->query($query)->fetchAll();
		//при нахождении записывать в массив $cart
		foreach ($goods as $key => $item) {
			if(isset($_SESSION['cart'])) {
				foreach($_SESSION['cart'] as $n => $value) {
					if($item['id'] == $value['id']) {
						$cart_anonim[] = ['id' => $item['id'],
										'name' => $item['name'],
										'cost' => $item['cost'],
										'weight' => $item['weight'],
										'count' => $value['count'],
										'vogue' => $item['vogue'],
										'category' => $item['category'],
										'description' => $item['description'],
										'img' => $item['img'],
										'receipt_data' => $item['receipt_data'],
										'count_in_cart' => $value['count'],
										'summ_cost' => $value['count'] * $item['cost']
									];
					}
				}
			}
		}
		return $cart_anonim;
	}
	public function getCart() {
		return $this->cart;
	}
	//ф-ция считает общую сумму, кол-во и вес
	private function calcTotalInfo() {
		if($this->login !== 'anonim') {
			$query = self::SUMMINFO. self::FROM. self::JOIN .$this->getWhere()." AND cart.status =1;";
			$resalt = $this->pdo->query($query)->fetchAll();
			if(count($resalt) > 0) {
				$this->total_cost = $resalt[0]['total_cost'];
				$this->total_count = $resalt[0]['total_count'];
				$this->total_weight = $resalt[0]['total_weight'];
			}
		}
		//если аноним, то считать по массиву $this->cart
		else {
			$this->total_cost = 0;
			$this->total_weight = 0;
			$this->total_count = 0;
			foreach ($this->cart as $key => $item) {
				$this->total_cost += ($item['cost'] * $item['count']);
				$this->total_count += $item['count'];
				$this->total_weight += ($item['count'] * $item['weight']);
			}
		}
	}
	public function getTotalCost() {
		return $this->total_cost;
	}
	public function getTotalWeight() {
		return $this->total_weight;
	}
	public function getTotalCount() {
		return $this->total_count;
	}
	//ф-ция обработки добавления/удаления товара из корзины
	public function actionsWithCart() {
		//если нажали на "Добввить в корзину" или "+"
		if(isset($_POST['action']) && ($_POST['action'] === 'addtocart') && isset($_POST['id']) ) {
			if($this->login !== 'anonim') {
				//запрос на поиск такого товара в корзине пользователя
				$where = $this->getWhere(). " AND goods_id = ".$_POST['id']." AND status = 1;";
				$query = self::CHECKCART. self::FROM. self::JOIN. $where;
				$resalt = $this->pdo->query($query)->fetchAll();
				if(count($resalt)>0) { //если товар есть
					//то увеличить кол-во на 1
					$query = self::UPDATEPLUS.$where;
					$this->pdo->query($query);
				}
				else { //если такого товара в корзине нет
					//то внести его в корзину
					$query = "INSERT INTO cart VALUES (NULL,".$_POST['id'].", 1,".$this->user_id.", 1, NULL );";
					$this->pdo->query($query);

				}
			}
			else {
				//если аноним
				if(isset($_SESSION['cart'])) {
					$len = count($_SESSION['cart']);
					$k= false;
					for($i=0; $i<$len; $i++) {
						if($_SESSION['cart'][$i]['id'] == $_POST['id']) {
							$_SESSION['cart'][$i]['count']++;
							$k = true;
						}
					}
					if(!$k) {
						$_SESSION['cart'][] = ['id' => $_POST['id'], 
												'count' => 1,
												'weight' => $_POST['weight'],
												'cost' => $_POST['cost'] ];
					}
				//если корзины в сессии еще не было, то создать
				} else {
					$_SESSION['cart'][0]['id'] = $_POST['id'];
					$_SESSION['cart'][0]['count'] = 1;
					$_SESSION['cart'][0]['weight'] = $_POST['weight'];
					$_SESSION['cart'][0]['cost'] = $_POST['cost'];
				}
			}
		//если нажали на "-"
		} elseif(isset($_POST['action']) && ($_POST['action'] === 'deltocart') && isset($_POST['id']) ) {
			if($this->login !== 'anonim') {
				//запрос на поиск такого товара в корзине пользователя
				$where = $this->getWhere(). " AND goods_id = ".$_POST['id']." AND status = 1;";
				$query = self::CHECKCART. self::FROM. self::JOIN. $where;
				$resalt = $this->pdo->query($query)->fetchAll();
				if(count($resalt)>0) { //если товар есть
					//то уменьшить кол-во в корзине на единицу
					$query = self::UPDATEMINUS.$where;
					$this->pdo->query($query);
				}
				//иначе(если такова товара нет в корзине), то ничего не делаем,
			}
			else {
				//если аноним
				if(isset($_SESSION['cart']) )
					foreach ($_SESSION['cart'] as $key => $value) {
						if($value['id'] == $_POST['id'])
							if($value['count'] > 0)
								$_SESSION['cart'][$key]['count']--;
					}
			}
		//если нажали на "Х" (удалить из корзины)
		} elseif(isset($_POST['action']) && ($_POST['action'] === 'del_element') && isset($_POST['id'])) {
			if($this->login !== 'anonim') {
				$query = "DELETE FROM cart WHERE user_id ="
							.$this->user_id." AND goods_id =".$_POST['id']." AND status = 1;";
				$this->pdo->query($query);
			}
			//если аноним
			else {
				foreach ($_SESSION['cart'] as $key => $value) {
					if($value['id'] == $_POST['id'])
						unset($_SESSION['cart'][$key]);
				}
				sort($_SESSION['cart']);//сортировка массива после удаления элемента(чтобы сдвинуть индексы)
			}
		}
		//записать изменения в $this->cart
		if($this->login !== 'anonim') {
			$this->cart = $this->arrToCart();
		} else { //если аноним, то
			$this->cart = $this->arrToCartAnonim();
		}
		//подсчет полной стоимости, веса, кол-ва
		$this->calcTotalInfo();
	}
	
	//ф-ция возвращает кол-во товара в базе goods по id
	public function getGoodsCount($id) {
		$query = "SELECT goods.`count` FROM goods WHERE id=".$id.";";
		return $this->pdo->query($query)->fetchAll()[0]['count']; //чтобы возврощал число
	}
	//ф-ция возвращает кол-во товара в корзине по id товара(для анонима из сессии)
	public function getGoodsCountInCart($id) {
		if($this->login !== 'anonim') {
			//запрос к базе
			$query = "SELECT goods_count 
						FROM cart 
						WHERE goods_id =".$id." AND user_id=".$this->user_id." AND status = 1;";
			return $this->pdo->query($query)->fetchAll()[0]['goods_count'];
		}
		else {
			//из сессии
			if(isset($_SESSION['cart'])) {
				$len = count($_SESSION['cart']);
				for($i=0; $i<$len; $i++) {
					if($_SESSION['cart'][$i]['id'] == $id) {
						return $_SESSION['cart'][$i]['count'];
					}
				}
			}
		}
	}

	// получить почту пользователя
	private function getEmail() {
		$query = "SELECT `user`.email FROM `user` WHERE id=".$this->user_id.";";
		return $this->pdo->query($query)->fetchAll();
	}
	//послать письмо покупателю
	public function sendMessage($delivery, $addr_delivery) {
		//$to = $this->getEmail()[0]['email'];
		$to = "<paradoxxru@list.ru>, <".$this->getEmail()[0]['email'].">";
		$subject = "заказ";
		$message = "<p>Уважаемый(ая), ".$this->login." ваш заказ принят.</p><br><br>";
		$message .= "<table style='border-collapse: separate; border-spacing: 2px; width: 100%;'>
			<th>Название</th><th>Вес</th><th>Цена</th><th>Кол-во</th><th>Стоимость</th>";
		foreach ($this->cart as $value) {
			$message .= "<tr>
						  <td style='border:1px solid;'>".$value['name']."</td>
					      <td style='border:1px solid;'>Вес: ".($value['weight']/1000)." кг.</td>
					      <td style='border:1px solid;'>".$value['cost']."</td>
					      <td style='border:1px solid;'>".$value['count_in_cart']."</td>
					      <td style='border:1px solid;'>".$value['summ_cost']."</td>
						</tr>";
		}
		$message .= "<tr><td></td><td></td><td>Всего:</td><td>Вес:</td><td>Общая сумма:</td><tr>
						<tr>
							<td style='border:none;'></td><td style='border:none;'></td>
							<td style='border:1px solid;'>".$this->getTotalCount()."</td>
							<td style='border:1px solid;'>".($this->getTotalWeight()/1000)." кг.</td>
							<td style='border:1px solid;'>".$this->getTotalCost()." руб.</td>
						<tr>
					</table><br><br>";
		if($delivery == 'delivery' && isset($addr_delivery)) {
			$dostavka = 'доставка по адресу: '.$addr_delivery;
		}
		else {
			$dostavka = 'самовывоз с адреса: СПб, ул. Б. Морская, д.5';
		}
		$message .= "Выбранный способ доставки - ".$dostavka;
		//$headers  = "Content-type: text/html; charset=windows-1251 \r\n"; 
		$headers = "Content-type: text/html; charset=UTF-8\r\n";
		$headers .= "From: <paradoxxru@list.ru>\r\n"; 
		//$headers .= "Reply-To: reply-to@example.com\r\n";
		if(isset($to) && isset($subject) && isset($message)) {
			mail($to, $subject, $message, $headers);
		}
	}
	// послать письмо продавцу из формы обратной связи
	public function sendMessageToSeller($message, $from) {
		$to = "<paradoxxru@list.ru>";
		$subject = "письмо от посетителя сайта";
		$message = $message . "<br><br> Письмо от ".$from."<br>";
		$headers = "Content-type: text/html; charset=UTF-8\r\n";
		$headers .= "From: <paradoxxru@list.ru>\r\n";
		mail($to, $subject, $message, $headers);
	}
	// смена статуса в корзине на 2(перевод в историю заказа) + добавить дату заказа
	public function changeStatusAndDate() {
		if(!empty($this->user_id)) {
			//сначала получить дату и время(чтобы у всех позиций не отличались секунды)
			$time = time();//определяем время(оно по Гринвичу)
			$time += 3 * 3600; // Добавляем 3 часа к времени по Гринвичу
			$date = date('Y-m-d H:i:s', $time);
			$query = "UPDATE cart SET status = 2, order_data ='".$date."'"
					." WHERE user_id =".$this->user_id." AND status = 1;";
			$this->pdo->query($query);
		}
	}
	// уменьшить кол-во товара в базе(при подтверждении заказа пользователем)
	public function changeCountInGoods() {
		foreach ($this->cart as $key => $value) {
			$count_in_base = $this->getGoodsCount($value['id']);
			$new_count = $count_in_base - $value['count_in_cart'];
			if($new_count >= 0) { 
				$query = "UPDATE goods SET count =".$new_count." WHERE id =".$value['id'].";";
				$this->pdo->query($query);
			}
		}
	}
	
}