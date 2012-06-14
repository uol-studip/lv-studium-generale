<?php

require 'bootstrap.php';

/**
 * LV_SGPlugin.class.php
 *
 * ...
 *
 * @author  Matthias Janssen
 * @version 0.5
 */

class LV_SGPlugin extends StudIPPlugin implements SystemPlugin {

    public function __construct() {
        parent::__construct();
        $this->build_menu();

    }

    private function build_menu () {

#PageLayout::disableHeader();

        $plugin_title = 'LV-SG';
        $navigation = new AutoNavigation(_($plugin_title));
        $navigation->setURL(PluginEngine::GetURL($this, array(), 'main'));

        if ($GLOBALS['perm']->have_perm('root')) {
            $navigation->setImage($this->getPluginURL().'/assets/images/lv_sg.png');
        }

        Navigation::addItem('/lv_sgplugin', $navigation);

        #-----

#        $headline = 'Veranstaltungen f'.chr(252).'r das Studium Generale';
        $headline 
          = 'Veranstaltungsverzeichnis f'.chr(252).'r Gasth'.chr(246).'rende';

        $navigation = new AutoNavigation(_($headline));
        $navigation->setURL(PluginEngine::GetURL($this, array(), 'main'));
        Navigation::addItem('/lv_sgplugin/main', $navigation);

    }

    public function initialize () {
       	require_once 'bootstrap.php';
    }

    public function perform($unconsumed_path) {
        $dispatcher = new Trails_Dispatcher(
            $this->getPluginPath(),
            rtrim(PluginEngine::getLink($this, array(), null), '/'),
            'main'
        );
        $dispatcher->controller = $this;
        $dispatcher->dispatch($unconsumed_path);
    }
}

#?>
