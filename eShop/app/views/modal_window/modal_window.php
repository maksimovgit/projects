<div class="form-entrance flex-column">
	<form class="entrance" method="post" action="index.php">
		<?php
			$str_in = 'Вход';
			if(isset($_GET['registration']) && $_GET['registration'] == 1) {
				$str_in = 'Регистрация';
				echo "
                        <div>
                            <label for='Name'>Имя: </label><input id='Name' type='text' name='name' placeholder='Ведите Имя' required>
                        </div>
                        <div>
                            <label for='Mail'>Почта: </label><input id='Mail' type='email' name='email' placeholder='Ведите почту' required>
                        </div>
                        <div>
                            <label for='Phone'>Телефон: </label><input id='Phone' type='text' name='phone' placeholder='Ведите телефон' required>
                        </div>
                        <div>
                            <label for='Addres'>Адрес: </label><input id='Addres' type='text' name='addres' placeholder='Ведите адрес' required>
                        </div>";
			}

		?>
		<div>
			<label for="login">Логин: </label><input id='login' type="text" name="login" placeholder='Ведите логин' required>
		</div>
		<div>
			<label for="pass">Пароль: </label><input id='pass' type="password" name="password" placeholder='Ведите пароль' required>
		</div>
		<button class="entrance-button"><?php echo $str_in ;?></button>
	</form>
	<div class="exit-button">
		<a href="">Exit</a>
	</div>
</div>