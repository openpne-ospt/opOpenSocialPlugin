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
    $is_view_profile_application = (empty($is_view_profile_application)) ? false : true;
    $this->setWidgets(array(
      'is_allow_add_application'    => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'is_allow_add_application'    => new sfValidatorBoolean(),
    ));

    $this->setDefaults(array(
      'is_allow_add_application'    => (bool)Doctrine::getTable('SnsConfig')->get('is_allow_add_application', false),
    ));

    $this->widgetSchema->setLabels(array(
      'is_allow_add_application'          => 'メンバーによるアプリ追加を許可',
    ));
    $this->widgetSchema->setNameFormat('application_config[%s]');
  }

  public function save()
  {
    foreach ($this->getValues() as $key => $value)
    {
      $snsConfig = Doctrine::getTable('SnsConfig')->findOneByName($key);
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
