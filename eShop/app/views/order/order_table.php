<h1><span><?php echo $user->getUserName(); ;?></span>, Ваш заказ<?php if(isset($_POST['d'])) echo " принят"; ;?></h1>
	<?php
		//получить массив заказа(он же пока массив корзины)
		$order = $userCart->getCart();
		$classForDelivery = 'normal'; 
		$classForConfirm = 'normal';
	?>
<div class="order">
    <table class="order__table">
        <th>Название</th><th>Характеристики</th><th>Цена</th><th>Кол-во</th><th>Стоимость</th><th>В наличии</th>
		<?php
			foreach ($order as $key => $value) {
				//получить кол-во товара "в наличии"(в виде массива)
				$count = $userCart->getGoodsCount($value['id']);
				if($count < $value['count_in_cart']) {
					echo "<tr class='unchecked'>";
					$classForDelivery = 'opacity';
					$classForConfirm = 'hidden';
				} else {
					echo "<tr>";
				}
				echo "    <td>".$value['name']."</td>
					      <td>Вес: ".($value['weight']/1000)." кг. Популярность: ".$value['vogue']."</td>
					      <td>".$value['cost']."</td>
					      <td>".$value['count_in_cart']."</td>
					      <td>".$value['summ_cost']."</td>
					      <td>".$count."</td>
				      </tr>";
			}
		?>
		<th></th><th></th><th>Всего:</th><th>Вес:</th><th>Общая сумма:</th>
		<tr>
			<td style='border:none'></td><td style='border:none'></td>
			<td><?php echo $userCart->getTotalCount();?> шт.</td>
			<td><?php echo $userCart->getTotalWeight()/1000;?> кг.</td>
			<td><?php echo $userCart->getTotalCost();?> руб.</td>
		<tr>
	</table>
	<?php
		if(empty($_POST['d'])) {
			echo
				'<div class="editOrder">
					<a href="/index.php?q=cart">Редактировать</a>
				</div>
				<div class="delivery ' .$classForDelivery. '">
					<div>
						<h5>Информация о доставке:</h5>
						<p>Самовывоз осуществляется по адресу: СПб, ул. Б. Морская, д.5</p>
						<p>Доставка осуществляется через 1-3 рабочих дня после оплаты</p>
					</div>
					<h5>Выберите способ доставки:</h5>
					<form class="formOrder" method="post" action="/index.php?q=order">
						<div>
							
							<input id="IDdelivery" type="radio" name="d" value="delivery">
							<label class="formOrderLabel" for="IDdelivery">Доставка</label>
							<label for="IDaddres">Укажите адрес доставки</label>
							<input id="IDaddres" type="text" name="addr" placeholder = "'.$user->getAddres().'">
						</div>
						<div>
							<input id="IDpickup" type="radio" name="d" value="pickup" checked>
							<label class="formOrderLabel" for="IDpickup">Самовывоз</label>
						</div>
						<div class="confirmOrder ' .$classForConfirm. '">    
							<button>Подтвердить</button>
						</div>
					</form>
				</div>';
		} else {
			echo "<div>
					<h3>Состав заказа отправлен на указанную вами почту</h3>
				</div>";
				if(isset($_POST['d']) && $_POST['d'] === 'delivery' && empty($_POST['addr'])) {
					$_POST['addr'] = $user->getAddres();
				}
				//посылать письмо продавцу и покупателю о заказе
				if(isset($_POST['d']))
					$userCart->sendMessage($_POST['d'], $_POST['addr']);

				// + уменьшать кол-во товара в базе goods(пока отключаем)
				//$userCart->changeCountInGoods();

				//перевести статус заказа на 2(этим же должна очиститься корзина) + добавить дату заказа
				$userCart->changeStatusAndDate();
		}
	?>
</div>