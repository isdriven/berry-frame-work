<div style="width:<?php  echo $constant->width; ?>;border:1px solid #ccc;padding-top:10px;padding-bottom:10px;">
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

