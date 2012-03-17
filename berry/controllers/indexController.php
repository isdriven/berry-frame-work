<?php
/***
 * indexController
 *
 **/
class indexController extends berryController
{
    // {{{  public function initAction ()
    /**
     * 初期化
     */
    public function initAction()
    {
        $this->view->target('header')->tag('header/normal')->val();
    }
    // }}}

    // {{{  public function indexAction ()
    /**
     * トップ処理
     */
    public function indexAction()
    {
        $this->view->target('title')->val('berry frame work');
    }
    // }}}
}
