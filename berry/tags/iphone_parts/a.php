<?php
$color = ifset( $val->border_color , 'black' );

?>
<a href="<?php echo $val->url ?>" style="padding-top:8px;padding-bottom:8px;display:block;width:310px;margin:2px auto;border:1px solid <?php echo $color ?>;-webkit-border-radius:10px;border-radius:10px;">
    <?php echo $contents; ?>
</a>
