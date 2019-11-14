<?php
    class DB
    {
        private static function connect() 
        {
            try
            {
                $pdo = new PDO('mysql:hostname=127.0.0.1; dbname=camagru;', 'root', 'root');
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $pdo;
            }
            catch (PDOException $e)
            {
                echo "Error: " . $e->getMessage();
            }
        }
        public static function query($query, $params = array())
        {
            try
            {
                $statement = self::connect()->prepare($query);
                $statement->execute($params);
                if (explode(' ', $query)[0] == 'SELECT')
                {
                    $data = $statement->fetchAll();
                    return $data;
                }
            }
            catch (PDOException $e)
            {
                echo "Error: " . $e->getMessage();
            }
        }
    }