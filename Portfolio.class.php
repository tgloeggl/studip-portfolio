<?php
require 'bootstrap.php';

/**
 * Portfolio.class.php
 *
 * @author  Till Glöggler <tgloeggl@uos.de>
 * @version 0.1a
 */


require_once dirname(__FILE__) . '/app/controllers/portfolio.php';

// load legacy code for older Stud.IP-Versions
if (version_compare($GLOBALS['SOFTWARE_VERSION'], "2.4", '<=')) {
    require_once 'compat/'. $GLOBALS['SOFTWARE_VERSION'] .'/StudipArrayObject.php';
    require_once 'compat/'. $GLOBALS['SOFTWARE_VERSION'] .'/EPP_SimpleCollection.php';
    require_once 'compat/'. $GLOBALS['SOFTWARE_VERSION'] .'/EPP_SimpleORMapCollection.php';
    require_once 'compat/'. $GLOBALS['SOFTWARE_VERSION'] .'/EPP_SimpleORMap.php';
    require_once 'compat/'. $GLOBALS['SOFTWARE_VERSION'] .'/EPP_StudipDocument.php';
    require_once 'compat/'. $GLOBALS['SOFTWARE_VERSION'] .'/CourseMember.php';
} else {
    // for version starting from 2.5 use the same stub
    require_once 'compat/2.5/EPP_SimpleCollection.php';
    require_once 'compat/2.5/EPP_SimpleORMapCollection.php';
    require_once 'compat/2.5/EPP_SimpleORMap.php';
    require_once 'compat/2.5/EPP_StudipDocument.php';
}

class Portfolio extends StudIPPlugin implements HomepagePlugin
{

    public function __construct() {
        parent::__construct();

        $navigation = new AutoNavigation(_('Portfolio'));
        $navigation->setURL(PluginEngine::getURL($this, array(), 'index'));
        // $navigation->setImage(Assets::image_path('blank.gif'));
        Navigation::addItem('/profile/portfolio', $navigation);
    }

    public function initialize () {

    
        PageLayout::addStylesheet($this->getPluginURL().'/assets/portfolio.css');
        PageLayout::addScript($this->getPluginURL().'/assets/portfolio.js');
    }

    public function getHomepageTemplate($user_id) {
        // ...
    }

    public function perform($unconsumed_path) {
        $this->setupAutoload();
        $dispatcher = new Trails_Dispatcher(
            $this->getPluginPath() . '/app',
            rtrim(PluginEngine::getLink($this, array(), null), '/'),
            'portfolio'
        );

        $dispatcher->plugin = $this;
        $dispatcher->dispatch($unconsumed_path);
    }

    private function setupAutoload() {
        if (class_exists("StudipAutoloader")) {
            StudipAutoloader::addAutoloadPath(__DIR__ . '/app/models');
        } else {
            spl_autoload_register(function ($class) {
                include_once __DIR__ . $class . '.php';
            });
        }
    }
}
