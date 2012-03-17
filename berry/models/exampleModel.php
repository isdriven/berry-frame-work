<?php
/***
 *demo
 **/

class demoModel extends berryModel  // extends berryModel
{
    // {{{ [ set database settings here ] 
    public $host=    "demo.com";     // set host name
    public $port=    null;           // if null, 3306
    public $user=    "user_name";    // set user name
    public $passwd=  "password";     // set pass word
    public $dbname=  "db_name";      // set database name
    // }}}

    function fetchAll($id)
    {
        //query to MySQL
    }
}
