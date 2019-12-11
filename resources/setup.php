<?php
    try
    {
        Database::create_db();  
        Database::create_table_users();
        Database::create_table_tokens();
        Database::create_table_posts();        
    }
    catch(PDOException $e)
    {
        echo "Initialization error:<br>" . $e->getMessage();
    }
?>