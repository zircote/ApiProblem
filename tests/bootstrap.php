<?php
/**
 * 
 */
defined('PHPUNIT_TEST_ACTIVE') || define('PHPUNIT_TEST_ACTIVE', true);
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__DIR__)));
defined('APPLICATION_ENV') || define('APPLICATION_ENV', 'testing');

$config = @include dirname(APPLICATION_PATH) . '/vendor/composer/autoload_namespaces.php';

$paths = array_merge(array(get_include_path()), $config ? : array());

foreach ($paths as $key => $path) {
    if (is_array($path)) {
        foreach ($path as $p) {
            array_unshift($paths, $p);
        }
        unset($paths[$key]);
    }
}

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, $paths));
require_once APPLICATION_PATH . '/vendor/autoload.php';
