<?php
if (! class_exists('SmsIR_UltraFastSend')) {
    class SmsIR_UltraFastSend
    {

        /**
         * Gets API Ultra Fast Send Url.
         *
         * @return string Indicates the Url
         */
        protected function getAPIUltraFastSendUrl()
        {
            return "api/UltraFastSend";
        }

        /**
         * Gets Api Token Url.
         *
         * @return string Indicates the Url
         */
        protected function getApiTokenUrl()
        {
            return "api/Token";
        }

        /**
         * Gets config parameters for sending request.
         *
         * @param string $APIKey    API Key
         * @param string $SecretKey Secret Key
         * @param string $APIURL    API URL
         *
         * @return void
         */
        public function __construct($APIKey, $SecretKey, $APIURL)
        {
            $this->APIKey = $APIKey;
            $this->SecretKey = $SecretKey;
            $this->APIURL = $APIURL;
        }

        /**
         * Ultra Fast Send Message.
         *
         * @param data[] $data array structure of message data
         *
         * @return string Indicates the sent sms result
         */
        public function ultraFastSend($data)
        {
            $token = $this->_getToken($this->APIKey, $this->SecretKey);
            if ($token != false) {
                $postData = $data;

                $url = $this->APIURL.$this->getAPIUltraFastSendUrl();
                $UltraFastSend = $this->_execute($postData, $url, $token);

                $object = json_decode($UltraFastSend);

                $result = false;
                if (is_object($object)) {
                    $result = $object->Message;
                } else {
                    $result = false;
                }
            } else {
                $result = false;
            }
            return $result;
        }

        /**
         * Gets token key for all web service requests.
         *
         * @return string Indicates the token key
         */
        private function _getToken()

        {
            $postData = array(
                'UserApiKey' => $this->APIKey,
                'SecretKey' => $this->SecretKey,
                'System' => 'php_rest_v_2_0'
            );

            $args = array(
                'body'        => $postData,
                'timeout'     => '5',
                'redirection' => '5',
                'httpversion' => '1.0',
                'blocking'    => true,
                'headers'     => array(),
                'cookies'     => array(),
            );

            $result = wp_remote_post( $this->APIURL.$this->getApiTokenUrl(), $args );
            $response = json_decode($result['body']);
            $resp = false;
            $IsSuccessful = '';

            $TokenKey = '';

            if (is_object($response)) {
                $IsSuccessful = $response->IsSuccessful;
                if ($IsSuccessful == true) {
                    $TokenKey = $response->TokenKey;
                    $resp = $TokenKey;
                } else {
                    $resp = false;
                }
            }
            return $resp;
        }

        /**
         * Executes the main method.
         *
         * @param postData[] $postData array of json data
         * @param string     $url      url
         * @param string     $token    token string
         *
         * @return string Indicates the curl execute result
         */
        private function _execute($postData, $url, $token)
        {
            $args = array(
                'body'        => $postData,
                'timeout'     => '5',
                'redirection' => '5',
                'httpversion' => '1.0',
                'blocking'    => true,
                'headers'     => array( 'x-sms-ir-secure-token'=>$token),
                'cookies'     => array(),
            );

            $result = wp_remote_post( $url, $args );
            return $result['body'];
        }
    }
}

