<?php
session_start();
/*************************
 * berry frame work core files
 *
 *  this file provides all of frame work.
 * 
 *  @Version   3.0
 *  @Update    2012.07
 *  @Package   berry frame work
 *  @Author    Ippei Sato
 ************************/
include( 'riseTemplate.php' );
include( 'riseTemplateLibrary.php' );

function berryFrameWork( $config )
{
    if( !is_object( $config ) || !is_subclass_of( $config ,'berryConfig' ) ){
        exit('berry frame work takes an Object, which extended from "berryConfig" ');
    }
    if( !$config->checkPath() ){
        exit('berry frame work needs config paths');
    }
    $config->refinePath();
    $change_list = null;
    $ignore_list = ( is_array( $config->list_ignore ) )? $config->list_ignore:array();
    $change_list = ( is_array( $config->list_change ) )? $config->list_change:array();
    $name_rules   = ( is_null( $config->rule_params ) ) ? 1 : $config->rule_params;

    $view = new riseTemplate();
    $library = new riseTepmlates();
    $view->setLibrary( $library );

    berry::save('controller_path', $config->path_controller);
    berry::save('func_path',  $config->path_func);
    berry::save('model_path', $config->path_model);
    berry::save('obj_path', $config->path_obj);
    berry::save('func',new berryfuncs());
    berry::save('obj',new berryobjs());
    berry::save('model',new berrymodels());
    berry::save('view' , $view);
    berry::save('template_path', $config->path_template);
    berry::save('name_params', $name_rules);
    berry::save('ignore_list',$ignore_list);
    berry::save('change_list',$change_list);
    berry::save('config',$config);
    berry::routing();
}

/***********************
 * config class
 **********************/
class berryConfig
{
    public function checkPath()
    {
        return ( $this->path_controller && 
                 $this->path_func &&
                 $this->path_model &&
                 $this->path_obj && 
                 $this->path_template );
    }

    public function refinePath()
    {
        $list = get_object_vars( $this );
        while( list( $k , $v ) = each( $list ) ){
            if( strpos( $k , 'path_' ) === 0 ){
                $this->{$k} = str_replace( '{app}' , BERRY_APP_DIR , $v );
            }
        }
    }

    public function init()
    {

    }
}

/************************
 * core class
 ***********************/
class berry
{
    static private $instance;
    private $list;
    private $memory = null;
    private function  __construct(){}
    static public function me()
    {
        if(!is_object(berry::$instance)){ berry::$instance=new berry();}
        return berry::$instance;
    }
    public function _save($key,$value)
    {
        if( !is_string( $key ) ){ return false; };
        if( is_null( $this->memory ) ){ $this->memory = array(); }
        $this->memory[$key] = $value;
    }
    public function _load($key)
    {
        return ( isset($this->memory[$key])) ? $this->memory[$key] : false;
    }
    public function _push( $val, $list_name )
    {
        if( !is_string( $val ) and !is_integer( $val ) ){ return false; }
        if( $this->list and in_array( $val , $this->list )){ return true; }
        if( !is_array( $this->list ) ){
            $this->list = array();
        }
        if( !isset( $this->list ) or !isset( $this->list[$list_name] ) or !is_array( $this->list[$list_name] ) ){
            $this->list[$list_name] = array();
        }
        $this->list[$list_name][$val] = 1;
    }

    public function _is( $val ,$list_name)
    {
        if( isset( $this->list ) and isset( $this->list[$list_name] ) and isset( $this->list[$list_name][$val] ) and $this->list[$list_name][$val]==1 ){ return true; }
        return false;
    }

    static public function save($key,$value)
    {
        $berry = berry::me();
        $berry->_save($key,$value);
    }

    static public function load($key)
    {
        $berry = berry::me();
        return $berry->_load($key);
    }

    public function _look($list_name)
    {
        if( $this->list and $this->list[$list_name]){
            $ls = $this->list[$list_name];
            while( list( $k, $v ) = each( $ls ) ){
                $res[] = $k;
            }
            return $res;
        }
        return array();
    }

