<?php
$path = realpath(dirname(__FILE__) . '/../');
set_include_path($path . PATH_SEPARATOR . get_include_path());

//Return loader instance.
require_once $path . '/Phrekyll/Autoloader.php';

return Phrekyll\Autoloader::getInstance();
