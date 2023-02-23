<?php

$linhas = '';

$arquivo = fopen('/home/backupDumps/backup-Master/.env', 'r');

if (empty($arquivo)) {
    print "Arquivo não encontrado /n";
    die();

}

while (!feof($arquivo))
    $linhas .= fgets($arquivo, 1024);
fclose($arquivo);
foreach (json_decode($linhas) as $linha) {
    try {
// Parametros para serem setados
        $username = $linha->username;
        $password = $linha->password;
        $database = $linha->database;
        $host = $linha->host;
        $diretorio = $linha->diretorio;
        apagarArquvosAntigos($linha);

//data do evento
        $date = date("Y-m-d-H-i-s");
        $exec = '+++++++++++++++++++++++++++++++++++++++++++++++++++' . "\r\n";

// define nome do arquivo
        $file = $database . '-' . $date . '.sql';

        $backupFile = $diretorio . $file;
        $mysqlDump = "mysqldump -h $host -u $username -p$password $database >$backupFile";

        $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        if (!$conn) {
            $exec .= '1. Não foi possível conectar ';
        }
        $exec .= '1.Conexão bem sucedida' . "\r\n";
//gerar diretório caso não exista
        if (!file_exists($diretorio)) {
            mkdir($diretorio, 0777, true);
            $exec .= '1.1 Gerado Pasta dump' . "\r\n";
        }

//gerar backup
        system($mysqlDump);
        $exec .= '2.backup ' . $database . ' realizado' . "\r\n";

//Cria o arquivo para ser zipado
        $zip = new ZipArchive;
        $zip->open($file . '.zip', ZipArchive::CREATE);

// Adiciona um arquivo à pasta

        $zip->addFile(
            $backupFile,
            $file
        );

// Fecha a pasta e salva o arquivo
        $zip->close();
        $exec .= '3. zip do arquivo ' . $file . ' realizado';

//move o arquivo
        rename($file . '.zip', $backupFile . '.zip');
        $exec .= '4. zip movido para dumps ';
        $exec .= date("Y-m-d-H:i:s") . "\r\n";

//exclui o arquivo sql
        unlink($backupFile);
        $exec .= '5. apagado ' . $file . "\r\n";


    } catch (Exception $e) {
        $exec .= '6. erro no database ' . $linha->database . 'Mensagem' . $e->getMessage() . 'linha:' . $e->getLine() . "\r\n";
        //Variável $fp armazena a conexão com o arquivo e o tipo de ação.
        $fp = fopen($diretorio . 'backup.log', "a+");

//Escreve no arquivo aberto.
        fwrite($fp, $exec);

//Fecha o arquivo.
        fclose($fp);
        continue;
    }
    //Variável $fp armazena a conexão com o arquivo e o tipo de ação.
    $fp = fopen($diretorio . 'backup.log', "a+");

//Escreve no arquivo aberto.
    fwrite($fp, $exec);


}
function apagarArquvosAntigos($linha)
{
    try {
        // Define o diretório a ser verificado e limpo
        $dir = $linha->diretorio;

// Define a data limite para manter os arquivos (30 dias atrás)
        $date_limit = strtotime('-2 days');

// Percorre todos os arquivos do diretório
        foreach (glob($dir . '/*.zip') as $file) {
            // Verifica se o arquivo é um arquivo regular (não é diretório)
            if (is_file($file)) {
                // Verifica a extensão do arquivo
                $ext = pathinfo($file, PATHINFO_EXTENSION);

                // Verifica se a extensão é ZIP
                if ($ext == 'zip') {
                    // Verifica a data de modificação do arquivo
                    $mod_date = filemtime($file);

                    // Se a data de modificação for anterior à data limite, deleta o arquivo
                    if ($mod_date < $date_limit) {
                        unlink($file);
                        echo "Arquivo $file apagado com sucesso.<br>";
                    }
                }
            }
        }
    } catch (Exception $exception) {
        $exec .= '6. erro no database ' . $linha->database . 'Mensagem' . $e->getMessage() . 'linha:' . $e->getLine() . "\r\n";
        //Variável $fp armazena a conexão com o arquivo e o tipo de ação.
        $fp = fopen($diretorio . 'backup.log', "a+");
        //escreve no arquivo
        fwrite($fp, $exec);
        //Fecha o arquivo.
        fclose($fp);
    }
}