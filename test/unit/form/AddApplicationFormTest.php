<?php

include dirname(__FILE__).'/../../bootstrap/unit.php';

$t = new lime_test(2, new lime_output_color());

$form = new AddApplicationForm();
$form->disableLocalCSRFProtection();

$form->bind(array('application_url' => 'http://example.com'));
$t->ok($form->isValid(), 'AddApplicationForm accepts valid form paramters.');

$form->bind(array('application_url' => ''));
$t->ok(!$form->isValid(), "AddApplicationForm doesn't accept invalid form paramters.");
