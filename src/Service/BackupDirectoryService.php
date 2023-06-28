<?php

namespace App\Service;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

class BackupDirectoryService
{

    public static function load()
    {
        $listFiles = [];
        $directories = explode(",", $_ENV['BACKUP_DIRECTORIES']);
        foreach ($directories as $directory) {
            $listFiles[] = self::listFiles($directory);
        }
        dd($listFiles);

    }

    public  static function listFiles($directory) {
        $files = [];

        $contents = scandir($directory);
        foreach ($contents as $item) {
            // Ignore current directory and parent directory
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $directory . '/' . $item;

            if (is_dir($path)) {
                // It's a directory, recursively call the function to list files inside this directory
                $subdirectoryFiles = listFiles($path);

                // Add the found files to the files array
                $files = array_merge($files, $subdirectoryFiles);
            } else {
                // It's a file, add it to the files array
                $files[] = $path;
            }
        }

        // Return the list of files
        return $files;
    }




}