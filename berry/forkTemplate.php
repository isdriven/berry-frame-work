<?php
/**
 * Fork Template
 * Simple And Powerful Template Engine For PHP
 *
 * @Author : Ippei Sato
 * @License : MIT License
 * @Version : 1.0
 */

class forkTemplate
{
    private $target;
    private $tags = array();
    private $dir;
    private $e;
    private $data = array();
    private $start_tag = "{@";
    private $end_tag = "}";
    private $constant;
    public function __construct( $dir , $e = "tag" )
    {
        $this->dir = $dir;
        if( $this->dir[ strlen( $this->dir ) - 1 ] !== '/' ){
            $this->dir = $this->dir.'/';
        }
        $this->e = "." . $e;
        $this->constant = new stdClass;
    }
    public function target( $name )
    {
        $this->target = $name;
        return $this;
    }
    public function tag( $name )
    {
        $this->tags[] =  $name;
        return $this;
    }
    public function val( $value = "" , $args = array() )
    {
        if( !isset( $this->target ) ){
            return false;
        }
        $this->val = new stdClass;
        foreach( $args as $k => $v ){
            $this->val->{$k} = $v;
        }
        if( isset( $this->data[ $this->target ] ) ){
            $this->data[ $this->target ] .= $this->createTags( $this->tags , $value );
        }else{
            $this->data[ $this->target ] = $this->createTags( $this->tags , $value );            
        }
        $this->tags = array();
        $this->val = null;
    }
    public function constant( $name , $value )
    {
        $this->constant->{$name} = $value;
    }
    public function render( $contents )
    {
        if( !empty( $this->data ) ){
            foreach( $this->data as $k => $v ){
                $contents = str_replace( $this->start_tag . $k  . $this->end_tag , $v  , $contents );
            }
        }
        $contents = preg_replace( "/\{@[a-zA-Z0-9\-_]+\}/" , "" , $contents );

        $this->clean();
        $this->contents = $contents;
        return $this->contents;
    }
    public function clean()
    {
        $this->data = array();
        $this->tags = array();
        $this->val = null;
        $this->target = null;
    }
    private function createTags( $tags , $contents )
    {
        if( empty( $tags ) ){
            return $contents;
        }
        if( sizeof( $tags ) == 1 ){
            if( is_array( $contents ) ){
                $temp = "";
                $tag = array_shift( $tags );
                foreach( $contents as $k => $v ){
                    $temp .= $this->exec( $tag , $v );
                }
                return $temp;
            }else{
                return $this->exec( array_shift( $tags ) , $contents );
            }
        }else{
            $tag = array_shift( $tags );
            return $this->createTags( $tag , $this->createTags( $tags , $contents ) );
        }
    }
    private function exec( $tag , $contents )
    {
        $val = $this->val;
        $constant = $this->constant;
        if( ( $file_name = $this->existTag( $tag ) ) !== false ){ 
            ob_start();
            include( $file_name );
            $buffer = ob_get_clean();
            return $buffer;
        }else{
            return $contents;
        }
    }
    private function existTag( $name )
    {
        $file =  $this->dir . $name . $this->e ;
        if( file_exists( $file ) ){
            return $file;
        }else{
            return false;
        }
    }
}
