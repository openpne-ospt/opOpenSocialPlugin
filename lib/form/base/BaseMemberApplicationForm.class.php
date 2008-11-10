<?php

/**
 * MemberApplication form base class.
 *
 * @package    form
 * @subpackage member_application
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 8807 2008-05-06 14:12:28Z fabien $
 */
class BaseMemberApplicationForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'member_id'      => new sfWidgetFormPropelSelect(array('model' => 'Member', 'add_empty' => true)),
      'application_id' => new sfWidgetFormPropelSelect(array('model' => 'Application', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorPropelChoice(array('model' => 'MemberApplication', 'column' => 'id', 'required' => false)),
      'member_id'      => new sfValidatorPropelChoice(array('model' => 'Member', 'column' => 'id', 'required' => false)),
      'application_id' => new sfValidatorPropelChoice(array('model' => 'Application', 'column' => 'id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('member_application[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'MemberApplication';
  }


}
