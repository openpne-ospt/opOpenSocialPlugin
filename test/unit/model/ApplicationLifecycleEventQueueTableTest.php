<?php

include dirname(__FILE__).'/../../bootstrap/unit.php';
include dirname(__FILE__).'/../../bootstrap/database.php';

$table = Doctrine::getTable('ApplicationLifecycleEventQueue');

$conn = $table->getConnection();
$conn->beginTransaction();

$t = new lime_test(3, new lime_output_color());

// ->getQueueGroups()
$t->is(array(array(1)), $table->getQueueGroups(), '->getQueueGroups() returns array of queue groups');

// ->getQueuesByApplicationId()
$queues = $table->getQueuesByApplicationId(1, 1);
$t->isa_ok($queues, 'Doctrine_Collection', '->getQueuesByApplicationId() returns instance of Doctrine_Collection');
$t->is($queues->count(), 1, '->getQueuesByApplicationId() returns 1 object');

$conn->rollback();
