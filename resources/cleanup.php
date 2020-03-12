 <?php

    $results = query("SELECT id, post FROM camagru.posts");
    foreach ($results as $result)
    {
        print $result;
        // if (!file_exists($result))
        // {
        //     query("DELETE post FROM camagru.posts WHERE id = ");
        // }
    }
 ?>