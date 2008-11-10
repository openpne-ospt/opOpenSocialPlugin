<?php

/**
 * ApplicationSetting form base class.
 *
 * @package    form
 * @subpackage application_setting
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 8807 2008-05-06 14:12:28Z fabien $
 */
class BaseApplicationSettingForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'application_id' => new sfWidgetFormPropelSelect(array('model' => 'Application', 'add_empty' => true)),
      'member_id'      => new sfWidgetFormPropelSelect(array('model' => 'Member', 'add_empty' => true)),
      'name'           => new sfWidgetFormInput(),
      'value'          => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorPropelChoice(array('model' => 'ApplicationSetting', 'column' => 'id', 'required' => false)),
      'application_id' => new sfValidatorPropelChoice(array('model' => 'Application', 'column' => 'id', 'required' => false)),
      'member_id'      => new sfValidatorPropelChoice(array('model' => 'Member', 'column' => 'id', 'required' => false)),
      'name'           => new sfValidatorString(array('max_length' => 128)),
      'value'          => new sfValidatorString(array('max_length' => 255)),
    ));

    $this->widgetSchema->setNameFormat('application_setting[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ApplicationSetting';
  }


}
