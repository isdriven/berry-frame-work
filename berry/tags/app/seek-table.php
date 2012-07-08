<?php
$vl = (array)$val;
?>
<table id="e-seek" >
<?php 
	$h_number = 0;
$size = sizeof( $vl );
foreach( $vl as $v ){  
	if( $h_number == 0 ){
		echo "<tr>";
	}
?>
<td><div class="seek-window" style="background-image:url(http://www.you-you-kan.jp/download/b02_200_200.jpg)"></div></td>
<?php 
		$h_number++;
	if( $h_number > 2 ){
		echo "</tr>";
		$h_number = 0;
	}
}?>
<?php
if( $h_number !== 0 ){
	echo "</tr>";
}?>
</table>
