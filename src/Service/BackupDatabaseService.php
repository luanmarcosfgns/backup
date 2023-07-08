<?php

namespace App\Service;

class BackupDatabaseService
{

    public static function load()
    {

        $databases = explode(",", $_ENV['BACKUP_DATABASE']);
        foreach ($databases as $database) {

            $date = date("Y-m-d-H-i-s");

            $file = $database . '-' . $date . '-backup.sql';

            $directoryTo = $_ENV['APP_DIRECTORIES']. '/storage/dumps/';

            self::validDirectoryStorage($directoryTo);

            $host = $_ENV['HOST'];
            $username = $_ENV['USERNAME'];
            $password = $_ENV['PASSWORD'];

            $mysqlDump = "mysqldump -h $host -u $username -p$password $database >$directoryTo.$file";
            system($mysqlDump);
        }

    }

    private static function validDirectoryStorage(string $directoryTo)
    {
        if(!is_dir($directoryTo)){
            mkdir($directoryTo, 0775, true);
        }
    }
}