<?php
$cat = \app\dataio\CListsBy::$categories;
//datafilter - значение по которому сравнивать(категория, стоимость)
//filtervalue - значение с которым сравнивать(конкретная категория, стоимость от/до 1000)
//filtertype - тип фильтра(равно, больше, меньше)
foreach ($cat as $key => $value) {
	//здесь же кодировать get параметр, отвечающий за категории(кириллица) - для Internet Explorer 
	$code_cyrillic = urlencode($key);
	echo "<li>
		<a href='/index.php?q=filter&datafilter=category&filtertype=equal&filtervalue=".$code_cyrillic."'>".$key."</a>
		<span>".$cat[$key] ."</span>
		</li>";
}
?>