<div class="all-contacts row">
	<!-- левая секция (ввод данных)-->
	<section class="contacts-left_section col-8">
		<form method="POST">					
			<!-- flex контейнер 1.2.a (поля ввода)-->
			<div class="input-fields row">
				<!-- ввод имени-->
				<div class="item-flex">
					<label for="nameField">Name</label>
					<input type="text" name="username" id="nameField" placeholder="введите имя" required> 
				</div>
				<!-- ввод почты-->
				<div class="item-flex">
					<label for="emailField">Email</label>
					<input type="email" name="emailField" id="emailField" placeholder="введите почту" required>
				</div>
				<!-- ввод сообщения -->
				<div class="message">
					<label>Message</label>
					<textarea name="message" id="message" rows="8"></textarea>
				</div>
			</div>
			<!-- flex контейнер 1.2.b (кнопки действий)-->
			<div class="action-button row">
				<div>
					<input class="send-message" type="submit" name="send-message" value="send message">
				</div>
				<div>
					<input class="clear-form" type="reset" value="clear">
				</div>
			</div>
		</form>
	</section>
	<!-- правая секция (контактная информация)-->
	<section class="contacts-right_section col-4">
		<div>
			<div class="row">
				<div class="icon-fa">
					<span class="fas fa-envelope"></span>
				</div>
				<div class="contacts">	
					<h3>Email</h3>
					<a href="">myemail@adress.com</a>
				</div>	
			</div>
		</div>
		<div>	
			<div class="row">
				<div class="icon-fa">
					<span class="fas fa-phone"></span>
				</div>
				<div class="contacts">
					<h3>Phone</h3>
					<span>+7 921 335 95 89</span>
				</div>	
			</div>
		</div>	
		<div>	
			<div class="row">
				<div class="icon-fa">
					<span class="fas fa-home"></span>
				</div>
				<div class="contacts">
					<h3>Adress</h3>
					<span>		
						190025 St-Petersberg
						<br>
							Nevskiy avenue, 36
						<br>
							Russian Federation
					</span>
				</div>	
			</div>
		</div>	
	</section>
</div>