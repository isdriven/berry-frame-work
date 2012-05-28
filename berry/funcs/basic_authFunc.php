<?php
/***
 * basicな認証を行う
 * 
 * @Author    :Ippei Sato
 * @Update    :2012.1.8
 */
class basic_authFunc
{
    protected $user_list = null;
    protected $realm = 'USER PROTECT';
    private $session_name = 'basic_auth';

    public function setPassWord( $list ) // 
    {
        $this->user_list = $list;
        return $this;
    }

    public function start() // 認証をかける
    {
        if( !$this->isValidUser() ){
            if( !$this->hasSession() ){
                $this->basicLock();
            }
        }
    }

    public function end() // 認証を解除
    {
        $this->func->session->del( $this->session_name );
        unset( $_SERVER['PHP_AUTH_USER'] );
        unset( $_SERVER['PHP_AUTH_PW'] );
    }

    public function getName() // 認証名を取得
    {
        return $this->getValidUser();
    }

    protected function getUserName() // 
    {
        if( isset( $_SERVER['PHP_AUTH_USER'] ) ){
            return $_SERVER['PHP_AUTH_USER'];
        }
        return false;
    }

    protected function getUserPw() // 
    {
        if( isset( $_SERVER['PHP_AUTH_PW'] ) ){
            return $_SERVER['PHP_AUTH_PW'];
        }
        return false;
    }

    protected function hasUserName() // 
    {
        if( $this->getUserName() ){
            return true;
        }
        return false;
    }

    protected function hasUserPw() // 
    {
        if( $this->getUserPw() ){
            return true;
        }
        return false;
    }

    protected function isValidUser() // 
    {
        if( !$this->hasUserName() or !$this->hasUserPw() ){
            return false;
        }
        if( !isset( $this->user_list[ $this->getUserName() ] ) ){
            return false;
        }
        if( $this->hasUserPw() and $this->user_list[ $this->getUserName() ] == $this->getUserPw() ){
            $this->setValidUser( $this->getUserName() );
            return true;
        }
        return false;
    }

    protected function hasSession() // sessionに値をもつか?
    {
        return $this->func->session->has( $this->session_name );
    }

    protected function basicLock() // 認証ロック開始
    {
        header('WWW-Authenticate: Basic realm="'.$this->realm.'"');
        header('HTTP/1.0 401 Unauthorized');
        header('Content-type: text/html; charset=utf8');
        exit();
    }

    protected function setValidUser( $name ) // 
    {
        $this->func->session->set( $this->session_name , $name );
    }

    protected function getValidUser() // 
    {
        return $this->func->session->get( $this->session_name );
    }

}
