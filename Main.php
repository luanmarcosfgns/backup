<?php
require "src/Service/BackupDatabaseService.php";
require "src/Util/VariableSystem.php";
require "src/Service/CompressionDirectoryService.php";
require "src/Service/SelectFileFoldersService.php";

use Analog\Analog;
use Analog\Handler\FirePHP;
use App\Service\BackupDatabaseService;
use App\Service\CompressionDirectoryService;
use App\Service\SelectFileFoldersService;
use App\Util\VariableSystem;
use Dotenv\Dotenv;

class Main
{
    public static function exec()
    {
        Analog::handler(FirePHP::init());
        VariableSystem::set();
        $dotenv = Dotenv::createMutable(__DIR__);
        $dotenv->safeLoad();
        BackupDatabaseService::load();
        CompressionDirectoryService::load();
        SelectFileFoldersService::moveZipFiles(__APP__ . '/storage/dumps',__APP__ . '/storage/files');
        SelectFileFoldersService::deleteDirectoryContents(__APP__ . '/storage/dumps');

    }
}