<?php

namespace App\Service;

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
}