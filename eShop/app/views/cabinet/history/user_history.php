<?php
$history = $cabinet->getHistory();
?>
<h3>История заказов</h3>
<div class="content_userHistory">
	<?php
		foreach ($history as $n => $value) {
			$total_sum = 0;
			$total_weight = 0;
			echo "<span><b>Заказ №".($n+1)."</b> от ".$value[0]['order_data']. "</span>";
			echo "<table class='table_histiry'>
					<tr><th>Вид</th><th>Название</th><th>Описание</th><th>Кол-во</th><th>Цена</th></tr>";
			foreach ($value as $k => $item) {
				$product = new \app\product\CProduct();
				$product->fromArray($item); //заполняем значениями
				$product->render('history_product'); //выводим
				$total_sum += $item['cost']*$item['count_in_cart'];
				$total_weight +=$item['weight']*$item['count_in_cart'];
			}
			echo "<tr class='sum_info'>
					<td colspan=5>Общий вес: ".($total_weight/1000)." кг. Общая стоимость: ".$total_sum." руб.</td>
				</tr>";
			echo "</table><br>";
		}	
	?>
</div>
