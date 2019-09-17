<div class="filter flex-column">
	<b>Фильрация по:</b>
	<ul class="first-level">
		<!--<p>По категории</p>-->
		<li>Категории
		    <ul class="filter__categories">
		    <?php
		        //вызвать getListCategory($goods) - формируем массив категорий
				\app\dataio\CListsBy::getListCategory($arr_goods);
				//подключить шаблон вывода списка по категориям
				$path_to_template = "../app/views/lists/list_by_category.php"; //определили куда выводить
				include($path_to_template); // выводим
		    ?>
		    </ul>
		</li>
	    <!--<p>По стоимости</p>-->
	    <li>Стоимости
		    <?php
				\app\dataio\CListsBy::getListCost($arr_goods); //считаем кол-во товара до 5000тр и от 5000тр
			?>
		    <ul class="filter__cost">
		        <li>
					<a href="/index.php?q=filter&datafilter=cost&filtertype=less&filtervalue=5000" data-price="less-5000">До 5 000р</a>
					<span><?php echo \app\dataio\ClistsBy::$count_cost_less;?></span>
				</li>
				<li>
					<a href="/index.php?q=filter&datafilter=cost&filtertype=more&filtervalue=5000" data-price="more-5000">От 5 000р</a>
					<span><?php echo \app\dataio\ClistsBy::$count_cost_more;?></span>
				</li>
		    </ul>
		</li>
	    <!--<p>По весу</p>-->
	    <li>Весу
		    <?php
				\app\dataio\CListsBy::getListWeight($arr_goods); //считаем кол-во товара до 5 кг и от 5 кг
			?>
		    <ul class="filter_weight">
		        <li>
					<a href="/index.php?q=filter&datafilter=weight&filtertype=less&filtervalue=5000" data-price="less-5000">До 5 кг</a>
					<span><?php echo \app\dataio\ClistsBy::$count_weight_less;?></span>
				</li>
				<li>
					<a href="/index.php?q=filter&datafilter=weight&filtertype=more&filtervalue=5000" data-price="more-5000">От 5 кг</a>
					<span><?php echo \app\dataio\ClistsBy::$count_weight_more;?></span>
				</li>
		    </ul>
		</li>
	    <!--<p>По популярности</p>-->
	    <li>Популярности
		    <?php
				\app\dataio\CListsBy::getListVogue($arr_goods); //считаем кол-во товара до 5 кг и от 5 кг
			?>
		    <ul class="filter_weight">
		        <li>
					<a href="/index.php?q=filter&datafilter=vogue&filtertype=less&filtervalue=5" data-price="less-5000">До 5
					</a>
					<span><?php echo \app\dataio\ClistsBy::$count_vogue_less;?></span>
				</li>
				<li>
					<a href="/index.php?q=filter&datafilter=vogue&filtertype=more&filtervalue=5" data-price="more-5000">От 5
					</a>
					<span><?php echo \app\dataio\ClistsBy::$count_vogue_more;?></span>
				</li>
		    </ul>
		</li>
	</ul>
    <p class="allCatalog"><a href="/index.php?q=catalog">Показать все товары</a></p>
</div>