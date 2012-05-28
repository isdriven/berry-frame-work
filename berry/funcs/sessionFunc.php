<?php
/***
 * functions of session
 * 
 * @Author    :Ippei Sato
 */
class sessionFunc
{
    // set value , if overwrite is true , write over.
    public function set( $name , $value , $overwrite = true )
    {
        if( !$this->has( $name ) ){
            $_SESSION[ $name ] = $value;
            return true;
        }else if( $overwrite ){
            $_SESSION[ $name ] = $value;
            return true;
        }
        return false;
    }
    // }}}

    // return value , if no value , return default
    public function get( $name , $default = null )
    {
        if( $this->has( $name ) ){
            return $_SESSION[ $name ];
        }else{
            return $default;
        }
    }
    // }}}

    // return true , if has the value.
    public function has( $name )
    {
        return isset( $_SESSION[$name] );
    }

    // test equal or not
    public function is( $name , $value , $strict = false )
    {
        if( $this->has( $name ) ){
            if( $strict ){
                return $this->get( $name ) === $value;
            }else{
                return $this->get( $name ) === $value;
            }
        }
    }
    
    // return all keys
    public function keys()
    {
        return array_keys( $_SESSION );
    }

    // delete 
    public function del( $name )
    {
        if( $this->has( $name ) ){
            unset( $_SESSION[ $name ] );
            return true;
        }
        return false;
    }

    // truncate session
    public function delAll()
    {
        $_SESSION = array();
    }

    // delete names
    public function dels( $list )
    {
        if( !is_array( $list ) ){
            return false;
        }
        foreach( $list as $v ){
            $this->del( $v );
        }
    }
    // }}}
}
