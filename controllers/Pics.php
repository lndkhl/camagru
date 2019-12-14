<?php

    class Pics extends Controller
    {
        function upload ()
        {
            if (Database::isLoggedIn())
            {
                if (isset($_POST['image']))
                {
                    $image_string = $_POST['image'];
                    $image_string = str_replace('data:image/png;base64,', '', $image_string);
                    $image_string = str_replace(' ', '+', $image_string);
                    $image_string = base64_decode($image_string);

                    $image = imagecreatefromstring($image_string);
                
                    $path = './images/' . time().'.png';
                    imagepng($image, $path);
                    static::query("INSERT INTO camagru.posts(post, user_id) VALUES (:path, :user_id)", array(":path"=>$path, ":user_id"=>static::isLoggedIn()));
                    echo "<br>Image succsessfully saved!<br>";
                }
                else
                {
                    die ("No image!");
                }
            }
            else
            {
                die ("You are not logged in!");
            }
        }
    }
?>