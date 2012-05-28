<?php
$styles = "";
$image_styles = "";
$second_styles = "";

$v = (array)$val;

if( isset( $v['v_align'] ) ){
	$second_styles .= "vertial-align:".$v['v_align'].";";
}else{
	$second_styles .= "vertical-align:top;";
}

if( isset( $v['align'] ) ){
	$second_styles .= "text-align:".$v['align'].";";
}else{
	$second_styles .= "text-align:center;";
}

if( isset( $v['width'] ) ){
	$styles .= "width:".$v['width']."px;";
}
if( isset( $v['height'] ) ){
	$styles .= "height:".$v['height']."px;";
}
if( isset( $v['image_width'] ) ){
	$image_styles .= "width:".$v['image_width']."px;";
}
if( isset( $v['image_height'] ) ){
	$image_styles .= "height:".$v['image_height']."px;";
}

?>
<table style="width:100%;">
<tr >
	<?php if( isset( $v['width'] ) ): ?>
	<td style="<?php echo $styles; ?>" >
	<img src="<?php echo $contents; ?>" style="<?php echo $image_styles; ?>" />
	</td>
	<td style="<?php echo $second_styles; ?>" >
	<?php if(isset( $v['second'] ) ){ echo $v['second']; } ?>
	</td>
	<?php else: ?>
	<td>
	<td>
	<img src="<?php echo $contents; ?>" style="<?php echo $styles; ?>" />
	</td>
	<?php endif; ?>
</tr>
</table>