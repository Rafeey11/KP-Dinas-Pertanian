<?php
$directory = new RecursiveDirectoryIterator('resources/views');
$iterator = new RecursiveIteratorIterator($directory);
$files = new RegexIterator($iterator, '/^.+\.blade\.php$/i', RecursiveRegexIterator::GET_MATCH);

$count = 0;
foreach ($files as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    
    // Replace onsubmit="return confirm('...');" or onsubmit='return confirm("...");'
    $newContent = preg_replace('/onsubmit="return confirm\([^)]+\);?"/', 'class="form-delete"', $content);
    
    if ($content !== $newContent) {
        file_put_contents($path, $newContent);
        echo "Updated $path\n";
        $count++;
    }
}
echo "Done replacing onsubmit in $count files.\n";
