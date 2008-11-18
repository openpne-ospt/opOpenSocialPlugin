<?php
set_include_path(get_include_path().PATH_SEPARATOR.dirname(__FILE__).'/../lib/vendor/Shindig/');
$this->dispatcher->connect('routing.load_configuration', array('opOpenSocialPluginRouting', 'listenToRoutingLoadConfigurationEvent'));
