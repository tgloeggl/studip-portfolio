<?php
/**
 * PortfolioPluginController - Short description for file
 *
 * Long description for file (if any)...
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 3 of
 * the License, or (at your option) any later version.
 *
 * @author      Till Glöggler <tgloeggl@uos.de>
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GPL version 3
 * @category    Stud.IP
 */
class PortfolioPluginController extends StudipController
{

    var $container;

    public function __construct($dispatcher)
    {
        SimpleORMap::expireTableScheme();
        parent::__construct($dispatcher);
        $this->plugin = $dispatcher->plugin;

        if (file_exists('assets/images/sidebar/schedule-sidebar.png')) {
            $this->infobox_picture = 'sidebar/schedule-sidebar.png';
        } else {
            $this->infobox_picture =  'infobox/schedules.jpg';
        }

        $this->flash = Trails_Flash::instance();
    }

    public function before_filter(&$action, &$args)
    {
        parent::before_filter($action, $args);

        $this->set_layout($GLOBALS['template_factory']->open('layouts/base'));
        PageLayout::setTitle('Portfolio');

        $this->container['user'] = $GLOBALS['user'];
        $this->timeformat = '%d.%m.%Y, %R';
        $this->body_id = 'portfolio';

        PageLayout::addStylesheet($this->getPluginURL().'/assets/portfolio.css');
        PageLayout::addScript($this->getPluginURL().'/assets/portfolio.js');

        PageLayout::addStylesheet($this->getPluginURL().'/assets/vendor/chosen/chosen.min.css');
        PageLayout::addScript($this->getPluginURL().'/assets/vendor/chosen/chosen.jquery.min.js');

        PageLayout::addStylesheet($this->getPluginURL().'/assets/vendor/select2-3.5.1/select2.min.css');
        PageLayout::addScript($this->getPluginURL().'/assets/vendor/select2-3.5.1/select2.min.js');
        PageLayout::addScript($this->getPluginURL().'/assets/vendor/select2-3.5.1/select2_locale_de.js');

        PageLayout::addScript($this->getPluginURL().'/assets/vendor/fileupload/jquery.fileupload.js');
    }

    // customized #url_for for plugins
    function url_for($to)
    {
        $args = func_get_args();

        # find params
        $params = array();
        if (is_array(end($args))) {
            $params = array_pop($args);
        }

        # urlencode all but the first argument
        $args = array_map('urlencode', $args);
        $args[0] = $to;

        return PluginEngine::getURL($this->dispatcher->plugin, $params, join('/', $args));
    }

    /**
     * render given data as json, data is converted to utf-8
     *
     * @param unknown $data
     */
    function render_json($data)
    {
        $this->set_content_type('application/json;charset=utf-8');
        return $this->render_text(json_encode(studip_utf8encode($data)));
    }
}