<?php
$val = (array)$val;

$number = sizeof( $val );
$size = intval( $constant->width / $number );

$result = array();

foreach( $val as $k => $v ){
	$add = '';

	if( isset( $v['src'] ) ){
		$add .= 'background-image:url('.$v['src'].');';
	}
	if( isset( $v['color'] ) ){
		$add .= 'color:'. $v['color'] .';';
	}

	if( $k == $number - 1 ){
		$add .= 'border-right:1px solid #ccc;';
	}
    $result[] = '<td><a href="'. $v['href'] . '" data-ajax=false style="width:' . $size . 'px;display:block;border-top:1px solid #ccc;border-bottom:1px solid #ccc;border-left:1px solid #ccc;padding-top:10px;padding-bottom:10px;color:black;'.$add.'" >' . $v['name'] .'</a></td>';
}

?>
<table>
<tr>
<?php echo implode( "\n" , $result ) ; ?>
</tr>
</table>