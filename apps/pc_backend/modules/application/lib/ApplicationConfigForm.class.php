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
    $defaults = array();
    $defaults['is_add_application'] = false;
    $snsConfig = SnsConfigPeer::retrieveByName('is_add_application');
    if ($snsConfig && $snsConfig->getValue())
    {
      $defaults['is_add_application'] = $snsConfig->getValue();
    }

    $this->setWidgets(array(
      'is_add_application' => new sfWidgetFormInputCheckbox()
    ));

    $this->setValidators(array(
      'is_add_application' => new sfValidatorBoolean()
    ));

    $this->setDefaults($defaults);

    $this->widgetSchema->setLabels(array(
      'is_add_application' => 'メンバーがアプリを追加することを許可する'
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
