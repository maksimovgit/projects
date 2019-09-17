<div id="slider-wrap">
	<ul id="gallery">
		<?php
			$query = "SELECT goods.id, goods.name, goods.img, goods.receipt_data
					  FROM goods
					  WHERE receipt_data > {d '2018-07-01'} AND receipt_data < {d '2019-02-13'}
					  ORDER BY receipt_data;";
			$resalt = $pdo->query($query)->fetchAll();
			foreach ($resalt as $key => $value) {
				echo "<li>
						<a href='/index.php?q=product&id=".$value['id']."'>
							<img src='assets/".$value['img']."'>
						</a>
					</li>";
			}
		?>
	</ul>
</div>
<div id="gallery-controls">
	<a href="#" id="control-prev">
		<img src="assets/images/gallery/prev.png">
	</a>
	<p>НОВЫЕ ПОСТУПЛЕНИЯ</p>
	<a href="#" id="control-next">
		<img src="assets/images/gallery/next.png">
	</a>
</div>