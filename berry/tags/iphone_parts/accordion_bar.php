<?php
$v = (array)$val;
if( !isset( $v['speed'] )){
    $v['speed'] = 500;
}
if( !isset( $v['contents'] ) ){
    $v['contents'] = "";
}

$add = "";

if( isset( $v['bg'] ) ){
	$add .= "background-color:".$v['bg'] . ";" ;
}

if( isset( $v['color'] ) ){
	$add .= "color:".$v['color'] .";" ;
}

if( isset( $v['src'] ) ){
	$add .= "background-image:url(".$v['src'].");";
}

?>
<div onclick="$(this).next().toggle( <?php echo $v['speed'];?> )" style="width:<?php  echo $constant->width; ?>;border:1px solid #ccc;padding-top:10px;padding-bottom:10px;<?php echo $add; ?>">
     <?php 
     if( !isset( $val->size ) ){
         $val->size = 'M' ;
     }
     if( $val->size == 'L' ){
         echo '<span style="font-size:17px;" >' . $contents . '</span>'; 
     }else if( $val->size == 'M' ){
         echo '<span style="font-size:14px;" >' . $contents . '</span>'; 
     }else if( $val->size == 'S' ){
         echo '<span style="" >' . $contents . '</span>'; 
     }
?>
</div>
<div style="display:none;">
         <?php  echo $v['contents'] ; ?>
</div>

