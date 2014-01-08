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

// load code for older Stud.IP-Versions
if (version_compare($GLOBALS['SOFTWARE_VERSION'], "2.4", '<=')) {
    require_once 'compat/'. $GLOBALS['SOFTWARE_VERSION'] .'/StudipArrayObject.php';
    require_once 'compat/'. $GLOBALS['SOFTWARE_VERSION'] .'/Portfolio_SimpleCollection.php';
    require_once 'compat/'. $GLOBALS['SOFTWARE_VERSION'] .'/Portfolio_SimpleORMapCollection.php';
    require_once 'compat/'. $GLOBALS['SOFTWARE_VERSION'] .'/Portfolio_SimpleORMap.php';
    require_once 'compat/'. $GLOBALS['SOFTWARE_VERSION'] .'/Portfolio_StudipDocument.php';
    require_once 'compat/'. $GLOBALS['SOFTWARE_VERSION'] .'/Portfolio_StudyCourse.php';
} else {
    // for versions newer than 2.5 use the same stub
    require_once 'compat/2.5/Portfolio_SimpleCollection.php';
    require_once 'compat/2.5/Portfolio_SimpleORMapCollection.php';
    require_once 'compat/2.5/Portfolio_SimpleORMap.php';
    require_once 'compat/2.5/Portfolio_StudipDocument.php';
    require_once 'compat/2.5/Portfolio_StudyCourse.php';
}

require_once 'vendor/trails/trails.php';
require_once 'app/controllers/studip_controller.php';
require_once 'app/controllers/authenticated_controller.php';
require_once 'app/controllers/portfolio.php';