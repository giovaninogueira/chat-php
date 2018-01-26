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
            parent::__construct($rule['DRIVER'].':'.'host='.$rule['HOST'] . ';' . $rule['DATABASE'], $rule['USER'], $rule['PASSWORD']);
        }
        catch (\Exception $e)
        {
            echo $e->getMessage();
            die;
        }
    }
}