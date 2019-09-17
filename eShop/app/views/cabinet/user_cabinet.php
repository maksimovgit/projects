<div class="cabinet__links">
	<?php
		if(isset($_GET['cabinet']) && $_GET['cabinet'] === 'history') {
			$class_history = 'cabinet__links_history select';
			$class_personal = 'cabinet__links_personal';
		} else {
			$class_history = 'cabinet__links_history';
			$class_personal = 'cabinet__links_personal select';
		}
	?>
	<ul>
		<li class="<?php echo $class_history ;?>">
			<a href="/index.php?q=cabinet&cabinet=history">История покупок</a>
		</li>
		<li class="<?php echo $class_personal ;?>">
			<a href="/index.php?q=cabinet&cabinet=personal">Личные данный</a>
		</li>
	</ul>
</div>
<div class="cabinet__content">
	<?php
		if(isset($_GET['q']) && $_GET['q'] === 'cabinet') {
			if(isset($_GET['cabinet']) && $_GET['cabinet'] === 'history')
				include('../app/views/cabinet/history/user_history.php');
			elseif($_GET['q'] === 'cabinet' || (isset($_GET['cabinet']) && $_GET['cabinet'] === 'personal'))
				if(isset($_GET['settings']) && $_GET['settings'] === 'change')
					include('../app/views/cabinet/personal/change_settings.php');
				else
					include('../app/views/cabinet/personal/personal_data.php');
		}
	?>
</div>