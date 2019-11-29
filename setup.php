<?php
    try
    {
        Database::create_db();   
    }
    catch(PDOException $e)
    {
        echo $sql . "<br>" . $e->getMessage();
    }
?>