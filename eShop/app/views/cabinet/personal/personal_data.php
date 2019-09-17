<?php
?>
<h3>Личные данные пользователя</h3>
<div class="content_userPersonal">
	<div>
		<?php
			$avatar = $user->getAvatar();
			if($avatar)
				$src = $avatar;
			else
				$src = 'no_foto2.png';
		?>
		<img src="assets/images/avatars/<?php echo $src ;?>" alt='фото'>	
	</div>
	<div class="data_user">
		<span>Имя : <b><?php echo $user->getUserName();?></b></span>
		<span>Логин : <b><?php echo $user->getLogin();?></b></span>
		<span>Почта : <b><?php echo $user->getMail();?></b></span>
		<span>Телефон : <b><?php echo $user->getPhone();?></b></span>
		<span>Адрес доставки : <b><?php echo $user->getAddres();?></b></span>
		<div>
			<br>
			<a href="/index.php?q=cabinet&cabinet=personal&settings=change">РЕДАКТИРОВАТЬ ЛИЧНЫЕ ДАННЫЕ</a>
		</div>
	</div>

</div>