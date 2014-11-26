<?php

namespace DomenyPl;

class Api
{
    private $strApiUrl = 'https://api.domeny.pl/'; // URL address of API
    private $strLogin; // client login to API
    private $strPassword; // client password to API
    /**
     *
     * @param string $strLogin
     * @param string $strPassword
     */
    public function __construct( $strLogin, $strPassword )
    {
        $this->strLogin = $strLogin;
        $this->strPassword = $strPassword;
    } // __construct

    public function sendCommand( $strCommandName, array $arrCommandParams = array() )
    {
        $resHandle = curl_init( $this->getApiUrl() );
        if ( $resHandle === false ) {
            throw new \Exception( 'An error occurred during CURL call initialization.' );
        }
        curl_setopt( $resHandle, CURLOPT_HEADER, false );
        curl_setopt( $resHandle, CURLOPT_RETURNTRANSFER, 1 );
        $arrCommandParams[ 'login' ] = $this->getApiLogin();
        $arrCommandParams[ 'password' ] = $this->getApiPassword();
        $arrCommandParams[ 'command' ] = $strCommandName;
        $boolResult = curl_setopt( $resHandle, CURLOPT_POSTFIELDS, http_build_query( array( 'params' =>
            json_encode( $arrCommandParams ) ), '', '&' ) );
        if ( ! $boolResult ) {
            throw new \Exception( 'Cant\'t assign "postfields" parameter' );
        }
        $mixResult = curl_exec( $resHandle );
        if ( curl_errno( $resHandle ) != 0 ) {
            throw new \Exception( 'Curl task failed: (Error:'.curl_errno( $resHandle ).') "'.curl_error(
                    $resHandle ).'" (URL - '.$this->getApiUrl().')' );
        }
        return json_decode( $mixResult, true );
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
} // class Api