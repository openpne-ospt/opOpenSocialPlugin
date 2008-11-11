<?php

/**
 * ApplicationI18n form base class.
 *
 * @package    form
 * @subpackage application_i18n
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 8807 2008-05-06 14:12:28Z fabien $
 */
class BaseApplicationI18nForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'           => new sfWidgetFormInput(),
      'directory_title' => new sfWidgetFormInput(),
      'screenshot'      => new sfWidgetFormInput(),
      'thumbnail'       => new sfWidgetFormInput(),
      'author'          => new sfWidgetFormInput(),
      'author_email'    => new sfWidgetFormInput(),
      'description'     => new sfWidgetFormTextarea(),
      'settings'        => new sfWidgetFormTextarea(),
      'views'           => new sfWidgetFormTextarea(),
      'version'         => new sfWidgetFormInput(),
      'updated_at'      => new sfWidgetFormDateTime(),
      'id'              => new sfWidgetFormInputHidden(),
      'culture'         => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'title'           => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'directory_title' => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'screenshot'      => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'thumbnail'       => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'author'          => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'author_email'    => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'description'     => new sfValidatorString(array('required' => false)),
      'settings'        => new sfValidatorString(array('required' => false)),
      'views'           => new sfValidatorString(array('required' => false)),
      'version'         => new sfValidatorString(array('max_length' => 64)),
      'updated_at'      => new sfValidatorDateTime(array('required' => false)),
      'id'              => new sfValidatorPropelChoice(array('model' => 'Application', 'column' => 'id', 'required' => false)),
      'culture'         => new sfValidatorPropelChoice(array('model' => 'ApplicationI18n', 'column' => 'culture', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('application_i18n[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ApplicationI18n';
  }


}
