<?php

namespace App\Service;

use Exception;
use Google\Client;
use Google\Service\Drive;
use Google_Service_Drive_Permission;

class SendGoogleDriveArchive
{
    public static function store()
    {
        try {
            $directory = __APP__ . '/storage/files';
            $files = BackupDirectoryService::listFiles($directory);
            foreach ($files as $fileName) {
                $client = new Client();
//                $client->setAccessToken($_ENV['GOOGLETOKEN']);
                $client->getOAuth2Service()->generateCodeVerifier();
                $dir = __APP__.'/credentials.json';
                if(!is_file(__APP__.'/credentials.json')){
                    echo "credencial não encontrada";
                    return false;
                }
                $client->addScope(Drive::DRIVE);
                $client->setAuthConfig($dir);
                $driveService = new Drive($client);
                $fileMetadata = new Drive\DriveFile(array(
                    'name' => basename($fileName)));
                $content = file_get_contents($fileName);

                $file = $driveService->files->create($fileMetadata, array(
                    'data' => $content,
                    'mimeType' => 'application/zip',
                    'uploadType' => 'resumable',
                    'fields' => 'id'));

                self::share($file,$driveService);
            }

        } catch (Exception $e) {
            echo "Error Message: " . $e;
        }

    }

    public static function share($file,$driveService)
    {
        $fileId = $file->id; // Substitua pelo ID do arquivo que você deseja compartilhar
        $email = $_ENV['SHARE_EMAIL']; // Substitua pelo e-mail com o qual você deseja compartilhar o arquivo

        $permission = new Google_Service_Drive_Permission();
        $permission->setEmailAddress($email);
        $permission->setType('user');
        $permission->setRole('reader');

        $driveService->permissions->create($fileId, $permission, ['sendNotificationEmail' => false]);
    }


}