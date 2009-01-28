<?php
$webprefix = sfContext::getInstance()->getController()->genUrl('@homepage');
$webprefix = preg_replace('/(.+)\/$/',"$1", $webprefix);
$shindigConfig = array(
  'debug' => false,
  'web_prefix' => $webprefix,
  'default_js_prefix' => $webprefix.'/gadgets/js/',
  'default_iframe_prefix' => $webprefix.'/gadgets/ifr?',

  'allow_plaintext_token' => false,
  'allow_anonymous_token' => false,

  'token_cipher_key' => SnsConfigPeer::get('shindig_token_cipher_key'),
  'token_hmac_key' => SnsConfigPeer::get('shindig_token_hmac_key'),
  'token_max_age' => SnsConfigPeer::get('shindig_token_max_age', 60*60),
  
  'base_path'      => realpath(dirname(__FILE__).'/..').'/',
  'features_path'  => realpath(dirname(__FILE__) . '/../features').'/',
  'container_path' => realpath(dirname(__FILE__) . '/../config').'/',
  'javascript_path'=> realpath(dirname(__FILE__) . '/../javascript').'/',

  'private_key_file' => realpath(dirname(__FILE__) . '/../certs').'/private.key', 
  'public_key_file'  => realpath(dirname(__FILE__) . '/../certs').'/public.crt',
  'private_key_phrase' => 'openpne3', 
  'jsondb_path' => realpath(dirname(__FILE__) . '/../javascript/sampledata').'/canonicaldb.json',

  'remote_content'   => 'opBasicRemoteContent',

  'person_service'   => 'opJsonDbOpensocialService',
  'activity_service' => 'opJsonDbOpensocialService',
  'app_data_service' => 'opJsonDbOpensocialService',
  'messages_service' => 'opJsonDbOpensocialService',

  'cache_time' => SnsConfigPeer::get('shindig_cache_time', 60*60),
  'cache_root' => sfConfig::get('sf_app_cache_dir').'/plugins/opOpenSocialPlugin',

  'curl_connection_timeout' => '15',
);
