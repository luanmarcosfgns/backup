<?php

namespace App\Service;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

class CompressionDirectoryService
{
    public static function load()
    {
        $files = self::listFiles($_ENV['APP_DIRECTORIES'] . '/storage/dumps/');
        if (!empty($files)) {
            self::compressFile($files);
        }

        $directories = self::listDiretory($_ENV['APP_DIRECTORIES'] . '/storage/dumps/');
        if (!empty($directories)) {
            self::compressFile(self::compressDirectory($directories));
        }


    }

    /**
     * @param $directory
     * @return array
     */
    public static function listFiles($directory): array
    {
        // Obtém a lista de itens no diretório
        $items = scandir($directory);

        // Remove os itens ' . ' e ' ..' da lista
        $items = array_diff($items, ['.', '..']);

        // Lista apenas os arquivos
        $files = [];
        foreach ($items as $item) {
            $filePath = $directory . '/' . $item;

            if (is_file($filePath)) {
                $files[] = $item;
            }

        }

        return $files;
    }

    /**
     * @param $directory
     * @return array
     */
    public static function listDiretory($directory): array
    {
        // Obtém a lista de itens no diretório
        $items = scandir($directory);

        // Remove os itens ' . ' e ' ..' da lista
        $items = array_diff($items, ['.', '..']);

        // Lista apenas os arquivos
        $files = [];
        foreach ($items as $item) {
            $filePath = $directory . '/' . $item;
            if (is_dir($filePath)) {
                $files[] = $item;
            }
        }

        return $files;
    }


    public static function compressDirectory($directories)
    {
        $zipFile = uniqid() . '.zip';
        $zip = new ZipArchive();
        $zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        foreach ($directories as $directory) {


            if (is_file($directory)) {
                $zip->addFile($directory);
            } elseif(is_dir($directory)) {
                $zip->addEmptyDir($directory);
            }


        }
        $zip->close();
        rename($_ENV['APP_DIRECTORIES'] .'/'.$zipFile, $_ENV['APP_DIRECTORIES'] . '/storage/dumps/' . $zipFile);
    }


    public static function compressFile($files)
    {
        foreach ($files as $file) {
            $zip = new ZipArchive;
            $zip->open($file . '.zip', ZipArchive::CREATE);
            $zip->addFile(
                $_ENV['APP_DIRECTORIES'] . '/storage/dumps/' . $file,
                $file
            );


// Fecha a pasta e salva o arquivo
            $zip->close();
            rename($_ENV['APP_DIRECTORIES'] . '/' . $file . '.zip', $_ENV['APP_DIRECTORIES'] . '/storage/dumps/' . $file . '.zip');
        }

    }


}