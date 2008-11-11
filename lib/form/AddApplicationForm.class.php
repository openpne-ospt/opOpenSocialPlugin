<?php
class AddApplicationForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'application_url' => new sfWidgetFormInput(),
    ));
    $this->setValidators(array(
      'application_url'      => new sfValidatorString(array(),array()),
    ));
    $this->widgetSchema->setNameFormat('contact[%s]');
  }
}
