<?php

class Portfolio_StudipAutoloader extends StudipAutoloader {
    /**
     * Locate the file where the class is defined.
     * Handles possible namespaces by mapping the path elements to the
     * directory structure.
     *
     * @param string $class  the name of the class
     * @param bool   $handle_namespace Should namespaces be handled by
     *                                 converting into directory structure?
     *
     * @return string|null   the path, if found, otherwise null
     */
    private static function findFile($class, $handle_namespace = true)
    {
        // Handle possible namespace
        if ($handle_namespace && strpos($class, '\\') !== false) {
            // Convert namespace into directory structure
            $namespaced = str_replace('\\', DIRECTORY_SEPARATOR, $class);
            $namespaced = strtolower(dirname($namespaced)) . DIRECTORY_SEPARATOR . basename($namespaced);
            $class = basename($namespaced);

            if ($filename = self::findFile($namespaced, false)) {
                return $filename;
            }
        }

        foreach (self::$autoload_paths as $path) {
            $base =  $path . DIRECTORY_SEPARATOR . $class;
            if (file_exists($base . '.class.php')) {
                return $base . '.class.php';
            } elseif (file_exists($base . '.php')) {
                return $base . '.php';
            }
        }
    }
}