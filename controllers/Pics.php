<?php

    class Pics extends Controller
    {
        function upload ()
        {
            if (isLoggedIn())
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