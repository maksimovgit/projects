<?php
//создание объектов CUser, CUserCart и CUserCabinet + отправка письма из формы обратной связи
	include('../app/startData_and_ajax/start_data.php');
?>
<?php
// определяем был ли аякс запрос
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
	//обработчик перехватов аякс запросов от jquery
	include('../app/startData_and_ajax/handling_ajax.php');
}
//если не было аякс запроса, то отдаем всю страницу
else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type"> 
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ESHOP</title>
	<link rel="stylesheet" type="text/css" href="assets/css/main.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous"> 
</head>
<body>
	<div class="header">
		<div class="inner-header">
			<div class="wrapper">
				<?php 
					//подключаем header
					include('../app/views/header/header.php');
				?>
			</div>
		</div>
	</div><!--end class="header"-->
	<!--слайдер - галерея изображений-->
	<section>
		<div class="wrapper slider-border">
			<?php
				//подключение слайдера
				include('../app/views/slider/slider.php');
			?>
		</div>
	</section>
	<!-- секция поиска по запросу пользователя -->
	<section>
		<div class="wrapper">
			<?php
				//подключение секции поиска
				include('../app/views/search_section/search_section.php');
			?>
		</div>
	</section>
	<section>
			<main>
				<div class="wrapper row">
					<section class="filter-section col-2">
						<?php
							//подключаем секцию фильтров
							include('../app/views/filter/filter_section.php');
						?>
					</section>
					<div class="content_sorting col-10">
						<div class="flex-column">
							<section class="sorting-section">
								<?php
									//подключаем секцию сортировок
									include('../app/views/sorting/sorting_section.php');
								?>
							</section>
							<section class="content-section">
								<h1>Результаты поиска по запросу: "<?php echo $request_user ;?>"</h1>
    							<div class="search_results">
    								<h2>Совпадения в названиях товаров:
    									<i> (<?php echo count($search_by_name) ;?>)</i>
    								</h2>
    								<div class="search_results_byname">
    									<?php if(count($search_by_name) > 0) { ?>
    									<table class='table_result_byname'>
											<tr>
												<th>Вид</th>
												<th>Название</th>
												<th>Описание</th>
												<th>Наличие</th>
												<th>Цена</th>
											</tr>
    										<?php
	    										foreach ($search_by_name as $key => $item) {
	    											//искать совпадение в строке, менять строку, перезаписывать элемент
	    											$up = mb_strtoupper($request_user, 'utf-8');
	    											$replace = '<b class="deficit">'.$up.'</b>';
	    											$new_name = preg_replace("/$request_user/ui", 
	    																		$replace, 
	    																		$item['name']);
	    											$item['name'] = $new_name;
	    											$product = new \app\product\CProduct();
										            $product->fromArray($item); 
										            $product->render('search_item'); 
	    										}
    										?>
    									</table>
    									<?php
    									}
    									?>
    								</div>
									<h2>Совпадения в описаниях товаров:
    									<i> (<?php echo count($search_by_descrip) ;?>)</i>
    								</h2>
									<div class="search_results_bydescrip">
										<?php if(count($search_by_descrip) > 0) { ?>
										<table class='table_result_byname'>
											<tr>
												<th>Вид</th>
												<th>Название</th>
												<th>Описание</th>
												<th>Наличие</th>
												<th>Цена</th>
											</tr>
    										<?php
	    										foreach ($search_by_descrip as $key => $item) {
	    											$up = mb_strtoupper($request_user, 'utf-8');
	    											$replace = '<b class="deficit">'.$up.'</b>';
	    											$new_descr = preg_replace("/$request_user/ui", 
	    																		$replace, 
	    																		$item['description']);
	    											$item['description'] = $new_descr;
	    											$product = new \app\product\CProduct();
										            $product->fromArray($item); 
										            $product->render('search_item'); 
	    										}
    										?>
    									</table>
    									<?php
    									}
    									?>
									</div>
    							</div>	
							</section>
						</div><!--end "flex-column"-->
					</div><!-- end "content_sorting"-->
				</div>
			</main>
	</section>
	<!-- форма и контакты -->
	<section class="feedback">
		<div class="wrapper">
			<?php
				//подключаем форму обратной связи и контакты
				include('../app/views/contacts/contacts_section.php');
			?>
		</div>
	</section>
	<div class="footer">
		<?php
			//подключаем footer
			include('../app/views/footer/footer.php');
		?>
	</div>
	<!-- модальное окно - Входа и Регистрации-->
	<div class="overlay" id="entrance">
		<?php
			//подключаем модальное окно
			include('../app/views/modal_window/modal_window.php');
		?>
	</div>

	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="assets/js/script.js"></script>
</body>
</html>
<?php
}