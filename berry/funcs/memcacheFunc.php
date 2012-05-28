<?php
/***
 * Memcacheをコントロールする基底クラス
 *
 * Author   : Ippei Sato
 * Package  : berry_fw_core
 * Update   : 2011.08.10
 */

class memcacheFunc
{
    private $mem;
    private $expire = 300; // キャッシュ時間
    private $host = "localhost"; 
    private $key_prefix = "test_";

    // {{{  public function __construct ()
    /**
     * 準備
     */
    public function __construct()
    {
        $this->mem = new Memcache;
        $this->mem->connect( $this->host );
    }
    // }}}
    
    // {{{  public function check ()
    /**
     * 使えるかどうかチェックする
     * もし使えない環境に呼び出された場合、即座にエラー
     */
    public function check()
    {
        if( !class_exists( 'Memcache' ) ){
            exit( 'no PECL::memcache' );
        }
    }
    // }}}

    // {{{  public function set ( $name , $val)
    /**
     * 値を格納
     */
    public function set( $name , $val )
    {
        $this->check();
        $this->mem->set( $this->key_prefix . $name , $val , MEMCACHE_COMPRESSED , $this->expire );
    }
    // }}}

    // {{{  public function get ( $name )
    /**
     * 取得します。
     */
    public function get( $name )
    {
        $this->check();
        return $this->mem->get( $this->key_prefix . $name );
    }
    // }}}

    // {{{  public function has ( $name )
    /**
     * 持っているか返します
     */
    public function has( $name )
    {
        return $this->get( $this->key_prefix . $name ) !== false ;
    }
    // }}}

    // {{{  public function del ( $name )
    /**
     * 削除します
     */
    public function del( $name )
    {
        $this->mem->delete( $this->key_prefix . $name );
    }
    // }}}

    // {{{  public function increment ( $name )
    /**
     * 足します
     */
    public function increment( $name , $num = 1 )
    {
        if( $this->has( $name ) ){
            $val = intval( $this->get( $name ) );
            $this->set( $name , $val + $num );
            return $val + $num;
        }
        return false;
    }
    // }}}

    // {{{  public function decrement ( $name , $num = 1 )
    /**
     * 引きます
     */
    public function decrement( $name , $num = 1 )
    {
        if( $this->has( $name ) ){
            $val = intval( $this->get( $name ) );
            $this->set( $name , $val - $num );
            return $val - $num;
        }
        return false;
    }
    // }}}

    // {{{  public function append ( $name , $val )
    /**
     * 末尾に追加します。
     */
    public function append( $name , $val ){
        if( $this->has( $name ) ){
            $src = $this->get( $name );
            $this->set( $name , $src . $val );
            return $src . $val ;
        }else{
            $this->set( $name , $val );
        }
    }
    // }}}

    // {{{  public function clean ()
    /**
     * 全てのキャッシュを削除
     */
    public function clean()
    {
        $this->mem->flush();
    }
    // }}}

    // {{{  public function setExpire ( $expire)
    /**
     * ...
     */
    public function setExpire( $expire )
    {
        $this->expire = $expire;
        return $this;
    }
    // }}}

    // {{{  public function setPrefix ( $prefix )
    /**
     * 接頭辞をセット
     */
    public function setPrefix( $prefix )
    {
        $this->key_prefix = $prefix;
    }
    // }}}



}
