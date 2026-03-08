<?php

$dir = __DIR__;

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$files = [];

foreach ($iterator as $file) {
    if ($file->isDir()) continue;
    
    $path = $file->getPathname();
    if (strpos($path, 'vendor') !== false || strpos($path, 'node_modules') !== false || strpos($path, 'storage') !== false || strpos($path, '.git') !== false || strpos($path, '.env') !== false) {
        continue;
    }

    $ext = pathinfo($path, PATHINFO_EXTENSION);
    if (!in_array($ext, ['php', 'blade.php', 'js', 'css', 'json'])) {
        continue;
    }

    $content = file_get_contents($path);
    if ($content === false) continue;

    $newContent = str_replace(
        ['Kataloque', 'Kataloque', 'kataloque'],
        ['Kataloque', 'Kataloque', 'kataloque'],
        $content
    );

    if ($newContent !== $content) {
        file_put_contents($path, $newContent);
        echo "Replaced in $path\n";
    }
}
