<?php
class ResetPassword extends Users
{
    function main_()
    {
        if (isset($_POST['resetpassword']))
        {
            $email = $_POST['email'];
            if (filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                if (static::query('SELECT id FROM camagru.users WHERE email=:email', array(':email'=>$email)))
                {
                    $subject = "Camagru password reset";
                    $cryptographically_strong = true;
                    $poken = bin2hex(openssl_random_pseudo_bytes(64, $cryptographically_strong));
                    if (mail($email, $subject, $poken))
                    {
                        echo "Email sent";
                    }
                    else
                    {
                        echo "Something went wrong, please try again";
                    }
                }
                else
                {
                    echo "The email address entered does not belong to a registered user";
                }
            }
            else
            {
                echo "Invalid email address entered";
            }
        }
    }
}