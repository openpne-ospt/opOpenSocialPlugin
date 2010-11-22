<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * MemberApplicationSetting form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class MemberApplicationSettingForm extends sfForm
{
  public function __construct($appSetting = array(),$options = array(), $CSRFSecret = null)
  {
    parent::__construct(array(), $options, $CSRFSecret);

    foreach ($appSetting as $setting)
    {
      $this->setDefault($setting->getName(), $setting->getValue());
    }
  }

  public function configure()
  {
    $this->widgetSchema->setNameFormat('setting[%s]');
  }

  public function setConfigWidgets($memberId, $modId)
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('Escaping'));

    $memberApp = MemberApplicationPeer::retrieveByPk($modId);
    if (!$memberApp)
    {
      return false;
    }

    if ($memberApp->getMemberId() != $memberId)
    {
      return false;
    }

    if (!$memberApp->getApplication()->hasSetting())
    {
      return false;
    }

    $settings = $memberApp->getApplication()->getSettings();

    foreach ($settings as $key => $setting)
    {
      $param   = array();
      $choices = array();
      $param['IsRequired'] = false;
      $param['Caption'] = sfOutputEscaper::escape(sfConfig::get('sf_escaping_method'), $setting['displayName']);
      if (empty($setting['type']) || $setting['type'] == 'HIDDEN')
      {
        continue;
      }
      switch ($setting['type'])
      {
        case 'BOOL' :
          $param['FormType']  = 'radio';
          $choices = array('1' => 'Yes', '0' => 'No');
          break;
        case 'ENUM' :
          $param['FormType'] = 'select';
          $choices = $setting['enumValues'];
          break;
        default :
          $param['FormType']  = 'input';
          $param['ValueType'] = '';
      }
      $this->widgetSchema[$key] = opFormItemGenerator::generateWidget($param, $choices);
      $this->validatorSchema[$key] = opFormItemGenerator::generateValidator($param, array_keys($choices));

      if ($setting['default'])
      {
        $this->setDefault($key, $setting['default']);
      }
    }

    $appSettings = MemberApplicationSettingPeer::getSettingsByMemberApplicationId($modId);

    foreach($appSettings as $appSetting)
    {
      $this->setDefault($appSetting->getName(), $appSetting->getValue());
    }

    return true;
  }
  
  public function save($modId)
  {
    $values = $this->getValues();

    foreach ($values as $name => $value)
    {
      $appSetting = MemberApplicationSettingPeer::retrieveByMemberApplicationIdAndName($modId, $name);
      if (!$appSetting)
      {
        $appSetting = new MemberApplicationSetting();
        $appSetting->setMemberApplicationId($modId);
        $appSetting->setName($name);
      }
      $appSetting->setValue($value);
      $appSetting->save();
    }
  }
}