    static public function push( $val , $list_name)
    {
        $berry = berry::me();
        $berry->_push( $val ,$list_name);
    }

    static public function is( $val ,$list_name)
    {
        $berry = berry::me();
        return $berry->_is( $val ,$list_name);
    }

    static public function look($list_name)
    {
        $berry = berry::me();
        return $berry->_look($list_name);
    }

    static public function refineUri( $uri )
    {
        $str_ignore = array('index.php','index.html','gadgets.xml','index.cgi');
        $str_ignore = array_merge( $str_ignore , berry::load('ignore_list') );
        $uri = str_replace( $str_ignore , '' , $uri );
        $str_change = array('.html','.php','.cgi','.xml');
        $str_change = array_merge( $str_change , berry::load('change_list') );
        $uri = str_replace( $str_change , '/', $uri );

        $nowdir = str_replace('index.php','',$_SERVER['SCRIPT_NAME']);
        if($nowdir !== '/'){
            $uri = str_replace($nowdir,'',$uri);
        }
        if(strpos($uri,'?') !== False){
            $uris = explode('?',$uri);
            $uri = $uris[0];
        }
        $uri = explode('/',trim($uri,'/'));
        return array( $uri , $nowdir );
    }

    static public function elict( $uri )
    {
        if($uri[0]){
            $controller = str_replace('-','',$uri[0]);
        }
        else{
            $controller = "index";
        }
        if( isset( $uri[1] ) ){
            $action = str_replace('-','',$uri[1]);
        }
        else{
            $action = "index";
        }
        $userparams = array();
        $count = 2;

        if( berry::load('name_params') == 1){
            while( isset( $uri[$count] ) ){
                if( !isset( $uri[$count+1] )){
                    $uri[$count+1] = "";
                }
                $userparams[$uri[$count]] = $uri[$count+1];
                $count += 2;
            }
        }else{
            while(isset($uri[$count])){
                $userparams["param".($count-1)] = $uri[$count];
                $count++;
            }
        }
        return array( $controller , $action , $userparams);
    }

    static public function target( $controller , $action )
    {
        $controllerdir = berry::load('controller_path');
        $templatedir = berry::load('template_path');
        $controller_file = "{$controllerdir}/{$controller}Controller.php";
        $controller_klass = "{$controller}Controller";
        $action_method = "{$action}Action";
        return array( $controller_file , $controller_klass , $action_method );
    }

    static public function tryPath( $file , $klass ,  $controller = null , $action = null)
    {
        if( !is_file( $file ) ){
            return false;
        }

        if( !berry::is( $file , 'installed::file' ) ){
            include( $file );
            berry::push( $file , 'installed::file');
        }

        if( !class_exists( $klass ) ){
            return false;
        }

        $object = new $klass();

        if( !method_exists( $object , "{$action}Action" ) ){
            return false;
        }

        if( !is_null( $controller ) ){
            $object->_name_controller = $controller;
        }
        if( !is_null( $action ) ){
            $object->_name_action = $action;
        }

        return $object;
    }

    static public function exe( $obj , $method , $init = true )
    {

        $controller = $obj->_name_controller;
        $action     = $obj->_name_action;

        if( $init and method_exists( $obj , 'init' ) ){
            $obj->init();
        }
        if( $init and method_exists( $obj , 'initAction' ) ){
            $obj->initAction();
        }
        $obj->{$method}();

        $obj->_name_controller = $controller;
        $obj->_name_action     = $action;
        return $obj;
    }

