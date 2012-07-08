<?php
$barwidth = 200;
if( $val->process > $val->max ){ $val->process = $val->max; }
$process = intval( ($val->process / $val->max) * $barwidth );
$base = $barwidth - $process;
$path = $constant->_ROOT_ . "images/common/";;
?>
<img src="<?php  echo $path; ?>f1.gif" style="width:<?php echo $process; ?>px;height:20px;" /><img src="<?php echo $path; ?>b2.gif" style="width:<?php echo $base; ?>px;height:20px;" />
