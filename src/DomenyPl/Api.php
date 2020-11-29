<?php

namespace DomenyPl;

use Exception;

class Api
{
    private $strApiUrl = 'https://api.domeny.pl/'; // URL address of API
    private $strLogin; // client login to API
    private $strPassword; // client password to API
    private $timeout = 10; // default command timeout
    /**
     *
     * @param string $strLogin
     * @param string $strPassword
     */
    public function __construct($strLogin, $strPassword, $strApiUrl = null)
    {
        $this->strLogin = $strLogin;
        $this->strPassword = $strPassword;
        if ($strApiUrl !== null) {
            $this->strApiUrl = $strApiUrl;
        }
    } // __construct

    /**
     * @param string $strCommandName
     * @param array $arrCommandParams
     * @return mixed
     * @throws Exception
     */
    public function sendCommand($strCommandName, array $arrCommandParams = array())
    {
        $resHandle = curl_init($this->getApiUrl());
        if ($resHandle === false) {
            throw new Exception('An error occurred during cURL call initialization.');
        }
        curl_setopt($resHandle, CURLOPT_HEADER, false);
        curl_setopt($resHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($resHandle, CURLOPT_TIMEOUT, $this->timeout);
        $arrCommandParams[ 'login' ] = $this->getApiLogin();
        $arrCommandParams[ 'password' ] = $this->getApiPassword();
        $arrCommandParams[ 'command' ] = $strCommandName;
        $boolResult = curl_setopt(
            $resHandle,
            CURLOPT_POSTFIELDS,
            http_build_query(
                array(
                    'params' => json_encode($arrCommandParams)
                ),
                '',
                '&'
            )
        );
        if (!$boolResult) {
            throw new Exception('Cant\'t assign "postfields" parameter');
        }
        $mixResult = curl_exec($resHandle);
        if (curl_errno($resHandle) != 0) {
            throw new Exception(
                'Curl task failed: (Error:'.curl_errno($resHandle).') "'.curl_error(
                    $resHandle
                ).'" (URL - '.$this->getApiUrl().')',
                curl_errno($resHandle)
            );
        }
        return json_decode($mixResult, true);
    } // sendCommand

    /**
     * returns URL address of API system
     *
     * @return string
     */
    public function getApiUrl()
    {
        return $this->strApiUrl;
    } // getApiUrl

    /**
     * returns client login to API system
     *
     * @return string
     */
    public function getApiLogin()
    {
        return $this->strLogin;
    } // getApiLogin

    /**
     * returns client password to API system
     *
     * @return string
     */
    public function getApiPassword()
    {
        return $this->strPassword;
    } // getApiPassword

    /**
     * @param int $timeout
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }
}
