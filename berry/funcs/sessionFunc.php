<?php
/***
 * Sessionの操作を行う
 * 
 * @Author    :Ippei Sato
 * @Pacage    :berry_fw_core
 * @Update    :2011.08.01
 */
class sessionFunc
{
    // {{{  public function set ( $name , $value )
    /**
     * 値をセットする。既にセットされている場合も上書きする
     */
    public function set( $name , $value )
    {
        $_SESSION[ $name ] = $value;
    }
    // }}}

    // {{{  public function get( $name )
    /**
     * 値を返す。もし値がない場合はnullを返す
     */
    public function get( $name )
    {
        if( isset( $_SESSION[ $name ] ) ){
            return $_SESSION[ $name ];
        }
        return null;
    }
    // }}}

    // {{{  public function has ( $name )
    /**
     * 該当する値が設定されているかどうか返す
     * 設定されていなければ、第二引数を返す(デフォルトはfalse)
     */
    public function has( $name , $second = false )
    {
        if( isset( $_SESSION[ $name ] ) ){
            return $_SESSION[ $name ] ;
        }else{
            return $second;
        }
    }
    // }}}

    // {{{  public function is ( $name , $value )
    /**
     * 第一引数として与えている値が$valueと等しいかどうか返す。
     * $strictがtrueの時は厳格な比較を行う
     */
    public function is( $name , $value , $strict = false )
    {
        if( !isset( $_SESSION[ $name ] ) ){
            return ( $value == null );
        }
        if( $_SESSION[ $name ] == $value ){
            if( $strict ){
                return ( $_SESSION[ $name ] === $value );
            }
            return ( $_SESSION[ $name ] == $value );
        }
    }
    // }}}

    // {{{  public function keys ()
    /**
     * 存在するすべてのキーを返す
     */
    public function keys()
    {
        $ret = array();
        foreach( $_SESSION as $k => $v ){
            $ret[] = $k;
        }
        return $ret;
    }
    // }}}

    // {{{  public function del ( $name )
    /**
     * 指定した名前が存在すれば、削除する
     */
    public function del( $name )
    {
        if( isset( $_SESSION[ $name ] ) ){
            unset( $_SESSION[ $name ] );
        }
    }
    // }}

    // {{{  public function delAll ()
    /**
     * セション配列を空にする
     */
    public function delAll()
    {
        $_SESSION = array();
    }
    // }}}
    
    // {{{  public function dels ( $list )
    /**
     * 指定した名前をすべて削除する。配列を受け取る
     */
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
