<?php
class Gallery extends Controller
{
    public static function main_()
    {
        $sth = static::query("SELECT post FROM camagru.posts WHERE user_id = 2");
        //print_r($sth);
        //print($sth[0][0]);
        echo '<img src="./images/1576665043.png">';
        echo '<img src="'.$sth[1][6].'">';
        //echo'<img src="data:image;base64,'.$sth['image'].'">';       
    }
}
?>