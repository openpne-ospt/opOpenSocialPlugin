<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opGadgetRenderingServlet
 *
 * @author Shogo Kawahara <kawahara@tejimaya.net>
 *
 */
class opGadgetRenderingServlet extends GadgetRenderingServlet {
  
  protected function appendJsConfig($context, $gadget, $hasForcedLibs)
  {
    $container = $context->getContainer();
    $containerConfig = $context->getContainerConfig();
    
    if ($hasForcedLibs) {
      $gadgetConfig = $containerConfig->getConfig($container, 'gadgets.features');
    } else {
      $gadgetConfig = array();
      $featureConfig = $containerConfig->getConfig($container, 'gadgets.features');
      foreach ($gadget->getJsLibraries() as $library) {
        $feature = $library->getFeatureName();
        if (! isset($gadgetConfig[$feature]) && ! empty($featureConfig[$feature])) {
          $gadgetConfig[$feature] = $featureConfig[$feature];
        }
      }
    }

    $requires = array();
    foreach ($gadget->getRequires() as $feature) {
      $requires[$feature->name] = new EmptyClass();
    }
    $gadgetConfig['core.util'] = $requires;

    foreach($gadgetConfig as $key => &$gc)
    {
      if ($key == "opensocial-0.8")
      {
        if(isset($gc['supportedFields']['person']))
        {
          $criteria = new Criteria();
          $criteria->add(OpensocialPersonFieldPeer::FIELD_NAME, null, Criteria::ISNOTNULL);
          $opPersonFields = OpensocialPersonFieldPeer::doSelect($criteria);
          $personFields  = array('id','name','thumbnailUrl','profileUrl');
          foreach ($opPersonFields as $opPersonField)
          {
            $personFields[] = $opPersonField->getFieldName();
          }

          $gc['supportedFields']['person'] = $personFields;
        }
      }
    }

    return "gadgets.config.init(" . json_encode($gadgetConfig) . ");\n";
  }
}
