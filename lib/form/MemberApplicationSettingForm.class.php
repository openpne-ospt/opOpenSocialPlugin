<?php

/**
 * MemberApplication form.
 *
 * @package    OpenPNE3
 * @subpackage form
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

    $this->widgetSchema->setLabels(array(
      'is_disp_other' => '他のメンバーによる閲覧を許可する',
      'is_disp_home'  => 'ホーム/プロフィールに表示する',
    ));

    $this->widgetSchema->setNameFormat('member_app_setting[%s]');
  }
}
