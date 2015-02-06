<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

// Requirements for DOMPDF
define('DOMPDF_ENABLE_AUTOLOAD', false);
require_once __DIR__.'/../vendor/dompdf/dompdf/dompdf_config.inc.php';

return $loader;
