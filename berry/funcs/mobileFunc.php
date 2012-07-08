<?php
/**
 * Mobile情報を識別し、然るべき処理を事前に行う
 *
 * @Author    :Ippei Sato
 * @Package   :berry_fw_core
 * @Update    :2011.08.01 
 */

class mobileFunc
{
    public function __construct()
    {
        //生成と同時にキャリア判定。個体識別情報取得
        //For DoCoMo,guid=ON is needed.
        $this->user_agent=$_SERVER['HTTP_USER_AGENT'];
        if( strpos( $this->user_agent , 'DoCoMo' ) !== false ){
            $this->carrier="docomo";
        }else{
            $this->carrier="else";
        }
        //au
        if( isset( $_SERVER['HTTP_X_UP_SUBNO'] ) )
        {
            $this->id=$_SERVER['HTTP_X_UP_SUBNO'];
            $this->carrier='au';
        }
        
        //SoftBank
        if( isset( $_SERVER['HTTP_X_JPHONE_UID'] ) )
        {
            $this->id=$_SERVER['HTTP_X_JPHONE_UID'];
            $this->carrier='sb';
        }
        
        //DoCoMo
        if( isset( $_SERVER['HTTP_X_DCMGUID'] ) )
        {
            $this->id=$_SERVER['HTTP_X_DCMGUID'];
            $this->carrier='docomo';
        }

        //iphone
        if( strpos( $this->user_agent , 'iPhone')!== false ){
            // iphoneならば、widthとheightを指定
            $this->carrier = 'iphone';
			$view = berry::load('view');
            $view->constant('width' , 320 );
            $view->constant('height' , 480 );
            $this->id = null;
        }
        // mobile_deviceにmobile情報を指定
    }

    // {{{  public function setType ( $name )
    /**
     * 任意にキャリアを設定する
     */
    public function setType( $name )
    {
        if( $name == 'iphone' ){
            $this->view->constant('width' , 320 );
            $this->view->constant('height' , 480 );
        }
        $this->carrier = $name;
    }
    // }}}

    // {{{  public function isIphone ()
    /**
     * iphoneならばtrue
     */
    public function isIphone()
    {
        if( $this->carrier == 'iphone' ){
            return true;
        }
        return false;
    }
    // }}}
}
