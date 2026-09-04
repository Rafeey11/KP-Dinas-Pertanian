<?php
$directory = new RecursiveDirectoryIterator('resources/views');
$iterator = new RecursiveIteratorIterator($directory);
$files = new RegexIterator($iterator, '/^.+\.blade\.php$/i', RecursiveRegexIterator::GET_MATCH);

$replacements = [
    '<div style="background:#fff;border-radius:var(--rounded-lg);border:1px solid var(--color-border);box-shadow:var(--shadow-sm);overflow:hidden;">' => '<div class="card">',
    '<div style="background:#fff;border-radius:var(--rounded-lg);border:1px solid var(--color-border);box-shadow:var(--shadow-sm);padding:var(--sp-4);margin-bottom:var(--sp-6);">' => '<div class="card card-filter">',
    '<div style="background:#fff;border-radius:var(--rounded-lg);border:1px solid var(--color-border);box-shadow:var(--shadow-sm);padding:var(--sp-6);">' => '<div class="card card-padded">',
    '<div style="background:#fff;border-radius:var(--rounded-lg);border:1px solid var(--color-border);box-shadow:var(--shadow-sm);">' => '<div class="card">',
];

$count = 0;
foreach ($files as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    $newContent = $content;
    
    foreach ($replacements as $search => $replace) {
        $newContent = str_replace($search, $replace, $newContent);
    }
    
    if ($content !== $newContent) {
        file_put_contents($path, $newContent);
        echo "Updated $path\n";
        $count++;
    }
}
echo "Done replacing inline styles in $count files.\n";