    static public function render( $obj )
    {
        $auto_template      = ( $obj->config->auto_template )? $obj->config->auto_template:true;
        $output_encoding    = ( $obj->config->output_encoding )? $obj->config->output_encoding:'utf8';
        $template_extension = ( is_string($obj->config->template_extension) )? $obj->config->template_extension:'html';

        if( $auto_template ){
            $template_file = "{$obj->_name_controller}/{$obj->_name_action}.{$template_extension}";


            if( !is_file( berry::load('template_path') . '/' . $template_file ) ){ return false; }

            $output  = $obj->view->render( $obj->config->path_template . '/' . $template_file );

            if( $output_encoding !== 'utf8' ){
                $output = mb_convert_encoding( $output , $output_encoding , 'utf8' );
            }
        }
        return $output;
    }

    static public function realError()
    {
        header("HTTP/1.1 404 Not Found");
        exit();
    }

    static public function routing()
    {
        if(!$_SERVER['SCRIPT_NAME'] or !$_SERVER['REQUEST_URI']){
            exit("berry frame work cannot work on this server.see http://berry.croisforce.com/manual/requipment/");
        }
        $uri = $_SERVER['REQUEST_URI'];

        list( $uri , $nowdir )= self::refineUri( $uri );
        list( $controller , $action , $userparams ) = self::elict( $uri );
        $params = array_merge($userparams,$_GET,$_POST);

        $view = berry::load('view');
        $view->set->_ROOT_ = $nowdir ;
        $view->set->userparams = $userparams;
        $view->set->params = $params;
		
        berry::save('_ROOT_',$nowdir);
        berry::save('userparams',$userparams);
        berry::save('params',$params);

        list( $file, $klass , $method ) = self::target( $controller , $action );
        $obj = self::tryPath( $file , $klass , $controller , $action );

        if( !is_object( $obj ) ){
            list( $file , $klass , $method ) = self::target( 'error' , 'index' );
            $obj = self::tryPath( $file , $klass , 'error', 'index' );
        }
        if( !is_object( $obj ) ){
            self::realError();
            exit();
        }
        self::exe( $obj , $method );
        echo self::render( $obj );
     }

    static public function choice( $path , $list )
    {
        if( !is_string( $path ) ){ return "";}
        if( $path == '' and !is_null( $list )){  return $list; }
        if( is_null( $list ) ){ return false; }
        if( !is_array( $list ) ){ return $list; }
        $path = explode( ' ' , $path );
        $target = array_shift( $path );
        $next = implode( '.' , $path );
        $res = array();
        switch( self::isWhat( $target ) ){
        case 'wildcard':
            while( list( $k, $v ) = each( $list ) ){
                $result =  berry::choice( $next , $v );
                if( $result !== false){ $res[$k] = $result;}
            }
            return $res;
            break;

        case 'string':
            if( $target == '0' ){ $target = 0;}
            else if( intval( $target ) !== 0 ){ $target = intval( $target ); }
            $result =  berry::choice( $next , $list[$target] );
            return $result; 
            break;

        case 'regxp':
            while( list( $k, $v ) = each( $list ) ){
                if( preg_match( $target , $k ) == 1 ){
                    $result = berry::choice( $next , $v );
                    if( $result !== false ){ $res[$k] = $result; } 
               }
            }
            return $res;
            break;

        case 'flow':
            $target = trim( $target , '*');
            while( list( $k,$v ) = each( $list ) ){
                if( strpos( $k , $target) !== false ){
                    $result = berry::choice( $next , $v );
                    if( $result !== false ){$res[$k] = $result;}
                }
            }
            return $res;
            break;

        case 'bottomflow':
            $target = trim( $target , '*' );
            while( list( $k,$v ) = each( $list ) ){
                if( strpos( $k , $target ) === 0 ){
                    $result = berry::choice( $next , $v );
                    if( $result !== false ){$res[$k] = $result;}
                }
            }
            return $res;
            break;

        case 'topflow':
            $target = trim( $target , '*' );
            while( list( $k,$v ) = each( $list ) ){
                $tlength = strlen( $target );
                $offset = strlen( $k ) - $tlength;
                if( strpos( $k , $target ) === $offset ){
                    $result = berry::choice( $next , $v );
                    if( $result !== false ){$res[$k] = $result;}
                }
            }
            return $res;
            break;
        }
    }

