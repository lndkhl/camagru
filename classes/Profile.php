<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Profile extends Users
{
    private static function parsePic()
    {
        if (static::isLoggedIn())
        {
            $user_id = static::isLoggedIn();
            if (isset($_POST['upload']) && !empty($_FILES['img']['name']))
            {
                $imgName = $_FILES['img']['name'];
                $fileType = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
                $allowTypes = array('jpg','png','jpeg');
                if(in_array($fileType, $allowTypes))
                {
                    $img = static::getUsername(static::isLoggedIn()) . date('-Y-m-d_h:i:s.') . $fileType;
                    if(!file_exists("uploads"))
                    {
                        mkdir("uploads", 0700, TRUE);
                    }
                    $targetDir = "uploads/";
                    $targetFilePath = $targetDir . $img;
                    
                    if(move_uploaded_file($_FILES["img"]["tmp_name"], $targetFilePath))
                    {
                        static::uploadPic($img, $user_id);            
                    }
                    else { echo "File upload failed, please try again."; }
                }
                else { echo "Sorry, only JPG, JPEG, PNG, files are allowed."; }
            }
            else if (isset($_POST['image']))
            {
                $image_string = $_POST['image'];
                $image_string = str_replace('data:image/png;base64,', '', $image_string);
                $image_string = str_replace(' ', '+', $image_string);
                $image_string = base64_decode($image_string);

                if (!file_exists("uploads"))
                {
                    mkdir("uploads", 0700, TRUE);
                }
                $pic = static::getUsername(static::isLoggedIn()). date('-Y-m-d_h:i:s') . '.png';
                $path = 'uploads/' . $pic;
                if (file_put_contents($path, $image_string) != FALSE)
                {
                    static::uploadPic($pic, $user_id);
                    /*
                    echo '<meta http-equiv="refresh" content="100;url=' . static::get_project_root("profile") . '/profile">';
                    Route::redirect("profile");
                    */
                }
                else { echo "File upload failed, please try again."; }
            }
        }
    }
    
    public static function main_()
    {
        if(static::isLoggedIn())
        {
            //static::displayLoggedInHeader();
            static::parsePic();
            //static::displayFooter();
        }
        else
        {
            Route::redirect("login");
            exit();
        }
        static::create_view("profile");
    }
}
?>