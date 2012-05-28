<div data-role="navbar">
    <ul>
<?php
foreach( $val->nav as $k => $v ){

    echo <<< END
<li><a href="{$constant->_ROOT_}{$v['href']}" data-ajax=false >{$v['name']}</a></li>
END;
}
?>
    </ul>
</div>
