<header>
	<ul class="hdr-list">
		<li>
			<a href="index.php?q=catalog"><img src="assets/images/baraholka6.jpg" alt="logo"></a>
		</li>
		<li class="about">
			интернет-магазин<br/>Для Всех и Каждого
		</li>
		<li>
			<a href="/index.php?q=cart" class="bascket">
				<span id="TotalCount" class="sf18"><?php echo $userCart->getTotalCount();?></span> товаров <br>на сумму 
				<span id="TotalCost" class="sf18"><?php echo $userCart->getTotalCost();?></span> руб.
			</a>
		</li>
	</ul>
	<div class="info-block">
		<ul class="net-icons">
			<li><a href="" class="fab fa-twitter"></a></li>
			<li><a href="" class="fab fa-facebook-f"></a></li>
			<li><a href="" class="fab fa-instagram"></a></li>
		</ul>
		<span class="tel">+7 921 335 95 89</span>
		<ul class="registration">
			<?php
				if($user->isAuth()) {
					$str = $user->getUserName();
					echo "<li><a href='/index.php?q=cabinet' id='UserCabinet'>".$str."</a></li>
					      <li><a href='/index.php?q=catalog&logout=1'>Выход</a></li>";
				}
				else {
					echo '<li><a href="?registration=0#entrance">Войти</a></li>
						<li><a href="?registration=1#entrance">Регистрация</a></li>';
				}
			?>
		</ul>
	</div>
</header>