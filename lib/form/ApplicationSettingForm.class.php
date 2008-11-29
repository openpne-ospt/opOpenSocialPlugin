<?php

/**
 * ApplicationSetting form.
 *
 * @package    form
 * @subpackage application_setting
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 * @version    SVN: $Id: sfPropelFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class ApplicationSettingForm extends OpenPNEFormAutoGenerate
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
    $memberApp = MemberApplicationPeer::retrieveByPk($modId);
    if (!$memberApp)
    {
      return false;
    }

    if ($memberApp->getMemberId() != $memberId)
    {
      return false;
    }

    if (!$memberApp->getApplication()->hasSettings())
    {
      return false;
    }

    $settings = $memberApp->getApplication()->getSettings();

    foreach ($settings as $key => $setting)
    {
      $param   = array();
      $choices = array();
      $param['Caption'] = $setting['displayName'];
      switch ($setting['type'])
      {
        case 'HIDDEN': continue 2;
        case 'BOOL' :
          $param['FormType'] = 'radio';
          $choices = array('1' => 'Yes', '0' => 'No');
          break;
        case 'ENUM' :
          $param['FormType'] = 'select';
          $choices = $setting['enumValues'];
          break;
        default :
          $param['FormType'] = 'textarea';
      }
      $this->widgetSchema[$key] = $this->generateWidget($param, $choices);
      $this->validatorSchema[$key] = $this->generateValidator($param, array_keys($choices));

      if ($setting['default'])
      {
        $this->setDefault($key, $setting['default']);
      }
    }

    $criteria = new Criteria();
    $criteria->add(ApplicationSettingPeer::MEMBER_APPLICATION_ID, $modId);
    $appSettings = ApplicationSettingPeer::doSelect($criteria);

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
      $appSetting = ApplicationSettingPeer::retrieveByMemberApplicationIdAndName($modId, $name);
      if (!$appSetting)
      {
        $appSetting = new ApplicationSetting();
        $appSetting->setMemberApplicationId($modId);
        $appSetting->setName($name);
      }
      $appSetting->setValue($value);
      $appSetting->save();
    }
  }
}
