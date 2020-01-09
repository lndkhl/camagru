<?php
class Gallery extends Controller
{
    public static function main_()
    {
        $sth = static::query("SELECT post FROM camagru.posts");
        $i = 0;
        $j = 0;
        while ($i < 100)
        {
            $j = 0;
            while ($j < 100)
            {
                echo '<img src="'.$sth[$i][$j].'">';
                $j += 1;
            }
            $i += 1;
        }
        //echo'<img src="data:image;base64,'.$sth['image'].'">';       
    }
}
?>