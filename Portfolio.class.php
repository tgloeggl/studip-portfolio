<?php
require 'bootstrap.php';

/**
 * Portfolio.class.php
 *
 * @author  Till Glöggler <tgloeggl@uos.de>
 * @version 0.1a
 */
class Portfolio extends StudIPPlugin implements HomepagePlugin
{

    public function __construct() {
        parent::__construct();

        if (Navigation::hasItem("/profile") && 
                $this->isActivated(get_userid(Request::username('username', 
                $GLOBALS['auth']->auth['uname'])), 'user')) {
            $navigation = new AutoNavigation(_('Portfolio'));
            $navigation->setURL(PluginEngine::getURL($this, array(), 'portfolio/index'));
            // $navigation->setImage(Assets::image_path('blank.gif'));
            Navigation::addItem('/profile/portfolio', $navigation);
        }
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
