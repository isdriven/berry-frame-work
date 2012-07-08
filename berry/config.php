<?php
/*******************************
 * config for berry frame work
 ******************************/

class defaultConfig extends berryConfig
{
    //paths {app} = BERRY_APP_DIR
    public $path_controller = '{app}/berry/controllers';
    public $path_model      = '{app}/berry/models';
    public $path_func       = '{app}/berry/funcs';
    public $path_obj        = '{app}/berry/objs';
    public $path_template   = '{app}/berry/views';

    //rules
    // if 1 , /a/b/c/d   a=b , c=d
    // if 2 , /a/b/c/d   param1 = a , param2 = b , param3 = c
    public $rule_params = 1;

    // ignore or change lists for url
    public $list_ignore = array();
    public $list_change = array();

    //render setting
    public $auto_template      = true;
    public $template_extension = 'php';
    public $output_encoding    = 'utf8';
    public $tags_extension     = 'php';
} 
