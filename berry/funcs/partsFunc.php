<?php
/***
 * 一般的なhtmlの生成を行う
 *
 */
class partsFunc
{
    // {{{  public function name(args)
    /**
     * <script></scirpt>の出力を行う
     */
    public function javascript( $target , $contents )
    {
        $this->view->target( $target )->val( '<script>'. $contents . '</script>' );
    }
    // }}}    

    // {{{  public function css( $target , $contents )
    /**
     * <style></style>で出力を行う
     */
    public function css( $target , $contents )
    {
        $this->view->target( $target )->val( '<style>' . $contents . '</style>' );
    }
    // }}}

    // {{{  public function javascriptFromFile( $target , $file )
    /**
     * __filesから選択してセットする
     */
    public function javascriptFromFile( $target , $file_name )
    {
        $this->javascript( $target , $this->func->load->file( $file_name ) );
    }
    // }}}


}
