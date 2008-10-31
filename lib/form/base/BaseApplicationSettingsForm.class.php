<?php

/**
 * ApplicationSettings form base class.
 *
 * @package    form
 * @subpackage application_settings
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 8807 2008-05-06 14:12:28Z fabien $
 */
class BaseApplicationSettingsForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'applications_id' => new sfWidgetFormPropelSelect(array('model' => 'Applications', 'add_empty' => true)),
      'member_id'       => new sfWidgetFormPropelSelect(array('model' => 'Member', 'add_empty' => true)),
      'name'            => new sfWidgetFormInput(),
      'value'           => new sfWidgetFormInput(),
      'id'              => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'applications_id' => new sfValidatorPropelChoice(array('model' => 'Applications', 'column' => 'id', 'required' => false)),
      'member_id'       => new sfValidatorPropelChoice(array('model' => 'Member', 'column' => 'id', 'required' => false)),
      'name'            => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'value'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'id'              => new sfValidatorPropelChoice(array('model' => 'ApplicationSettings', 'column' => 'id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('application_settings[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ApplicationSettings';
  }


}
