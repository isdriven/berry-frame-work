<?php
/********************
 * berry frame work
 ********************
 *
 * PHP Frame Work For WEB Application 
 * 
 *   This file is Front Controller
 *
 * @Package    berry frame work
 * @Author     Ippei Sato
 * @License    THE MIT LICENSE
 * @Version    2.0
 * @PHP Ver.   5.2.*
 * @WEB SITE   http://berry.croisfroce.com
 **/

//set path of this file and load core file
define( "BERRY_APP_DIR" , dirname( __FILE__ ) );
include( BERRY_APP_DIR . '/berry/berry.framework.php' );

//load config file and make config instance.
include( BERRY_APP_DIR . '/berry/config.php' );
$config = new defaultConfig();

//start! enjoy!
berryFrameWork( $config );

//program end