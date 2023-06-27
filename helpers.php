<?php
function abort($message, $code = 500)
{
    throw new Exception($message, $code);
}

function requireDirectory($directory): void
{
    $files = scandir($directory);

    foreach ($files as $file) {
        $filePath = $directory . '/' . $file;

        if (is_file($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) === 'php') {
            require $filePath;
        }
    }
}

