<?php
if(isset($_POST['action'])) {
	//обработка экшенов
	$userCart->actionsWithCart();
		
	$answer['totalCost'] = $userCart->getTotalCost();
	$answer['totalWeight'] = $userCart->getTotalWeight();
	$answer['totalCount'] = $userCart->getTotalCount();
	$answer['goodsCountInBase'] = $userCart->getGoodsCount($_POST['id']);
	if($_POST['action'] === 'del_element') {
		$answer['count_in_cart'] = 0;
	} else {
		$answer['count_in_cart'] = $userCart->getGoodsCountInCart($_POST['id']);
	}

	$answer = json_encode($answer);
	echo $answer;
		
}