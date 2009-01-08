<?php

/**
 * ApplicationI18n filter form.
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Shogo Kawahara
 */
class ApplicationI18nFormFilter extends BaseApplicationI18nFormFilter
{
  public function __construct($defaults = array(), $options = array(), $CSRFSecret = null)
  {
    return parent::__construct($defaults, $options, false);
  }

  public function configure()
  {
    $this->setWidgets(array(
      'title' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'title' => new sfValidatorPass(),
    ));

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    $this->widgetSchema->setNameFormat('application[%s]');
  }
}