    static public function isWhat( $str )
    {
        if( $str == '*'){ return 'wildcard';}
        else if( $str[0] == '/' and $str[strlen($str)-1] =='/'){ return 'regxp';}
        else if( $str[0] == '*' and $str[strlen($str)-1] == '*'){ return 'flow';}
        else if( $str[0] == '*'){ return 'topflow';}
        else if( $str[strlen($str)-1] == '*'){ return 'bottomflow';}
        else { return 'string'; }
    }
}

/*******************
 *controller
 ******************/
class berryController
{
    var $model,$func,$obj,$_ROOT_,$userparam,$param,$option;
    public function __construct()
    {
        $this->view       =  berry::load('view');
        $this->model      =  berry::load('model');
        $this->func       =  berry::load('func');
        $this->obj        =  berry::load('obj');
        $this->_ROOT_     =  berry::load('_ROOT_');
        $this->userparams =  berry::load('userparams');
        $this->params     =  berry::load('params');
        $this->config     =  berry::load('config');

        $this->config->controller = $this;
        $this->config->init();
    }
    public function redirect($path)
    {
        header("Location:".$path);
        exit();
    }
    function forward($path = null)
    {
        if( is_null( $path ) ) { return false; }

        if( strpos( $path , '/' )===false ){
            $path = $this->_name_controller . '/'. $path;
        }

        list( $uri , $nowdir )= berry::refineUri( $path );
        list( $controller , $action , $userparams ) = berry::elict( $uri );

        $userparams = array_merge( $this->userparams , $userparams );

        $params = array_merge($userparams,$_GET,$_POST);

        $view = berry::load('view');
        $view->constant( '_ROOT_' , $nowdir );
        $view->constant( 'userparams' , $userparams );
        $view->constant( 'params' , $params );

        berry::save('_ROOT_',$nowdir);
        berry::save('userparams',$userparams);
        berry::save('params',$params);

        list( $file, $klass , $method ) = berry::target( $controller , $action );
        $obj = berry::tryPath( $file , $klass , $controller , $action );

        if( !is_object( $obj ) ){
            list( $file , $klass , $method ) = berry::target( 'error' , 'index' );
            $obj = berry::tryPath( $file , $klass , 'error', 'index' );
        }
        if( !is_object( $obj ) ){
            berry::realError();
            exit();
        }
        berry::exe( $obj , $method , false );
        echo berry::render( $obj );
        exit();
    }

    public function getInstalled()
    {
        $res = array();
        $res['func'] = berry::look('installed::func');
        $res['model'] = berry::look('installed::model');
        $res['obj'] = berry::look('installed::obj');
        return $res;
    }

    public function isInstalled( $name , $as_)
    {
        if( $as_ !== 'func' and $as_ !== 'model' and $as_ !== 'obj'){
            return false;
        }
        return berry::is( $name , "installed::{$as_}");
    }

    public function getParam( $name , $default = null)
    {
        if( isset( $this->params[ $name ] ) ){
            return $this->params[ $name ] ;
        }else if( isset( $default ) ){
            return $default;
        }
        return false;
    }

    public function isParam( $name , $expect ) // 
    {
        if( isset( $this->params[ $name ] ) ){
            return $this->params[ $name ]  == $expect;
        }
        return false;
    }

    public function hasParam( $name ) // 
    {
        return isset( $this->params[ $name ] );
    }

    public function isCriticalParam( $names , $message ) // 
    {
        if( is_array( $names ) ){
            foreach( $names as $v ){
                if( !$this->hasParam( $v ) ){
                    exit( $message );
                }
            }
        }else{
            if( !$this->hasParam( $names ) ){
                exit( $message );
            }
        }
        return true;
    }

