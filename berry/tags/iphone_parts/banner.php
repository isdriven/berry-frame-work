<?php
$val = (array)$val;

$size = ($contents !== "" )? $contents : $constant->width ;

$result = array();
foreach( $val as $k => $v ){
    if( !isset( $v['height'] ) ){
        $v['height'] = 20;
    }
    if( isset( $v['trans'] ) ){
        $trans = "background:-webkit-gradient(linear, left bottom, right bottom,  
            from(rgba(255,255,255,0)),  
            to(#fff));";
        //$trans = "background:-webkit-gradient( liner , left , left , from( rgba( 0 , 0 , 0 , 0 ) ) , to( rgba( 0 , 0 , 0 , 1 ) ) )";
    }else{
        $trans = "";
    }
    if( !isset( $v['text'] ) ){
        $v['text'] = "";
    }

    $res = '<a href="'. $v['href'] . '" data-ajax=false style="height:'.$v['height'].'px;width:' . $size . 'px;display:block;border-bottom:1px solid #ccc;border-left:1px solid #ccc;padding-top:0px;padding-bottom:0px;color:black;';

	if( isset( $v['src'] ) ){ 
		$res .= 'background-image:url('.$v['src'].');';
	}
	$res .= 'background-repeat:no-repeat;" ><div style="width:100%;height:100%;'.$trans.';"><table style="width:100%;height:100%;"><tr><td style="font-size:15px;vertical-align:middle;text-align:right;">'.$v['text'].'</td></tr></table></div></a>';

	$result[] = $res;
}

?>
<table>
<tr>
<td style="<?php  echo $size; ?>px;">
<?php echo implode( "\n" , $result ) ; ?>
</td>
</tr>
</table>