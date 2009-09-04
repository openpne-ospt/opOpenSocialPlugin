<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Application Config Form.
 *
 * @package    opOpenSocialPlugin
 * @subpackage form
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class ApplicationConfigForm extends sfForm
{
  public function configure()
  {
    $is_add_application = SnsConfigPeer::get('is_add_application', false);
    $is_add_application = (empty($is_add_application)) ? false : true;
    $is_view_profile_application = SnsConfigPeer::get('is_view_profile_application', true);
    $is_view_profile_application = (empty($is_view_profile_application)) ? false : true;
    $this->setWidgets(array(
      'is_add_application'          => new sfWidgetFormInputCheckbox(),
      'is_view_profile_application' => new sfWidgetFormInputCheckbox(),
      'home_application_limit'      => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'is_add_application'          => new sfValidatorBoolean(),
      'is_view_profile_application' => new sfValidatorBoolean(),
      'home_application_limit'      => new sfValidatorInteger(array('min' => 0)),
    ));

    $this->setDefaults(array(
      'is_add_application'          => $is_add_application,
      'is_view_profile_application' => $is_view_profile_application,
      'home_application_limit'      => SnsConfigPeer::get('home_application_limit', 3),
    ));

    $this->widgetSchema->setLabels(array(
      'is_add_application'          => 'メンバーによるアプリ追加を許可',
      'is_view_profile_application' => 'プロフィール画面にアプリを表示する',
      'home_application_limit'      => 'ホーム・プロフィール画面のアプリ表示上限',
    ));
    $this->widgetSchema->setNameFormat('application_config[%s]');
  }

  public function save()
  {
    foreach ($this->getValues() as $key => $value)
    {
      $snsConfig = SnsConfigPeer::retrieveByName($key);
      if (!$snsConfig)
      {
        $snsConfig = new SnsConfig();
        $snsConfig->setName($key);
      }
      $snsConfig->setValue($value);
      $snsConfig->save();
    }
    return true;
  }
}
