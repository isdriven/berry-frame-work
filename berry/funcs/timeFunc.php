<?php
/***
 * functins of timestamp
 *
 * @Author    :Ippei Sato
 * @Update    :2011.08.17
 */
class timeFunc
{
    private $ts;
    private $key;
    private $db;

    // return timestamp. in same process , return same value.
    public function ts()
    {
        if( !isset( $this->ts ) ){
            $this->ts = time();
        }
        return $this->ts;
    }

    // test the timestamp is over the border hour in today.
    public function isOverBorderTimeStamp( $border_hour /*0-23*/ , $test_timestamp ){
        $now = $this->ts();

        // 現在の時間がボーダー時間より下なら、ボーダーは昨日の日時になる
        $now_hour = intval(date('G', $now ));

        if( intval($now_hour) < intval($border_hour) ){
            $border_timestamp = mktime( $border_hour , 0 , 0 , date('n',$now) , (date('j',$now)-1) ,date('Y',$now ) );
        }else{
            $border_timestamp = mktime( $border_hour , 0 , 0 , date('n',$now) , date('j',$now) ,date('Y',$now ) );
        }
        return $border_timestamp < $test_timestamp ;
    }
}
