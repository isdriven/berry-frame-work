<?php
/***
 * articleの処理を行う
 *
 * Author : Ippei Sato
 * Update : 2011 . 06
 * Use    : Wikiシステムのレンダリング
 */
class articleFunc
{
    function __construct()
    {
    }

    /**
     * articleのレンダリングを行います
     *
     */
    function render( $str )
    {
        $li = explode( "\r\n" , $str );
        $size = sizeof( $li );

        $this->modeStack = array();
        $body = array();
        $mode = '';
        $p_stack = array();
        $this->superMode = false;
        // 順次解析
        for( $i = 0 ; $i < $size ; $i++ ){

            $row_line = $li[$i];
            $line = trim( $li[$i] );
            $length = strlen( $line );

            $this->mode == '';
            // モードを判定する
            if( $length == 0 ){
                // 空行にて、全てのモードを終了させる。
                $body[] = $this->endMode();
            }else if( $line[0] == '*' ){
                // タイトルモード ---> 全てのモードを終了させる
                $body[] = $this->endMode();
                $body[] = $this->h( $line );
                $this->padding = 0;

            }else if( $line[0] == '-' ){

                // 前のulを終了させる
                $this->endMode();
                if( $this->mode !== 'li' ){
                    $body[] = $this->startMode( 'ul' );
                }
                $body[] = $this->startMode( 'li' ,  $line );
                $body[] = str_replace( '-' , '' , $line) ;
            }else if( strpos( $line , '>>>' ) === 0 ){
                $padding = $this->padding;
                if( $this->mode == 'p' ){
                    $body[] = $this->endMode( 'p' );
                }
                $this->padding = $padding;
                $moreclass = "pad-1";
                $lang = trim( str_replace( '>>>' , '' , $line ) );


                $body[] = sprintf( '<div class="%s"><pre class="brush:%s" >', $moreclass , $lang);
                $this->superMode = true;
            }else if( strpos( $line , '<<<' ) === 0 ){
                $body[] = "</pre></div>";
                $this->superMode = false;
            }else if( strpos( $line , 'image,' ) === 0 ){
                // ここから追加!
                $body[] = $this->endMode();
                $data = explode( ',' , $line );

                $direction = ifset( $data[2] , 'left' );
                $width = ifset( $data[3] , '' );
                $link = ifset( $data[4] , 'f' );

                if( $width !== '' ){
                    $width = "width:{$width}px;";
                }

                if( $link == 'f' ){
                    $body[] = "<div style='margin-top:20px;margin-bottom:20px;text-align:{$direction};' class='pad-1'> <img src='{$data[1]}' style='{$width}'/> </div>";
                }else{
                    $image = "<div style='margin-top:20px;margin-bottom:20px;text-align:{$direction};' class='pad-1'> <img src='{$data[1]}' style='{$width}'/> </div>";
                    $body[] = "<a href='{$data[1]}' target='_blank' >{$image}</a>";
                }

            }else if( strpos( $line , 'http') === 0 ){
                if( $this->mode !== 'p'){
                    $body[] = $this->startMode( 'p' );
                    $body[] = "<a href='{$line}' target='_blank' >".$this->getUrlTitle($line)."</a><br />";
                }else{
                    $body[] = "<a href='{$line}' target='_blank' >".$this->getUrlTitle($line)."</a><br />";
                }
            }else{
                if( $this->mode !== 'p' and !$this->superMode ){
                    $body[] = $this->startMode( 'p' );
                }

                if( $this->superMode ){
                    $body[] = $row_line;
                }else{
                    $body[] = $line."<br />";
                }
            }
        }
        $body[] = $this->endMode();
        $body =  implode( "\r\n" , $body );

        return $body;
    }

    function startMode( $as , $line = null)
    {
        if( $as == 'li' ){
            $this->padding = $this->getLiPadding( $line );
        }
        $size = sizeof( $this->modeStack );

        $this->modeStack[] = $as;
        $this->mode = $as;
        $padding = $this->padding;

        if( $this->mode == 'p' ){
            $padding = 1;
        }

        if( $padding != 0  ){
            return "<{$as} class='pad-{$padding}'>";
        }else{
            return "<{$as}>";
        }
    }

    function endMode( $to = null )
    {
        $modes = $this->modeStack;

        $ret = array();
        $s = sizeof( $modes );

        for( $i = ($s-1) ; 0 <= $i ; $i-- ){
            $v = $modes[$i];
            $ret[] = "</{$v}>";
            unset( $modes[$i] );
            if( $to == $v ){
                break;
            }
        }

        $this->modeStack = array_values( $modes );
        $this->mode = '';
        $this->padding = 0;
        return implode( "\r\n" , $ret );
    }

    function resetPadding()
    {
        $this->padding = 0;
    }

    /**
     * li
     */
    function li( $str )
    {
        $n = 1;
        $n = cond( ($str[1] == '-') , $n+1 , $n );
        $n = cond( ($str[2] == '-') , $n+1 , $n );
        $str = str_replace( '-' , '' , $str );
        $this->padding = $n;
        return sprintf( "<li class='pad-%s'>%s</li>", $n , $str );
    }

    function getLiPadding( $str )
    {
        $n = 1;
        $n = cond( ($str[1] == '-') , $n+1 , $n );
        $n = cond( ($str[2] == '-') , $n+1 , $n );
        $n = cond( ($str[3] == '-') , $n+1 , $n );
        return $n;
    }

    /**
     * 段落
     */
    function h( $str )
    {
        $n = 1;
        $n = cond( ($str[1] == '*') , $n+1 , $n );
        $n = cond( ($str[2] == '*') , $n+1 , $n );
        $n = cond( ($str[3] == '*') , $n+1 , $n );
        $str = str_replace( '*' , '' , $str );
        return sprintf( "<h%s>%s</h%s>", $n , $str , $n );
    }

    /**
     * 指定urlのタイトルを取得します。
     */
    function getUrlTitle( $url )
    {
        $body = @file_get_contents( $url ) or false;
        if( $body === false ){
            return "404 Error";
        }
        preg_match( "/<title>(.*?)<\/title>/is" ,  $body , $m);
        if( isset( $m[1] ) ){
            $result = $m[1];
        }else{
            $result = "No Title Found";
        }

        preg_match( "/charset=\"?([A-Za-z1-9\-\_]+)\"?/" , $body , $m);

        $encoding = strtoupper( $m[1] );

        $encoding = str_replace( '-' , '_' , strtoupper( $m[1] ) );

        switch( $encoding ){
        case "UTF_8":
            break;

        case "SHIFT_JIS":
            $result = mb_convert_encoding( $result , 'UTF-8' , 'SJIS' );
            break;

        case "EUC_JP":
        default:
            $result = mb_convert_encoding( $result , 'UTF-8' , 'EUC-JP' );
            break;
        }

        $result = preg_replace( "/\s/" , "" , $result );
        return $result;
    }
}
