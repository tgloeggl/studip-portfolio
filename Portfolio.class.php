<?php
require 'bootstrap.php';

/**
 * Portfolio.class.php
 *
 * @author  Till Gl�ggler <tgloeggl@uos.de>
 * @version 1.0
 */

require_once 'app/models/Abschluss.php';
require_once 'app/models/Helper.php';
require_once 'app/models/Perm.php';
require_once 'app/models/Permissions.php';
require_once 'app/models/Portfolios.php';
require_once 'app/models/PortfoliosStudiengangCombos.php';
require_once 'app/models/StudiengangCombos.php';
require_once 'app/models/Tags.php';
require_once 'app/models/TaskUserFiles.php';
require_once 'app/models/TaskUserFeedback.php';
require_once 'app/models/TaskUsers.php';
require_once 'app/models/Tasks.php';

class Portfolio extends StudIPPlugin implements HomepagePlugin, SystemPlugin
{

    public function __construct() {
        parent::__construct();

        if (Navigation::hasItem("/profile") 
                && $this->isActivated(get_userid(Request::username('username', 
                    $GLOBALS['auth']->auth['uname'])), 'user')
                /* && !$GLOBALS['perm']->have_perm('admin') */) {
            
            $navigation = new AutoNavigation(_('Portfolio'));
            $navigation->setURL(PluginEngine::getURL($this, array(), 'portfolio/index'));
            // $navigation->setImage(Assets::image_path('blank.gif'));
            Navigation::addItem('/profile/portfolio', $navigation);
        }
        
        if (Navigation::hasItem("/admin/config") 
                && $GLOBALS['perm']->have_perm('admin')) {

            $navigation = new AutoNavigation(_('Portfolio'));
            $navigation->setURL(PluginEngine::getURL($this, array(), 'admin/set/index'));
            // $navigation->setImage(Assets::image_path('blank.gif'));
            Navigation::addItem('/admin/config/portfolio', $navigation);
        }
    }

    public function getHomepageTemplate($user_id) {
        // ...
    }

    public function perform($unconsumed_path) {
        #$this->setupAutoload();
        $dispatcher = new Trails_Dispatcher(
            $this->getPluginPath() . '/app',
            rtrim(PluginEngine::getLink($this, array(), null), '/'),
            'portfolio'
        );

        $dispatcher->plugin = $this;
        $dispatcher->dispatch($unconsumed_path);
    }

    /*
    private function setupAutoload() {
        spl_autoload_register(function ($class) {
            include_once Portfolio::findFile(__DIR__ .'/app/models', $class);
        });
        // Portfolio_StudipAutoloader::addAutoloadPath(__DIR__ . '/app/models');
    }

    static function findFile($path, $class, $handle_namespace = true)
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

        $base =  $path . DIRECTORY_SEPARATOR . $class;
        if (file_exists($base . '.class.php')) {
            return $base . '.class.php';
        } elseif (file_exists($base . '.php')) {
            return $base . '.php';
        }
    }
     *
     */
}
