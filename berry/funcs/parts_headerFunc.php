<?php
/***
 * ヘッダーを生成するためのインターフェース
 * 
 * @Author    :Ippei Sato
 * @Package   :berry_fw_view
 * @Update    :2011.08.17
 */
class parts_headerFunc
{
    // {{{  public function loadJqueryMobile()
    /**
     * jQueryMobileをロードします。
     */
    public function loadJqueryMobile()
    {
        $this->view->constant('jqueryMobile' , true );        
    }
    // }}}

    // {{{  public function loadIphoneHeader()
    /**
     * iphone用のheaderをロードします。
     */
    public function loadIphoneHeader()
    {
        $this->view->target('header')->tag('header/iphone_header' )->val();
        $this->view->target('body')->tag('iphone_parts/page')->val('main');
    }
    // }}}

    // {{{  public function loadNormalHeader()
    /**
     * 普通のheaderをロードします。
     */
    public function loadNormalHeader()
    {
        $this->view->target('header')->tag('header/normal_header')->val();
    }
    // }}}

    // {{{  public function addJsFile( $js_file_name )
    /**
     * jsのファイルのロードを追加します。
     */
    public function loadAdditionalJsFile( $js_file_name )
    {
        $this->view->target('javascript')->tag('header/scripts')->val( $js_file_name );
    }
    // }}}

    // {{{  public function loadAdditionalCssFile( $css_file_name )
    /**
     * cssのファイルのロードを追加します。
     */
    public function loadAdditionalCssFile( $css_file_name )
    {
        $this->view->target('css')->tag('header/css')->val( $css_file_name );        
    }
    // }}}

}
