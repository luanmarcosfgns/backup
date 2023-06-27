<?php

namespace App\Service;

class SelectFileFoldersService
{
    public static function moveZipFiles($sourceDirectory, $destinationDirectory)
    {
        if (!is_dir($sourceDirectory) || !is_dir($destinationDirectory)) {
            throw new Exception("Diretório de origem ou destino inválido");
        }

        $files = glob($sourceDirectory . '/*.zip'); // Obtém todos os arquivos ZIP no diretório de origem

        foreach ($files as $file) {
            if (is_file($file)) {
                $destination = $destinationDirectory . '/' . basename($file);

                // Move o arquivo ZIP para o diretório de destino
                if (!rename($file, $destination)) {
                    throw new Exception("Falha ao mover o arquivo: " . basename($file));
                }
            }
        }

        echo "Arquivos ZIP movidos com sucesso para o diretório de destino.";
    }

    public static function deleteDirectoryContents($directory): void
    {
        $files = glob($directory . '/*'); // Obtém todos os arquivos e pastas dentro do diretório

        foreach ($files as $file) {
            if (is_file($file)) {
                // Se for um arquivo, remove-o
                unlink($file);
            } elseif (is_dir($file)) {
                // Se for uma pasta, chama recursivamente a função para remover seu conteúdo
                deleteDirectoryContents($file);
                // Remove a pasta vazia
                rmdir($file);
            }
        }
    }
}