    public function render( $file_name = null , $output = true , $end = true)
    {
        if( is_null( $file_name ) ){
            return false;
        }
        $file_name = $this->config->path_template . '/' . $file_name . '.' . $this->config->template_extension;

        if( !file_exists( $file_name ) ){
            exit('no file for render :'. $file_name );
        }
        $buffer = $this->view->render( file_get_contents( $file_name ) );

        $output_encoding    = ( $this->config->output_encoding )? $this->config->output_encoding:'utf8';

        if( $output ){
            if( $output_encoding !== 'utf8' ){
                $buffer = mb_convert_encoding( $output , $output_encoding , 'utf8' );
            }
            echo $buffer;
        }else{
            return $buffer;
        }
        if( $end ){
            exit();
        }
    }
}

/*************
 *func
 ************/
class berryfuncs
{
    function __construct()
    {
        $this->path=berry::load('func_path');
    }

    function __get( $name )
    {
        return $this->load( $name );
    }
    
    function load($functionsname,$as_name = null)
    {
        $real_name = $functionsname;
        if( !is_null( $as_name ) ){
            $functionsname = $as_name;
        }

        if( isset( $this->{$functionsname} ) and is_object($this->{$functionsname})){
            return $this->{$functionsname};;
        }

        $funcName="{$real_name}Func";
        if(file_exists($this->path."/{$funcName}.php")){
            if( !berry::is( $real_name , 'installed::func') ){
                include($this->path."/{$funcName}.php");
            }
        }else{
            return false;
        }
        $this->{$functionsname}             = new $funcName();
        $this->{$functionsname}->view       = berry::load('view');
        $this->{$functionsname}->func         =berry::load('func');
        $this->{$functionsname}->params     = berry::load('params');
        $this->{$functionsname}->userparams = berry::load('userparams');
        $this->{$functionsname}->_ROOT_     = berry::load('_ROOT_');
        $res = array();
        berry::push( "$real_name" , 'installed::func');
        return $this->{$functionsname};
    }
}

/****************
 *model
 ***************/
class berrymodels
{
    function __construct()
    {
        $this->path = berry::load('model_path');
    }

    function __get( $name )
    {
        return $this->load( $name );
    }
    
    function load($dbname, $as_name = null)
    {
        $real_name = $dbname;
        if( isset( $as_name ) ){
            $dbname = $as_name ;
        }

        $modelName="{$real_name}Model";
        if(isset( $this->{$dbname} ) and is_object($this->{$dbname})){
            return $this->{$dbname};
        }
        if( file_exists($this->path."/{$modelName}.php")){
            if( !berry::is( $real_name , 'installed::model' ) ){
                include($this->path."/{$modelName}.php");
            }
        }else{
            return false;
        }
        $this->{$dbname} = new $modelName();
        berry::push( "$real_name" ,'installed::model');
        return $this->{$dbname};
    }
}

class dbclass
{
    var $passwd, $user, $host, $port, $handle, $result, $history;
    protected function __construct()
    {
        if(!$this->host || !$this->user || !$this->passwd){
            throw new berryDataBaseError("connection faild");
        }
        $this->plug();
    }

    private function plug()
    {
        if($this->port!==null){
            $this->hostname=$this->host.":".$this->port;
        }else{
            $this->hostname=$this->host;
        }
        
        try{
            $this->handle=mysql_connect($this->hostname,$this->user,$this->passwd);
        }
        catch(Exception $e){
            throw new berryDataBaseError("connection faild");
            exit();
        }
        $this->passwd="*****";
        $this->host="*****";
        $this->user="*****";
        $this->connectioned=true;
    }

    public function implode( $separator , $list )
    {
        while( list( $k, $v ) = each( $list ) ){
            if( $v == 'NOW()' || $v == 'now()' ){
                $str = " {$k} = NOW() ";
            }else{
                $str = " {$k} = :? ";
            }
            $ret[] = $this->format( $str , array( $v ) );
        }
        return implode( $separator , $ret ); 
    }

