<?php

if (!class_exists('CSRFProtection')) {
    class CSRFProtection {
        public static function tokenTag() { return ''; }
    }
}

if (!class_exists('SkipLinks')) {
    class SkipLinks {
        public static function addIndex($name, $id) {}
        public static function addLink($name, $id) {}
    }
}

$version = substr($GLOBALS['SOFTWARE_VERSION'], 0, 3);

// load code for older Stud.IP-Versions
if (version_compare($version, "3.0", '<=')) {

    require_once 'compat/'. $version .'/StudipArrayObject.php';
    require_once 'compat/'. $version .'/Portfolio_SimpleCollection.php';
    require_once 'compat/'. $version .'/Portfolio_SimpleORMapCollection.php';
    require_once 'compat/'. $version .'/Portfolio_SimpleORMap.php';
    require_once 'compat/'. $version .'/Portfolio_StudipDocument.php';
    require_once 'compat/'. $version .'/Portfolio_StudyCourse.php';
    require_once 'compat/'. $version .'/UserStudyCourse.php';
} else {
    // for versions newer than 3.0 use the same stub
    require_once 'compat/3.0/Portfolio_SimpleCollection.php';
    require_once 'compat/3.0/Portfolio_SimpleORMapCollection.php';
    require_once 'compat/3.0/Portfolio_SimpleORMap.php';
    require_once 'compat/3.0/Portfolio_StudipDocument.php';
    require_once 'compat/3.0/Portfolio_StudyCourse.php';
}

require_once 'vendor/trails/trails.php';
require_once 'app/controllers/studip_controller.php';
require_once 'app/controllers/authenticated_controller.php';
require_once 'app/controllers/portfolio_plugin_controller.php';
require_once 'app/controllers/portfolio.php';