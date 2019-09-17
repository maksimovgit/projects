<?php
	$user = new \app\auth\CUser($pdo);
    if(isset($_GET['logout'])) //если нажали выход, то logout
        $user->logout();
    //создаем объект-корзину
    $userCart = new \app\dataio\CUserCart($pdo,$user->getLogin(),$user->getUserId());

    //создать объект "личный кабинет пользователя"
    $cabinet = new \app\dataio\CUserCabinet($pdo,$user->getUserId());

	//если поля формы обратной связи заполненны, то отправить письмо продавцу
    if(
        isset($_POST['send-message']) 
        && isset($_POST['emailField']) 
        && isset($_POST['username'])
        && $_POST['send-message'] == 'send message'
    ) {
        $userCart->sendMessageToSeller($_POST['message'], $_POST['emailField']);
    }

