<?php

class LIFX_Http {

    const API_URL = 'https://api.lifx.com';
    const API_VERSION = 'v1beta1';

    protected $header;

    /**
     * Construct the LIFX Http Client
     * You can get an authentication token at https://cloud.lifx.com/settings
     * 
     * @param string $token Authentication token
     */
    public function __construct($token) {
        $this->setHeader($token);
    }

    /**
     * Gets lights belonging to the authenticated account. Filter the lights using selectors.
     * 
     * @param string $selector Filter default: all.
     * @return array Returns the list of bulbs belonging to the account.
     * 
     * https://developer.lifx.com/#list-lights
     */
    public function getLights($selector = "all") {
        $url = $this->generateUrl("lights", $selector);
        $result = $this->curlRequest("GET", $url);
        return json_decode($result, true);
    }

    /**
     * Turn off lights if they are on, or turn them on if they are off. 
     * Physically powered off lights are ignored.
     * 
     * @param string $selector Filter default: all.
     * @return array Returns the list of affected bulbs and their response.
     * 
     * https://developer.lifx.com/#toggle-power
     */
    public function toggleLights($selector = "all") {
        $url = $this->generateUrl("lights", $selector, "toggle");
        $result = $this->curlRequest("POST", $url);
        return json_decode($result, true);
    }
    
    /**
     * Turn lights on, or turn lights off.
     * 
     * @param string $state on | off.
     * @param float $duration Fade to the given state over a duration of seconds.
     * @param string $selector Filter default: all.
     * @return array Returns the list of affected bulbs and their response.
     * 
     * https://developer.lifx.com/#set-power
     */
    public function powerLights($state, $duration = 1, $selector = "all") {
        $url = $this->generateUrl("lights", $selector, "power");
        $data = array("state" => $state, "duration" => $duration);
        $result = $this->curlRequest("PUT", $url, $data);
        return json_decode($result, true);
    }
    
    /**
     * Set the lights to any color.
     * 
     * @param string $color refer to: https://developer.lifx.com/#colors
     * @param float $duration Fade to the given state over a duration of seconds.
     * @param boolean $poweron Turn on first? Defaults to 'true'.
     * @param string $selector Filter default: all.
     * @return array Returns the list of affected bulbs and their response.
     * 
     * https://developer.lifx.com/#set-color
     */
    public function setColor($color, $duration = 1, $poweron = true, $selector = "all") {
        $url = $this->generateUrl("lights", $selector, "color");
        $data = array("color" => $color, "duration" => $duration, "power_on" => $poweron);
        $result = $this->curlRequest("PUT", $url, $data);
        return json_decode($result, true);
    }
    
    /**
     * Set and sends the cURL Request to the LIFX API.
     * 
     * @param string $request Method Request GET|POST|PUT.
     * @param string $url The URL of the request method.
     * @param array $data Post Params if needed.
     * @return string Returns the cURL response (json encoded).
     * 
     */
    private function curlRequest($request, $url, $data = null){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $request);
        if(isset($data)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $url);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    private function generateUrl($module, $selector, $action = "") {
        return self::API_URL . "/" . self::API_VERSION . "/" . $module . "/" . $selector . "/" . $action;
    }

    private function setHeader($token) {
        $this->header = array("Authorization: Bearer " . $token);
    }

}
