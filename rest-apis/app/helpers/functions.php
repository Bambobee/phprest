<?php
spl_autoload_register(function ($className) {
    // Define possible directories where class files may exist
    $directories = [
        __DIR__ . '/../controllers/',
        __DIR__ . '/../models/',
        __DIR__ . '/../libraries/'
    ];

    // Loop through directories to find the file
    foreach ($directories as $directory) {
        $file = $directory . strtolower($className) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }

    // If the file is not found, show an error
    die("Class file for '$className' not found.");
});

?>