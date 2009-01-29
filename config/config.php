<?php
sfToolkit::addIncludePath(array(
  //Shindig
  dirname(__FILE__).'/../lib/vendor/Shindig/',
));
$this->dispatcher->connect('routing.load_configuration', array('opOpenSocialPluginRouting', 'listenToRoutingLoadConfigurationEvent'));
