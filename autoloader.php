<?php
function autoload($className)
{
    //Писати шлях до кореневої папки проекту
    $rd='/var/www/test.com/public_html/OOP/';
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }

    $fileName .= $className . '.php';

    require $rd.$fileName;
}
spl_autoload_register('autoload');
?>
