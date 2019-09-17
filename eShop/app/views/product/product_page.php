<?php

?>

<div class="goods__item">
    <span class="goods__item-name">
    	<?php
    	 echo "<b>Название: </b>". $this->name;
    	?>
	</span>
    <img class="goods__item-preview" src="assets/<?php echo $this->img; ?>" alt="">
    <span class="goods__item-cost"><?php echo "<b>Цена: </b>". $this->cost. " руб."; ?></span>
    <span class="goods__item-weight"><?php echo "<b>Вес: </b>". ($this->weight/1000)." кг."; ?></span>
    <span class="goods__item-description"><?php echo "<b>Описание: </b><br>". $this->description; ?></span>
    <span class="goods__item-vogue"><?php echo "<b>Популярность: </b>". $this->vogue; ?></span>
    <span class="goods__item-category"><?php echo "<b>Категория: </b>". $this->category; ?></span>
    <span class="<?php echo $this->class_goods_item_count ;?>"><?php echo "<b>Количество: </b>". $this->count; ?></span>
    <a class="<?php echo $this->class_for_a ;?>" data-action="addtocart" 
        href="/index.php?q=product<?php echo'&id='.$this->id
                                            .'&weight='.$this->weight
                                            .'&cost='.$this->cost
                                            .'&action=addtocart';?>">В корзину</a>
    <a href="/index.php?q=catalog">Назад в каталог</a>
</div>