<?php

include dirname(__FILE__).'/../../bootstrap/unit.php';
include dirname(__FILE__).'/../../bootstrap/database.php';

$t = new lime_test(1, new lime_output_color());

// ->addApplication()
$t->diag('->addApplication()');
$application = Doctrine::getTable('Application')->addApplication('http://gist.github.com/raw/183507/ae5502d896121aebda501cbaadca55bcc1231efe/gistfile1.xsl', true, 'ja_JP');
$t->isa_ok($application, 'Application', '->addApplication() return Application object');
