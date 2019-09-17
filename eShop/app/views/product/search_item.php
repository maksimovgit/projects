<?php
?>
<tr>
	<td>
		<a href="/index.php?q=product&id=<?php echo $this->id ;?>"><img src='assets/<?php echo $this->img ;?>'></a>
	</td>
	<td><?php echo $this->name ;?></td>
	<td class="search_descr"><?php echo $this->description ;?></td>
	<td><?php echo $this->count ;?></td>
	<td><?php echo $this->cost." руб." ;?></td>
</tr>