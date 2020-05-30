<?php
class setup extends database
{
    public static function initialize()
    {
        static::create_db();
        static::create_table_users();
        static::create_table_verification_tokens();
        static::create_table_tokens();
        static::create_table_password_tokens();
        static::create_table_posts();
        static::create_table_comments();
    }
}
?>