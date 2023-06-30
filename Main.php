<?php
require "src/Service/BackupDatabaseService.php";
require "src/Util/VariableSystem.php";
require "src/Service/CompressionDirectoryService.php";
require "src/Service/SelectFileFoldersService.php";
require "src/Service/BackupDirectoryService.php";
require "src/Service/SendGoogleDriveArchive.php";


use Analog\Analog;
use Analog\Handler\FirePHP;
use App\Service\BackupDatabaseService;
use App\Service\BackupDirectoryService;
use App\Service\CompressionDirectoryService;
use App\Service\SelectFileFoldersService;
use App\Service\SendFileGoogleDriveService;
use App\Service\SendGoogleDriveArchive;
use App\Util\VariableSystem;
use Dotenv\Dotenv;

class Main
{
    public static function exec()
    {
        VariableSystem::set();
        $dotenv = Dotenv::createMutable(__DIR__);
        $dotenv->safeLoad();
        ini_set('memory_limit', $_ENV['MEMORY_LIMIT']);
        BackupDatabaseService::load();
        CompressionDirectoryService::load();
        SelectFileFoldersService::moveZipFiles(__APP__ . '/storage/dumps', __APP__ . '/storage/files');
        SelectFileFoldersService::deleteFilesSQL(__APP__ . '/storage/dumps');
        BackupDirectoryService::load();
        SelectFileFoldersService::moveZipFiles(__APP__ . '/storage/dumps', __APP__ . '/storage/files');
        SelectFileFoldersService::permanenceArchives(__APP__ . '/storage/send');
        SendGoogleDriveArchive::store();
        if ($_ENV["SHARE_GOOGLE_DRIVE"] == "YES") {
            SelectFileFoldersService::moveZipFiles(__APP__ . '/storage/files', __APP__ . '/storage/send',);
        }

    }
}