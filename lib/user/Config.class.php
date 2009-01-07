<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Config of Shindig
 *
 * @author Shogo Kawahara <kawahara@tejimaya.net>
 */

class ConfigException extends Exception {}

class Config
{
  static private $config = false;
  static private function loadConfig()
  {
    $webprefix = sfContext::getInstance()->getController()->genUrl('@homepage');
    $webprefix = preg_replace('/(.+)\/$/', "$1", $webprefix);
    $shindigConfig = array(
      // Show debug backtrace's. Disable this on a production site
      'debug' => false,
      // Allow plain text security tokens, this is only here to allow the sample files to work. Disable on a production site
      'allow_plaintext_token' => true,
      // Compress the inlined javascript, saves upto 50% of the document size
      'compress_javascript' => true, 

      // The URL Prefix under which shindig lives ie if you have http://myhost.com/shindig/php set web_prefix to /shindig/php
      'web_prefix' => $webprefix, 
      // If you changed the web prefix, add the prefix to these too
      'default_js_prefix' => $webprefix.'/gadgets/js/', 
      'default_iframe_prefix' => $webprefix.'/gadgets/ifr?', 

      // The X-XRDS-Location value for your implementing container, if any, see http://code.google.com/p/partuza/source/browse/trunk/Library/XRDS.php for an example
      'xrds_location' => '',

      // Allow anonymous (READ) access to the profile information? (aka REST and JSON-RPC interfaces)
      // setting this to false means you have to be authenticated through OAuth to read the data
      'allow_anonymous_token' => true,	

      // The encryption keys for encrypting the security token, and the expiration of it. Make sure these match the keys used in your container/site
      'token_cipher_key' => 'INSECURE_DEFAULT_KEY',
      'token_hmac_key'   => 'INSECURE_DEFAULT_KEY', 
      'token_max_age'   => SnsConfigPeer::get('application_token_max_age', 60*60), 
       
      // Ability to customize the style thats injected into the gadget document. Don't forget to put the link/etc colors in shindig/config/container.js too!
      'gadget_css' => 'body,td,div,span,p{font-family:arial,sans-serif;} a {color:#0000cc;}a:visited {color:#551a8b;}a:active {color:#ff0000;}body{margin: 0px;padding: 0px;background-color:white;}',
      
      // P3P privacy policy to use for the iframe document
      'P3P' => 'CP="CAO PSA OUR"', 
      
      // The locations of the various required components on disk. If you did a normal svn checkout there's no need to change these
      'base_path'      => realpath(dirname(__FILE__).'/../vendor/Shindig').'/',
      'features_path'  => realpath(dirname(__FILE__) . '/../vendor/Shindig/features').'/',
      'container_path' => realpath(dirname(__FILE__) . '/../vendor/Shindig/config').'/',
      'javascript_path'=> realpath(dirname(__FILE__) . '/../vendor/Shindig/javascript').'/',

      // The OAuth SSL certificates to use, and the pass phrase for the private key  
      'private_key_file' => realpath(dirname(__FILE__) . '/../vendor/Shindig/certs').'/private.key', 
      'public_key_file'  => realpath(dirname(__FILE__) . '/../vendor/Shindig/certs').'/public.crt', 
      'private_key_phrase' => 'openpne3', 
      'jsondb_path' => realpath(dirname(__FILE__) . '/../vendor/Shindig/javascript/sampledata').'/canonicaldb.json',

      // Force these libraries to be external (included through <script src="..."> tags), this way they could be cached by the browser
      'focedJsLibs' => '', 

      // Configurable classes. Change these to the class name to use, and make sure the auto-loader can find them
      'blacklist_class'       => 'BasicGadgetBlacklist', 
      'remote_content'        => 'opBasicRemoteContent', 
      'security_token_signer' => 'BasicSecurityTokenDecoder', 
      'security_token'        => 'BasicSecurityToken', 
      // Caching back-end to use. Shindig ships with CacheFile and CacheMemcache out of the box
      'data_cache' => 'CacheFile',

      // New RESTful API data service classes to use
      'person_service'   => 'opJsonDbOpensocialService',
      'activity_service' => 'opJsonDbOpensocialService',
      'app_data_service' => 'opJsonDbOpensocialService',
      'messages_service' => 'opJsonDbOpensocialService',
      // Also scan these directories when looking for <Class>.php files. You can include multiple paths by seperating them with a , 
      'extension_class_paths' => '',
      
      'userpref_param_prefix' => 'up_',
      'libs_param_name' => 'libs', 

      // If you use CacheMemcache as caching backend, change these to the memcache server settings
      'cache_host' => 'localhost',
      'cache_port' => 11211,       
      'cache_time' => SnsConfigPeer::get('application_cache_time', 60*60),
      // If you use CacheFile as caching backend, this is the directory where it stores the temporary files
      'cache_root' => sfConfig::get('sf_app_cache_dir').'/plugins/opOpenSocialPlugin',

      // connection timeout setting for all curl requests, set this time something low if you want errors reported
      // quicker to the end user, and high (between 10 and 20) if your on a slow connection
      'curl_connection_timeout' => '10',

      // If your development server is behind a proxy, enter the proxy details here in 'proxy.host.com:port' format.
      'proxy' => ''
    );
    if (!self::$config)
    {
      self::$config = $shindigConfig;
    }
  }
  static function get($key, $default = null)
  {
    if (!self::$config) {
      self::loadConfig();
    }
    if (isset(self::$config[$key]))
    {
      return self::$config[$key];
    }
    else
    {
      if ($default)
      {
        return $default;
      }
      throw new ConfigException("Invalid Config Key");
    }
  }
}
