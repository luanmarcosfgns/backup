<?php

namespace App\Service;

use DirectoryIterator;
use Exception;

class SelectFileFoldersService
{
    public static function moveZipFiles($sourceDirectory, $destinationDirectory)
    {

        $files = scandir($sourceDirectory); // Obtém todos os arquivos ZIP no diretório de origem

        foreach ($files as $file) {
            if (is_file($sourceDirectory . '/' . $file) && stripos($file, '.zip') !== false) {
                $destination = $destinationDirectory . '/' . $file;
                rename($sourceDirectory . '/' . $file, $destination);
            }
        }


    }

    public static function deleteFilesSQL($directory): void
    {
        $files = scandir($directory); // Obtém todos os arquivos e pastas dentro do diretório

        foreach ($files as $file) {
            if (!in_array($file, ['.', '..'])) {
                if (is_file($directory . '/' . $file)) {
                    unlink($directory . '/' . $file);
                }
            }

        }
    }

    public static function permanenceArchives(string $directory)
    {
        $iterator = new DirectoryIterator($directory);
        $now = time();
        $days = empty($_ENV['DAYS']) ? 3 : $_ENV['DAYS'];
        $expirationTime = $now - ($days * 24 * 60 * 60); // 10 days in seconds

        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isFile() && $fileInfo->getMTime() < $expirationTime) {
                unlink($fileInfo->getPathname());
            }
        }
    }
}