<?php
?>
<div class="content-changeSettings">
	<form class="settings" action="/index.php?q=cabinet&cabinet=personal" method="post" enctype="multipart/form-data">
		<div>
			<label for="UserName">Редактировать имя</label>
			<input id="UserName" type="text" name="changeName" value="<?php echo $user->getUserName() ;?>">
		</div>
		<div>
			<label for="UserLogin">Поменять логин</label>
			<input id="UserLogin" type="text" name="changeLogin" value="<?php echo $user->getLogin() ;?>">
		</div>
		<div>
			<div class="user-img">
				<?php
					$avatar = $user->getAvatar();
					if($avatar)
						$src = $avatar;
					else
						$src = 'no_foto2.png';
				?>
				<img src="assets/images/avatars/<?php echo $src ;?>" alt='фото'>
			</div>
			<div class="upload_file">
				<label>
					<input type="file" name="changeAvatar" id="Uploaded" onchange="getFileName ();">
					<span>Сменить аватар</span>
				</label>
				<div>
					<span id="FileName">файл не выбран</span>
				</div>
			</div>
		</div>
		<div>
			<label for="UserPhone">Изменить телефон</label>
			<input id="UserPhone" type="text" name="changePhone" value="<?php echo $user->getPhone() ;?>">
		</div>
		<div>
			<label for="UserMail">Изменить почта</label>
			<input id="UserMail" type="email" name="changeMail" value="<?php echo $user->getMail() ;?>">
		</div>
		<div>
			<label for="UserAddres">Поменять адрес доставки</label>
			<input id="UserAddres" type="text" name="changeAddres" value="<?php echo $user->getAddres() ;?>">
		</div>
		<div>
			<label for="UserPass">Сменить пароль</label>
			<input id="UserPass" type="password" name="changePass" placeholder="**********">
		</div>
		<div>
			<label for="ConfirmPass">Введите действующий пароль</label>
			<input id="ConfirmPass" type="password" name="confirmPass">
		</div>
		<div>
			<label for="RepeatConfirmPass">Повторите ввод пароля</label>
			<input id="RepeatConfirmPass" type="password" name="repeatConfirmPass">
		</div>
		<div class="submit_change">
			<button type="submit">Сохранить изменения</button>
		</div>
	</form>
</div>