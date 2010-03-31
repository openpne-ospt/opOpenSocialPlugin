<?php

include dirname(__FILE__).'/../../bootstrap/unit.php';
include dirname(__FILE__).'/../../bootstrap/database.php';

$table = Doctrine::getTable('ApplicationLifecycleEventQueue');

$t = new lime_test(1, new lime_output_color());

// ->getQueueGroups()
$t->is(array(array(1, 'event.addapp')), $table->getQueueGroups(), '->getQueueGroups() returns array of queue groups');
