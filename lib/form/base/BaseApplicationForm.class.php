<?php

/**
 * Application form base class.
 *
 * @package    form
 * @subpackage application
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 8807 2008-05-06 14:12:28Z fabien $
 */
class BaseApplicationForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'        => new sfWidgetFormInputHidden(),
      'url'       => new sfWidgetFormInput(),
      'height'    => new sfWidgetFormInput(),
      'scrolling' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'        => new sfValidatorPropelChoice(array('model' => 'Application', 'column' => 'id', 'required' => false)),
      'url'       => new sfValidatorString(array('max_length' => 128)),
      'height'    => new sfValidatorInteger(),
      'scrolling' => new sfValidatorInteger(),
    ));

    $this->widgetSchema->setNameFormat('application[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Application';
  }

  public function getI18nModelName()
  {
    return 'ApplicationI18n';
  }

  public function getI18nFormClass()
  {
    return 'ApplicationI18nForm';
  }

}
