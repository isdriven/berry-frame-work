<?php
/***
 * ページャーを作成する機能
 * 情報からページングのための判定を行う
 *
 * @Author    :Ippei Sato
 * @Package   :berry_fw_core
 * @Update    :2011.08.01
 */

class pagerFunc
{
    public $item_count;
    public $span;
    public $current;
    public $pages;

    // {{{  public function __construct ( $item_count , $span = 5 , $current = 1 , $pages = 5 )
    /**
     * 初期化を行う
     */
    public function __construct( $item_count , $span = 5 , $current = 1 , $pages = 5 )
    {
        $this->item_count = $item_count;
        $this->span = $span;
        $this->current = $current;
        $this->pages = $pages;
    }
    // }}}

    // 最大ページを取得
    public function getMaxPage()
    {
        if( $this->item_count <= $this->span ){
            return 1;
        }
        $count = $this->item_count / $this->span;
        if( ( $this->item_count % $this->span ) !== 0 ){
            $count++;
        }
        return intval( $count );
    }

    // 現在使用するページ群を取得
    public function getPages( $url = null  )
    {
        $max = $this->getMaxPage();

        $half = intval( $this->pages/2 );

        $start = 1;
        if( $max < $this->pages ){
            $start = 1;
        }else if(  $this->current  > $half ){
            $start = $this->current - $half;
            if( $this->current > $max - $half ){
                $start = $max  - $this->pages  + 1 ;
            }
        }

        for( $i = $start ; $i < $start + $this->pages ; $i++ ){
            if( $i > $max ){
                break;
            }
            if( isset( $url ) ){
                $my_url = "";
                if( strpos( $url , '?' ) !== false ){
                    $my_url = $url . "&page=".$i;
                }else{
                    $my_url = $url . "?page=".$i;
                }
            }
            if( $i == $this->current ){
                $ret[] = array( 'page' => $i , 'url'=> $my_url , 'current'=>true );
            }else{
                $ret[] = array( 'page' => $i , 'url'=> $my_url , 'current'=>false );
            }

        }
        return $ret;
    }
}
