<?php
$vl = (array)$val;
?>
<table>
<?php foreach( $vl as $v ){  ?>
<tr>
<td style="width:150px;"><?php echo date( 'n/d G時 i分  ' , $v['ts'] );?></td>
<td><a href="<?php  echo $v['number'] ; ?>" class="n-button"><?php echo $v['name'] ?></a>  <?php echo $v['message'] ;  ?></td>
</tr>
<?php }?>
</table>
