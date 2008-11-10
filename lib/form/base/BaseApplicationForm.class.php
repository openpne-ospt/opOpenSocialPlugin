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
      'id'              => new sfWidgetFormInputHidden(),
      'url'             => new sfWidgetFormInput(),
      'culture'         => new sfWidgetFormInput(),
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
      'height'          => new sfWidgetFormInput(),
      'scrolling'       => new sfWidgetFormInput(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorPropelChoice(array('model' => 'Application', 'column' => 'id', 'required' => false)),
      'url'             => new sfValidatorString(array('max_length' => 128)),
      'culture'         => new sfValidatorString(array('max_length' => 7)),
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
      'height'          => new sfValidatorInteger(),
      'scrolling'       => new sfValidatorInteger(),
      'updated_at'      => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('application[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Application';
  }


}
