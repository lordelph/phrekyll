<?php
namespace Phrekyll;

use Phrekyll\Autoloader as Loader;

$path = realpath(dirname(__FILE__) . '/../');
set_include_path($path . PATH_SEPARATOR . get_include_path());

require_once $path . '/app/bootstrap.php';
