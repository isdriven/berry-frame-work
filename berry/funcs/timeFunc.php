<?php
/***
 * タイムスタンプを管理し、その時間差などを判定する
 *
 * @Author    :Ippei Sato
 * @Package   :berry_fw_core
 * @Update    :2011.08.17
 */
class timeFunc
{
    private $ts;
    private $key;
    private $db;

    // {{{  public function ts ()
    /**
     * タイムスタンプを返す。ただし同一プロセス上では同じ値を返す
     */
    public function ts()
    {
        if( !isset( $this->ts ) ){
            $this->ts = time();
        }
        return $this->ts;
    }
    // }}}

    // {{{  public function setKey ( $key )
    /**
     * アプリケーションを識別するキーをセットする
     */
    public function setKey( $key )
    {
        $this->key = $key;
    }
    // }}}

    // {{{  public function setDb ( $db )
    /**
     * タイムスタンプを管理するためのDBをセットする
     * このDbは以下の３つのメソッドを実装している必要がある。
     *
     * set( $name , $value )  
     * get( $name )
     * del( $name )
     */
    public function setDb( $db )
    {
        $this->db = $db;
    }
    // }}}
}
