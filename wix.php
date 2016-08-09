<?php
/* origin: https://github.com/wix/wix-sdk-sample-app-php/blob/master/php-demo-blog/php5/lib/Wix.php
 * Wix Class
 */

class Wix
{
    private $appId; // YOUR APP ID
    private $secret; // YOUR SECRET KEY
    private function base64UrlDecode($input)
    {
        return base64_decode(strtr($input, '-_', '+/'));
        //return base64_decode($input);
    }
    private function parseSignedRequest($signed_request)
    {
        list($encoded_sig, $payload) = explode('.', $signed_request, 2);
        // decode the data
        $sig = $this->base64UrlDecode($encoded_sig);

        $data = json_decode($this->base64UrlDecode($payload), true);

        // check sig
        $expected_sig = hash_hmac('sha256', $payload, $this->secret, $raw = true);
        if ($sig !== $expected_sig) {
            die('permission denied');
            return null;
        }
        return $data;
    }
    public function __construct($appId = null, $secret = null)
    {
        $this->appId = $appId !== null ? $appId : WIX_APP_IP;
        $this->secret = $secret !== null ? $secret : WIX_SECRET;
    }
    public function getAppId()
    {
        return $this->appId;
    }
    public function getSecret()
    {
        return $this->secret;
    }
    /*
     * returns the section-url the app should work relative on
     * If inside Wix, it'll return the parent url from the query line,
     * else, we'll return the relative REQUEST_URI for this file
     */
    public function getSectionUrl()
    {
        $parentUrl = isset($_GET['section-url']) ? $_GET['section-url'] : null;
        if (!$parentUrl) {
            // we're not using dirname, becouse if we did not specify a file dirname will give us
            // the parent directory of the REQUEST_URI. this is a little trick to get the right parent
            $parentUrl = substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], "/"));
        }
        return $parentUrl;
    }
    public function getTarget()
    {
        return isset($_GET['target']) ? $_GET['target'] : "_self";
    }
    public function getWidth()
    {
        // get width pixels
        return isset($_GET['width']) ? $_GET['width'] : null;
    }
    public function getViewMode()
    {
        // get width pixels
        return isset($_GET['viewMode']) ? $_GET['viewMode'] : null;
    }
    // returns your decoded instance - can return null
    public function getDecodedInstance()
    {
        $instance = isset($_GET['instance']) ? $_GET['instance'] : null;
        if ($instance === null) {
            error_log("Instance not found!");
            return null;
        }
        $instance = $this->parseSignedRequest($instance);

        return $instance;
    }
    public function getCompIdFromRequest() {
        $compId = isset($_GET['origCompId']) ? $_GET['origCompId'] : null;
        if ($compId === null) {
          $compId = isset($_GET['compId']) ? $_GET['compId'] : null;
          if ($compId === null) {
            error_log("compId not found!");
            return null;
          }
        }
        return $compId;
    }
    public function getOriginCompIdFromRequest() {
        $originCompId = isset($_GET['originCompId']) ? $_GET['originCompId'] : null;
        if ($originCompId === null) {
          error_log("compId not found!");
          return null;
        }
        return $originCompId;
    }
}