    public function query()
    {
        if(!$this->connectioned){
            return false;
        }
        
        $args=func_get_args();
        
        if(empty($args)){
            throw new berryDataBaseError("NO QUERY FOR berrydb::query");
        }

        list(,$str)=each($args);
        
        if(func_num_args()==1){
            $this->result=mysql_query($str,$this->handle);
            $this->history($str);            
            
            if(mysql_error()!==""){
                $this->outputErrors(mysql_error(),$str);
            }
            return $str;
        }
        
        array_shift($args);
        $str=$this->format($str,$args);
        $this->result=mysql_query($str,$this->handle);
        if(mysql_error()!==""){
            $this->outputErrors(mysql_error(),$str);
        }
        $this->history($str);
    }
    
    private function format($str,$array)
    {
        $num=0;
        while(list(,$v)=each($array)){
            if($this->is_num($v)){
                $v=intval($v);
            }else{
                $v='"'.$this->esc($v).'"';
            }
            $rev[]=$v;
        }
        $qu=explode(':?',$str);
        $size=sizeof($qu);
        $ret="";

        if( $size == 1 ){
            return $str;
        }

        while(list($k,$v)=each($qu)){
            $ret.=$v;
            if(!empty($rev)){
                $ret.=array_shift($rev);
            }
        }
        return $ret;
    }
    
    public function res()
    {
        return $this->result;
    }
    
    public function expand()
    {
        $res=$this->result;
        
        if(!is_resource($res)){
            return false;
        }
        
        $ret=array();
        while($row=mysql_fetch_assoc($res)){
            $ret[]=$row;
        }
        return $ret;
    }

    public function esc($str)
    {
        return mysql_real_escape_string($str,$this->handle);
    }

    protected function is_num($v)
    {
        if( $v === "" ){ return false; }
        $v=preg_replace('/[0-9]/','',$v);
        if($v===""){
            return true;
        }
        return false;
    }
    
    public function outputErrors($mysqlerror,$str)
    {
        $format="<table style='border:1px solid #ccc'><tr style='border:1px solid #ccc;'><th><b>berryDbError Summary</b></th><td></td><td></td></tr><tr style='border:1px solid #ccc'><th>mysql_error()</th><td>%s</td><td></td></tr><tr style='border:1px solid #ccc;'><th>Query</th><td>%s</td><td></td></tr></table>";
        $message=sprintf($format,$mysqlerror,$str);
        exit($message);
    }

    public function showHistory()
    {
        $history=explode(';',$this->history);
        if(!empty($history)){
            $format="<table style='border:1px solid #ccc;'><tr><th>Query History</th><td></td></tr>";
            $body="";
            while(list(,$v)=each($history)){
                if(strpos($v,'set names ')!==false){
                    $encode=str_replace('set names ','',$v);
                    continue;
                }
                if(strpos($v,'use ')!==false){
                    $tablename=str_replace('use ','',$v);
                    continue;
                }
                $body.="<tr><th></th><td>{$v}</td></tr>";
            }
            $format.="<tr><th></th><td>encode:{$encode}<br />table:{$tablename}</td></tr>";
            $format.=$body;
            echo $format;
        }else{
            echo "no history";
        }
    }

    public function history($log)
    {
        $this->history.=$log.";\n\r";
    }

    public function select( $table , $list = array() , $target = '*' ) // normal select
    {
        if( empty( $list ) ){ return false; }
        $this->query( sprintf( "select %s from %s where %s" , $target , $table , $this->implode( ' and ' , $list ) ) );
        return $this->expand();
    }

}

class berryModel extends dbclass
{
    public function __construct()
    {
        parent::__construct();
        $this->query('set names utf8');
        if($this->dbname){
            $this->query("use {$this->dbname}");
        }
    }
}

/************
 *obj
 ***********/
class berryobjs
{
    public function __construct()
    {
        $this->path=berry::load('obj_path');
    }

    public function __get( $name )
    {
        return $this->load( $name );
    }

