<?php
/***
 * 外部指定ファイルを直接読み込みます。
 * ロードされるファイルはberry/__filesにおいてある必要があります。
 */
class loadFunc
{
    // {{{  public function file ( $file_path )
    /**
     * 指定ファイルを読み込む。ファイルはberry/__filesにおいてある必要がある。
     */
    public function file( $file_path )
    {
        $dir = BERRY_APP_DIR . "/berry/__files/";
        if( is_file( $dir . $file_path ) ){
            return file_get_contents( $dir . $file_path );
        }
        return "";
    }
    // }}}

    // {{{  public function getPath ()
    /**
     * __filesへのパスを返す
     */
    public function getPath()
    {
        return BERRY_APP_DIR. "/berry/__files/";
    }
    // }}}
}
