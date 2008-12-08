<?php

/**
 * MemberApplication form.
 *
 * @package    form
 * @subpackage member_application
 * @author     Shogo Kawahara<kawahara@tejimaya.net>
 */
class MemberApplicationSettingForm extends BaseMemberApplicationForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'is_disp_other' => new sfWidgetFormInputCheckbox(),
      'is_disp_home'  => new sfWidgetFormInputCheckbox(),
    ));
    $this->setValidators(array(
      'is_disp_other' => new sfValidatorBoolean(array('required' => false)),
      'is_disp_home'  => new sfValidatorBoolean(array('required' => false)),
    ));
    $this->widgetSchema->setNameFormat('member_app_setting[%s]');
  }

  public function save($moduleId)
  {
    $values = $this->getValues();
    $memberApplication = MemberApplicationPeer::retrieveByPk($moduleId);
    if (!$memberApplication)
    {
      return false;
    }

    $memberApplication->setIsDispOther($values['is_disp_other']);
    $memberApplication->setIsDispHome($values['is_disp_home']);
    $memberApplication->save();
    return true;
  }
}