    public function load($objectsname , $as_name = null)
    {
        $real_name = $objectsname;

        if( !is_null( $as_name ) ){
            $objectsname = $as_name ;
        }
        $objName="{$real_name}Obj";
        if( isset( $this->{$objectsname} ) and is_object($this->{$objectsname})){
            return true;
        }
        if( file_exists($this->path."/{$objName}.php")){
            if( !berry::is( $real_name , 'installed::obj' ) ){
                include($this->path."/{$objName}.php");
            }
        }else{
            return false;
        }
        $this->{$objectsname}             =new $objName();
        $this->{$objectsname}->view       =berry::load('view');
        $this->{$objectsname}->params     =berry::load('params');
        $this->{$objectsname}->userparams =berry::load('userparams');
        $this->{$objectsname}->_ROOT_     =berry::load('_ROOT_');
        $this->{$objectsname}->func       =berry::load('func');
        $this->{$objectsname}->model      =berry::load('model');
        $this->{$objectsname}->obj        =berry::load('obj');
        berry::push( "$real_name" , 'installed::obj' );
        if(method_exists($this->{$objectsname},"init")){
            $this->{$objectsname}->init();
        }
        return $this->{$objectsname};
    }
}

/****************
 *Debug Fuctions
 ***************/
function d()
{
    $args = func_get_args();
    ob_start();
    foreach( $args as $k=>$v ){
        var_dump( $v );
    }
    $buffer = ob_get_clean();
    echo "<pre style='text-align:left;' >$buffer</pre>";
}

function d_input()
{
    d(array('GET'=>$_GET,'POST'=>$_POST));
}

/*___ version 0.2 < ___*/

function choice( $path , $list)
{
    return berry::choice( $path , $list );
}

function ifset()
{
    $args = func_get_args();
    while( list( $k , $v ) = each( $args ) ){
        if( isset( $v ) ){ return $v; }
    }
}

function cond( $condition , $val1 , $val2 )
{
    if( $condition ){ return $val1; }
    else{ return $val2; }
}

class berrylist
{
    static private $_list;
    static public function create( $name = null )
    {
        if( is_null( $name ) ){
            return new berryIterator();
        }else{
            if( !isset( self::$_list[$name] ) || !is_object( self::$_list[$name] ) ){
                self::$_list[$name] = new berryIterator();
            }
            return self::$_list[$name];
        }
    }

    static public function c( $name = null )
    {
        return self::create( $null );
    }
}

class bl extends berrylist
{
}

class berryIterator implements Iterator
{
    protected $_items = array(); 
    protected $_flags;
    protected $_counter = 0;

    public function set()
    {
        $args = func_get_args();
        while( list( $k , $v ) = each( $args ) ){
            if( is_array( $v ) ){
                while( list( , $vv ) = each( $v ) ){
                    $this->push( $vv );
                }
            }
        }
        return $this;
    }

    public function flip( $k , $v )
    {
        if( isset( $this->_items[$k] ) ){
            $this->_items[$k] = $v;
        }
    }

    public function push()
    {
        $args = func_get_args();
        while( list( $k, $v ) = each( $args ) ){
            $this->_push( $v );
        }
        return $this;
    }

    public function unshift()
    {
        $args = func_get_args();
        while( list( $k, $v ) = each( $args ) ){
            $this->_unshift( $v );
        }
    }

    protected function _push( $val )
    {
        $this->_items[$this->_counter] = $val;
        if( is_string( $val ) || is_integer( $val ) ){
            $this->_flags[$val] = 1;
        }
        $this->_counter++;
    }

    protected function _unshift( $val )
    {
        array_unshift( $this->_items , $val );
        $this->_counter++;
    }

    public function has( $name = null )
    {
        if( !isset( $name ) ){ return false; }
        if( !is_string( $name ) && !is_integer( $name ) ){
            return false;
        }
        return ( $this->_flags[$name] == 1 );
    }

    public function size()
    {
        return $this->_counter;
    }
    
    public function shuffle()
    {
        shuffle( $this->_items );
        return $this;
    }

    public function reverse()
    {
        $this->_items = array_reverse( $this->_items );
        return $this;
    }

