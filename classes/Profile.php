<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Profile extends Users
{
    private static function uploadPic($imgname)
    {
        if (static::isLoggedIn())
        {
            $likes = 0;
            $comments = 0;
            $user_id = static::isLoggedIn();
            static::query('INSERT INTO ' . static::get_db_name() . '.posts (imgname, likes, comments, user_id) VALUES (:imgname, :likes, :comments, :user_id)',
                        array(':imgname'=>$imgname, ':likes'=>$likes, ':comments'=>$comments, ':user_id'=>$user_id));
        }
    }

    private static function parsePic()
    {
        if (static::isLoggedIn())
        {
            if (!empty($_FILES['img']['name']))
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
                        static::uploadPic($img);
                        $return = array('status'=>200, 'message'=>"image uploaded successfully!");      
                    }
                    else { $return = array('status'=>403, 'message'=>"image upload failed, please try again."); }
                }
                else { $return = array('status'=>403, 'message'=>"only JPG, JPEG and PNG files allowed"); }
                print_r(json_encode($return));
            }
            if (isset($_POST['image']))
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
                    static::uploadPic($pic);
                    $return = array('status'=>200, 'message'=>"image uploaded successfully!");
                    http_response_code(200);      
                }
                else { $return = array('status'=>403, 'message'=>"image upload failed, please try again."); http_response_code(403); }
                print_r(json_encode($return));
            }
        }
    }
    
    public static function main_()
    {
        if(static::isLoggedIn())
        {
            static::create_view("profile");
            //static::displayLoggedInHeader();
            static::parsePic();
            //static::displayFooter();
        }
        else
        {
            Route::redirect("login");
            exit();
        }
    }
}
?>