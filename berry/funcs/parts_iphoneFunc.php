<?php
/***
 * iphone_parts を用いてiphone風のviewを生成するためのインターフェース機能
 *
 * @Author  : Ippei Sato
 * @Paceage : berry_fw_view_iphone
 * @Update  : 2011.08.16
 */

class parts_iphoneFunc
{
    // {{{  public function createNavi ( $list )
    /**
     * Navigatiom Barを構築
     */
    public function createNavi( $target , $list )
    {
        $this->view->target('page_body')->tag( 'iphone_parts/nav' )
            ->val( '' , array( 'nav'=> $list  ) );
    }
    // }}}

    // {{{  public function acordion_bar( $main , $options )
    /**
     * アコーディオンの生成
     */
    public function accordion_bar( $target , $main , $options = array() )
    {
        $this->view->target( $target )->tag('iphone_parts/accordion_bar')->val( $main ,  $options );
    }
    // }}}

    // {{{  public function image_text( $target , $main , $options )
    /**
     * イメージテキストの生成
     */
    public function image_text( $target , $main , $options = array() )
    {
        $this->view->target( $target )
            ->tag('iphone_parts/image_text')
            ->val( $main  , $options  );
    }
    // }}}

    // {{{  public function middle_div ( $main , $options )
    /**
     * divでくくって生成
     */
    public function div( $target , $main , $options = array() )
    {
        $this->view->target( $target )->tag('iphone_parts/middle_div')->val( $main , $options );
    }
    // }}}

    // {{{  public function space ( $target , $size )
    /**
     * スペースを生成
     */
    public function space( $target , $size )
    {
        $this->view->target( $target )->tag('iphone_parts/space')->val( $size );
    }
    // }}}

    // {{{  public function navi_vertical ( $target , $main , $options )
    /**
     * 縦メニューの生成
     */
    public function navi_vertical( $target , $main , $options )
    {
        $this->view->target( $target )->tag('iphone_parts/navi_b')->val( $main , $options );        
    }
    // }}}

    // {{{  public function navi_horizontal ( $target , $main , $options )
    /**
     * 横メニューの生成
     */
    public function navi_horizontal( $target , $main , $options )
    {
        $this->view->target( $target )->tag('iphone_parts/navi_a')->val( $main , $options );                
    }
    // }}}

    // {{{  public function banners ()
    /**
     * 背景画像+文字+リンクのフィールドを生成
     */
    public function banner( $target , $main , $options )
    {
        $this->view->target( $target )->tag('iphone_parts/banner')->val( $main  , $options );
    }
    // }}}

    // {{{  public function a( $target , $text , $link , $color )
    /**
     * シンプルなリンクを生成
     */
    public function a( $target , $text , $url , $border_color )
    {
        $this->view->target( $target )->tag('iphone_parts/a')->val( $text , array( 'url'=>$url , 'border_color'=>$border_color ) );
    }
    // }}}
}