    public function slice( $start = null , $length = null )
    {
        if( is_null( $start ) ){ $start = 0; }
        if( $this->_counter < $start ){ return false;  }
        if( $start < 0 ){ $start = $this->_counter + $start; }
        if( is_null( $length ) || ( $start + $length > $this->_counter ) ){
            $last = $this->_counter; 
        }else{
            $last = $start + $length;
        }
        $ret = berrylist::create();
        for( $i = $start; $i < $last ; $i++){
            $ret->push( $this->_items[$i] );
        }
        return $ret;
    }

    public function get( $offset = null )
    {
        if( $offset < 0 ){ $offset = $this->_counter + $offset; }
        if( is_null( $offset ) ){ return false; }
        if( isset( $this->_items[$offset] ) ){ return $this->_items[$offset]; }
        return false;
    }

    public function pop()
    {
        if( $this->_counter > 0 ){
            $this->_counter--;
            return array_pop( $this->_items );
        }
        return false;
    }

    public function shift()
    {
        if( $this->_counter > 0 ){
            $this->_counter--;
            return array_shift( $this->_items );
        }
        return false;
    }

    public function join( $separator )
    {
        return implode( $separator , $this->_items );
    }

    //--Iterator methods--
    public function rewind()
    {
        reset( $this->_items );
    }

    public function current()
    {
        return current( $this->_items );
    }

    public function key()
    {
        return key( $this->_items );
    }

    public function next()
    {
        return next( $this->_items );
    }

    public function valid()
    {
        return $this->current() !== false ;
    }

    public function each()
    {
        $this->k = $this->key();
        $this->v = $this->current();
        $ret = $this->valid();
        $this->next();
        return $ret;
    }
}

class berrystr
{
    protected $contents;
    public function __construct( $strings ) // 
    {
        $this->contents = $strings;
    }

    static public function make( $strings )
    {
        return new berrystr( $strings );
    }
    
    static public function create( $strings )
    {
        return new berrystr( $strings );
    }

    public function remove( $str ) // 
    {
        $this->contents = str_replace( $str , '' , $this->contents );
        return $this;
    }

    public function replace( $str , $rep ) // 
    {
        $this->contents = str_replace( $str , $rep , $this->contents );
        return $this;
    }

    public function trim() // 
    {
        $this->contents = trim( $this->contents );
        return $this;
    }

    public function concat( $before = "" , $after = "") // 
    {
        $this->contents = $before . $this->contents . $after;
    }

    public function splitSelect( $deli , $index ) // 
    {
        $tmp = explode( $deli , $this->contents );
        if( $index == -1 ){
            $index = sizeof( $tmp ) - 1 ;
        }
        foreach( $tmp as $k=>$v ){
            if( $k == intval( $index )){
                $this->contents = $v;
                return $this;
            }
        }
        $this->contents = '';
        return $this;
    }
    
    public function splitFirst( $deli ) // 
    {
        $this->splitSelect( $deli , 0 );
        return $this;
    }

    public function splitLast( $deli ) // 
    {
        $this->splitSelect( $deli , -1 );
        return $this;
    }

    public function __toString() // 
    {
        return $this->contents;
    }

    public function append( $str ) // 
    {
        return $this->concat( "" , $str );
    }

    public function substr( $begin , $end ) // 
    {
        $this->contents = substr( $this->contents , $begin , $end - $begin );
        return $this;
    }

    public function firstIs( $str ) // 
    {
        $tmp = substr( $this->contents , 0 , strlen( $str ) );
        return $tmp == $str;
    }

    public function lastIs( $str ) // 
    {
        $tmp = substr( $this->contents , (strlen( $this->contents ) - strlen( $str ) ) , strlen( $str ) );
        return $tmp == $str;
    }

    public function regexp( $str , $rep ) // 
    {
        $this->contents = preg_replace( $str , $rep , $this->contents );
        return $this;
    }

    public function get() // 
    {
        return $this->contents;
    }
}