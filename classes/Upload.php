<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Upload extends Users
{
    public static function main_()
    {
        if (static::isLoggedIn())
        {
            $user_id = static::isLoggedIn();
            if (isset($_POST['upload']) && !empty($_FILES['img']['name']))
            {
                $imgName = $_FILES['img']['name'];
                $fileType = pathinfo($imgName, PATHINFO_EXTENSION);
                $allowTypes = array('jpg','png','jpeg');
                if(in_array($fileType, $allowTypes))
                {
                    $img = static::getUsername(static::isLoggedIn()) . date('-Y-m-d_h:i:s.') . $fileType;
                    if(!file_exists("uploads"))
                    {
                        mkdir("uploads");
                    }
                    $targetDir = "uploads/";
                    $targetFilePath = $targetDir . $img;
        
                    if(move_uploaded_file($_FILES["img"]["tmp_name"], $targetFilePath))
                    {
                        static::uploadPic($targetFilePath, $user_id);            
                    }
                    else
                    {
                        echo "File upload failed, please try again.";
                    }
                }
                else
                {
                    echo "Sorry, only JPG, JPEG, PNG, files are allowed.";
                }
            }
        }
        else
        {
            Route::redirect("login");
            exit();
        }
        Profile::main_();
    }
}
?>