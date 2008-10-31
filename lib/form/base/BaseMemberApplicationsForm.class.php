<?php

/**
 * MemberApplications form base class.
 *
 * @package    form
 * @subpackage member_applications
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 8807 2008-05-06 14:12:28Z fabien $
 */
class BaseMemberApplicationsForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'member_id'       => new sfWidgetFormPropelSelect(array('model' => 'Member', 'add_empty' => true)),
      'applications_id' => new sfWidgetFormPropelSelect(array('model' => 'Applications', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorPropelChoice(array('model' => 'MemberApplications', 'column' => 'id', 'required' => false)),
      'member_id'       => new sfValidatorPropelChoice(array('model' => 'Member', 'column' => 'id', 'required' => false)),
      'applications_id' => new sfValidatorPropelChoice(array('model' => 'Applications', 'column' => 'id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('member_applications[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'MemberApplications';
  }


}
