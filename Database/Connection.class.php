<?php

namespace Database;

class Connection extends \PDO
{
    public function __construct()
    {
        $file_connection = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Database' . DIRECTORY_SEPARATOR . 'Database.ini';
        if(!file_exists($file_connection))
            throw new \Exception('Arquivo de BD não encontrado');
        $rule = parse_ini_file($file_connection);
        try
        {
            parent::__construct(
                'mysql:host='.$rule['HOST'].';'.
                'dbname='.$rule['DATABASE'],
                $rule['USER'],
                $rule['PASSWORD'],
                array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            );

        }
        catch (\Exception $e)
        {
            echo $e->getMessage();
            die;
        }
    }
}