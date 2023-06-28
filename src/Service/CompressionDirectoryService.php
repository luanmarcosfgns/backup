<?php

namespace App\Service;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

class CompressionDirectoryService
{
    public static function load()
    {
        $files = self::listFiles(__APP__ . '/storage/dumps/');
        if (!empty($files)) {
            self::compressFile($files);
        }

        $directories = self::listDiretory(__APP__ . '/storage/dumps/');
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
        foreach ($directories as $directory) {


            $zipFile = __APP__ . '/storage/dumps/' . uniqid() . ' . zip';

            // Cria uma instância da classe ZipArchive
            $zip = new ZipArchive();

            // Abre o arquivo ZIP para escrita
            if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                // Obtém a lista de todos os arquivos e pastas no diretório
                $files = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($directory),
                    RecursiveIteratorIterator::LEAVES_ONLY
                );

                foreach ($files as $file) {
                    // Ignora o diretório atual e o diretório pai
                    if (!$file->isDir()) {
                        $filePath = $file->getRealPath();

                        // Obtém o caminho relativo do arquivo em relação à pasta base
                        $relativePath = substr($filePath, strlen($directory) + 1);

                        // Adiciona o arquivo ao arquivo ZIP com seu caminho relativo
                        $zip->addFile($filePath, $relativePath);
                    }
                }

                // Fecha o arquivo ZIP
                $zip->close();


            } else {
                abort("Não foi possivel zipar o arquivo");
            }
        }
    }


    public static function compressFile($files)
    {
        foreach ($files as $file) {
            $zip = new ZipArchive;
            $zip->open($file . '.zip', ZipArchive::CREATE);
            $zip->addFile(
                __APP__ . '/storage/dumps/'.$file,
                $file
            );


// Fecha a pasta e salva o arquivo
            $zip->close();
            rename(__APP__ .'/'.$file.'.zip',__APP__ . '/storage/dumps/'.$file.'.zip');
        }

    }


}