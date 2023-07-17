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

        $dotenv = Dotenv::createMutable(__DIR__);
        $dotenv->safeLoad();
        ini_set('memory_limit', $_ENV['MEMORY_LIMIT']);
        VariableSystem::set();
        BackupDatabaseService::load();
        CompressionDirectoryService::load();
        SelectFileFoldersService::moveZipFiles($_ENV['APP_DIRECTORIES'] . '/storage/dumps', $_ENV['APP_DIRECTORIES'] . '/storage/files');
        SelectFileFoldersService::deleteFilesSQL($_ENV['APP_DIRECTORIES'] . '/storage/dumps');
        BackupDirectoryService::load();
        SelectFileFoldersService::moveZipFiles($_ENV['APP_DIRECTORIES'] . '/storage/dumps', $_ENV['APP_DIRECTORIES'] . '/storage/files');
        SelectFileFoldersService::permanenceArchives($_ENV['APP_DIRECTORIES'] . '/storage/send');
        if ($_ENV["SHARE_GOOGLE_DRIVE"] == "YES") {
        SendGoogleDriveArchive::store();
        SelectFileFoldersService::moveZipFiles($_ENV['APP_DIRECTORIES'] . '/storage/files', $_ENV['APP_DIRECTORIES'] . '/storage/send',);
        }

    }
}