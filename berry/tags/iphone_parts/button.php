<?php
if( !isset( $val->ajax ) ){
    $val->ajax = false;
}
if( !isset( $val->href ) ){
    $val->href = "";
}
?>
<a href="<?php echo $val->href; ?>" data-role="button" <?php if( $val->ajax === false ){ echo "data-ajax=false";} ?> > <?php echo $contents; ?></a>
