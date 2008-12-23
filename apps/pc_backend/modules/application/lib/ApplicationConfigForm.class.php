<?php
/**
 * Application Config Form.
 *
 * @package    OpenPNE
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
      'application_max_token_age'   => new sfWidgetFormInput(),
      'application_cache_time'      => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'is_add_application'          => new sfValidatorBoolean(),
      'is_view_profile_application' => new sfValidatorBoolean(),
      'home_application_limit'      => new sfValidatorInteger(array('min' => 0)),
      'application_max_token_age'   => new sfValidatorInteger(array('min' => 0)),
      'application_cache_time'      => new sfValidatorInteger(array('min' => 0)),
    ));

    $this->setDefaults(array(
      'is_add_application'          => $is_add_application,
      'is_view_profile_application' => $is_view_profile_application,
      'home_application_limit'      => SnsConfigPeer::get('home_application_limit', 3),
      'application_max_token_age'   => SnsConfigPeer::get('application_max_token_age', 60*60),
      'application_cache_time'      => SnsConfigPeer::get('application_cache_time', 24*60*60),
    ));

    $this->widgetSchema->setLabels(array(
      'is_add_application'          => 'メンバーによるアプリ追加を許可',
      'is_view_profile_application' => 'プロフィール画面にアプリを表示する',
      'home_application_limit'      => 'ホーム・プロフィール画面のアプリ表示上限',
      'application_max_token_age'   => 'トークン有効期限(秒)',
      'application_cache_time'      => 'キャッシュ有効期限(秒)',
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
