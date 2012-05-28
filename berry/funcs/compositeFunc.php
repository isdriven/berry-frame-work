<?php
/***
 * 透過GIFの合成、アニメーションGIFの作成、合成
 * 
 * Author : Ippei Sato
 * Update : 2011 . 07
 * Use    : アバターの作成
 */
/*** How To Use ***
$c = new compositeFunc();

// 透過GIF合成
$c->base( "/images/flower001_200.gif")
->image( "images/flower002_200.gif")
->image( "images/flower003_200.gif")
->image( "images/flower004_200.gif")
//->image( "images/flower005_200.gif")
->composite()
->save( "images/anime4.gif");

// GIFアニメーション作成
$c->base( "images/flower_6_1.gif" )
->image( "images/flower_6_2.gif" )
->image( "images/flower_6_3.gif" )
->image( "images/flower_6_4.gif" )
->createAnimation()
->save( "images/anime5.gif");

// GIFアニメーションの合成
$c->base( "images/flower_6_1.gif")
->image( "images/anime4.gif")
->image( "images/anime5.gif")
->compositeAnimation()
->save( "images/anime7.gif" );
****/
class compositeFunc
{
    private $images = array();
    private $base = "";
    private $delay = 0;
    private $result = null;
    private $error_is_exit = true;
    // {{{  public function check ()
    /**
     * ImageMagickとPECL::imagickが使用可能かどうか確認する
     */
    public function check()
    {
        if( !class_exists( "imagick" ) ){
            $this->error( 'no ImageMagick or PECL::imagick' );            
        }
        return true;
    }
    // }}}

    // {{{  public function setBase ()
    /**
     * ベースとなる透明画像を保存(出力サイズ)
     */
    public function base( $base )
    {
        if( file_exists( $base ) ){
            $this->base = $base;
        }else{
            $this->exit(' no file:'. $base );
        }
        return $this;
    }
    // }}}

    // {{{  public function image ( $file_name )
    /**
     * GIFイメージを格納
     */
    public function image( $file_name )
    {
        if( file_exists( $file_name ) ){
            $this->images[] = $file_name;
        }else{
            $this->error(' no file:'.$file_name );
        }
        return $this;
    }
    // }}}

    // {{{  public function composite ()
    /**
     * 一枚に合成する
     */
    public function composite()
    {
        $this->check();
        $this->result = new Imagick( $this->base );
        foreach( $this->images as $k => $v ){
            $screen =  new Imagick( $v );
            // アニメーションアイテムの場合、一番始めだけ
            if( $screen->getNumberImages() > 1 ){
                $screen->setImageIndex( 0 );
            }
            $this->result->compositeImage( $screen , imagick::COMPOSITE_OVER , 0 , 0 );
            $screen->destroy();
        }
        return $this;
    }
    // }}}

    // {{{  public function createAnimation ()
    /**
     * GIFアニメを生成する
     */
    public function createAnimation( $delay = 20  , $loop = 0 )
    {
        $this->check();
        $this->result = new Imagick( $this->base );
        $this->result->setImageIterations( $loop );
        foreach( $this->images as $k => $v ){
            $screen =  new Imagick( $v );
            $screen->setImageDelay( $delay );
            $this->result->setImageDispose( 2 );
            $this->result->addImage( $screen );
            $screen->destroy();
        }
        return $this;
    }
    // }}}

    // {{{  public function compositeAnimation ()
    /**
     * GIFアニメーションを合成する
     */
    public function compositeAnimation( $delay = 20 , $loop = 0 )
    {
        $this->check();
        // 全てのイメージをオブジェクトにする
        $imgobj = array();
        foreach( $this->images as $i ){
            if( file_exists( $i ) ){
                $imgobj[] = new Imagick( $i );
            }else{
                $this->error('no image:'.$i );
            }
        }

        // 最大フレーム数を割り出す
        $max_frame = 0;
        foreach( $imgobj as $i ){
            $frame_number = $i->getNumberImages();
            if( $max_frame < $frame_number ){
                $max_frame = $frame_number;
            }
        }

        // アニメーションアイテムがある場合のみ、このメソッドの処理起動
        if( $max_frame > 1 ){
            if( file_exists( $this->base ) ){
                $this->result = new Imagick( $this->base );
                $this->clone = $this->result->clone();
                $this->result->setImageIterations( $loop );
            }else{
                $this->error( "no file:". $this->base  );
            }

            for( $i = 0 ; $i < $max_frame ; $i++){
                if( $i !== 0 ){
                    $clone = $this->clone->clone();
                }

                foreach( $imgobj as $v ){
                    $frame_count = $v->getNumberImages();
                    if( $frame_count == 1 ){
                        $index = 0;
                    }else{
                        $index = $i % $frame_count;
                    }
                    $v->setImageIndex( $index );
                    if( $i == 0 ){
                        $this->result->compositeImage( $v , imagick::COMPOSITE_OVER , 0 , 0 );
                    }else{
                        $clone->compositeImage( $v , imagick::COMPOSITE_OVER , 0 , 0 );
                    }
                }

                if( $i !== 0 ){
                    $clone->setImageDelay( $delay );
                    $this->result->setImageDispose( 3 );
                    $this->result->addImage( $clone );
                }
                if( $i !== 0 ){
                    $clone->destroy();
                    unset( $clone );
                }
            }
            $this->clone->destroy();
        }
        // 全てのイメージを削除する

        foreach( $imgobj as $v ){
            $v->destroy();
        }
        return $this;
    }
    // }}}

    // {{{  public function getBlob ()
    /**
     * バイナリイメージを取得します。
     */
    public function getBlob()
    {
        if( isset( $this->result ) ){
            return $this->result->getImageBlog();
        }
        return null;
    }
    // }}}

    // {{{  public function save ( $new )
    /**
     * イメージ名を指定して保存する
     */
    public function save( $new )
    {
        if( isset( $this->result ) ){
            if( $this->result->getNumberImages() == 1 ){
                $this->result->writeImage( $new );
            }else{
                $this->result->writeImages( $new , true );
            }
            return $new;
        }else{
            return false;
        }
    }
    // }}}

    // {{{  public function error ( $message )
    /**
     * 異常終了を統合する
     */
    public function error( $message )
    {
        if( $error_is_exit ){
            exit( $message );
        }else{
            return false;
        }
    }
    // }}}
}

