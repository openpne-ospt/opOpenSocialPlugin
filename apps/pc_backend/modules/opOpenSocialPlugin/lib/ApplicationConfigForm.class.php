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
 * @author     Shogo Kawahara <kawahara@bucyou.net>
 */
class ApplicationConfigForm extends sfForm
{
  protected function getSnsConfig($name, $default = null)
  {
    return Doctrine::getTable('SnsConfig')->get($name, $default);
  }

  public function configure()
  {
    $addApplicationRuleChoices =  Doctrine::getTable('Application')->getAddApplicationRuleChoices();
    $this->setWidgets(array(
      'add_application_rule' => new sfWidgetFormChoice(array('choices' => $addApplicationRuleChoices)),
      'opensocial_activity_post_limit_time' => new sfWidgetFormInput(),
      'opensocial_is_enable_mobile' => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'add_application_rule' => new sfValidatorChoice(array('choices' => array_keys($addApplicationRuleChoices))),
      'opensocial_activity_post_limit_time' => new sfValidatorInteger(array('min' => 0)),
      'opensocial_is_enable_mobile' => new sfValidatorBoolean(),
    ));

    $this->setDefaults(array(
      'add_application_rule' => (int)$this->getSnsConfig('add_application_rule', ApplicationTable::ADD_APPLICATION_DENY),
      'opensocial_activity_post_limit_time' => (int)$this->getSnsConfig('opensocial_activity_post_limit_time', 30),
      'opensocial_is_enable_mobile' => (boolean)$this->getSnsConfig('opensocial_is_enable_mobile', false)
    ));

    $this->widgetSchema->setLabels(array(
      'add_application_rule' => 'Allow the SNS members to add apps',
      'opensocial_activity_post_limit_time' => 'Continuous activity post prohibition time (s)',
      'opensocial_is_enable_mobile' => 'Enable mobile Apps'
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
