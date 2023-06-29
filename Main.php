<?php
require "src/Service/BackupDatabaseService.php";
require "src/Util/VariableSystem.php";
require "src/Service/CompressionDirectoryService.php";
require "src/Service/SelectFileFoldersService.php";
require "src/Service/BackupDirectoryService.php";


use Analog\Analog;
use Analog\Handler\FirePHP;
use App\Service\BackupDatabaseService;
use App\Service\BackupDirectoryService;
use App\Service\CompressionDirectoryService;
use App\Service\SelectFileFoldersService;
use App\Service\SendFileGoogleDriveService;
use App\Util\VariableSystem;
use Dotenv\Dotenv;

class Main
{
    public static function exec()
    {
        ini_set('memory_limit', '4000M');
        VariableSystem::set();
        $dotenv = Dotenv::createMutable(__DIR__);
        $dotenv->safeLoad();
        BackupDatabaseService::load();
        CompressionDirectoryService::load();
        SelectFileFoldersService::moveZipFiles(__APP__ . '/storage/dumps', __APP__ . '/storage/files');
        SelectFileFoldersService::deleteFilesSQL(__APP__ . '/storage/dumps');
        BackupDirectoryService::load();
        SelectFileFoldersService::moveZipFiles(__APP__ . '/storage/dumps', __APP__ . '/storage/files');
        SelectFileFoldersService::permanenceArchives(__APP__ . '/storage/files');
    }
}