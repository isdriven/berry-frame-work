<?php
$val = (array)$val;

$size = ($contents !== "" )? $contents : 100 ;

$result = array();
foreach( $val as $k => $v ){
    if( isset( $v['height'] ) ){
        $pad = intval( ( $v['height'] - 10 ) / 2 );
        if( $pad < 0 ){
            $pad = 1;
        }
    }else{
        $pad = 5;
    }
	$add = '';

	if( isset( $v['src'] ) ){
		$add .= 'background-image:url('.$v['src'].');';
	}
	if( isset( $v['color'] ) ){
		$add .= 'color:'.$v['color'].';';
	}

    if( $k == 0 ){
        $result[] = '<a href="'. $v['href'] . '" data-ajax=false style="width:' . $size . 'px;display:block;border-top:1px solid #ccc;border-bottom:1px solid #ccc;border-left:1px solid #ccc;border-right:1px solid #ccc;padding-top:'.$pad.'px;padding-bottom:'.$pad .'px;color:black;'.$add.'" >' . $v['name'] .'</a>';
    }else{
        $result[] = '<a href="'. $v['href'] . '" data-ajax=false style="width:' . $size . 'px;display:block;border-bottom:1px solid #ccc;border-left:1px solid #ccc;border-right:1px solid #ccc;padding-top:'.$pad.'px;padding-bottom:'.$pad.'px;color:black;'.$add.'" >' . $v['name'] .'</a>';
    }
}

?>
<table>
<tr>
<td style="<?php  echo $size; ?>px;">
<?php echo implode( "\n" , $result ) ; ?>
</td>
</tr>
</table>